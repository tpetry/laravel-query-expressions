<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\String;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;

class Uuid4 implements Expression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar)
    {
        // MySQL: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        // SQLite: The expression needs to be enclosed by parentheses to be used as a default value in create table statements.
        // SQLite: First character in 4th group is hardcoded to 8 because the required 8, 9, A, or B can't be randomly generated.
        return match ($this->identify($grammar)) {
            'mariadb', 'mysql' => "(lower(concat(hex(random_bytes(4)),'-',hex(random_bytes(2)),'-4',substr(hex(random_bytes(2)), -3),'-',hex((ascii(random_bytes(1))>>6)+8),substr(hex(random_bytes(2)),-3),'-',hex(random_bytes(6)))))",
            'pgsql' => 'gen_random_uuid()',
            'sqlite' => "(lower(hex(randomblob(4))||'-'||hex(randomblob(2))||'-4'||substr(hex(randomblob(2)), -3)||'-8'||substr(hex(randomblob(2)),-3)||'-'||hex(randomblob(6))))",
            'sqlsrv' => 'lower(newid())',
        };
    }
}
