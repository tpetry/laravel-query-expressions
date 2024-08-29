<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Aggregate;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Count implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
        private readonly bool $distinct = false,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->value);

        return match ($this->distinct) {
            true => "count(distinct {$value})",
            false => "count({$value})",
        };
    }
}
