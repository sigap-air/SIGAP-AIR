<?php
/**
 * PBI-07 — Test Update Status Penanganan oleh Petugas
 * TANGGUNG JAWAB: Falah Adhi Chandra
 *
 * Test cases:
 * 1. Petugas dapat update status dari ditugaskan ke diproses
 * 2. Petugas dapat update status dari diproses ke selesai
 * 3. SLA ditandai fulfilled setelah selesai
 * 4. Notifikasi terkirim ke pelapor saat selesai
 */
namespace Tests\Browser\Pages;

use App\Models\{User, Kategori, Zona, Pengaduan, Petugas, Assignment, Sla};
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class PBI07_UpdateStatusTest extends DuskTestCase
{
    use DatabaseTruncation;

    private function createTestData(): array
    {
        $userPetugas = User::factory()->create(['role' => 'petugas']);
        $pelapor     = User::factory()->create(['role' => 'masyarakat']);
        $supervisor  = User::factory()->create(['role' => 'supervisor']);
        $kategori    = Kategori::factory()->create(['sla_jam' => 24]);
        $zona        = Zona::factory()->create();

        $petugas = Petugas::create([
            'user_id'             => $userPetugas->id,
            'nomor_pegawai'       => 'PTG-9999',
            'status_ketersediaan' => 'tersedia',
        ]);

        $pengaduan = Pengaduan::create([
            'nomor_tiket'       => 'SIGAP-UPDATE-001',
            'user_id'           => $pelapor->id,
            'kategori_id'       => $kategori->id,
            'zona_id'           => $zona->id,
            'lokasi'            => 'Jl. Test Update',
            'deskripsi'         => 'Deskripsi pengaduan untuk testing update status penanganan.',
            'status'            => 'ditugaskan',
            'tanggal_pengajuan' => now(),
        ]);

        $sla = Sla::create([
            'pengaduan_id' => $pengaduan->id,
            'deadline'     => now()->addHours(24),
            'is_overdue'   => false,
            'is_fulfilled' => false,
        ]);

        $assignment = Assignment::create([
            'pengaduan_id'      => $pengaduan->id,
            'petugas_id'        => $petugas->id,
            'supervisor_id'     => $supervisor->id,
            'instruksi'         => 'Segera tangani.',
            'jadwal_penanganan' => now()->addHour(),
            'status_assignment' => 'ditugaskan',
        ]);

        return [$userPetugas, $petugas, $pengaduan, $assignment, $sla, $pelapor];
    }

    /** @test */
    public function petugas_dapat_update_status_ke_diproses()
    {
        [$userPetugas, $petugas, $pengaduan, $assignment] = $this->createTestData();

        $this->browse(function (Browser $browser) use ($userPetugas, $assignment) {
            $browser->loginAs($userPetugas)
                    ->visit('/petugas/tugas/' . $assignment->id)
                    ->assertSee('Update Status')
                    ->select('status_assignment', 'diproses')
                    ->type('catatan_penanganan', 'Sedang dalam perjalanan ke lokasi.')
                    ->press('Simpan Update')
                    ->assertSee('berhasil diperbarui');
        });

        $this->assertEquals('diproses', $assignment->fresh()->status_assignment);
        $this->assertEquals('diproses', $pengaduan->fresh()->status);
    }

    /** @test */
    public function petugas_dapat_menyelesaikan_tugas_dan_notifikasi_terkirim()
    {
        [$userPetugas, $petugas, $pengaduan, $assignment, $sla, $pelapor] = $this->createTestData();

        // Set status ke diproses dulu
        $assignment->update(['status_assignment' => 'diproses']);
        $pengaduan->update(['status' => 'diproses']);

        $this->browse(function (Browser $browser) use ($userPetugas, $assignment) {
            $browser->loginAs($userPetugas)
                    ->visit('/petugas/tugas/' . $assignment->id)
                    ->select('status_assignment', 'selesai')
                    ->type('catatan_penanganan', 'Penanganan selesai. Pipa telah diperbaiki.')
                    ->press('Simpan Update')
                    ->assertSee('diselesaikan');
        });

        $assignmentFresh = $assignment->fresh();
        $this->assertEquals('selesai', $assignmentFresh->status_assignment);
        $this->assertEquals('selesai', $pengaduan->fresh()->status);
        $this->assertNotNull($assignmentFresh->tanggal_selesai);

        // Cek SLA terpenuhi
        $this->assertTrue($sla->fresh()->is_fulfilled);

        // Cek notifikasi ke pelapor
        $notif = \App\Models\Notifikasi::where('user_id', $pelapor->id)
            ->where('pengaduan_id', $pengaduan->id)
            ->where('judul', 'like', '%Selesai%')
            ->first();
        $this->assertNotNull($notif, 'Notifikasi harus terkirim ke pelapor saat selesai');
    }
}
