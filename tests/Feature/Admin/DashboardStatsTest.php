<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_fetch_dashboard_stats_json(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->getJson(route('admin.dashboard.stats'))
            ->assertOk()
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

    public function test_non_admin_cannot_fetch_dashboard_stats(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $this->actingAs($supervisor)
            ->getJson(route('admin.dashboard.stats'))
            ->assertForbidden();
    }

    public function test_guest_cannot_fetch_dashboard_stats(): void
    {
        $this->getJson(route('admin.dashboard.stats'))
            ->assertForbidden();
    }
}
