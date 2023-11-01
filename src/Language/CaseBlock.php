<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Language;

use Illuminate\Contracts\Database\Query\ConditionExpression;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;
use Tpetry\QueryExpressions\Function\Conditional\ManyArgumentsExpression;

class CaseBlock extends ManyArgumentsExpression implements ConditionExpression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param  non-empty-array<int, CaseCondition>  $when
     */
    public function __construct(
        array $when,
        private readonly string|Expression|null $else = null,
    ) {
        parent::__construct($when);
    }

    public function getValue(Grammar $grammar)
    {
        $conditions = implode(' ', $this->getExpressions($grammar));

        return match ($this->else) {
            null => "(case {$conditions} end)",
            default => "(case {$conditions} else {$this->stringize($grammar, $this->else)} end)",
        };
    }
}
