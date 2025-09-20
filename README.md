# Prayer Times Calculation Package for PHP

A minimalist and offline prayer times calculation package for PHP, supporting Laravel and other PHP frameworks.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.0-blue)](https://php.net)

## Features

- 🕌 Accurate prayer times calculation using astronomical algorithms
- 🌍 Support for multiple calculation methods (MWL, ISNA, Egypt, Makkah, Karachi, Custom)
- 📱 Offline calculation - no internet connection required
- ⚡ Fast and lightweight
- 🔧 Laravel integration with service provider and facade
- 🎯 Framework agnostic - works with any PHP framework
- ✅ Comprehensive test coverage

## Installation

### Via Composer

```bash
composer require muslims-community/prayer-times-calculation
```

### Laravel Auto-Discovery

The package supports Laravel's auto-discovery feature. The service provider and facade will be registered automatically.

### Manual Laravel Registration

If auto-discovery is disabled, add the service provider to your `config/app.php`:

```php
'providers' => [
    // ...
    MuslimsCommunity\PrayerTimes\PrayerTimesServiceProvider::class,
],

'aliases' => [
    // ...
    'PrayerTimes' => MuslimsCommunity\PrayerTimes\Facades\PrayerTimes::class,
],
```

### Publish Configuration (Laravel)

```bash
php artisan vendor:publish --tag=prayer-times-config
```

## Usage

### Basic Usage (Framework Agnostic)

```php
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
    new DateTime(),  // Date
    3.0,      // Timezone offset (UTC+3)
    $options
);

// Get prayer times
$times = $prayerTimes->getTimes();

echo "Fajr: " . $times->fajr . "\n";
echo "Sunrise: " . $times->sunrise . "\n";
echo "Dhuhr: " . $times->dhuhr . "\n";
echo "Asr: " . $times->asr . "\n";
echo "Maghrib: " . $times->maghrib . "\n";
echo "Isha: " . $times->isha . "\n";
```

### Laravel Usage with Facade

```php
use MuslimsCommunity\PrayerTimes\Facades\PrayerTimes;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

// Using the facade with parameters
$times = PrayerTimes::calculate(
    latitude: 21.4225,
    longitude: 39.8262,
    date: new DateTime('2023-10-15'),
    timezone: 3.0,
    method: CalculationMethod::MWL,
    asrJurisdiction: AsrJurisdiction::STANDARD
);

// Using the facade with configuration
$times = PrayerTimes::calculateFromConfig(
    latitude: 21.4225,
    longitude: 39.8262
);

// Convert to array
$timesArray = $times->toArray();
```

### Laravel Usage with Dependency Injection

```php
use MuslimsCommunity\PrayerTimes\PrayerTimesManager;

class PrayerController extends Controller
{
    public function __construct(
        private PrayerTimesManager $prayerTimesManager
    ) {}

    public function getTimes(Request $request)
    {
        $times = $this->prayerTimesManager->calculate(
            $request->latitude,
            $request->longitude
        );

        return response()->json($times->toArray());
    }
}
```

### Custom Calculation Method

```php
use MuslimsCommunity\PrayerTimes\Data\CalculationOptions;
use MuslimsCommunity\PrayerTimes\Enums\CalculationMethod;
use MuslimsCommunity\PrayerTimes\Enums\AsrJurisdiction;

$options = new CalculationOptions(
    CalculationMethod::CUSTOM,
    AsrJurisdiction::HANAFI,
    18.5,  // Custom Fajr angle
    17.5   // Custom Isha angle
);

$prayerTimes = new PrayerTimesSDK(
    33.6844,  // Latitude
    73.0479,  // Longitude
    new DateTime(),
    5.0,      // UTC+5
    $options
);

$times = $prayerTimes->getTimes();
```

## Configuration (Laravel)

The configuration file `config/prayer-times.php` allows you to set default values:

```php
return [
    'method' => env('PRAYER_TIMES_METHOD', 'MWL'),
    'asr_jurisdiction' => env('PRAYER_TIMES_ASR_JURISDICTION', 'Standard'),
    'timezone' => env('PRAYER_TIMES_TIMEZONE', null),
    'fajr_angle' => env('PRAYER_TIMES_FAJR_ANGLE', null),
    'isha_angle' => env('PRAYER_TIMES_ISHA_ANGLE', null),
];
```

### Environment Variables

Add these to your `.env` file:

```env
PRAYER_TIMES_METHOD=MWL
PRAYER_TIMES_ASR_JURISDICTION=Standard
PRAYER_TIMES_TIMEZONE=3
PRAYER_TIMES_FAJR_ANGLE=18
PRAYER_TIMES_ISHA_ANGLE=17
```

## Calculation Methods

| Method | Fajr Angle | Isha Angle | Description |
|--------|------------|------------|-------------|
| MWL | 18° | 17° | Muslim World League |
| ISNA | 15° | 15° | Islamic Society of North America |
| Egypt | 19.5° | 17.5° | Egyptian General Authority of Survey |
| Makkah | 18.5° | 18.5° | Umm Al-Qura University, Makkah |
| Karachi | 18° | 18° | University of Islamic Sciences, Karachi |
| Custom | Custom | Custom | User-defined angles |

## Asr Jurisdictions

- **Standard**: Asr when shadow length equals object length plus shadow at noon
- **Hanafi**: Asr when shadow length equals twice the object length plus shadow at noon

## Testing

```bash
composer test
```

## Requirements

- PHP 8.0 or higher
- Laravel 9.0+ (for Laravel integration)

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Credits

- [Mahmoud Alsamman](https://github.com/mahmoudalsaman)
- Based on the TypeScript version of the prayer times calculation library

## Support

For support, please open an issue on GitHub or contact the maintainers.