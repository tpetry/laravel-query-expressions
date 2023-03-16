<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Logical\CondXor;

it('can combine multiple expressions with XOR')
    ->expect(new CondXor(new Expression('1 = 1'), new Expression('1 = 1')))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 = 1 xor 1 = 1)')
    ->toBePgsql('((1 = 1 and not 1 = 1) or (not 1 = 1 and 1 = 1))')
    ->toBeSqlite('((1 = 1 and not 1 = 1) or (not 1 = 1 and 1 = 1))')
    ->toBeSqlsrv('((1 = 1 and not 1 = 1) or (not 1 = 1 and 1 = 1))');
