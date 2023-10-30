<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Aggregate\Sum;

it('can aggregate a column by SUM')
    ->expect(new Sum('val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('sum(`val`)')
    ->toBePgsql('sum("val")')
    ->toBeSqlite('sum("val")')
    ->toBeSqlsrv('sum([val])');

it('can aggregate an expression by SUM')
    ->expect(new Sum(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('sum(1)')
    ->toBePgsql('sum(1)')
    ->toBeSqlite('sum(1)')
    ->toBeSqlsrv('sum(1)');
