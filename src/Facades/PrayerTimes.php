<?php

namespace MuslimsCommunity\PrayerTimes\Facades;

use Illuminate\Support\Facades\Facade;

class PrayerTimes extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'prayer-times';
    }
}