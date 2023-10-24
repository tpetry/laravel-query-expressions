<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Language;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;
use Tpetry\QueryExpressions\Function\Conditional\ManyArgumentsExpression;

class CaseBlock extends ManyArgumentsExpression implements ConditionExpression {

    use IdentifiesDriver;
    use StringizeExpression;
    /**
     * @param  non-empty-array<int, CaseCondition>  $when
     */
    public function __construct(private readonly array $when, private readonly string|Expression $else)
    {
        parent::__construct($when);
    }

    public function getValue(Grammar $grammar)
    {
        $conditions = implode(' ', $this->getExpressions($grammar));
        $else = $this->stringize($grammar, $this->else);

        return "(case {$conditions} {$else} end)";
    }
}
