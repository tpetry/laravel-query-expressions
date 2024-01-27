<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;

/**
 * @internal
 */
abstract class VariableLengthOperatorExpression extends OperatorExpression
{
    private array $values = [];

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
        string|Expression ...$values,
    ) {
        $this->values = $values;
    }

    public function getValue(Grammar $grammar): string
    {
        $value = "{$this->stringize($grammar, $this->value1)} {$this->operator()} {$this->stringize($grammar, $this->value2)}";

        foreach ($this->values as $additional) {
            $value .= " {$this->operator()} {$this->stringize($grammar, $additional)}";
        }

        return "({$value})";
    }
}
