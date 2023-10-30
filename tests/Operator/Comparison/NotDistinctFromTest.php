<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Comparison\NotDistinctFrom;

it('can compare two columns by not distinct check')
    ->expect(new NotDistinctFrom('val1', 'val2'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val1` <=> `val2`)')
    ->toBePgsql('("val1" is not distinct from "val2")')
    ->toBeSqlite('("val1" is "val2")')
    ->toBeSqlsrv('([val1] = [val2] or ([val1] is null and [val2] is null))');

it('can compare two expressions by not distinct check')
    ->expect(new NotDistinctFrom(new Expression(1), new Expression(2)))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 <=> 2)')
    ->toBePgsql('(1 is not distinct from 2)')
    ->toBeSqlite('(1 is 2)')
    ->toBeSqlsrv('(1 = 2 or (1 is null and 2 is null))');

it('can compare an expression and a column by not distinct check')
    ->expect(new NotDistinctFrom('val', new Expression(0)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` <=> 0)')
    ->toBePgsql('("val" is not distinct from 0)')
    ->toBeSqlite('("val" is 0)')
    ->toBeSqlsrv('([val] = 0 or ([val] is null and 0 is null))');

it('can compare a column and an expression by not distinct check')
    ->expect(new NotDistinctFrom(new Expression(0), 'val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(0 <=> `val`)')
    ->toBePgsql('(0 is not distinct from "val")')
    ->toBeSqlite('(0 is "val")')
    ->toBeSqlsrv('(0 = [val] or (0 is null and [val] is null))');
