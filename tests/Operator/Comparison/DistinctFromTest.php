<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Comparison\DistinctFrom;

it('can compare two columns by distinct check')
    ->expect(new DistinctFrom('val1', 'val2'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(not `val1` <=> `val2`)')
    ->toBePgsql('("val1" is distinct from "val2")')
    ->toBeSqlite('("val1" is not "val2")')
    ->toBeSqlsrv('([val1] != [val2] or ([val1] is not null and [val2] is null) or ([val1] is null and [val2] is not null))');

it('can compare two expressions by distinct check')
    ->expect(new DistinctFrom(new Expression(1), new Expression(2)))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(not 1 <=> 2)')
    ->toBePgsql('(1 is distinct from 2)')
    ->toBeSqlite('(1 is not 2)')
    ->toBeSqlsrv('(1 != 2 or (1 is not null and 2 is null) or (1 is null and 2 is not null))');

it('can compare an expression and a column by distinct check')
    ->expect(new DistinctFrom('val', new Expression(0)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(not `val` <=> 0)')
    ->toBePgsql('("val" is distinct from 0)')
    ->toBeSqlite('("val" is not 0)')
    ->toBeSqlsrv('([val] != 0 or ([val] is not null and 0 is null) or ([val] is null and 0 is not null))');

it('can compare a column and an expression by distinct check')
    ->expect(new DistinctFrom(new Expression(0), 'val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(not 0 <=> `val`)')
    ->toBePgsql('(0 is distinct from "val")')
    ->toBeSqlite('(0 is not "val")')
    ->toBeSqlsrv('(0 != [val] or (0 is not null and [val] is null) or (0 is null and [val] is not null))');
