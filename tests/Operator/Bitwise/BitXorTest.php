<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Bitwise\BitXor;

it('can bitwise XOR two columns')
    ->expect(new BitXor('val1', 'val2'))
    ->toBeExecutable(['val1 int', 'val2 int'])
    ->toBeMysql('(`val1` ^ `val2`)')
    ->toBePgsql('("val1" # "val2")')
    ->toBeSqlite('(("val1" | "val2") - ("val1" & "val2"))')
    ->toBeSqlsrv('([val1] ^ [val2])');

it('can bitwise XOR two expressions')
    ->expect(new BitXor(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('(1 ^ 2)')
    ->toBePgsql('(1 # 2)')
    ->toBeSqlite('((1 | 2) - (1 & 2))')
    ->toBeSqlsrv('(1 ^ 2)');

it('can bitwise XOR an expression and a column')
    ->expect(new BitXor('val', new Expression(0)))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(`val` ^ 0)')
    ->toBePgsql('("val" # 0)')
    ->toBeSqlite('(("val" | 0) - ("val" & 0))')
    ->toBeSqlsrv('([val] ^ 0)');

it('can bitwise XOR a column and an expression')
    ->expect(new BitXor(new Expression(0), 'val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(0 ^ `val`)')
    ->toBePgsql('(0 # "val")')
    ->toBeSqlite('((0 | "val") - (0 & "val"))')
    ->toBeSqlsrv('(0 ^ [val])');
