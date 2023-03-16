<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Comparison\GreaterThan;

it('can compare two columns by greater than check')
    ->expect(new GreaterThan('val1', 'val2'))
    ->toBeExecutable(['val1 int', 'val2 int'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val1` > `val2`)')
    ->toBePgsql('("val1" > "val2")')
    ->toBeSqlite('("val1" > "val2")')
    ->toBeSqlsrv('([val1] > [val2])');

it('can compare two expressions by greater than check')
    ->expect(new GreaterThan(new Expression(1), new Expression(2)))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 > 2)')
    ->toBePgsql('(1 > 2)')
    ->toBeSqlite('(1 > 2)')
    ->toBeSqlsrv('(1 > 2)');

it('can compare an expression and a column by greater than check')
    ->expect(new GreaterThan('val', new Expression(0)))
    ->toBeExecutable(['val int'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` > 0)')
    ->toBePgsql('("val" > 0)')
    ->toBeSqlite('("val" > 0)')
    ->toBeSqlsrv('([val] > 0)');

it('can compare a column and an expression by greater than check')
    ->expect(new GreaterThan(new Expression(0), 'val'))
    ->toBeExecutable(['val int'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(0 > `val`)')
    ->toBePgsql('(0 > "val")')
    ->toBeSqlite('(0 > "val")')
    ->toBeSqlsrv('(0 > [val])');
