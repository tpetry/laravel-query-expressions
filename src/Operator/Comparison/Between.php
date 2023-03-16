<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Comparison;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Between implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
        private readonly string|Expression $min,
        private readonly string|Expression $max,

    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value = $this->stringize($grammar, $this->value);
        $min = $this->stringize($grammar, $this->min);
        $max = $this->stringize($grammar, $this->max);

        return "({$value} between {$min} and {$max})";
    }
}
