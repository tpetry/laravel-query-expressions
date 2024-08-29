<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Conditional;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

/**
 * @interal
 */
abstract class ManyArgumentsExpression implements Expression
{
    use StringizeExpression;

    /**
     * @param  non-empty-array<int, string|\Illuminate\Contracts\Database\Query\Expression>  $expressions
     */
    public function __construct(
        private readonly array $expressions,
    ) {}

    /**
     * @return non-empty-array<int, float|int|string>
     */
    protected function getExpressions(Grammar $grammar): array
    {
        return array_map(
            callback: fn ($expression) => $this->stringize($grammar, $expression),
            array: $this->expressions,
        );
    }
}
