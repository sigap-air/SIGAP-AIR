<?php

namespace Tests\Support;

use Laravel\Dusk\Browser;

trait WaitsForFilterQueryString
{
    protected function tungguUrlBerisiQuery(Browser $browser, string $fragment): void
    {
        $browser->waitUsing(10, 100, function () use ($browser, $fragment) {
            $url = $browser->driver->getCurrentURL();

            return str_contains($url, '?') && str_contains($url, $fragment);
        });
    }
}
