<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Aggregate;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class SumFilter implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
        private readonly Expression $filter,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->value);
        $filter = $this->stringize($grammar, $this->filter);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => "sum(case when {$filter} then {$value} else 0 end)",
            'pgsql', 'sqlite' => "sum({$value}) filter (where {$filter})",
        };
    }
}
