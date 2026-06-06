<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseDuskTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseDuskTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
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
        $options = (new ChromeOptions)->addArguments(
            collect([
                $this->shouldStartMaximized()
                    ? '--start-maximized'
                    : '--window-size=1920,1080',

                '--disable-search-engine-choice-screen',
                '--disable-smooth-scrolling',
            ])->unless(
                $this->hasHeadlessDisabled(),
                function ($items) {
                    return $items->merge([
                        '--disable-gpu',
                        '--headless=new',
                    ]);
                }
            )->all()
        );

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL']
                ?? env('DUSK_DRIVER_URL')
                ?? 'http://localhost:9515',

            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }
}