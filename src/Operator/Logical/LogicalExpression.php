<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Logical;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

/**
 * @internal
 */
abstract class LogicalExpression implements ConditionExpression
{
    use StringizeExpression;

    public function __construct(
        private readonly Expression $value1,
        private readonly Expression $value2,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $value1 = $this->stringize($grammar, $this->value1);
        $value2 = $this->stringize($grammar, $this->value2);
        $operator = $this->operator();

        return "({$value1} {$operator} {$value2})";
    }

    abstract protected function operator(): string;
}
