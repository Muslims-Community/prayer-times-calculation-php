<?php

namespace MuslimsCommunity\PrayerTimes\Utils;

use DateTime;

class Astronomical
{
    public static function degreesToRadians(float $degrees): float
    {
        return ($degrees * M_PI) / 180;
    }

    public static function radiansToDegrees(float $radians): float
    {
        return ($radians * 180) / M_PI;
    }

    public static function normalizeAngle(float $angle): float
    {
        $angle = fmod($angle, 360);
        return $angle < 0 ? $angle + 360 : $angle;
    }

    public static function julianDate(DateTime $date): float
    {
        $year = (int) $date->format('Y');
        $month = (int) $date->format('n');
        $day = (int) $date->format('j');

        $adjustedYear = $year;
        $adjustedMonth = $month;

        if ($month <= 2) {
            $adjustedYear -= 1;
            $adjustedMonth += 12;
        }

        $a = floor($adjustedYear / 100);
        $b = 2 - $a + floor($a / 4);

        return floor(365.25 * ($adjustedYear + 4716)) +
               floor(30.6001 * ($adjustedMonth + 1)) +
               $day + $b - 1524.5;
    }

    public static function equationOfTime(float $jd): float
    {
        $n = $jd - 2451545.0;
        $l = self::normalizeAngle(280.460 + 0.9856474 * $n);
        $g = self::degreesToRadians(self::normalizeAngle(357.528 + 0.9856003 * $n));
        $lambda = self::degreesToRadians($l + 1.915 * sin($g) + 0.020 * sin(2 * $g));

        $alpha = atan2(cos(self::degreesToRadians(23.439)) * sin($lambda), cos($lambda));
        $deltaAlpha = $l - self::radiansToDegrees($alpha);

        if ($deltaAlpha > 180) {
            return ($deltaAlpha - 360) * 4;
        }
        if ($deltaAlpha < -180) {
            return ($deltaAlpha + 360) * 4;
        }
        return $deltaAlpha * 4;
    }

    public static function sunDeclination(float $jd): float
    {
        $n = $jd - 2451545.0;
        $l = self::normalizeAngle(280.460 + 0.9856474 * $n);
        $g = self::degreesToRadians(self::normalizeAngle(357.528 + 0.9856003 * $n));
        $lambda = self::degreesToRadians($l + 1.915 * sin($g) + 0.020 * sin(2 * $g));

        return self::radiansToDegrees(asin(sin(self::degreesToRadians(23.439)) * sin($lambda)));
    }

    public static function calculateHourAngle(float $latitude, float $declination, float $angle): float
    {
        $latRad = self::degreesToRadians($latitude);
        $decRad = self::degreesToRadians($declination);
        $angleRad = self::degreesToRadians($angle);

        $cosHourAngle = (cos($angleRad) - sin($decRad) * sin($latRad)) /
                        (cos($decRad) * cos($latRad));

        if (abs($cosHourAngle) > 1) {
            return NAN;
        }

        return self::radiansToDegrees(acos($cosHourAngle));
    }

    public static function timeFromAngle(float $hourAngle): float
    {
        return $hourAngle / 15;
    }

    public static function formatTime(float $hours): string
    {
        if (is_nan($hours)) {
            return 'NaN:NaN';
        }

        $totalMinutes = round($hours * 60);
        $h = floor($totalMinutes / 60) % 24;
        $m = $totalMinutes % 60;

        return sprintf('%02d:%02d', $h, $m);
    }

    public static function addMinutes(string $timeStr, int $minutes): string
    {
        $parts = explode(':', $timeStr);
        $hours = isset($parts[0]) ? (int) $parts[0] : 0;
        $mins = isset($parts[1]) ? (int) $parts[1] : 0;
        $totalMinutes = $hours * 60 + $mins + $minutes;

        while ($totalMinutes < 0) {
            $totalMinutes += 24 * 60;
        }

        $newHours = floor($totalMinutes / 60) % 24;
        $newMins = $totalMinutes % 60;

        return sprintf('%02d:%02d', $newHours, $newMins);
    }
}