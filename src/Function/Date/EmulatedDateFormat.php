<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Date;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Expression as QueryExpression;

/**
 * @property-read string|\Illuminate\Database\Query\Expression $expression
 *
 * @uses \Tpetry\QueryExpressions\Concerns\IdentifiesDriver
 * @uses \Tpetry\QueryExpressions\Concerns\StringizeExpression
 */
trait EmulatedDateFormat
{
    protected function getEmulatedExpression(Grammar $grammar, string|Expression $emulatedCharacter): Expression
    {
        if ($grammar->isExpression($emulatedCharacter)) {
            $emulatedCharacter = $this->stringize($grammar, $emulatedCharacter);
        }

        /** @var string $emulatedCharacter */
        return new QueryExpression(sprintf(
            $emulatedCharacter,
            ...array_fill(
                start_index: 0,
                count: substr_count($emulatedCharacter, '%s'),
                value: $this->stringize($grammar, $this->expression)
            )
        ));
    }

    protected function getEmulatableCharacter(Grammar $grammar, string $character): string|Expression|null
    {
        return match ($this->identify($grammar)) {
            'mysql' => $this->getEmulatableCharacterForMysql($character),
            'sqlite' => $this->getEmulatableCharacterForSqlite($character),
            'pgsql' => $this->getEmulatableCharacterForPgsql($character),
            'sqlsrv' => $this->getEmulatableCharacterForSqlsrv($character),
        };
    }

    protected function getEmulatableCharacterForMysql(string $character): string|Expression|null
    {
        return match ($character) {
            'a' => 'LOWER(DATE_FORMAT(%s, \'%%p\'))',
            'g' => '(HOUR(%s) + 11) %% 12 + 1',
            'G' => 'HOUR(%s)',
            't' => 'DAY(LAST_DAY(%s))',
            'U' => 'UNIX_TIMESTAMP(%s)',
            'w' => '(DAYOFWEEK(%s) + 5) %% 7 + 1',
            default => null,
        };
    }

    protected function getEmulatableCharacterForSqlite(string $character): string|Expression|null
    {
        /** @var array<int, int> $daysRange */
        $daysRange = range(0, 6);
        /** @var array<int, int> $monthsRange */
        $monthsRange = range(1, 12);

        /** @var array<string, string> $dayAbbreviations */
        $dayAbbreviations = array_map(
            // @phpstan-ignore-next-line
            fn ($dayIndex) => Carbon::now()->weekday($dayIndex)->getTranslatedShortDayName(),
            $daysRange
        );

        /** @var array<string, string> $monthFullNames */
        $monthFullNames = array_map(
            fn ($monthIndex) => Carbon::now()->month($monthIndex)->getTranslatedMonthName(),
            $monthsRange
        );

        /** @var array<string, string> $dayFullNames */
        $dayFullNames = array_map(
            // @phpstan-ignore-next-line
            fn ($dayIndex) => Carbon::now()->weekday($dayIndex)->getTranslatedDayName(),
            $daysRange
        );

        /** @var array<string, string> $monthShortNames */
        $monthShortNames = array_map(
            fn ($monthIndex) => Carbon::now()->month($monthIndex)->getTranslatedShortMonthName(),
            $monthsRange
        );

        return match ($character) {
            'a' => 'LOWER(STRFTIME(\'%%p\', %s))',
            'D' => sprintf(
                '(CASE %s END)',
                implode(
                    ' ',
                    array_map(
                        fn ($dayIndex, $dayAbbrev) => "WHEN CAST(STRFTIME('%%w', %s) AS INTEGER) = $dayIndex THEN '$dayAbbrev'",
                        $daysRange,
                        $dayAbbreviations
                    )
                )
            ),
            'F' => sprintf(
                '(CASE %s END)',
                implode(
                    ' ',
                    array_map(
                        fn ($monthIndex, $monthFull) => "WHEN CAST(STRFTIME('%%m', %s) AS INTEGER) = $monthIndex THEN '$monthFull'",
                        $monthsRange,
                        $monthFullNames
                    )
                )
            ),

            'g' => '(STRFTIME(\'%%H\', %s) + 11) %% 12 + 1',
            'G' => 'LTRIM(STRFTIME(\'%%H\', %s), \'0\')',
            'h' => '(CASE WHEN STRFTIME(\'%%H\', %s) > \'12\' THEN STRFTIME(\'%%H\', %s) - 12 ELSE STRFTIME(\'%%H\', %s) END)',
            'j' => 'LTRIM(STRFTIME(\'%%d\', %s), \'0\')',
            'l' => sprintf(
                '(CASE %s END)',
                implode(
                    ' ',
                    array_map(
                        fn ($dayIndex, $dayFull) => "WHEN CAST(STRFTIME('%%w', %s) AS INTEGER) = $dayIndex THEN '$dayFull'",
                        $daysRange,
                        $dayFullNames
                    )
                )
            ),
            'M' => sprintf(
                '(CASE %s END)',
                implode(
                    ' ',
                    array_map(
                        fn ($monthIndex, $monthShort) => "WHEN CAST(STRFTIME('%%m', %s) AS INTEGER) = $monthIndex THEN '$monthShort'",
                        $monthsRange,
                        $monthShortNames
                    )
                )
            ),
            'n' => 'LTRIM(STRFTIME(\'%%m\', %s), \'0\')',
            'o' => 'STRFTIME(\'%%Y\', %s, \'weekday 0\', \'-3 days\')',
            't' => 'STRFTIME(\'%%d\', DATE(%s, \'+1 month\', \'start of month\', \'-1 day\'))',
            'W' => '(STRFTIME(\'%%j\', %s, \'weekday 0\', \'-3 days\') - 1) / 7 + 1',
            'w' => '(STRFTIME(\'%%w\', %s) + 6) %% 7 + 1',
            'y' => 'SUBSTR(STRFTIME(\'%%Y\', %s), 3, 2)',
            default => null,
        };
    }

    protected function getEmulatableCharacterForPgsql(string $character): string|Expression|null
    {
        return match ($character) {
            'a' => 'LOWER(TO_CHAR(%s, \'AM\'))',
            'F' => 'TRIM(TO_CHAR(%s, \'Month\'))',
            'g' => '(CAST((EXTRACT(HOUR FROM %s)::INTEGER + 11) %% 12 + 1 AS VARCHAR(2)))',
            'G' => 'CAST(EXTRACT(HOUR FROM %s)::INTEGER AS VARCHAR(2))',
            'l' => 'TRIM(TO_CHAR(%s, \'Day\'))',
            'o' => '(CASE WHEN EXTRACT(MONTH FROM %s)::INTEGER = 1 AND EXTRACT(DAY FROM %s)::INTEGER <= 3 THEN EXTRACT(YEAR FROM %s)::INTEGER - 1 ELSE EXTRACT(YEAR FROM %s)::INTEGER END)',
            't' => 'EXTRACT(DAY FROM DATE_TRUNC(\'month\', %s) + INTERVAL \'1 month - 1 day\')::INTEGER',
            'U' => 'EXTRACT(EPOCH FROM %s)::INTEGER',
            'w' => 'EXTRACT(DOW FROM %s)::INTEGER',
            default => null,
        };
    }

    protected function getEmulatableCharacterForSqlsrv(string $character): string|Expression|null
    {
        return match ($character) {
            'a' => 'LOWER(FORMAT(%s, \'tt\'))',
            'F' => 'DATENAME(MONTH, %s)',
            'g' => '(DATEPART(HOUR, %s) + 11) %% 12 + 1',
            'G' => 'CAST(DATEPART(HOUR, %s) AS VARCHAR(2))',
            'j' => 'CAST(DAY(%s) AS VARCHAR(2))',
            'l' => 'DATENAME(WEEKDAY, %s)',
            'M' => 'LEFT(DATENAME(MONTH, %s), 3)',
            'n' => 'CAST(MONTH(%s) AS VARCHAR(2))',
            'o' => '(CASE WHEN MONTH(%s) = 1 AND DAY(%s) <= 3 THEN YEAR(%s) - 1 ELSE YEAR(%s) END)',
            't' => 'CAST(DAY(EOMONTH(%s)) AS VARCHAR(2))',
            'U' => 'DATEDIFF(SECOND, \'1970-01-01\', %s)',
            'w' => '(CAST(DATEPART(WEEKDAY, %s) AS VARCHAR(2)) + 5) %% 7 + 1',
            'W' => 'CAST(DATEPART(ISO_WEEK, %s) AS VARCHAR(2))',
            'y' => 'RIGHT(CAST(YEAR(%s) AS VARCHAR(4)), 2)',
            default => null,
        };
    }
}
