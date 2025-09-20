<?php

namespace MuslimsCommunity\PrayerTimes;

use Illuminate\Support\ServiceProvider;

class PrayerTimesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('prayer-times', function () {
            return new PrayerTimesManager();
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/prayer-times.php',
            'prayer-times'
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/prayer-times.php' => config_path('prayer-times.php'),
            ], 'prayer-times-config');
        }
    }
}