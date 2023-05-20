<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Function\String\Concat;

it('can concatenate string values')
    ->expect(new Concat(['Hello', ' ', 'World']))
    ->toBeExecutable()
    ->toBeMysql('concat(`Hello`, ` `, `World`)')
    ->toBePgsql('concat("Hello", " ", "World")')
    ->toBeSqlite('"Hello" || " " || "World"')
    ->toBeSqlsrv('concat([Hello], [ ], [World])');
