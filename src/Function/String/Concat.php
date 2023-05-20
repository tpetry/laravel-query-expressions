<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\String;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Function\Conditional\ManyArgumentsExpression;

class Concat extends ManyArgumentsExpression implements Expression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar)
    {
        $expressions = $this->getExpressions($grammar);
        $expressionsStr = implode(', ', $expressions);

        return match ($this->identify($grammar)) {
            'mysql', 'pgsql', 'sqlsrv' => "concat({$expressionsStr})",
            'sqlite' => implode(' || ', $expressions),
        };
    }
}
