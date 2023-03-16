<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Arithmetic\Add;

it('can add two columns')
    ->expect(new Add('val1', 'val2'))
    ->toBeExecutable(['val1 int', 'val2 int'])
    ->toBeMysql('(`val1` + `val2`)')
    ->toBePgsql('("val1" + "val2")')
    ->toBeSqlite('("val1" + "val2")')
    ->toBeSqlsrv('([val1] + [val2])');

it('can add two expressions')
    ->expect(new Add(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('(1 + 2)')
    ->toBePgsql('(1 + 2)')
    ->toBeSqlite('(1 + 2)')
    ->toBeSqlsrv('(1 + 2)');

it('can add an expression and a column')
    ->expect(new Add('val', new Expression(0)))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(`val` + 0)')
    ->toBePgsql('("val" + 0)')
    ->toBeSqlite('("val" + 0)')
    ->toBeSqlsrv('([val] + 0)');

it('can add a column and an expression')
    ->expect(new Add(new Expression(0), 'val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(0 + `val`)')
    ->toBePgsql('(0 + "val")')
    ->toBeSqlite('(0 + "val")')
    ->toBeSqlsrv('(0 + [val])');
