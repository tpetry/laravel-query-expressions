<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Time;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class ExtractYear implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $column,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $column = $this->stringize($grammar, $this->column);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => "year({$column})",
            'pgsql' => "extract(year from {$column})::int",
            'sqlite' => "cast(strftime('%Y', {$column}) as integer)",
        };
    }
}
