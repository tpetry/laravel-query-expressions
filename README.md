# Laravel Query Expressions to replace DB::raw() calls

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tpetry/laravel-query-expressions.svg?style=flat-square)](https://packagist.org/packages/tpetry/laravel-query-expressions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/tpetry/laravel-query-expressions/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/tpetry/laravel-query-expressions/actions/workflows/tests.yml?query=workflow%3Atests+branch%3Amain)
[![GitHub Static Analysis Action Status](https://img.shields.io/github/actions/workflow/status/tpetry/laravel-query-expressions/static-analysis.yml?branch=main&label=static%20analysis&style=flat-square)](https://github.com/tpetry/laravel-query-expressions/actions/workflows/static-analysis.yml?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/tpetry/laravel-query-expressions/code-style.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/tpetry/laravel-query-expressions/actions/workflows/code-style.yml?query=workflow%3Atests+branch%3Amain)

Laravel's database implementation provides a good way of working with multiple databases while abstracting away the inner workings.
You don't have to consider minor syntax differences when using a query builder or how column names are escaped to avoid interfering with reserved keywords.

However, when we want to use more database functionality, we have to fall back to raw SQL expressions and write database-specific code to e.g. quote column names or column aliases.
The Query Expressions package builds on new features introduced in Laravel 10 to solve that problem.
All provided classes abstract some SQL functionality that is automatically transformed to the correct syntax for your used database engine.

```php
// Instead of:
DB::table('table')
    ->when(isPostgreSQL(), fn ($query) => $query->select(DB::raw('coalesce("user", "admin") AS "value"')))
    ->when(isMySQL(), fn ($query) => $query->select(DB::raw('coalesce(`user`, `admin`) AS `value`')))

// You can use:
DB::table('table')
    ->select(new Alias(new Coalesce(['user', 'admin']), 'count'));
```

## Installation

You can install the package via composer:

```bash
composer require tpetry/laravel-query-expressions
```

## Usage

TODO

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [tpetry](https://github.com/tpetry)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
