<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Language;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Alias implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
        private readonly string $name,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $expression = $this->stringize($grammar, $this->expression);
        $name = $grammar->wrap($this->name);

        return "{$expression} as {$name}";
    }
}
