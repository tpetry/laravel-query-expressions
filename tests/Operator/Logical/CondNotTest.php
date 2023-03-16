<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Logical\CondNot;

it('can negate an expression with NOT')
    ->expect(new CondNot(new Expression('1 = 1')))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(not 1 = 1)')
    ->toBePgsql('(not 1 = 1)')
    ->toBeSqlite('(not 1 = 1)')
    ->toBeSqlsrv('(not 1 = 1)');
