<?php
/**
 * PBI-05 — Test Verifikasi Pengaduan oleh Supervisor
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Test cases:
 * 1. Approve pengaduan → redirect ke halaman assignment
 * 2. Reject pengaduan dengan alasan → status berubah + notifikasi terkirim
 */
namespace Tests\Browser\Pages;

use App\Models\{User, Kategori, Zona, Pengaduan, Sla};
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class PBI05_VerifikasiPengaduanTest extends DuskTestCase
{
    use DatabaseTruncation;

    private function buatPengaduan(): Pengaduan
    {
        $pelapor  = User::factory()->create(['role' => 'masyarakat']);
        $kategori = Kategori::factory()->create(['sla_jam' => 24]);
        $zona     = Zona::factory()->create();

        $pengaduan = Pengaduan::create([
            'nomor_tiket'       => 'SIGAP-TEST-0001',
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'Jl. Test Verifikasi No. 1',
            'deskripsi'         => 'Deskripsi pengaduan untuk keperluan testing verifikasi.',
            'status'            => 'menunggu_verifikasi',
            'tanggal_pengajuan' => now(),
        ]);

        Sla::create([
            'pengaduan_id' => $pengaduan->id,
            'deadline'     => now()->addHours(24),
            'is_overdue'   => false,
            'is_fulfilled' => false,
        ]);

        return $pengaduan;
    }

    /** @test */
    public function supervisor_dapat_menyetujui_pengaduan_dan_diarahkan_ke_assignment()
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);
        $pengaduan  = $this->buatPengaduan();

        $this->browse(function (Browser $browser) use ($supervisor, $pengaduan) {
            $browser->loginAs($supervisor)
                    ->visit('/supervisor/verifikasi/' . $pengaduan->id)
                    ->assertSee($pengaduan->nomor_tiket)
                    ->assertSee('Setujui Pengaduan');

            // Klik tombol setujui (konfirmasi dialog)
            $browser->acceptDialogs()
                    ->click('.bg-green-600')
                    ->assertPathContains('assignment');
        });

        $this->assertEquals('disetujui', $pengaduan->fresh()->status);
    }

    /** @test */
    public function supervisor_dapat_menolak_pengaduan_dengan_alasan()
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);
        $pengaduan  = $this->buatPengaduan();

        $this->browse(function (Browser $browser) use ($supervisor, $pengaduan) {
            $browser->loginAs($supervisor)
                    ->visit('/supervisor/verifikasi/' . $pengaduan->id)
                    ->assertSee('Tolak Pengaduan')
                    ->click('.bg-red-600')
                    ->type('alasan_penolakan', 'Pengaduan tidak lengkap dan tidak sesuai prosedur.')
                    ->waitForText('Tolak Pengaduan')
                    ->assertSee('verifikasi');
        });

        $pengaduanFresh = $pengaduan->fresh();
        $this->assertEquals('ditolak', $pengaduanFresh->status);
        $this->assertNotNull($pengaduanFresh->alasan_penolakan);
    }
}
