<?php

namespace MuslimsCommunity\PrayerTimes\Data;

use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

class CalculationOptions
{
    public function __construct(
        public CalculationMethod $method,
        public AsrJurisdiction $asrJurisdiction,
        public ?float $fajrAngle = null,
        public ?float $ishaAngle = null
    ) {}
}