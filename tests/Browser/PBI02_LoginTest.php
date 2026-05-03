<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI02_LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * TC.Login.002 — Login dengan kredensial salah
     * Catatan: form login menggunakan JS confirm dialog (data-confirm)
     * sehingga ->acceptDialog() wajib dipanggil setelah ->press()
     */
    public function testLoginKredensialSalah(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->assertPathIs('/login')
                    ->type('email', 'salah@email.com')
                    ->type('password', 'wrongpassword')
                    ->press('Masuk')
                    ->acceptDialog()  // Handle JS confirm: "Yakin ingin login ke akun ini?"
                    ->assertPathIs('/login')
                    ->assertSee('These credentials do not match our records.');
        });
    }

    /**
     * TC.Login.003 — Login dengan field kosong
     * Field email bertipe required → browser mencegah submit sebelum dialog muncul,
     * sehingga tidak perlu acceptDialog() di sini.
     */
    public function testLoginFieldKosong(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->assertPathIs('/login')
                    ->press('Masuk')
                    // Browser HTML5 validation mencegah submit → tetap di /login
                    ->assertPathIs('/login')
                    ->assertDontSee('Dashboard');
        });
    }
}