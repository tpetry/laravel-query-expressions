<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\Time;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;

class Now implements Expression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar)
    {
        // MySQL: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        // PostgreSQL: The CURRENT_TIMESTAMP constant is frozen within transactions.
        // SQLite: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlite' => '(current_timestamp)',
            'pgsql' => 'statement_timestamp()',
            'sqlsrv' => 'current_timestamp',
        };
    }
}
