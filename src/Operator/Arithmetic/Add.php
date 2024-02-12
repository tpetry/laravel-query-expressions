<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Arithmetic;

class Add extends ArithmeticExpression
{
    protected function operator(): string
    {
        return '+';
    }
}
