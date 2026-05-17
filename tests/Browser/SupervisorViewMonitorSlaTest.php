<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SupervisorViewMonitorSlaTest extends DuskTestCase
{
    /**
     * Skenario 2: Supervisor dapat melihat halaman Monitor SLA.
     * Tipe: Positive
     */
    public function test_supervisor_can_view_monitor_sla(): void
    {
        $supervisor = User::where('role', 'supervisor')->first();

        $this->browse(function (Browser $browser) use ($supervisor) {
            $browser->loginAs($supervisor)
                    ->visit('/supervisor/monitor-sla')
                    ->pause(1000)
                    ->assertSee('Monitor SLA')
                    ->assertSee('SLA BERJALAN')
                    ->assertSee('OVERDUE')
                    ->assertSee('TERPENUHI')
                    ->assertSee('PERLU TINDAKAN')
                    ->assertSee('CARI TIKET')
                    ->assertSee('STATUS SLA')
                    ->assertSee('KATEGORI');
        });
    }
}
