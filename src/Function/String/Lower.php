<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\String;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Lower implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->stringize($grammar, $this->expression);

        return match ($this->identify($grammar)) {
            'mysql', 'sqlite' => "(lower({$expression}))",
            'pgsql', 'sqlsrv' => "lower({$expression})",
        };
    }
}
