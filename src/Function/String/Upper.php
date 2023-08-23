<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\String;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Upper implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        return "upper({$this->stringize($grammar, $this->expression)})";
    }
}
