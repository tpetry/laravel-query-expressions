<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Logical;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class CondXor implements ConditionExpression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly Expression $value1,
        private readonly Expression $value2,

    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value1 = $this->stringize($grammar, $this->value1);
        $value2 = $this->stringize($grammar, $this->value2);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => "({$value1} xor {$value2})",
            'pgsql', 'sqlite', 'sqlsrv' => "(({$value1} and not {$value2}) or (not {$value1} and {$value2}))",
        };
    }
}
