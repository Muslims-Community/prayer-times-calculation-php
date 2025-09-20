<?php

namespace MuslimsCommunity\PrayerTimes\Tests;

use PHPUnit\Framework\TestCase;
use MuslimsCommunity\PrayerTimes\Methods;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use InvalidArgumentException;

class MethodsTest extends TestCase
{
    public function testGetMethodAnglesForPredefinedMethods(): void
    {
        $mwlAngles = Methods::getMethodAngles(CalculationMethod::MWL);
        $this->assertEquals(18, $mwlAngles->fajr);
        $this->assertEquals(17, $mwlAngles->isha);

        $isnaAngles = Methods::getMethodAngles(CalculationMethod::ISNA);
        $this->assertEquals(15, $isnaAngles->fajr);
        $this->assertEquals(15, $isnaAngles->isha);

        $egyptAngles = Methods::getMethodAngles(CalculationMethod::EGYPT);
        $this->assertEquals(19.5, $egyptAngles->fajr);
        $this->assertEquals(17.5, $egyptAngles->isha);

        $makkahAngles = Methods::getMethodAngles(CalculationMethod::MAKKAH);
        $this->assertEquals(18.5, $makkahAngles->fajr);
        $this->assertEquals(18.5, $makkahAngles->isha);

        $karachiAngles = Methods::getMethodAngles(CalculationMethod::KARACHI);
        $this->assertEquals(18, $karachiAngles->fajr);
        $this->assertEquals(18, $karachiAngles->isha);
    }

    public function testGetMethodAnglesForCustomMethod(): void
    {
        $customAngles = Methods::getMethodAngles(CalculationMethod::CUSTOM, 20.0, 18.0);
        $this->assertEquals(20.0, $customAngles->fajr);
        $this->assertEquals(18.0, $customAngles->isha);
    }

    public function testGetMethodAnglesForCustomMethodWithoutAngles(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Custom method requires both fajrAngle and ishaAngle to be specified');

        Methods::getMethodAngles(CalculationMethod::CUSTOM);
    }

    public function testGetMethodAnglesForCustomMethodWithOnlyFajrAngle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Custom method requires both fajrAngle and ishaAngle to be specified');

        Methods::getMethodAngles(CalculationMethod::CUSTOM, 20.0);
    }

    public function testGetMethodAnglesForCustomMethodWithOnlyIshaAngle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Custom method requires both fajrAngle and ishaAngle to be specified');

        Methods::getMethodAngles(CalculationMethod::CUSTOM, null, 18.0);
    }
}