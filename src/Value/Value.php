<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Value;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;

class Value implements Expression
{
    public function __construct(
        private readonly string|int|float|bool|null $value,
        private readonly bool $binary = false,
    ) {
    }

    public function getValue(Grammar $grammar): string
    {
        return $grammar->escape($this->value, $this->binary);
    }
}
