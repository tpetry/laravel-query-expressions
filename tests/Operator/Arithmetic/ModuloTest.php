<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Arithmetic\Modulo;

it('can modulo two columns')
    ->expect(new Modulo('val1', 'val2'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('(`val1` % `val2`)')
    ->toBePgsql('("val1" % "val2")')
    ->toBeSqlite('("val1" % "val2")')
    ->toBeSqlsrv('([val1] % [val2])');

it('can modulo two expressions')
    ->expect(new Modulo(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('(1 % 2)')
    ->toBePgsql('(1 % 2)')
    ->toBeSqlite('(1 % 2)')
    ->toBeSqlsrv('(1 % 2)');

it('can modulo an expression and a column')
    ->expect(new Modulo('val', new Expression(0)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('(`val` % 0)')
    ->toBePgsql('("val" % 0)')
    ->toBeSqlite('("val" % 0)')
    ->toBeSqlsrv('([val] % 0)');

it('can modulo a column and an expression')
    ->expect(new Modulo(new Expression(0), 'val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('(0 % `val`)')
    ->toBePgsql('(0 % "val")')
    ->toBeSqlite('(0 % "val")')
    ->toBeSqlsrv('(0 % [val])');

it('can modulo variadic values')
    ->expect(new Modulo(new Expression(0), 'val1', 'val2', new Expression(1)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('(0 % `val1` % `val2` % 1)')
    ->toBePgsql('(0 % "val1" % "val2" % 1)')
    ->toBeSqlite('(0 % "val1" % "val2" % 1)')
    ->toBeSqlsrv('(0 % [val1] % [val2] % 1)');
