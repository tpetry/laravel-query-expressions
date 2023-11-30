<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Language;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class CaseGroup implements Expression
{
    use IdentifiesDriver;
    use StringizeExpression;

    /**
     * @param  non-empty-array<int, CaseRule>  $when
     */
    public function __construct(
        private readonly array $when,
        private readonly string|Expression|null $else = null,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $conditions = array_map(
            callback: fn ($expression) => $this->stringize($grammar, $expression),
            array: $this->when,
        );
        $conditions = implode(' ', $conditions);

        return match ($this->else) {
            null => "(case {$conditions} end)",
            default => "(case {$conditions} else {$this->stringize($grammar, $this->else)} end)",
        };
    }
}
