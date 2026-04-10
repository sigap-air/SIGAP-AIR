<?php
/**
 * PBI-15 — Test Dashboard Statistik Real-Time
 * TANGGUNG JAWAB: Imanuel Karmelio V. Liuw
 */
namespace Tests\Browser\Pages;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI15_DashboardStatistikTest extends DuskTestCase
{
    /** @test */
    public function supervisor_melihat_widget_kpi_di_dashboard()
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $this->browse(function (Browser $browser) use ($supervisor) {
            $browser->loginAs($supervisor)
                    ->visit('/supervisor/dashboard')
                    ->assertSee('Tiket Menunggu Verifikasi')
                    ->assertSee('Tiket Sedang Diproses')
                    ->assertSee('Tiket Selesai')
                    ->assertSee('Overdue');
        });
    }

    // TODO IMANUEL: Tambah test untuk grafik dan tren bulanan
}
