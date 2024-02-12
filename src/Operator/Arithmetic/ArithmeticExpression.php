<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Arithmetic;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

/**
 * @internal
 */
abstract class ArithmeticExpression implements Expression
{
    use StringizeExpression;

    /** @var string[]|Expression[] */
    private readonly array $values;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
        string|Expression ...$values,
    ) {
        $this->values = $values;
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = implode(" {$this->operator()} ", $this->expressions($grammar));

        return "({$expression})";
    }

    /**
     * @return array<int, float|int|string>
     */
    protected function expressions(Grammar $grammar): array
    {
        return array_map(
            fn (string|Expression $value) => $this->stringize($grammar, $value),
            [$this->value1, $this->value2, ...$this->values],
        );
    }

    abstract protected function operator(): string;
}
