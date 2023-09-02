<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Date;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class Month implements Expression
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
            'mysql' => "date_format({$expression}, '%Y-%m')",
            'sqlite' => "strftime('%Y-%m', {$expression})",
            'pgsql' => "to_char({$expression}, 'YYYY-MM')",
            'sqlsrv' => "format({$expression}, 'yyyy-MM')",
        };
    }
}
