<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Conditional;

use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class IfEmpty extends Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
        private readonly string|Expression $fallbackExpression,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        return "ifnull(nullif({$this->stringize($grammar, $this->expression)},''),{$this->stringize($grammar, $this->fallbackExpression)})";
    }
}
