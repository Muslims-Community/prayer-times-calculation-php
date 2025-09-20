<?php

namespace MuslimsCommunity\PrayerTimes\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use MuslimsCommunity\PrayerTimes\Utils\Astronomical;

class AstronomicalTest extends TestCase
{
    public function testDegreesToRadians(): void
    {
        $this->assertEqualsWithDelta(M_PI, Astronomical::degreesToRadians(180), 0.001);
        $this->assertEqualsWithDelta(M_PI / 2, Astronomical::degreesToRadians(90), 0.001);
        $this->assertEqualsWithDelta(0, Astronomical::degreesToRadians(0), 0.001);
    }

    public function testRadiansToDegrees(): void
    {
        $this->assertEqualsWithDelta(180, Astronomical::radiansToDegrees(M_PI), 0.001);
        $this->assertEqualsWithDelta(90, Astronomical::radiansToDegrees(M_PI / 2), 0.001);
        $this->assertEqualsWithDelta(0, Astronomical::radiansToDegrees(0), 0.001);
    }

    public function testNormalizeAngle(): void
    {
        $this->assertEqualsWithDelta(45, Astronomical::normalizeAngle(45), 0.001);
        $this->assertEqualsWithDelta(45, Astronomical::normalizeAngle(405), 0.001);
        $this->assertEqualsWithDelta(315, Astronomical::normalizeAngle(-45), 0.001);
        $this->assertEqualsWithDelta(0, Astronomical::normalizeAngle(360), 0.001);
    }

    public function testJulianDate(): void
    {
        $date = new DateTime('2023-10-15');
        $jd = Astronomical::julianDate($date);

        $this->assertIsFloat($jd);
        $this->assertGreaterThan(2000000, $jd);
    }

    public function testFormatTime(): void
    {
        $this->assertEquals('12:30', Astronomical::formatTime(12.5));
        $this->assertEquals('06:15', Astronomical::formatTime(6.25));
        $this->assertEquals('00:00', Astronomical::formatTime(0));
        $this->assertEquals('23:59', Astronomical::formatTime(23.983333));
        $this->assertEquals('NaN:NaN', Astronomical::formatTime(NAN));
    }

    public function testAddMinutes(): void
    {
        $this->assertEquals('12:35', Astronomical::addMinutes('12:30', 5));
        $this->assertEquals('13:00', Astronomical::addMinutes('12:30', 30));
        $this->assertEquals('00:15', Astronomical::addMinutes('23:45', 30));
        $this->assertEquals('12:25', Astronomical::addMinutes('12:30', -5));
        $this->assertEquals('23:30', Astronomical::addMinutes('00:00', -30));
    }

    public function testTimeFromAngle(): void
    {
        $this->assertEqualsWithDelta(1, Astronomical::timeFromAngle(15), 0.001);
        $this->assertEqualsWithDelta(2, Astronomical::timeFromAngle(30), 0.001);
        $this->assertEqualsWithDelta(6, Astronomical::timeFromAngle(90), 0.001);
    }
}