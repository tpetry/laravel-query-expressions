<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Aggregate;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class CountFilter implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly Expression $filter,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $filter = $this->stringize($grammar, $this->filter);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => "sum({$filter})",
            'pgsql', 'sqlite' => "count(*) filter (where {$filter})",
            'sqlsrv' => "sum(case when {$filter} then 1 else 0 end)",
        };
    }
}
