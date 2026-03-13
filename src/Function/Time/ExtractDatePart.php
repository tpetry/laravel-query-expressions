<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Time;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use InvalidArgumentException;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class ExtractDatePart implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param  'year'  $part
     */
    public function __construct(
        private readonly string|Expression $column,
        private readonly string $part,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $column = $this->stringize($grammar, $this->column);

        return match ($this->part) {
            'year' => $this->extractYear($grammar, $column), // @phpstan-ignore-line match.alwaysTrue
            default => throw new InvalidArgumentException("Invalid date part: '{$this->part}'."),
        };
    }

    private function extractYear(Grammar $grammar, string $column): string
    {
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => "(year({$column}))",
            'pgsql' => "extract(year from {$column})::int",
            'sqlite' => "cast(strftime('%Y', {$column}) as integer)",
        };
    }
}
