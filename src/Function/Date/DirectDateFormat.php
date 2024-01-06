<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Date;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Expression as QueryExpression;

/**
 * @property-read string|\Illuminate\Database\Query\Expression $expression
 *
 * @uses \Tpetry\QueryExpressions\Concerns\IdentifiesDriver
 * @uses \Tpetry\QueryExpressions\Concerns\StringizeExpression
 */
trait DirectDateFormat
{
    /**
     * @var array<'mysql'|'sqlite'|'pgsql'|'sqlsrv', array<string, string>>
     */
    protected array $formatCharacters = [
        'mysql' => [
            'A' => '%p',
            'd' => '%d',
            'D' => '%a',
            'F' => '%M',
            'H' => '%H',
            'h' => '%h',
            'i' => '%i',
            'j' => '%e',
            'l' => '%W',
            'm' => '%m',
            'M' => '%b',
            'n' => '%c',
            'o' => '%x',
            's' => '%s',
            'W' => '%v',
            'Y' => '%Y',
            'y' => '%y',
        ],
        'sqlite' => [
            'A' => '%p',
            'd' => '%d',
            'H' => '%H',
            'i' => '%M',
            'm' => '%m',
            's' => '%S',
            'U' => '%s',
            'Y' => '%Y',
        ],
        'pgsql' => [
            'A' => 'AM',
            'd' => 'DD',
            'D' => 'Dy',
            'h' => 'HH12',
            'H' => 'HH24',
            'i' => 'MI',
            'j' => 'FMDD',
            'm' => 'MM',
            'M' => 'Mon',
            'n' => 'FMMM',
            's' => 'SS',
            'W' => 'IW',
            'y' => 'YY',
            'Y' => 'YYYY',
        ],
        'sqlsrv' => [
            'A' => 'tt',
            'd' => 'dd',
            'D' => 'ddd',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            'm' => 'MM',
            's' => 'ss',
            'Y' => 'yyyy',
        ],
    ];

    protected function getDateFormatExpression(Grammar $grammar, string $character): Expression
    {
        $formatCharacter = $this->formatCharacters[$this->identify($grammar)][$character];

        return new QueryExpression(
            match ($this->identify($grammar)) {
                'mysql' => "DATE_FORMAT({$this->stringize($grammar, $this->expression)}, '{$formatCharacter}')",
                'sqlite' => "STRFTIME('{$formatCharacter}', {$this->stringize($grammar, $this->expression)})",
                'pgsql' => "TO_CHAR({$this->stringize($grammar, $this->expression)}, '{$formatCharacter}')",
                'sqlsrv' => "FORMAT({$this->stringize($grammar, $this->expression)}, '{$formatCharacter}')",
            }
        );
    }
}
