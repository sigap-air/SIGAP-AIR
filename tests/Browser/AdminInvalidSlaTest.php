<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Kategori;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminInvalidSlaTest extends DuskTestCase
{
    /**
     * Skenario 1.1: Admin gagal mengubah SLA karena nilai di bawah 1 jam (Validation Error).
     * Tipe: Negative
     */
    public function test_admin_cannot_update_sla_with_invalid_value(): void
    {
        $admin = User::where('role', 'admin')->first();
        $kategori = Kategori::first();

        $this->browse(function (Browser $browser) use ($admin, $kategori) {
            $browser->loginAs($admin)
                    ->visit('/admin/sla/' . $kategori->id . '/edit')
                    ->pause(1000)
                    ->assertSee('Edit Batas Waktu SLA')
                    // Memasukkan angka 0 (di bawah nilai minimum 1)
                    ->clear('#sla_jam')
                    ->type('#sla_jam', '0')
                    // Bypassing HTML5 validation untuk benar-benar menguji server-side validation Laravel
                    ->script("document.getElementById('form-edit-sla').setAttribute('novalidate', 'novalidate');");
            
            $browser->press('Simpan Perubahan')
                    ->pause(2000)
                    // URL tetap di halaman edit dan muncul pesan error validasi spesifik
                    ->assertPathIs('/admin/sla/' . $kategori->id . '/edit')
                    ->assertSee('SLA minimal 1 jam.');
        });
    }
}
