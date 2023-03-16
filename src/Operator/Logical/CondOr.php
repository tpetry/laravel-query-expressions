<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Logical;

class CondOr extends LogicalExpression
{
    protected function operator(): string
    {
        return 'or';
    }
}
