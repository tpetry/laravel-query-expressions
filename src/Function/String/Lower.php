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
        private readonly ?string $collation = null,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        $expression = $this->collation ? match ($this->identify($grammar)) {
            'mysql' => "convert({$this->stringize($grammar, $this->expression)} using {$this->collation})",
            'pgsql' => "{$this->stringize($grammar, $this->expression)} collate {$this->collation}",
            'sqlsrv' => "{$this->stringize($grammar, $this->expression)} collate {$this->collation}",
            'sqlite' => "{$this->stringize($grammar, $this->expression)} collate {$this->collation}",
        } : $this->expression;

        return "lower({$expression})";
    }
}
