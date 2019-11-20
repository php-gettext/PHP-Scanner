# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.1.1] - Unreleased
### Fixed
- Extract comments of functions prepended with echo, print or return [#6]
- Tested extracted comments from code

## [1.1.0] - 2019-11-19
### Added
- In v1.0, non-scalar arguments (others than string, int and float) were discarded. Now the arrays are included too [#5]

## [1.0.1] - 2019-11-11
### Fixed
- Anonimous function produce fatal errors [#1]

## 1.0.0 - 2019-11-05
First version

[#1]: https://github.com/php-gettext/PHP-Scanner/issues/1
[#5]: https://github.com/php-gettext/PHP-Scanner/issues/5
[#6]: https://github.com/php-gettext/PHP-Scanner/issues/6

[1.1.1]: https://github.com/php-gettext/PHP-Scanner/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/php-gettext/PHP-Scanner/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/php-gettext/PHP-Scanner/compare/v1.0.0...v1.0.1
