<?php

namespace Tpetry\QueryExpressions\Language;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class CaseCondition implements Expression
{
    use StringizeExpression;

    public function __construct(private readonly string|Expression $result, private readonly string|Expression $condition)
    {

    }

    public function getValue(Grammar $grammar)
    {
        $condition = $this->stringize($grammar, $this->condition);
        $result = $this->stringize($grammar, $this->result);

        return "when {$condition} then {$result}";
    }
}
