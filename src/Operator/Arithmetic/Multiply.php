<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Arithmetic;

use Tpetry\QueryExpressions\Operator\VariableLengthOperatorExpression;

class Multiply extends VariableLengthOperatorExpression
{
    protected function operator(): string
    {
        return '*';
    }
}
