<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Operator\Comparison;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class StrListContains implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $value1,
        private readonly string|Expression $value2,
        private readonly bool $strict = false,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        $value1 = $this->stringize($grammar, $this->value1);
        $value2 = $this->stringize($grammar, $this->value2);

        return match ($this->identify($grammar)) {
            'mysql' => '(FIND_IN_SET(' . ($this->strict ? 'binary ' : '') . "$value1, $value2))",
            'pgsql' => match ($this->strict) {
                true => "($value1::varchar = ANY(STRING_TO_ARRAY($value2, ',')))",
                false => "(lower($value1::varchar) = ANY(STRING_TO_ARRAY(lower($value2), ',')))",
            },
            'sqlite' => match ($this->strict) {
                true => "(','||$value2||',' GLOB '*,'||$value1||',*')",
                false => "(','||$value2||',' LIKE '%,'||$value1||',%')",
            },
            'sqlsrv' => "($value1 " . ($this->strict ? 'COLLATE SQL_LATIN1_GENERAL_CP1_CS_AS ' : '') . "IN (SELECT [value] FROM STRING_SPLIT($value2, ',')))",
        };
    }
}
