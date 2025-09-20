# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-09-20

### Added
- 🚀 Initial release of Prayer Times Calculation PHP package
- 🕌 Core PrayerTimesSDK class for accurate prayer times calculation
- 🌍 Support for multiple calculation methods:
  - Muslim World League (MWL)
  - Islamic Society of North America (ISNA)
  - Egyptian General Authority of Survey (Egypt)
  - Umm Al-Qura University, Makkah (Makkah)
  - University of Islamic Sciences, Karachi (Karachi)
  - Custom angles support
- ⚖️ Support for different Asr jurisdictions (Standard, Hanafi)
- 🔧 Laravel service provider and facade integration with auto-discovery
- ⚙️ Configuration file for Laravel projects with environment variable support
- ✅ Comprehensive test suite with PHPUnit (AstronomicalTest, MethodsTest, PrayerTimesSDKTest)
- 🧮 Astronomical utility functions for precise calculations
- 🎯 Framework-agnostic design - works with any PHP framework
- 📖 Complete documentation with API reference and examples
- 📦 Packagist integration for easy installation via Composer

### Features
- 📱 Offline prayer times calculation - no internet required
- 🔬 Accurate astronomical algorithms based on proven calculations
- 🚀 PHP 8.0+ support with modern PHP features (enums, typed properties, readonly properties)
- 🔄 Laravel auto-discovery support for seamless integration
- ⚙️ Configurable calculation methods and custom angles
- ⏰ Time formatting and manipulation utilities
- 🌐 Support for any geographic location worldwide
- 🎨 Clean, object-oriented API design
- 💾 Lightweight with minimal dependencies

### Documentation
- 📚 Comprehensive README with quick start guide
- 📖 Detailed API documentation (DOCUMENTATION.md)
- 🧪 Code examples for all major use cases
- 🔧 Laravel integration examples
- 🌍 Multi-location calculation examples
- ⚙️ Configuration guide for Laravel projects

### Technical Details
- Minimum PHP version: 8.0
- Dependencies: None for core functionality
- Laravel compatibility: 9.0+
- Test coverage: Comprehensive unit tests
- Code style: PSR-12 compliant
- Architecture: SOLID principles, clean code practices