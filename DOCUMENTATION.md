# Prayer Times Calculation PHP - API Documentation

## Table of Contents

1. [Overview](#overview)
2. [Installation](#installation)
3. [Quick Start](#quick-start)
4. [API Reference](#api-reference)
5. [Configuration](#configuration)
6. [Examples](#examples)
7. [Testing](#testing)
8. [Contributing](#contributing)

## Overview

The Prayer Times Calculation PHP package provides accurate Islamic prayer times calculation using astronomical algorithms. It's designed to work offline without requiring internet connectivity and supports multiple calculation methods used worldwide.

### Key Features

- 🕌 **Accurate Calculations**: Uses precise astronomical algorithms
- 🌍 **Multiple Methods**: Supports MWL, ISNA, Egypt, Makkah, Karachi, and custom methods
- 📱 **Offline**: No internet connection required
- ⚡ **Performance**: Fast and lightweight
- 🔧 **Laravel Integration**: Built-in service provider and facade
- 🎯 **Framework Agnostic**: Works with any PHP framework
- ✅ **Well Tested**: Comprehensive test coverage

## Installation

### Requirements

- PHP 8.0 or higher
- Composer

### Via Composer

```bash
composer require muslims-community/prayer-times-calculation
```

### Laravel Auto-Discovery

For Laravel projects, the package automatically registers itself. No manual configuration needed.

## Quick Start

### Basic Usage

```php
<?php

use DateTime;
use MuslimsCommunity\PrayerTimes\PrayerTimesSDK;
use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

// Create calculation options
$options = new CalculationOptions(
    CalculationMethod::MWL,
    AsrJurisdiction::STANDARD
);

// Initialize the SDK
$prayerTimes = new PrayerTimesSDK(
    21.4225,  // Latitude (Makkah)
    39.8262,  // Longitude (Makkah)
    new DateTime('2023-10-15'),
    3.0,      // Timezone offset (UTC+3)
    $options
);

// Get prayer times
$times = $prayerTimes->getTimes();

echo "Today's Prayer Times:\n";
echo "Fajr: " . $times->fajr . "\n";
echo "Sunrise: " . $times->sunrise . "\n";
echo "Dhuhr: " . $times->dhuhr . "\n";
echo "Asr: " . $times->asr . "\n";
echo "Maghrib: " . $times->maghrib . "\n";
echo "Isha: " . $times->isha . "\n";
```

## API Reference

### Core Classes

#### PrayerTimesSDK

The main class for calculating prayer times.

```php
class PrayerTimesSDK
{
    public function __construct(
        float $latitude,
        float $longitude,
        DateTime $date,
        float $timezone,
        CalculationOptions $options
    );

    public function getTimes(): PrayerTimes;
}
```

**Parameters:**
- `$latitude`: Geographic latitude (-90 to 90)
- `$longitude`: Geographic longitude (-180 to 180)
- `$date`: Date for calculation
- `$timezone`: Timezone offset from UTC in hours
- `$options`: Calculation options (method, jurisdiction, angles)

#### CalculationOptions

Configuration for prayer time calculations.

```php
class CalculationOptions
{
    public function __construct(
        CalculationMethod $method,
        AsrJurisdiction $asrJurisdiction,
        ?float $fajrAngle = null,
        ?float $ishaAngle = null
    );
}
```

#### PrayerTimes

Result object containing calculated prayer times.

```php
class PrayerTimes
{
    public readonly string $fajr;
    public readonly string $sunrise;
    public readonly string $dhuhr;
    public readonly string $asr;
    public readonly string $maghrib;
    public readonly string $isha;

    public function toArray(): array;
}
```

### Enums

#### CalculationMethod

Available calculation methods:

```php
enum CalculationMethod: string
{
    case MWL = 'MWL';           // Muslim World League
    case ISNA = 'ISNA';         // Islamic Society of North America
    case EGYPT = 'Egypt';       // Egyptian General Authority
    case MAKKAH = 'Makkah';     // Umm Al-Qura University
    case KARACHI = 'Karachi';   // University of Islamic Sciences
    case CUSTOM = 'Custom';     // Custom angles
}
```

#### AsrJurisdiction

Asr calculation methods:

```php
enum AsrJurisdiction: string
{
    case STANDARD = 'Standard'; // Shadow length = object length
    case HANAFI = 'Hanafi';     // Shadow length = 2 × object length
}
```

### Laravel Integration

#### Facade Usage

```php
use MuslimsCommunity\PrayerTimes\Facades\PrayerTimes;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;

// Calculate with parameters
$times = PrayerTimes::calculate(
    latitude: 21.4225,
    longitude: 39.8262,
    date: new DateTime(),
    timezone: 3.0,
    method: CalculationMethod::MWL
);

// Calculate using config defaults
$times = PrayerTimes::calculateFromConfig(
    latitude: 21.4225,
    longitude: 39.8262
);
```

#### Service Container

```php
use MuslimsCommunity\PrayerTimes\PrayerTimesManager;

// Dependency injection
class PrayerController extends Controller
{
    public function __construct(
        private PrayerTimesManager $prayerTimesManager
    ) {}

    public function getTimes(Request $request)
    {
        $times = $this->prayerTimesManager->calculate(
            $request->latitude,
            $request->longitude,
            $request->date ? new DateTime($request->date) : new DateTime(),
            $request->timezone ?? config('prayer-times.timezone')
        );

        return response()->json($times->toArray());
    }
}
```

## Configuration

### Laravel Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=prayer-times-config
```

Configuration file (`config/prayer-times.php`):

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Calculation Method
    |--------------------------------------------------------------------------
    | The default method for prayer time calculations.
    | Available: MWL, ISNA, Egypt, Makkah, Karachi, Custom
    */
    'method' => env('PRAYER_TIMES_METHOD', 'MWL'),

    /*
    |--------------------------------------------------------------------------
    | Asr Jurisdiction
    |--------------------------------------------------------------------------
    | The method for calculating Asr prayer time.
    | Available: Standard, Hanafi
    */
    'asr_jurisdiction' => env('PRAYER_TIMES_ASR_JURISDICTION', 'Standard'),

    /*
    |--------------------------------------------------------------------------
    | Default Timezone
    |--------------------------------------------------------------------------
    | Default timezone offset in hours from UTC.
    */
    'timezone' => env('PRAYER_TIMES_TIMEZONE', null),

    /*
    |--------------------------------------------------------------------------
    | Custom Angles (for Custom method only)
    |--------------------------------------------------------------------------
    | Custom Fajr and Isha angles in degrees.
    */
    'fajr_angle' => env('PRAYER_TIMES_FAJR_ANGLE', null),
    'isha_angle' => env('PRAYER_TIMES_ISHA_ANGLE', null),
];
```

### Environment Variables

Add to your `.env` file:

```env
PRAYER_TIMES_METHOD=MWL
PRAYER_TIMES_ASR_JURISDICTION=Standard
PRAYER_TIMES_TIMEZONE=3
PRAYER_TIMES_FAJR_ANGLE=18
PRAYER_TIMES_ISHA_ANGLE=17
```

## Examples

### Example 1: Basic Calculation

```php
<?php

require 'vendor/autoload.php';

use DateTime;
use MuslimsCommunity\PrayerTimes\PrayerTimesSDK;
use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

// New York coordinates
$latitude = 40.7128;
$longitude = -74.0060;
$timezone = -5.0; // EST

$options = new CalculationOptions(
    CalculationMethod::ISNA,
    AsrJurisdiction::STANDARD
);

$prayerTimes = new PrayerTimesSDK(
    $latitude,
    $longitude,
    new DateTime(),
    $timezone,
    $options
);

$times = $prayerTimes->getTimes();
print_r($times->toArray());
```

### Example 2: Custom Calculation Method

```php
<?php

use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

// Custom angles for specific location
$options = new CalculationOptions(
    CalculationMethod::CUSTOM,
    AsrJurisdiction::HANAFI,
    18.5,  // Custom Fajr angle
    17.5   // Custom Isha angle
);

$prayerTimes = new PrayerTimesSDK(
    33.6844,  // Islamabad
    73.0479,
    new DateTime(),
    5.0,      // PKT
    $options
);

$times = $prayerTimes->getTimes();
echo "Prayer times with custom angles:\n";
foreach ($times->toArray() as $prayer => $time) {
    echo ucfirst($prayer) . ": " . $time . "\n";
}
```

### Example 3: Laravel API Endpoint

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MuslimsCommunity\PrayerTimes\Facades\PrayerTimes;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;

class PrayerTimesController extends Controller
{
    public function getTimes(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'date' => 'sometimes|date',
            'timezone' => 'sometimes|numeric|between:-12,14',
            'method' => 'sometimes|in:MWL,ISNA,Egypt,Makkah,Karachi,Custom'
        ]);

        try {
            $times = PrayerTimes::calculate(
                latitude: $request->latitude,
                longitude: $request->longitude,
                date: $request->date ? new \DateTime($request->date) : new \DateTime(),
                timezone: $request->timezone ?? 0,
                method: CalculationMethod::from($request->method ?? 'MWL')
            );

            return response()->json([
                'success' => true,
                'data' => $times->toArray(),
                'location' => [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating prayer times',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
```

### Example 4: Batch Calculation for Multiple Days

```php
<?php

use DateTime;
use DateInterval;
use MuslimsCommunity\PrayerTimes\PrayerTimesSDK;
use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

function calculateMonthlyPrayerTimes(float $lat, float $lng, float $tz): array
{
    $options = new CalculationOptions(
        CalculationMethod::MWL,
        AsrJurisdiction::STANDARD
    );

    $results = [];
    $date = new DateTime();

    for ($i = 0; $i < 30; $i++) {
        $prayerTimes = new PrayerTimesSDK($lat, $lng, $date, $tz, $options);
        $times = $prayerTimes->getTimes();

        $results[$date->format('Y-m-d')] = $times->toArray();
        $date->add(new DateInterval('P1D'));
    }

    return $results;
}

// Usage
$monthlyTimes = calculateMonthlyPrayerTimes(21.4225, 39.8262, 3.0);
```

## Calculation Methods Details

| Method | Fajr Angle | Isha Angle | Used By |
|--------|------------|------------|---------|
| **MWL** | 18° | 17° | Muslim World League |
| **ISNA** | 15° | 15° | Islamic Society of North America |
| **Egypt** | 19.5° | 17.5° | Egyptian General Authority of Survey |
| **Makkah** | 18.5° | 18.5° | Umm Al-Qura University, Makkah |
| **Karachi** | 18° | 18° | University of Islamic Sciences, Karachi |
| **Custom** | User-defined | User-defined | Custom implementation |

## Testing

Run the test suite:

```bash
# Run all tests
composer test

# Run with coverage
composer test -- --coverage-text

# Run specific test
./vendor/bin/phpunit tests/PrayerTimesSDKTest.php
```

### Test Structure

```
tests/
├── AstronomicalTest.php      # Tests for astronomical calculations
├── MethodsTest.php           # Tests for different calculation methods
└── PrayerTimesSDKTest.php    # Main SDK functionality tests
```

## Error Handling

The package throws exceptions for invalid inputs:

```php
try {
    $prayerTimes = new PrayerTimesSDK(
        91.0, // Invalid latitude (> 90)
        0.0,
        new DateTime(),
        0.0,
        $options
    );
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage();
}
```

## Performance Considerations

- Calculations are performed locally without network requests
- Results can be cached for repeated use with same parameters
- Consider using Laravel's cache for frequently requested locations

```php
// Example caching in Laravel
$cacheKey = "prayer_times_{$lat}_{$lng}_" . date('Y-m-d');
$times = Cache::remember($cacheKey, 86400, function () use ($lat, $lng) {
    return PrayerTimes::calculateFromConfig($lat, $lng);
});
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass (`composer test`)
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

### Development Setup

```bash
# Clone the repository
git clone https://github.com/Muslims-Community/prayer-times-calculation-php.git
cd prayer-times-calculation-php

# Install dependencies
composer install

# Run tests
composer test

# Check code style
composer phpcs
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

- 📧 Email: memoibraheem1@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/Muslims-Community/prayer-times-calculation-php/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/Muslims-Community/prayer-times-calculation-php/discussions)

## Acknowledgments

- Based on astronomical algorithms for accurate prayer time calculations
- Inspired by various prayer time calculation libraries
- Thanks to the Muslim developer community for feedback and contributions