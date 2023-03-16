<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Aggregate;

class Max extends AggregateExpression
{
    protected function aggregate(): string
    {
        return 'max';
    }
}
