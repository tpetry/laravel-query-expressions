<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Aggregate;

class Count extends AggregateExpression
{
    protected function aggregate(): string
    {
        return 'count';
    }
}
