<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Conditional;

use Illuminate\Database\Grammar;

class Coalesce extends ManyArgumentsExpression
{
    public function getValue(Grammar $grammar): string
    {
        $expressionsStr = implode(', ', $this->getExpressions($grammar));

        return "coalesce({$expressionsStr})";
    }
}
