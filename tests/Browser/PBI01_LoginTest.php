<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI01_LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testLoginPositif(): void
    {
        // Buat user admin karena DatabaseMigrations me-reset database setiap test
        $user = User::factory()->create([
            'email'    => 'admin@sigapair.test',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('http://127.0.0.1:8000/login')
                    ->assertPathIs('/login')
                    ->type('email', 'admin@sigapair.test')
                    ->type('password', 'password')
                    ->press('Masuk')
                    ->acceptDialog()   // Handle JS confirm: "Yakin ingin login ke akun ini?"
                    ->assertPathIs('/admin/dashboard')
                    ->assertSee('Dashboard');
        });
    }
}