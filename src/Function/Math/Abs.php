<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Math;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Abs implements Expression
{
    use StringizeExpression;
    use IdentifiesDriver;

    public function __construct(
        private readonly string|Expression $expression
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringize($grammar, $this->expression);

        return match ($this->identify($grammar)) {
            'mysql', 'sqlite' => "(abs({$expression}))",
            'pgsql', 'sqlsrv' => "abs({$expression})",
        };
    }
}
