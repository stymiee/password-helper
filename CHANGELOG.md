# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2026-06-05

### Added
- New example file `examples/PasswordHelperExample.php` demonstrating password generation, validation, and strength checking

### Changed
- **Updated codebase to support PHP 8.0**
- Improved password complexity logic
- Updated test cases in `PolicyTest`, `ValidatorTest`, and `GeneratorTest` to match new class interfaces
- Added getter methods for `minLength` and `maxLength` in the `Generator` class
- Updated assertions in `GeneratorTest` to use PHPUnit 8.5 compatible methods
- Adjusted test expectations in `ValidatorTest` to align with current validation logic
- Updated README to reflect PHP 8.0 compatibility and requirements

## [2.0.0] - 2024-06-07

### Added
- Added `__debugInfo()` to PHP 7+ for debugging purposes
- Added PHP Documentor support
- Converted `$this->` to `self::` for unit tests

## [1.0.0] - 2020-06-10

### Added
- Initial release
