<?php

namespace MuslimsCommunity\PrayerTimes;

use MuslimsCommunity\PrayerTimes\Data\MethodAngles;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use InvalidArgumentException;

class Methods
{
    private const CALCULATION_METHODS = [
        'MWL' => ['fajr' => 18, 'isha' => 17],
        'ISNA' => ['fajr' => 15, 'isha' => 15],
        'Egypt' => ['fajr' => 19.5, 'isha' => 17.5],
        'Makkah' => ['fajr' => 18.5, 'isha' => 18.5],
        'Karachi' => ['fajr' => 18, 'isha' => 18],
        'Custom' => null,
    ];

    public static function getMethodAngles(
        CalculationMethod $method,
        ?float $customFajr = null,
        ?float $customIsha = null
    ): MethodAngles {
        if ($method === CalculationMethod::CUSTOM) {
            if ($customFajr === null || $customIsha === null) {
                throw new InvalidArgumentException(
                    'Custom method requires both fajrAngle and ishaAngle to be specified'
                );
            }
            return new MethodAngles($customFajr, $customIsha);
        }

        $angles = self::CALCULATION_METHODS[$method->value] ?? null;
        if (!$angles) {
            throw new InvalidArgumentException("Invalid calculation method: {$method->value}");
        }

        return new MethodAngles($angles['fajr'], $angles['isha']);
    }
}