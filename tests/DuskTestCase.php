<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Creates the application instance for Dusk / PHPUnit.
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Prepare for Dusk test execution.
     *
     * ChromeDriver harus sama major version dengan Chrome:
     *   php artisan dusk:chrome-driver --detect
     *
     * Opsional di .env / .env.dusk.local:
     *   DUSK_CHROMEDRIVER_PATH=C:\path\to\chromedriver.exe  — pakai binary manual
     *   DUSK_START_CHROMEDRIVER=false — jangan jalankan driver bawaan Dusk; jalankan
     *       chromedriver yang cocok sendiri di port 9515 (atau set DUSK_DRIVER_URL).
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (static::runningInSail()) {
            return;
        }

        $startFlag = $_ENV['DUSK_START_CHROMEDRIVER'] ?? getenv('DUSK_START_CHROMEDRIVER');
        $startInternal = ! is_string($startFlag) || $startFlag === ''
            ? true
            : filter_var($startFlag, FILTER_VALIDATE_BOOL);

        if ($startInternal === false) {
            return;
        }

        $custom = $_ENV['DUSK_CHROMEDRIVER_PATH'] ?? getenv('DUSK_CHROMEDRIVER_PATH');
        if (is_string($custom) && $custom !== '' && is_file($custom)) {
            static::useChromedriver($custom);
        }

        static::startChromeDriver(['--port=9515']);
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }
}
