<?php

namespace Tests\Feature\Masyarakat;

use Tests\TestCase;
use App\Models\{User, Pengaduan, Rating, KategoriPengaduan, Zona};
use Illuminate\Foundation\Testing\RefreshDatabase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    protected $masyarakat;
    protected $pengaduan;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup test data
        $this->masyarakat = User::factory()->create(['role' => 'masyarakat']);

        $kategori = KategoriPengaduan::create([
            'nama_kategori' => 'Kebocoran Pipa',
            'is_active' => true,
        ]);

        $zona = Zona::create([
            'nama_zona' => 'Zona Barat',
            'is_active' => true,
        ]);

        $this->pengaduan = Pengaduan::create([
            'nomor_tiket' => 'SIGAP-20260523-0001',
            'user_id' => $this->masyarakat->id,
            'kategori_id' => $kategori->id,
            'zona_id' => $zona->id,
            'lokasi' => 'Jl. Raya Sudirman No. 123',
            'deskripsi' => 'Pipa bocor di depan rumah',
            'status' => 'selesai',
            'tanggal_pengajuan' => now(),
        ]);
    }

    /** @test */
    public function guest_cannot_access_rating_form()
    {
        $response = $this->get(route('masyarakat.rating.create', $this->pengaduan->nomor_tiket));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_cannot_rate_other_users_pengaduan()
    {
        $other_user = User::factory()->create(['role' => 'masyarakat']);

        $response = $this->actingAs($other_user)
            ->get(route('masyarakat.rating.create', $this->pengaduan->nomor_tiket));

        $response->assertStatus(403);
    }

    /** @test */
    public function cannot_rate_incomplete_pengaduan()
    {
        $this->pengaduan->update(['status' => 'diproses']);

        $response = $this->actingAs($this->masyarakat)
            ->get(route('masyarakat.rating.create', $this->pengaduan->nomor_tiket));

        $response->assertStatus(400);
    }

    /** @test */
    public function user_can_view_rating_form_for_completed_pengaduan()
    {
        $response = $this->actingAs($this->masyarakat)
            ->get(route('masyarakat.rating.create', $this->pengaduan->nomor_tiket));

        $response->assertStatus(200);
        $response->assertViewIs('masyarakat.rating.create');
        $response->assertViewHas('pengaduan', $this->pengaduan);
    }

    /** @test */
    public function user_can_submit_rating()
    {
        $response = $this->actingAs($this->masyarakat)
            ->post(route('masyarakat.rating.store', $this->pengaduan->nomor_tiket), [
                'rating' => 5,
                'komentar' => 'Pelayanan sangat memuaskan!',
            ]);

        $response->assertRedirect(route('masyarakat.pengaduan.riwayat.show', $this->pengaduan->nomor_tiket));

        $this->assertDatabaseHas('rating_feedback', [
            'pengaduan_id' => $this->pengaduan->id,
            'user_id' => $this->masyarakat->id,
            'rating' => 5,
            'komentar' => 'Pelayanan sangat memuaskan!',
        ]);
    }

    /** @test */
    public function cannot_rate_same_pengaduan_twice()
    {
        // Submit first rating
        $this->actingAs($this->masyarakat)
            ->post(route('masyarakat.rating.store', $this->pengaduan->nomor_tiket), [
                'rating' => 4,
                'komentar' => 'Baik',
            ]);

        // Try to access form again
        $response = $this->actingAs($this->masyarakat)
            ->get(route('masyarakat.rating.create', $this->pengaduan->nomor_tiket));

        $response->assertStatus(400);
    }

    /** @test */
    public function rating_validation_required()
    {
        $response = $this->actingAs($this->masyarakat)
            ->post(route('masyarakat.rating.store', $this->pengaduan->nomor_tiket), [
                'rating' => '',
                'komentar' => 'Baik',
            ]);

        $response->assertSessionHasErrors('rating');
    }

    /** @test */
    public function rating_must_be_between_1_and_5()
    {
        $response = $this->actingAs($this->masyarakat)
            ->post(route('masyarakat.rating.store', $this->pengaduan->nomor_tiket), [
                'rating' => 10,
                'komentar' => 'Baik',
            ]);

        $response->assertSessionHasErrors('rating');
    }

    /** @test */
    public function komentar_optional()
    {
        $response = $this->actingAs($this->masyarakat)
            ->post(route('masyarakat.rating.store', $this->pengaduan->nomor_tiket), [
                'rating' => 3,
            ]);

        $response->assertRedirect(route('masyarakat.pengaduan.riwayat.show', $this->pengaduan->nomor_tiket));

        $this->assertDatabaseHas('rating_feedback', [
            'pengaduan_id' => $this->pengaduan->id,
            'rating' => 3,
            'komentar' => null,
        ]);
    }

    /** @test */
    public function komentar_max_500_characters()
    {
        $long_comment = str_repeat('a', 501);

        $response = $this->actingAs($this->masyarakat)
            ->post(route('masyarakat.rating.store', $this->pengaduan->nomor_tiket), [
                'rating' => 5,
                'komentar' => $long_comment,
            ]);

        $response->assertSessionHasErrors('komentar');
    }
}
