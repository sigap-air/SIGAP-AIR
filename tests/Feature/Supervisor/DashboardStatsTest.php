<?php

namespace Tests\Feature\Supervisor;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_fetch_dashboard_stats_json(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $response = $this->actingAs($supervisor)
            ->getJson(route('supervisor.dashboard.stats'));

        $response->assertOk()
            ->assertJsonStructure([
                'kpi' => [
                    'total_masuk',
                    'menunggu_verifikasi',
                    'diproses',
                    'selesai',
                    'overdue',
                ],
                'per_kategori',
                'per_zona',
                'tren_bulanan',
                'updated_at',
            ]);
    }

    public function test_non_supervisor_cannot_fetch_dashboard_stats(): void
    {
        $masyarakat = User::factory()->create(['role' => 'masyarakat']);

        $this->actingAs($masyarakat)
            ->getJson(route('supervisor.dashboard.stats'))
            ->assertForbidden();
    }

    public function test_guest_cannot_fetch_dashboard_stats(): void
    {
        $this->getJson(route('supervisor.dashboard.stats'))
            ->assertForbidden();
    }
}
