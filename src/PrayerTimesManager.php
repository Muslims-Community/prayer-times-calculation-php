<?php

namespace MuslimsCommunity\PrayerTimes;

use DateTime;
use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Data\PrayerTimes;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;

class PrayerTimesManager
{
    public function calculate(
        float $latitude,
        float $longitude,
        ?DateTime $date = null,
        ?float $timezone = null,
        ?CalculationMethod $method = null,
        ?AsrJurisdiction $asrJurisdiction = null,
        ?float $fajrAngle = null,
        ?float $ishaAngle = null
    ): PrayerTimes {
        $date = $date ?? new DateTime();
        $timezone = $timezone ?? $this->getDefaultTimezone($date);
        $method = $method ?? CalculationMethod::MWL;
        $asrJurisdiction = $asrJurisdiction ?? AsrJurisdiction::STANDARD;

        $options = new CalculationOptions(
            $method,
            $asrJurisdiction,
            $fajrAngle,
            $ishaAngle
        );

        $sdk = new PrayerTimesSDK($latitude, $longitude, $date, $timezone, $options);
        return $sdk->getTimes();
    }

    public function calculateFromConfig(
        float $latitude,
        float $longitude,
        ?DateTime $date = null,
        ?array $config = null
    ): PrayerTimes {
        $config = $config ?? config('prayer-times', []);
        $date = $date ?? new DateTime();

        $timezone = $config['timezone'] ?? $this->getDefaultTimezone($date);
        $method = CalculationMethod::from($config['method'] ?? 'MWL');
        $asrJurisdiction = AsrJurisdiction::from($config['asr_jurisdiction'] ?? 'Standard');

        return $this->calculate(
            $latitude,
            $longitude,
            $date,
            $timezone,
            $method,
            $asrJurisdiction,
            $config['fajr_angle'] ?? null,
            $config['isha_angle'] ?? null
        );
    }

    private function getDefaultTimezone(DateTime $date): float
    {
        $timezone = $date->getTimezone();
        $offset = $timezone->getOffset($date);
        return $offset / 3600;
    }
}