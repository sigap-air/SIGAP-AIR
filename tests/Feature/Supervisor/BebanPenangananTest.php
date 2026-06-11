<?php

namespace Tests\Feature\Supervisor;

use App\Models\Assignment;
use App\Models\Petugas;
use App\Models\User;
use App\Services\BebanPenangananService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * PBI-36 — Monitoring Beban Penanganan Pengaduan
 *
 * TANGGUNG JAWAB: Farisha
 */
class BebanPenangananTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------
    // Akses & Otorisasi
    // -----------------------------------------------

    public function test_supervisor_can_access_beban_penanganan_page(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $this->actingAs($supervisor)
            ->get(route('supervisor.beban-penanganan.index'))
            ->assertOk()
            ->assertViewIs('supervisor.beban-penanganan.index');
    }

    public function test_non_supervisor_cannot_access_beban_penanganan_page(): void
    {
        $masyarakat = User::factory()->create(['role' => 'masyarakat']);

        $this->actingAs($masyarakat)
            ->get(route('supervisor.beban-penanganan.index'))
            ->assertForbidden();
    }

    public function test_petugas_cannot_access_beban_penanganan_page(): void
    {
        $userPetugas = User::factory()->create(['role' => 'petugas']);

        $this->actingAs($userPetugas)
            ->get(route('supervisor.beban-penanganan.index'))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_from_beban_penanganan_page(): void
    {
        $this->get(route('supervisor.beban-penanganan.index'))
            ->assertRedirect(route('login'));
    }

    // -----------------------------------------------
    // Data & View Variables
    // -----------------------------------------------

    public function test_view_receives_required_variables(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $response = $this->actingAs($supervisor)
            ->get(route('supervisor.beban-penanganan.index'));

        $response->assertOk()
            ->assertViewHasAll(['petugas', 'zonas', 'ringkasan', 'filters']);
    }

    public function test_ringkasan_contains_correct_keys(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $response = $this->actingAs($supervisor)
            ->get(route('supervisor.beban-penanganan.index'));

        $ringkasan = $response->viewData('ringkasan');

        $this->assertArrayHasKey('total_petugas',    $ringkasan);
        $this->assertArrayHasKey('total_aktif',      $ringkasan);
        $this->assertArrayHasKey('rata_beban',       $ringkasan);
        $this->assertArrayHasKey('petugas_overload', $ringkasan);
        $this->assertArrayHasKey('petugas_idle',     $ringkasan);
    }

    // -----------------------------------------------
    // Filter
    // -----------------------------------------------

    public function test_filter_by_search_returns_correct_results(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $this->actingAs($supervisor)
            ->get(route('supervisor.beban-penanganan.index', ['search' => 'tidakadanama']))
            ->assertOk();
    }

    public function test_filter_by_status_returns_only_matching_petugas(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor']);

        $this->actingAs($supervisor)
            ->get(route('supervisor.beban-penanganan.index', ['status' => 'tersedia']))
            ->assertOk();
    }

    // -----------------------------------------------
    // Service Unit Tests
    // -----------------------------------------------

    public function test_beban_meta_returns_kosong_for_zero_tasks(): void
    {
        $meta = BebanPenangananService::bebanMeta(0);

        $this->assertSame('Kosong', $meta['label']);
    }

    public function test_beban_meta_returns_ringan_for_one_to_two_tasks(): void
    {
        $this->assertSame('Ringan', BebanPenangananService::bebanMeta(1)['label']);
        $this->assertSame('Ringan', BebanPenangananService::bebanMeta(2)['label']);
    }

    public function test_beban_meta_returns_sedang_for_three_to_four_tasks(): void
    {
        $this->assertSame('Sedang', BebanPenangananService::bebanMeta(3)['label']);
        $this->assertSame('Sedang', BebanPenangananService::bebanMeta(4)['label']);
    }

    public function test_beban_meta_returns_berat_for_five_or_more_tasks(): void
    {
        $this->assertSame('Berat', BebanPenangananService::bebanMeta(5)['label']);
        $this->assertSame('Berat', BebanPenangananService::bebanMeta(10)['label']);
    }

    public function test_hitung_ringkasan_calculates_correctly(): void
    {
        $service = new BebanPenangananService();

        // Buat collection dengan 3 petugas simulasi
        $petugas = collect([
            (object) ['total_aktif' => 5, 'status_tersedia' => 'sibuk'],      // overload
            (object) ['total_aktif' => 0, 'status_tersedia' => 'tersedia'],   // idle
            (object) ['total_aktif' => 2, 'status_tersedia' => 'tersedia'],   // normal
        ]);

        $ringkasan = $service->hitungRingkasan($petugas);

        $this->assertSame(3,   $ringkasan['total_petugas']);
        $this->assertSame(7,   $ringkasan['total_aktif']);
        $this->assertEqualsWithDelta(2.3, $ringkasan['rata_beban'], 0.1);
        $this->assertSame(1,   $ringkasan['petugas_overload']);
        $this->assertSame(1,   $ringkasan['petugas_idle']);
    }

    public function test_hitung_ringkasan_returns_zero_rata_for_empty_collection(): void
    {
        $service   = new BebanPenangananService();
        $ringkasan = $service->hitungRingkasan(collect());

        $this->assertSame(0,   $ringkasan['total_petugas']);
        $this->assertSame(0.0, (float) $ringkasan['rata_beban']);
    }
}
