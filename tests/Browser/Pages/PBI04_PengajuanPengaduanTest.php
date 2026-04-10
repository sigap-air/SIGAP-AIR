<?php
/**
 * PBI-04 — Test Pengajuan Pengaduan Digital
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Test cases:
 * 1. Happy path: pengaduan berhasil dikirim + nomor tiket muncul
 * 2. Validasi error: form kosong tidak bisa submit
 * 3. SLA otomatis terbuat setelah pengaduan masuk
 */
namespace Tests\Browser\Pages;

use App\Models\{User, Kategori, Zona};
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class PBI04_PengajuanPengaduanTest extends DuskTestCase
{
    use DatabaseTruncation;

    /** @test */
    public function masyarakat_dapat_mengajukan_pengaduan_dan_mendapat_nomor_tiket()
    {
        $user     = User::factory()->create(['role' => 'masyarakat']);
        $kategori = Kategori::factory()->create(['nama_kategori' => 'Air Keruh Test', 'sla_jam' => 24]);
        $zona     = Zona::factory()->create(['nama_zona' => 'Zona A Test']);

        $this->browse(function (Browser $browser) use ($user, $kategori, $zona) {
            $browser->loginAs($user)
                    ->visit('/masyarakat/pengaduan/create')
                    ->assertSee('Pengaduan Baru')
                    ->select('kategori_id', $kategori->id)
                    ->select('zona_id', $zona->id)
                    ->type('lokasi', 'Jl. Contoh No. 1, RT 01/RW 01')
                    ->type('deskripsi', 'Air keluar sangat keruh sejak pagi hari dan berbau tidak sedap.')
                    ->press('Kirim Pengaduan')
                    ->assertSee('SIGAP-')
                    ->assertSee('berhasil dikirim');
        });

        // Pastikan SLA otomatis terbuat
        $pengaduan = \App\Models\Pengaduan::where('user_id', $user->id)->first();
        $this->assertNotNull($pengaduan, 'Pengaduan harus tersimpan di database');
        $this->assertNotNull($pengaduan->sla, 'SLA harus otomatis terbuat');
        $this->assertEquals($pengaduan->tanggal_pengajuan->addHours(24)->toDateString(), $pengaduan->sla->deadline->toDateString());
    }

    /** @test */
    public function form_kosong_tidak_dapat_disubmit()
    {
        $user = User::factory()->create(['role' => 'masyarakat']);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/masyarakat/pengaduan/create')
                    ->press('Kirim Pengaduan')
                    ->assertSee('wajib');
        });
    }

    /** @test */
    public function deskripsi_terlalu_pendek_menampilkan_error_validasi()
    {
        $user     = User::factory()->create(['role' => 'masyarakat']);
        $kategori = Kategori::factory()->create();
        $zona     = Zona::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $kategori, $zona) {
            $browser->loginAs($user)
                    ->visit('/masyarakat/pengaduan/create')
                    ->select('kategori_id', $kategori->id)
                    ->select('zona_id', $zona->id)
                    ->type('lokasi', 'Jl. Test')
                    ->type('deskripsi', 'Pendek') // < 20 karakter
                    ->press('Kirim Pengaduan')
                    ->assertSee('minimal');
        });
    }
}
