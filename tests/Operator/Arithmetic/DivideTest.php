<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Arithmetic\Divide;

it('can divide two columns')
    ->expect(new Divide('val1', 'val2'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('(`val1` / `val2`)')
    ->toBePgsql('("val1" / "val2")')
    ->toBeSqlite('("val1" / "val2")')
    ->toBeSqlsrv('([val1] / [val2])');

it('can divide two expressions')
    ->expect(new Divide(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('(1 / 2)')
    ->toBePgsql('(1 / 2)')
    ->toBeSqlite('(1 / 2)')
    ->toBeSqlsrv('(1 / 2)');

it('can divide an expression and a column')
    ->expect(new Divide('val', new Expression(0)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('(`val` / 0)')
    ->toBePgsql('("val" / 0)')
    ->toBeSqlite('("val" / 0)')
    ->toBeSqlsrv('([val] / 0)');

it('can divide a column and an expression')
    ->expect(new Divide(new Expression(0), 'val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('(0 / `val`)')
    ->toBePgsql('(0 / "val")')
    ->toBeSqlite('(0 / "val")')
    ->toBeSqlsrv('(0 / [val])');
