<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Language;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class CaseRule implements Expression
{
    use StringizeExpression;

    public function __construct(
        private readonly string|Expression $result,
        private readonly ConditionExpression $condition,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $condition = $this->stringize($grammar, $this->condition);
        $result = $this->stringize($grammar, $this->result);

        return "when {$condition} then {$result}";
    }
}
