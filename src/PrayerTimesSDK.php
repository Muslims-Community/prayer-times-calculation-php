<?php

namespace MuslimsCommunity\PrayerTimes;

use DateTime;
use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Data\PrayerTimes;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;
use MuslimsCommunity\PrayerTimes\Utils\Astronomical;

class PrayerTimesSDK
{
    private float $latitude;
    private float $longitude;
    private DateTime $date;
    private float $timezone;
    private CalculationOptions $options;

    public function __construct(
        float $latitude,
        float $longitude,
        DateTime $date,
        float $timezone,
        CalculationOptions $options
    ) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->date = $date;
        $this->timezone = $timezone;
        $this->options = $options;
    }

    public function getTimes(): PrayerTimes
    {
        $jd = Astronomical::julianDate($this->date);
        $declination = Astronomical::sunDeclination($jd);
        $eqTime = Astronomical::equationOfTime($jd);
        $angles = Methods::getMethodAngles(
            $this->options->method,
            $this->options->fajrAngle,
            $this->options->ishaAngle
        );

        $dhuhr = $this->calculateDhuhr($eqTime);
        $sunrise = $this->calculateSunrise($declination, $eqTime);
        $sunset = $this->calculateSunset($declination, $eqTime);
        $fajr = $this->calculateFajr($declination, $eqTime, $angles->fajr);
        $isha = $this->calculateIsha($declination, $eqTime, $angles->isha);
        $asr = $this->calculateAsr($declination, $eqTime, $this->options->asrJurisdiction);

        $sunsetTime = Astronomical::formatTime($sunset);
        $maghribTime = $sunsetTime === 'NaN:NaN' ? 'NaN:NaN' : Astronomical::addMinutes($sunsetTime, 1);

        return new PrayerTimes(
            Astronomical::formatTime($fajr),
            Astronomical::formatTime($sunrise),
            Astronomical::formatTime($dhuhr),
            Astronomical::formatTime($asr),
            $maghribTime,
            Astronomical::formatTime($isha)
        );
    }

    private function calculateDhuhr(float $eqTime): float
    {
        $timeCorrection = $eqTime + 4 * $this->longitude - 60 * $this->timezone;
        $solarNoon = 12 - $timeCorrection / 60;
        return $solarNoon + 2 / 60;
    }

    private function calculateSunrise(float $declination, float $eqTime): float
    {
        $hourAngle = Astronomical::calculateHourAngle($this->latitude, $declination, -0.833);
        if (is_nan($hourAngle)) {
            return NAN;
        }

        $timeCorrection = $eqTime + 4 * $this->longitude - 60 * $this->timezone;
        $sunrise = 12 - Astronomical::timeFromAngle($hourAngle) - $timeCorrection / 60;

        return $sunrise >= 0 ? $sunrise : $sunrise + 24;
    }

    private function calculateSunset(float $declination, float $eqTime): float
    {
        $hourAngle = Astronomical::calculateHourAngle($this->latitude, $declination, -0.833);
        if (is_nan($hourAngle)) {
            return NAN;
        }

        $timeCorrection = $eqTime + 4 * $this->longitude - 60 * $this->timezone;
        $sunset = 12 + Astronomical::timeFromAngle($hourAngle) - $timeCorrection / 60;
        return $sunset >= 0 ? $sunset : $sunset + 24;
    }

    private function calculateFajr(float $declination, float $eqTime, float $fajrAngle): float
    {
        $hourAngle = Astronomical::calculateHourAngle($this->latitude, $declination, -$fajrAngle);
        if (is_nan($hourAngle)) {
            return NAN;
        }

        $timeCorrection = $eqTime + 4 * $this->longitude - 60 * $this->timezone;
        $fajr = 12 - Astronomical::timeFromAngle($hourAngle) - $timeCorrection / 60;
        return $fajr >= 0 ? $fajr : $fajr + 24;
    }

    private function calculateIsha(float $declination, float $eqTime, float $ishaAngle): float
    {
        $hourAngle = Astronomical::calculateHourAngle($this->latitude, $declination, -$ishaAngle);
        if (is_nan($hourAngle)) {
            return NAN;
        }

        $timeCorrection = $eqTime + 4 * $this->longitude - 60 * $this->timezone;
        $isha = 12 + Astronomical::timeFromAngle($hourAngle) - $timeCorrection / 60;
        return $isha >= 0 ? $isha : $isha + 24;
    }

    private function calculateAsr(float $declination, float $eqTime, AsrJurisdiction $jurisdiction): float
    {
        $shadowFactor = $jurisdiction === AsrJurisdiction::HANAFI ? 2 : 1;
        $latRad = Astronomical::degreesToRadians($this->latitude);
        $decRad = Astronomical::degreesToRadians($declination);

        $tanAltitude = 1 / ($shadowFactor + tan(abs($latRad - $decRad)));
        $altitude = Astronomical::radiansToDegrees(atan($tanAltitude));

        $hourAngle = Astronomical::calculateHourAngle($this->latitude, $declination, 90 - $altitude);
        if (is_nan($hourAngle)) {
            return NAN;
        }

        $timeCorrection = $eqTime + 4 * $this->longitude - 60 * $this->timezone;
        $asr = 12 + Astronomical::timeFromAngle($hourAngle) - $timeCorrection / 60;
        return $asr >= 0 ? $asr : $asr + 24;
    }
}