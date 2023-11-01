<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Aggregate\SumFilter;

it('can aggregate a column by SUM with a filter')
    ->expect(new SumFilter('val1', new Expression('val2 = 1')))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('sum(case when val2 = 1 then `val1` else 0 end)')
    ->toBePgsql('sum("val1") filter (where val2 = 1)')
    ->toBeSqlite('sum("val1") filter (where val2 = 1)')
    ->toBeSqlsrv('sum(case when val2 = 1 then [val1] else 0 end)');

it('can aggregate an expression by SUM with a filter')
    ->expect(new SumFilter(new Expression(1), new Expression('val = 1')))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('sum(case when val = 1 then 1 else 0 end)')
    ->toBePgsql('sum(1) filter (where val = 1)')
    ->toBeSqlite('sum(1) filter (where val = 1)')
    ->toBeSqlsrv('sum(case when val = 1 then 1 else 0 end)');
