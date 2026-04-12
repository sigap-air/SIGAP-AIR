<?php
/**
 * PBI-06 — Test Assignment Petugas
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Test cases:
 * 1. Supervisor dapat menugaskan petugas ke pengaduan
 * 2. Pengaduan status berubah menjadi ditugaskan
 * 3. Notifikasi terkirim ke petugas dan pelapor
 */
namespace Tests\Browser\Pages;

use App\Models\{User, Kategori, Zona, Pengaduan, Petugas, Sla};
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class PBI06_AssignmentPetugasTest extends DuskTestCase
{
    use DatabaseTruncation;

    /** @test */
    public function supervisor_dapat_menugaskan_petugas()
    {
        $supervisor  = User::factory()->create(['role' => 'supervisor']);
        $userPetugas = User::factory()->create(['role' => 'petugas']);
        $kategori    = Kategori::factory()->create(['sla_jam' => 24]);
        $zona        = Zona::factory()->create();

        // Buat petugas di zona yang sama
        $petugas = Petugas::create([
            'user_id'             => $userPetugas->id,
            'nomor_pegawai'       => 'PTG-0001',
            'status_ketersediaan' => 'tersedia',
        ]);
        $petugas->zonas()->attach($zona->id);

        $pelapor   = User::factory()->create(['role' => 'masyarakat']);
        $pengaduan = Pengaduan::create([
            'nomor_tiket'       => 'SIGAP-ASGN-0001',
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'Jl. Test Assignment',
            'deskripsi'         => 'Deskripsi pengaduan untuk keperluan testing assignment petugas.',
            'status'            => 'disetujui',
            'tanggal_pengajuan' => now(),
        ]);

        Sla::create([
            'pengaduan_id' => $pengaduan->id,
            'deadline'     => now()->addHours(24),
            'is_overdue'   => false,
            'is_fulfilled' => false,
        ]);

        $this->browse(function (Browser $browser) use ($supervisor, $pengaduan, $petugas) {
            $jadwal = now()->addHours(2)->format('Y-m-d\TH:i');

            $browser->loginAs($supervisor)
                    ->visit('/supervisor/assignment/' . $pengaduan->id . '/create')
                    ->assertSee($pengaduan->nomor_tiket)
                    ->assertSee('Tugaskan Petugas')
                    ->radio('petugas_id', $petugas->id)
                    ->type('jadwal_penanganan', $jadwal)
                    ->type('instruksi', 'Bawa peralatan standar dan segera tindak lanjuti.')
                    ->press('Tugaskan Petugas')
                    ->assertPathContains('verifikasi')
                    ->assertSee('berhasil ditugaskan');
        });

        $pengaduanFresh = $pengaduan->fresh();
        $this->assertEquals('ditugaskan', $pengaduanFresh->status);
        $this->assertNotNull($pengaduanFresh->assignment);

        // Cek notifikasi terkirim ke petugas
        $notifPetugas = \App\Models\Notifikasi::where('user_id', $petugas->user_id)->first();
        $this->assertNotNull($notifPetugas, 'Notifikasi harus terkirim ke petugas');
    }
}
