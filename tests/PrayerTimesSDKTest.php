<?php

namespace MuslimsCommunity\PrayerTimes\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use MuslimsCommunity\PrayerTimes\PrayerTimesSDK;
use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

class PrayerTimesSDKTest extends TestCase
{
    public function testCalculatePrayerTimesForMakkah(): void
    {
        $options = new CalculationOptions(
            CalculationMethod::MWL,
            AsrJurisdiction::STANDARD
        );

        $sdk = new PrayerTimesSDK(
            21.4225,  // Makkah latitude
            39.8262,  // Makkah longitude
            new DateTime('2023-10-15'),
            3.0,      // UTC+3
            $options
        );

        $times = $sdk->getTimes();

        $this->assertNotEquals('NaN:NaN', $times->fajr);
        $this->assertNotEquals('NaN:NaN', $times->sunrise);
        $this->assertNotEquals('NaN:NaN', $times->dhuhr);
        $this->assertNotEquals('NaN:NaN', $times->asr);
        $this->assertNotEquals('NaN:NaN', $times->maghrib);
        $this->assertNotEquals('NaN:NaN', $times->isha);

        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $times->fajr);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $times->sunrise);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $times->dhuhr);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $times->asr);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $times->maghrib);
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}$/', $times->isha);
    }

    public function testCalculatePrayerTimesWithCustomMethod(): void
    {
        $options = new CalculationOptions(
            CalculationMethod::CUSTOM,
            AsrJurisdiction::HANAFI,
            18.0,  // Custom Fajr angle
            17.0   // Custom Isha angle
        );

        $sdk = new PrayerTimesSDK(
            33.6844,  // Latitude
            73.0479,  // Longitude
            new DateTime('2023-06-21'),
            5.0,      // UTC+5
            $options
        );

        $times = $sdk->getTimes();

        $this->assertNotEquals('NaN:NaN', $times->fajr);
        $this->assertNotEquals('NaN:NaN', $times->isha);
    }

    public function testDifferentCalculationMethods(): void
    {
        $date = new DateTime('2023-10-15');
        $latitude = 21.4225;
        $longitude = 39.8262;
        $timezone = 3.0;

        $methods = [
            CalculationMethod::MWL,
            CalculationMethod::ISNA,
            CalculationMethod::EGYPT,
            CalculationMethod::MAKKAH,
            CalculationMethod::KARACHI
        ];

        foreach ($methods as $method) {
            $options = new CalculationOptions($method, AsrJurisdiction::STANDARD);
            $sdk = new PrayerTimesSDK($latitude, $longitude, $date, $timezone, $options);
            $times = $sdk->getTimes();

            $this->assertNotEquals('NaN:NaN', $times->fajr, "Failed for method: {$method->value}");
            $this->assertNotEquals('NaN:NaN', $times->isha, "Failed for method: {$method->value}");
        }
    }

    public function testAsrJurisdictionDifference(): void
    {
        $date = new DateTime('2023-10-15');
        $latitude = 21.4225;
        $longitude = 39.8262;
        $timezone = 3.0;

        $standardOptions = new CalculationOptions(
            CalculationMethod::MWL,
            AsrJurisdiction::STANDARD
        );
        $hanafiOptions = new CalculationOptions(
            CalculationMethod::MWL,
            AsrJurisdiction::HANAFI
        );

        $standardSdk = new PrayerTimesSDK($latitude, $longitude, $date, $timezone, $standardOptions);
        $hanafiSdk = new PrayerTimesSDK($latitude, $longitude, $date, $timezone, $hanafiOptions);

        $standardTimes = $standardSdk->getTimes();
        $hanafiTimes = $hanafiSdk->getTimes();

        $this->assertNotEquals($standardTimes->asr, $hanafiTimes->asr);
    }
}