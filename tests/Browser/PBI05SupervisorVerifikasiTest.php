<?php

namespace Tests\Browser;

use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\User;
use App\Models\Zona;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI05SupervisorVerifikasiTest extends DuskTestCase
{
    private const SUPERVISOR_EMAIL = 'supervisor@sigapair.test';
    private const SUPERVISOR_PASSWORD = 'password';

    private function bypassConfirm(Browser $browser): void
    {
        $browser->script("document.querySelectorAll('form[data-confirm]').forEach(function(form){ form.dataset.confirmed = 'true'; });");
    }

    private function loginAsDefaultSupervisor(Browser $browser): Browser
    {
        $browser->visit('/login');

        if (str_contains($browser->driver->getCurrentURL(), '/login')) {
            $this->bypassConfirm($browser);

            $browser->type('email', self::SUPERVISOR_EMAIL)
                ->type('password', self::SUPERVISOR_PASSWORD)
                ->press('Masuk');
        }

        return $browser->waitForLocation('/supervisor/dashboard')
            ->assertPathIs('/supervisor/dashboard');
    }

    private function createPendingPengaduan(string $suffix): Pengaduan
    {
        $unique = strtolower(substr(uniqid(), -8));

        $pelapor = User::factory()->create([
            'name' => 'Pelapor ' . $suffix,
            'username' => "pelapor_{$suffix}_{$unique}",
            'email' => "pelapor_{$suffix}_{$unique}@example.test",
            'role' => 'masyarakat',
            'password' => bcrypt('password'),
            'no_telepon' => '081234567890',
            'is_active' => true,
        ]);

        $kategori = Kategori::factory()->create([
            'nama_kategori' => 'Kategori Verifikasi ' . $suffix,
            'kode_kategori' => 'KAT-' . strtoupper(substr(uniqid(), -6)),
            'sla_jam' => 24,
            'is_active' => true,
        ]);

        $zona = Zona::factory()->create([
            'nama_zona' => 'Zona Verifikasi ' . $suffix,
            'kode_zona' => 'ZON-' . strtoupper(substr(uniqid(), -6)),
            'is_active' => true,
        ]);

        return Pengaduan::create([
            'nomor_tiket' => 'SIGAP-PBI05-' . strtoupper(substr(uniqid(), -6)),
            'user_id' => $pelapor->id,
            'kategori_id' => $kategori->id,
            'zona_id' => $zona->id,
            'lokasi' => 'Jl. Supervisor Test ' . $suffix,
            'deskripsi' => 'Pengaduan untuk pengujian verifikasi supervisor case ' . $suffix,
            'status' => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);
    }

    public function test_tc_pbi05_001_dashboard_supervisor_tampil(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsDefaultSupervisor($browser)
                ->assertSee('Dashboard Supervisor')
                ->screenshot('TC_PBI05_001_dashboard_supervisor_tampil');
        });
    }

    public function test_tc_pbi05_002_halaman_antrean_verifikasi_tampil(): void
    {
        $this->createPendingPengaduan('002');

        $this->browse(function (Browser $browser) {
            $this->loginAsDefaultSupervisor($browser)
                ->clickLink('Verifikasi Pengaduan')
                ->waitForLocation('/supervisor/verifikasi')
                ->assertPathIs('/supervisor/verifikasi')
                ->assertSee('Antrean Verifikasi')
                ->screenshot('TC_PBI05_002_antrean_verifikasi_tampil');
        });
    }

    public function test_tc_pbi05_003_detail_pengaduan_tampil_lengkap(): void
    {
        $pengaduan = $this->createPendingPengaduan('003');

        $this->browse(function (Browser $browser) use ($pengaduan) {
            $this->loginAsDefaultSupervisor($browser)
                ->visit('/supervisor/verifikasi/' . $pengaduan->id)
                ->assertSee($pengaduan->nomor_tiket)
                ->assertSee($pengaduan->lokasi)
                ->assertSee('Keputusan Verifikasi')
                ->screenshot('TC_PBI05_003_detail_pengaduan_tampil');
        });
    }

    public function test_tc_pbi05_004_approve_pengaduan_berhasil(): void
    {
        $pengaduan = $this->createPendingPengaduan('004');

        $this->browse(function (Browser $browser) use ($pengaduan) {
            $this->loginAsDefaultSupervisor($browser)
                ->visit('/supervisor/verifikasi/' . $pengaduan->id);

            $browser->script("document.querySelector('input[name=\"keputusan\"][value=\"disetujui\"]').checked = true; document.getElementById('formVerifikasi').submit();");

            $browser->waitForText('Tugaskan Petugas')
                ->assertPathContains('/supervisor/assignment/')
                ->screenshot('TC_PBI05_004_approve_pengaduan');
        });

        $this->assertEquals('disetujui', $pengaduan->fresh()->status);
    }

    public function test_tc_pbi05_005_tolak_pengaduan_dengan_alasan_valid(): void
    {
        $pengaduan = $this->createPendingPengaduan('005');
        $alasan = 'Data laporan tidak lengkap dan butuh bukti foto yang lebih jelas.';

        $this->browse(function (Browser $browser) use ($pengaduan, $alasan) {
            $this->loginAsDefaultSupervisor($browser)
                ->visit('/supervisor/verifikasi/' . $pengaduan->id);

            $browser->script("document.querySelector('input[name=\"keputusan\"][value=\"ditolak\"]').checked = true; document.querySelector('textarea[name=\"alasan_penolakan\"]').value = ".json_encode($alasan)."; document.getElementById('formVerifikasi').submit();");

            $browser->pause(1200)
                ->assertPathContains('/supervisor/verifikasi')
                ->screenshot('TC_PBI05_005_tolak_dengan_alasan');
        });

        $this->assertEquals('ditolak', $pengaduan->fresh()->status);
        $this->assertEquals($alasan, $pengaduan->fresh()->alasan_penolakan);
    }

    public function test_tc_pbi05_006_validasi_alasan_penolakan_kosong(): void
    {
        $pengaduan = $this->createPendingPengaduan('006');

        $this->browse(function (Browser $browser) use ($pengaduan) {
            $this->loginAsDefaultSupervisor($browser)
                ->visit('/supervisor/verifikasi/' . $pengaduan->id);

            $browser->script("document.querySelector('input[name=\"keputusan\"][value=\"ditolak\"]').checked = true; document.getElementById('formVerifikasi').submit();");

            $browser->waitForText('Alasan')
                ->assertSee('Alasan')
                ->screenshot('TC_PBI05_006_validasi_alasan_kosong');
        });

        $this->assertEquals('menunggu_verifikasi', $pengaduan->fresh()->status);
    }
}
