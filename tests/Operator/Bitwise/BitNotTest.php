<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Bitwise\BitNot;

it('can bitwise NOT a column')
    ->expect(new BitNot('val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(~`val`)')
    ->toBePgsql('(~"val")')
    ->toBeSqlite('(~"val")')
    ->toBeSqlsrv('(~[val])');

it('can bitwise NO an expression')
    ->expect(new BitNot(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('(~1)')
    ->toBePgsql('(~1)')
    ->toBeSqlite('(~1)')
    ->toBeSqlsrv('(~1)');
