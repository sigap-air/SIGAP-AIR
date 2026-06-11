<?php
/**
 * PBI-13 — Test filter, reset, export CSV, dan sorting daftar pengaduan (Admin)
 * TANGGUNG JAWAB: Imanuel Karmelio V. Liuw
 *
 * Data fixture: {@see \App\Services\Testing\PengaduanFilterScenarioService}
 * Akun login: {@see \Database\Seeders\DatabaseSeeder} (admin@sigapair.test, password)
 */
namespace Tests\Browser\Pages;

use App\Services\Testing\PengaduanFilterScenarioService;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Support\WaitsForFilterQueryString;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class PBI13_PengaduanFilterTest extends DuskTestCase
{
    use DatabaseTruncation;
    use WaitsForFilterQueryString;

    /**
     * Setelah migrate:fresh & setiap truncate, jalankan DatabaseSeeder
     * (akun admin/supervisor/petugas/masyarakat @sigapair.test + master zona/kategori).
     */
    protected $seed = true;

    /** @test */
    public function admin_memfilter_pengaduan_berdasarkan_nomor_tiket()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildNomorTiketFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitForInput('nomor_tiket')
                ->type('nomor_tiket', '0001')
                ->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'nomor_tiket=');

            $browser->assertQueryStringHas('nomor_tiket', '0001')
                ->assertSee($ctx['tiketMatch'])
                ->assertDontSee($ctx['tiketOther']);
        });
    }

    /** @test */
    public function admin_memfilter_pengaduan_berdasarkan_status()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildStatusFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitForInput('status')
                ->select('status', 'menunggu_verifikasi')
                ->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'status=menunggu_verifikasi');

            $browser->assertQueryStringHas('status', 'menunggu_verifikasi')
                ->assertSee($ctx['nomorMenunggu'])
                ->assertDontSee($ctx['nomorDisetujui']);
        });
    }

    /** @test */
    public function admin_memfilter_pengaduan_berdasarkan_zona()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildZonaFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitForInput('zona_id')
                ->select('zona_id', (string) $ctx['zonaUtara']->id)
                ->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'zona_id=');

            $browser->assertQueryStringHas('zona_id', (string) $ctx['zonaUtara']->id)
                ->assertSee('Bandung Utara')
                ->assertDontSee($ctx['nomorSelatan']);
        });
    }

    /** @test */
    public function admin_memfilter_pengaduan_berdasarkan_kategori()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildKategoriFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitForInput('kategori_id')
                ->select('kategori_id', (string) $ctx['kategoriAirKeruh']->id)
                ->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'kategori_id=');

            $browser->assertQueryStringHas('kategori_id', (string) $ctx['kategoriAirKeruh']->id)
                ->assertSee('Air Keruh')
                ->assertDontSee($ctx['nomorKategoriLain']);
        });
    }

    /** @test */
    public function admin_memfilter_pengaduan_berdasarkan_petugas()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildPetugasFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitForInput('petugas_id')
                ->select('petugas_id', (string) $ctx['petugas']->id)
                ->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'petugas_id=');

            $browser->assertQueryStringHas('petugas_id', (string) $ctx['petugas']->id)
                ->assertSee($ctx['namaPetugas'])
                ->assertSee($ctx['nomorDitugaskan'])
                ->assertDontSee($ctx['nomorTanpaAssignment']);
        });
    }

    /** @test */
    public function admin_memfilter_pengaduan_berdasarkan_rentang_tanggal()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildTanggalFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitForInput('dari');

            $browser->driver->executeScript(
                'document.querySelector("input[name=dari]").value = arguments[0];'
                . 'document.querySelector("input[name=sampai]").value = arguments[1];',
                [$ctx['dari'], $ctx['sampai']]
            );

            $browser->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'dari=');

            $browser->assertQueryStringHas('dari', $ctx['dari'])
                ->assertQueryStringHas('sampai', $ctx['sampai'])
                ->assertSee($ctx['nomorLama'])
                ->assertDontSee($ctx['nomorBaru']);
        });
    }

    /** @test */
    public function admin_memfilter_hanya_pengaduan_overdue_sla()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildOverdueFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitFor("input[name='overdue']")
                ->check('overdue')
                ->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'overdue=');

            $browser->assertQueryStringHas('overdue', '1')
                ->assertSee($ctx['nomorOverdue'])
                ->assertDontSee($ctx['nomorTidakOverdue']);
        });
    }

    /** @test */
    public function admin_dapat_mereset_filter_pengaduan()
    {
        $ctx = app(PengaduanFilterScenarioService::class)->buildNomorTiketFilterFixtures();

        $this->browse(function (Browser $browser) use ($ctx) {
            $browser->loginAs($ctx['admin'])
                ->visit('/admin/pengaduan')
                ->waitForInput('nomor_tiket')
                ->type('nomor_tiket', '0001')
                ->press('🔍 Terapkan');

            $this->tungguUrlBerisiQuery($browser, 'nomor_tiket=');

            $browser->assertQueryStringHas('nomor_tiket');

            $browser->visit(route('admin.pengaduan.index'))
                ->assertPathIs('/admin/pengaduan')
                ->assertQueryStringMissing('nomor_tiket')
                ->assertSee($ctx['tiketMatch'])
                ->assertSee($ctx['tiketOther']);
        });
    }

    /** @test */
    public function admin_dapat_mengekspor_daftar_pengaduan_ke_csv()
    {
        $admin = app(PengaduanFilterScenarioService::class)->buildExportCsvFixtures();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/pengaduan')
                ->waitFor('a[href*="export-csv"]');
        });

        $response = $this->actingAs($admin)->get(route('admin.pengaduan.export-csv'));
        $response->assertOk();
        $this->assertStringContainsString('Nomor Tiket', $response->streamedContent());
    }

    /** @test */
    public function admin_dapat_mengurutkan_pengaduan_berdasarkan_tanggal()
    {
        $admin = app(PengaduanFilterScenarioService::class)->admin();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/pengaduan')
                ->waitUsing(10, 100, function () use ($browser) {
                    $links = $browser->driver->findElements(
                        WebDriverBy::xpath('//a[contains(@href, "sort=tanggal_pengajuan") and contains(., "Tanggal")]')
                    );

                    return count($links) > 0;
                });

            $browser->driver->findElement(
                WebDriverBy::xpath('//a[contains(@href, "sort=tanggal_pengajuan") and contains(., "Tanggal")]')
            )->click();

            $this->tungguUrlBerisiQuery($browser, 'sort=tanggal_pengajuan');

            $browser->assertQueryStringHas('sort', 'tanggal_pengajuan')
                ->assertQueryStringHas('dir', 'asc');
        });
    }
}
