<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Kategori;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminUpdateSlaTest extends DuskTestCase
{
    /**
     * Skenario 1: Admin dapat mengubah konfigurasi SLA Kategori.
     * Tipe: Positive
     */
    public function test_admin_can_update_sla_category(): void
    {
        $admin = User::where('role', 'admin')->first();
        $kategori = Kategori::first();

        $this->browse(function (Browser $browser) use ($admin, $kategori) {
            $browser->loginAs($admin)
                    ->visit('/admin/sla')
                    ->assertSee('Konfigurasi SLA')
                    ->assertSee('Daftar Kategori & Batas SLA')
                    ->assertSee($kategori->nama_kategori)
                    // Klik tombol edit SLA untuk kategori pertama
                    ->click('#edit-sla-' . $kategori->id)
                    ->pause(1000)
                    ->assertPathIs('/admin/sla/' . $kategori->id . '/edit')
                    ->assertSee('Edit Batas Waktu SLA')
                    // Ubah batas SLA menjadi 48 jam menggunakan input
                    ->clear('#sla_jam')
                    ->type('#sla_jam', '48')
                    ->press('Simpan Perubahan')
                    ->pause(2000)
                    ->assertPathIs('/admin/sla')
                    ->assertSee('berhasil diperbarui');
        });
    }
}
