<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Aggregate;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

/**
 * @internal
 */
abstract class AggregateExpression implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->value);
        $aggregate = $this->aggregate();

        return "{$aggregate}({$value})";
    }

    abstract protected function aggregate(): string;
}
