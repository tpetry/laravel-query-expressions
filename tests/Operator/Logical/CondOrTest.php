<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Logical\CondOr;

it('can combine multiple expressions with OR')
    ->expect(new CondOr(new Expression('1 = 1'), new Expression('1 = 1')))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 = 1 or 1 = 1)')
    ->toBePgsql('(1 = 1 or 1 = 1)')
    ->toBeSqlite('(1 = 1 or 1 = 1)')
    ->toBeSqlsrv('(1 = 1 or 1 = 1)');
