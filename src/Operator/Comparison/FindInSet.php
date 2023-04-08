<?php

namespace Tpetry\QueryExpressions\Operator\Comparison;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class FindInSet implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value1 = $this->stringize($grammar, $this->value1);
        $value2 = $this->stringize($grammar, $this->value2);

        return match ($this->identify($grammar)) {
            'mysql' => "(FIND_IN_SET($value1, $value2))",
            'pgsql' => "($value1::varchar = ANY(STRING_TO_ARRAY($value2, ',')))",
            'sqlite' => "(','||$value2||',' like '%,'||$value1||',%')",
            'sqlsrv' => "($value1 IN (SELECT [value] FROM STRING_SPLIT($value2, ',')))",
        };
    }
}
