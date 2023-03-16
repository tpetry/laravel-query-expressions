<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Logical\CondAnd;

it('can combine multiple expressions with AND')
    ->expect(new CondAnd(new Expression('1 = 1'), new Expression('1 = 1')))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 = 1 and 1 = 1)')
    ->toBePgsql('(1 = 1 and 1 = 1)')
    ->toBeSqlite('(1 = 1 and 1 = 1)')
    ->toBeSqlsrv('(1 = 1 and 1 = 1)');
