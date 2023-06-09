<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Logical;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class CondNot implements ConditionExpression
{
    use StringizeExpression;

    public function __construct(
        private readonly Expression $value,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value = $this->stringize($grammar, $this->value);

        return "(not {$value})";
    }
}
