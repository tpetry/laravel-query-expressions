<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Arithmetic\Power;

it('can power two columns')
    ->expect(new Power('val1', 'val2'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('power(`val1`, `val2`)')
    ->toBePgsql('("val1" ^ "val2")')
    ->toBeSqlite('power("val1", "val2")')
    ->toBeSqlsrv('power([val1], [val2])');

it('can power two expressions')
    ->expect(new Power(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('power(1, 2)')
    ->toBePgsql('(1 ^ 2)')
    ->toBeSqlite('power(1, 2)')
    ->toBeSqlsrv('power(1, 2)');

it('can power an expression and a column')
    ->expect(new Power('val', new Expression(0)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('power(`val`, 0)')
    ->toBePgsql('("val" ^ 0)')
    ->toBeSqlite('power("val", 0)')
    ->toBeSqlsrv('power([val], 0)');

it('can power a column and an expression')
    ->expect(new Power(new Expression(0), 'val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('power(0, `val`)')
    ->toBePgsql('(0 ^ "val")')
    ->toBeSqlite('power(0, "val")')
    ->toBeSqlsrv('power(0, [val])');

it('can power variadic values')
    ->expect(new Power(new Expression(0), 'val1', 'val2', new Expression(1)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('power(power(power(0, `val1`), `val2`), 1)')
    ->toBePgsql('(0 ^ "val1" ^ "val2" ^ 1)')
    ->toBeSqlite('power(power(power(0, "val1"), "val2"), 1)')
    ->toBeSqlsrv('power(power(power(0, [val1]), [val2]), 1)');
