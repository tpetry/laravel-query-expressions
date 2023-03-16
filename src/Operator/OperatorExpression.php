<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

/**
 * @internal
 */
abstract class OperatorExpression implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        return "({$this->stringize($grammar, $this->value1)} {$this->operator()} {$this->stringize($grammar, $this->value2)})";
    }

    abstract protected function operator(): string;
}
