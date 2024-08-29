<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Bitwise;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class BitNot implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
    ) {}

    public function getValue(Grammar $grammar)
    {
        $value = $this->stringize($grammar, $this->value);

        return "(~{$value})";
    }
}
