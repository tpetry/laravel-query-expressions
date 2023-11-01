<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Aggregate\Count;

it('can aggregate a column by COUNT')
    ->expect(new Count('val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('count(`val`)')
    ->toBePgsql('count("val")')
    ->toBeSqlite('count("val")')
    ->toBeSqlsrv('count([val])');

it('can aggregate a column by COUNT with DISTINCT')
    ->expect(new Count('val', true))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('count(distinct `val`)')
    ->toBePgsql('count(distinct "val")')
    ->toBeSqlite('count(distinct "val")')
    ->toBeSqlsrv('count(distinct [val])');

it('can aggregate an expression by COUNT')
    ->expect(new Count(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('count(1)')
    ->toBePgsql('count(1)')
    ->toBeSqlite('count(1)')
    ->toBeSqlsrv('count(1)');

it('can aggregate an expression by COUNT with DISTINCT')
    ->expect(new Count(new Expression(1), true))
    ->toBeExecutable()
    ->toBeMysql('count(distinct 1)')
    ->toBePgsql('count(distinct 1)')
    ->toBeSqlite('count(distinct 1)')
    ->toBeSqlsrv('count(distinct 1)');
