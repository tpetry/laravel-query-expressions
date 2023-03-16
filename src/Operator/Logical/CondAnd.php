<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Logical;

class CondAnd extends LogicalExpression
{
    protected function operator(): string
    {
        return 'and';
    }
}
