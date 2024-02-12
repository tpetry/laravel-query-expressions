<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Arithmetic;

use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Power extends ArithmeticExpression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function getValue(Grammar $grammar): string
    {
        return match ($this->identify($grammar)) {
            'mysql', 'sqlite', 'sqlsrv' => $this->buildPowerFunctionChain($grammar),
            'pgsql' => parent::getValue($grammar),
        };
    }

    protected function buildPowerFunctionChain(Grammar $grammar): string
    {
        $expressions = $this->expressions($grammar);

        // Build the initial expressions by using the two required parameters of the object.
        $value0 = array_shift($expressions);
        $value1 = array_shift($expressions);
        $expression = "power({$value0}, {$value1})";

        // For each remaining value call the power function again with the last result and the new value.
        while (count($expressions) > 0) {
            $value = array_shift($expressions);
            $expression = "power({$expression}, $value)";
        }

        return $expression;
    }

    protected function operator(): string
    {
        return '^';
    }
}
