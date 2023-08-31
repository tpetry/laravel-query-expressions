<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Time;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;

class ConvertTimezone implements Expression
{
    use IdentifiesDriver,
        StringizeExpression;

    public function __construct(
        private readonly string|Expression $expression,
        private readonly string|Expression $startTimezone,
        private readonly string|Expression $targetTimezone,
    ) {
    }

    public function getValue(Grammar $grammar)
    {
        return match ($this->identify($grammar)) {
            'mysql' => "convert_tz({$this->stringize($grammar, $this->expression)},{$this->stringize($grammar, $this->startTimezone)},{$this->stringize($grammar, $this->targetTimezone)})",
            'pgsql', 'sqlsrv' => "(({$this->stringize($grammar, $this->expression)} at time zone {$this->stringize($grammar, $this->startTimezone)}) at time zone {$this->stringize($grammar, $this->targetTimezone)})",
            'sqlite' => "datetime({$this->stringize($grammar, $this->expression)},{$this->stringize($grammar, $this->startTimezone)},{$this->stringize($grammar, $this->targetTimezone)})",
        };
    }
}
