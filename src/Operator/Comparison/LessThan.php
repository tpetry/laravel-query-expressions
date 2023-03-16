<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Comparison;

use Tpetry\QueryExpressions\Operator\OperatorExpression;

class LessThan extends OperatorExpression
{
    protected function operator(): string
    {
        return '<';
    }
}
