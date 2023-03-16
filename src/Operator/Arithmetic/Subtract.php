<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Arithmetic;

use Tpetry\QueryExpressions\Operator\OperatorExpression;

class Subtract extends OperatorExpression
{
    protected function operator(): string
    {
        return '-';
    }
}
