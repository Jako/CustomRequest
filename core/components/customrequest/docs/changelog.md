# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.2] - 2019-03-20
### Added
- Edit CustomRequest system settings in the custom manager page
### Changed
- Use $_REQUEST variable instead of $_SERVER variable to avoid subfolder installation issues
- Retrieve $requestUri only during OnPageNotFound

## [1.3.1] - 2018-11-13
### Added
- Log found configuration and the set request parameters
### Changed
- Cache valid configurations, even if one configuration is invalid
- Change the log level of debug messages

## [1.3.0] - 2018-07-06
### Added
- Equal aliases in different contexts are now possible
- Switch the context to the requested resource context
- Change the default engine to InnoDB
### Changed
- Calculate the alias on base of the resource context

## [1.2.7] - 2017-09-28
### Changed
- Regard the order of the configs

## [1.2.6] - 2016-06-13
### Added
- Automatic urldecode of the remaining URL parameter part

## [1.2.5] - 2016-05-19
### Changed
- Bugfix: Save configurations issue

## [1.2.4] - 2016-02-04
### Changed
- Fixing a cache issue with URLs in different contexts than the current

## [1.2.3] - 2016-01-27
### Changed
- Fixing a configuration caching issue

## [1.2.2] - 2016-01-19
### Added
- Fixing a GPM table prefix issue

## [1.2.1] - 2016-01-13
### Added
- Enhanced validation rules for a configuration during create/update

## [1.2.0] - 2016-01-11
### Added
- Configurations are cached
- The alias of a configuration could contain a regular expression

## [1.1.4] - 2015-09-10
### Changed
- Fixing the RegEx evaluation

## [1.1.3] - 2015-04-18
### Changed
- Fixing the cultureKey replacement

## [1.1.2] - 2015-03-17
### Changed
- Fixing the cultureKey replacement

## [1.1.1] - 2015-03-03
### Changed
- Bugfixes

## [1.1.0] - 2015-03-03
### Added
- Custom manager page that replaces the config files
- Automatic Import for old config files

## [1.0.3] - 2015-01-29
### Added
- Build by Git-Package-Management
### Changed
- Bugfix: Existing url parameters issue

## [1.0.2] - 2014-05-27
### Added
- Detect/Log missing/not valid config files
### Changed
- Debug logging

## [1.0.1] - 2013-1-27
### Changed
- Updated documentation and examples ('resourceId' instead of 'id')

## [1.0.0] - 2013-06-09
### Added
- Initial release for MODX Revolution
