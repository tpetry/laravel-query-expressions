# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.8.0] - 2023-11-10
### Added
* `Abs` mathematical function

## [0.7.1] - 2023-09-18
### Added
* Argument type of ManyArgumentsExpressions missed passing column names as string

## [0.7.0] - 2023-09-01
### Added
* `Upper` and `Lower` string transformations

## [0.6.0] - 2023-08-31
### Added
* `Count` aggregate has an optional distinct parameter

## [0.5.1] - 2023-06-26
### Fixed
* Alias names with dots did not work

## [0.5.0] - 2023-06-09
### Added
* Conditional and comparison expressions can directly be used as sole where() parameter
* StrListContains expression to emulate MySQL's FIND_IN_SET() for all databases
* Concat expression to harmonize string concatenation

## [0.4.0] - 2023-06-01
### Added
* `Value` class to embed any escaped value into a query

## [0.3.0] - 2023-05-16
### Added
* Current time
* Timestamp binning

## [0.2.0] - 2023-04-21
### Added
* UUIDv4 generation

## [0.1.0] - 2023-03-16
### Added
* Initial release of query expressions
