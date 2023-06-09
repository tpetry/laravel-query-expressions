<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Comparison;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Tpetry\QueryExpressions\Operator\OperatorExpression;

class GreaterThanOrEqual extends OperatorExpression implements ConditionExpression
{
    protected function operator(): string
    {
        return '>=';
    }
}
