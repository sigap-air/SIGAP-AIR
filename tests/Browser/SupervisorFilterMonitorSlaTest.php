<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SupervisorFilterMonitorSlaTest extends DuskTestCase
{
    /**
     * Skenario 3: Supervisor dapat memfilter Monitor SLA.
     * Tipe: Positive
     */
    public function test_supervisor_can_filter_monitor_sla(): void
    {
        $supervisor = User::where('role', 'supervisor')->first();

        $this->browse(function (Browser $browser) use ($supervisor) {
            $browser->loginAs($supervisor)
                    ->visit('/supervisor/monitor-sla')
                    ->pause(1000)
                    ->select('status_sla', 'overdue')
                    ->press('Filter')
                    ->pause(2000)
                    ->assertQueryStringHas('status_sla', 'overdue')
                    ->assertSee('Daftar Pengaduan');
        });
    }
}
