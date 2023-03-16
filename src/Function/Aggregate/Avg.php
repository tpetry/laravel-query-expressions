<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Aggregate;

class Avg extends AggregateExpression
{
    protected function aggregate(): string
    {
        return 'avg';
    }
}
