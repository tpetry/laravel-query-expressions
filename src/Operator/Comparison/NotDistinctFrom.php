<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Comparison;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class NotDistinctFrom implements ConditionExpression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value1 = $this->stringize($grammar, $this->value1);
        $value2 = $this->stringize($grammar, $this->value2);

        // Sqlsrv: IS DISTINCT FROM is not available in version 2017 and 2019
        return match ($this->identify($grammar)) {
            'mysql' => "({$value1} <=> {$value2})",
            'pgsql' => "({$value1} is not distinct from {$value2})",
            'sqlite' => "({$value1} is {$value2})",
            'sqlsrv' => "({$value1} = {$value2} or ({$value1} is null and {$value2} is null))",
        };
    }
}
