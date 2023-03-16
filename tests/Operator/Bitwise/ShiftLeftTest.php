<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Bitwise\ShiftLeft;

it('can bitwise shift left two columns')
    ->expect(new ShiftLeft('val1', 'val2'))
    ->toBeExecutable(['val1 int', 'val2 int'])
    ->toBeMysql('(`val1` * power(2, `val2`))')
    ->toBePgsql('("val1" << "val2")')
    ->toBeSqlite('("val1" << "val2")')
    ->toBeSqlsrv('([val1] * power(2, [val2]))');

it('can bitwise shift left two expressions')
    ->expect(new ShiftLeft(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('(1 * power(2, 2))')
    ->toBePgsql('(1 << 2)')
    ->toBeSqlite('(1 << 2)')
    ->toBeSqlsrv('(1 * power(2, 2))');

it('can bitwise shift left an expression and a column')
    ->expect(new ShiftLeft('val', new Expression(0)))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(`val` * power(2, 0))')
    ->toBePgsql('("val" << 0)')
    ->toBeSqlite('("val" << 0)')
    ->toBeSqlsrv('([val] * power(2, 0))');

it('can bitwise shift left a column and an expression')
    ->expect(new ShiftLeft(new Expression(0), 'val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(0 * power(2, `val`))')
    ->toBePgsql('(0 << "val")')
    ->toBeSqlite('(0 << "val")')
    ->toBeSqlsrv('(0 * power(2, [val]))');
