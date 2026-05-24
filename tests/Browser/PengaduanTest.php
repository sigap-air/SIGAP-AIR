<?php

namespace Tests\Browser;

use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Sla;
use App\Models\User;
use App\Models\Zona;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PengaduanTest extends DuskTestCase
{
    private const DEFAULT_MASYARAKAT_EMAIL = 'masyarakat@sigapair.test';

    private function bypassConfirm(Browser $browser): void
    {
        $browser->script('window.confirm = function () { return true; };');
    }

    private function visitPengaduanCreate(Browser $browser): Browser
    {
        $browser->visit('/masyarakat/pengaduan/create')
            ->assertPathIs('/masyarakat/pengaduan/create')
            ->waitFor('select[name="kategori_id"]');

        $this->bypassConfirm($browser);

        return $browser;
    }

    private function fillPengaduanForm(Browser $browser, Kategori $kategori, Zona $zona, array $fields): Browser
    {
        $browser->script(
            "document.querySelector('[name=\"kategori_id\"]').value = '{$kategori->id}';"
            . "document.querySelector('[name=\"zona_id\"]').value = '{$zona->id}';"
        );

        if (isset($fields['lokasi'])) {
            $browser->type('lokasi', $fields['lokasi']);
        }

        if (isset($fields['no_telepon'])) {
            $browser->type('no_telepon', $fields['no_telepon']);
        }

        if (isset($fields['deskripsi'])) {
            $browser->type('deskripsi', $fields['deskripsi']);
        }

        return $browser;
    }

    private function loginAsSeededMasyarakat(Browser $browser): Browser
    {
        $user = User::where('email', self::DEFAULT_MASYARAKAT_EMAIL)->firstOrFail();

        return $browser->loginAs($user)
            ->visit('/masyarakat/dashboard')
            ->assertPathIs('/masyarakat/dashboard');
    }

    private function createValidImageFixture(): string
    {
        $dir = storage_path('app/testing');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $path = $dir . '/dusk-valid.jpg';

        if (! file_exists($path) || @getimagesize($path) === false) {
            if (function_exists('imagecreatetruecolor')) {
                $image = imagecreatetruecolor(32, 32);
                $background = imagecolorallocate($image, 70, 130, 180);
                imagefill($image, 0, 0, $background);
                imagejpeg($image, $path, 90);
                imagedestroy($image);
            } else {
                file_put_contents(
                    $path,
                    base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAn/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCwAB//2Q==')
                );
            }
        }

        return $path;
    }

    private function createInvalidPdfFixture(): string
    {
        $dir = storage_path('app/testing');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $path = $dir . '/dusk-invalid.pdf';
        if (! file_exists($path)) {
            file_put_contents($path, "%PDF-1.1\n1 0 obj\n<< /Type /Catalog >>\nendobj\ntrailer\n<<>>\n%%EOF");
        }

        return $path;
    }

    private function seedMasterData(): array
    {
        $kategori = Kategori::factory()->create([
            'nama_kategori' => 'Air Mati Test',
            'kode_kategori' => 'KAT-' . strtoupper(substr(uniqid(), -6)),
            'sla_jam' => 24,
            'is_active' => true,
        ]);

        $zona = Zona::factory()->create([
            'nama_zona' => 'Zona Test A',
            'kode_zona' => 'ZON-' . strtoupper(substr(uniqid(), -6)),
            'is_active' => true,
        ]);

        return [$kategori, $zona];
    }

    /**
     * TC.PBI04.001
     * Submit pengaduan berhasil
     */
    public function test_submit_pengaduan_berhasil(): void
    {
        [$kategori, $zona] = $this->seedMasterData();
        $imagePath = $this->createValidImageFixture();
        $marker = 'TC001-' . uniqid();

        $this->browse(function (Browser $browser) use ($kategori, $zona, $imagePath, $marker) {
            $this->loginAsSeededMasyarakat($browser);
            $this->visitPengaduanCreate($browser);
            $this->fillPengaduanForm($browser, $kategori, $zona, [
                'lokasi' => 'Jl Mawar Testing No. 1',
                'no_telepon' => '08123456789',
                'deskripsi' => "Air mati sejak pagi dan tidak mengalir sama sekali di rumah. {$marker}",
            ]);

            $browser->attach('foto_bukti', $imagePath)
                ->press('Kirim Pengaduan')
                ->waitForText('Pengaduan Berhasil Dikirim', 20)
                ->assertSee('Pengaduan Berhasil Dikirim')
                ->assertSee('Nomor tiket pengaduan kamu:')
                ->screenshot('TC_PBI04_001_submit_berhasil');
        });

        $pengaduan = Pengaduan::where('deskripsi', 'like', "%{$marker}%")->latest('id')->first();
        $this->assertNotNull($pengaduan);
        $this->assertNotNull($pengaduan->nomor_tiket);
        $this->assertStringStartsWith('SIGAP-', $pengaduan->nomor_tiket);
        $this->assertNotNull(Sla::where('pengaduan_id', $pengaduan->id)->first());
    }

    /**
     * TC.PBI04.002
     * Form pengaduan tampil
     */
    public function test_form_pengaduan_tampil(): void
    {
        $this->seedMasterData();

        $this->browse(function (Browser $browser) {
            $this->loginAsSeededMasyarakat($browser);
            $this->visitPengaduanCreate($browser)
                ->assertSee('Pengaduan Baru')
                ->assertSee('Kategori Pengaduan')
                ->assertSee('Zona Wilayah')
                ->assertSee('Nomor Telepon')
                ->screenshot('TC_PBI04_002_form_tampil');
        });
    }

    /**
     * TC.PBI04.003
     * Submit form kosong
     */
    public function test_submit_form_kosong(): void
    {
        $this->seedMasterData();

        $this->browse(function (Browser $browser) {
            $this->loginAsSeededMasyarakat($browser);
            $this->visitPengaduanCreate($browser);

            $browser->script("document.querySelectorAll('[required]').forEach(function(el){el.removeAttribute('required');});");

            $browser->script("document.querySelector('form').submit();");

            $browser->waitForText('Kategori pengaduan wajib dipilih.')
                ->assertSee('Kategori pengaduan wajib dipilih.')
                ->screenshot('TC_PBI04_003_form_kosong');
        });
    }

    /**
     * TC.PBI04.004
     * Nomor telepon invalid
     */
    public function test_nomor_telepon_invalid(): void
    {
        [$kategori, $zona] = $this->seedMasterData();
        $imagePath = $this->createValidImageFixture();

        $this->browse(function (Browser $browser) use ($kategori, $zona, $imagePath) {
            $this->loginAsSeededMasyarakat($browser);
            $this->visitPengaduanCreate($browser);
            $this->fillPengaduanForm($browser, $kategori, $zona, ['lokasi' => 'Jl Testing']);

            $browser->script("var tel=document.querySelector('[name=\"no_telepon\"]'); tel.removeAttribute('pattern'); tel.removeAttribute('required'); tel.oninput = null; tel.value='abcd123';");

            $browser->type('deskripsi', 'Air mati total sejak pagi di seluruh rumah.')
                ->attach('foto_bukti', $imagePath);

            $browser->script("document.querySelector('form').submit();");

            $browser
                ->waitForText('Nomor telepon hanya boleh berisi angka.')
                ->assertSee('Nomor telepon hanya boleh berisi angka.')
                ->screenshot('TC_PBI04_004_nomor_invalid');
        });
    }

    /**
     * TC.PBI04.005
     * Deskripsi terlalu pendek
     */
    public function test_deskripsi_terlalu_pendek(): void
    {
        [$kategori, $zona] = $this->seedMasterData();
        $imagePath = $this->createValidImageFixture();

        $this->browse(function (Browser $browser) use ($kategori, $zona, $imagePath) {
            $this->loginAsSeededMasyarakat($browser);
            $this->visitPengaduanCreate($browser);
            $this->fillPengaduanForm($browser, $kategori, $zona, [
                'lokasi' => 'Jl Testing',
                'no_telepon' => '08123456789',
            ]);

            $browser->script("document.querySelector('[name=\"deskripsi\"]').removeAttribute('minlength');");

            $browser->type('deskripsi', 'Air mati')
                ->attach('foto_bukti', $imagePath)
                ->press('Kirim Pengaduan')
                ->assertSee('20 karakter')
                ->screenshot('TC_PBI04_005_deskripsi_pendek');
        });
    }

    /**
     * TC.PBI04.006
     * Upload file invalid
     */
    public function test_upload_file_invalid(): void
    {
        [$kategori, $zona] = $this->seedMasterData();
        $pdfPath = $this->createInvalidPdfFixture();

        $this->browse(function (Browser $browser) use ($kategori, $zona, $pdfPath) {
            $this->loginAsSeededMasyarakat($browser);
            $this->visitPengaduanCreate($browser);
            $this->fillPengaduanForm($browser, $kategori, $zona, [
                'lokasi' => 'Jl Testing',
                'no_telepon' => '08123456789',
                'deskripsi' => 'Air mati sejak pagi dan belum ada aliran hingga malam hari.',
            ]);

            $browser->script("document.querySelector('[name=\"foto_bukti\"]').removeAttribute('accept');");

            $browser->attach('foto_bukti', $pdfPath);

            $browser->script("document.querySelector('form').submit();");

            $browser->assertSee('File yang diunggah harus berupa gambar')
                ->screenshot('TC_PBI04_006_upload_invalid');
        });
    }
}