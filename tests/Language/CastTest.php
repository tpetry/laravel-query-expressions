<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Language\Cast;

it('can cast a column to an int')
    ->expect(new Cast('val', 'int'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('val');
    })
    ->toBeMysql('cast(`val` as signed)')
    ->toBePgsql('cast("val" as int)')
    ->toBeSqlite('cast("val" as integer)')
    ->toBeSqlsrv('(([val])*1)');

it('can cast an expression to an int')
    ->expect(new Cast(new Expression("'42'"), 'int'))
    ->toBeExecutable()
    ->toBeMysql("cast('42' as signed)")
    ->toBePgsql("cast('42' as int)")
    ->toBeSqlite("cast('42' as integer)")
    ->toBeSqlsrv("(('42')*1)");

it('can cast a column to a bigint')
    ->expect(new Cast('val', 'bigint'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('val');
    })
    ->toBeMysql('cast(`val` as signed)')
    ->toBePgsql('cast("val" as bigint)')
    ->toBeSqlite('cast("val" as integer)')
    ->toBeSqlsrv('cast([val] as bigint)');

it('can cast an expression to a bigint')
    ->expect(new Cast(new Expression("'42'"), 'bigint'))
    ->toBeExecutable()
    ->toBeMysql("cast('42' as signed)")
    ->toBePgsql("cast('42' as bigint)")
    ->toBeSqlite("cast('42' as integer)")
    ->toBeSqlsrv("cast('42' as bigint)");

it('can cast a column to a float')
    ->expect(new Cast('val', 'float'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('val');
    })
    ->toBeMysql('((`val`)*1.0)')
    ->toBePgsql('cast("val" as real)')
    ->toBeSqlite('cast("val" as real)')
    ->toBeSqlsrv('cast([val] as float(24))');

it('can cast an expression to a float')
    ->expect(new Cast(new Expression("'42.42'"), 'float'))
    ->toBeExecutable()
    ->toBeMysql("(('42.42')*1.0)")
    ->toBePgsql("cast('42.42' as real)")
    ->toBeSqlite("cast('42.42' as real)")
    ->toBeSqlsrv("cast('42.42' as float(24))");

it('can cast a column to a double')
    ->expect(new Cast('val', 'double'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('val');
    })
    ->toBeMysql('((`val`)*1.0)')
    ->toBePgsql('cast("val" as double precision)')
    ->toBeSqlite('cast("val" as real)')
    ->toBeSqlsrv('cast([val] as float(53))');

it('can cast an expression to a double')
    ->expect(new Cast(new Expression("'42.42'"), 'double'))
    ->toBeExecutable()
    ->toBeMysql("(('42.42')*1.0)")
    ->toBePgsql("cast('42.42' as double precision)")
    ->toBeSqlite("cast('42.42' as real)")
    ->toBeSqlsrv("cast('42.42' as float(53))");
