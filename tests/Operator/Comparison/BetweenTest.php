<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Comparison\Between;

it('can compare a column by between check with two columns')
    ->expect(new Between('val', 'min', 'max'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
        $table->integer('min');
        $table->integer('max');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` between `min` and `max`)')
    ->toBePgsql('("val" between "min" and "max")')
    ->toBeSqlite('("val" between "min" and "max")')
    ->toBeSqlsrv('([val] between [min] and [max])');

it('can compare a column by between check with a column and expression')
    ->expect(new Between('val', 'min', new Expression(99)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
        $table->integer('min');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` between `min` and 99)')
    ->toBePgsql('("val" between "min" and 99)')
    ->toBeSqlite('("val" between "min" and 99)')
    ->toBeSqlsrv('([val] between [min] and 99)');

it('can compare a column by between check with an expression and column')
    ->expect(new Between('val', new Expression(1), 'max'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
        $table->integer('max');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` between 1 and `max`)')
    ->toBePgsql('("val" between 1 and "max")')
    ->toBeSqlite('("val" between 1 and "max")')
    ->toBeSqlsrv('([val] between 1 and [max])');

it('can compare an expression by between check with two columns')
    ->expect(new Between(new Expression(1), 'min', 'max'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('min');
        $table->integer('max');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 between `min` and `max`)')
    ->toBePgsql('(1 between "min" and "max")')
    ->toBeSqlite('(1 between "min" and "max")')
    ->toBeSqlsrv('(1 between [min] and [max])');

it('can compare an expression by between check with an expression and column')
    ->expect(new Between(new Expression(1), new Expression(0), 'max'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('max');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 between 0 and `max`)')
    ->toBePgsql('(1 between 0 and "max")')
    ->toBeSqlite('(1 between 0 and "max")')
    ->toBeSqlsrv('(1 between 0 and [max])');

it('can compare an expression by between check with a column and expression')
    ->expect(new Between(new Expression(1), 'min', new Expression(99)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('min');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 between `min` and 99)')
    ->toBePgsql('(1 between "min" and 99)')
    ->toBeSqlite('(1 between "min" and 99)')
    ->toBeSqlsrv('(1 between [min] and 99)');

it('can compare an expression by between check with two expressions')
    ->expect(new Between(new Expression(1), new Expression(5), new Expression(99)))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(1 between 5 and 99)')
    ->toBePgsql('(1 between 5 and 99)')
    ->toBeSqlite('(1 between 5 and 99)')
    ->toBeSqlsrv('(1 between 5 and 99)');
