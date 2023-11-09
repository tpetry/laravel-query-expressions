<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Math\Abs;

it('can abs a column')
    ->expect(new Abs('val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('(abs(`val`))')
    ->toBePgsql('abs("val")')
    ->toBeSqlite('(abs("val"))')
    ->toBeSqlsrv('abs([val])');

it('can abs an expression')
    ->expect(new Abs(new Expression('sum(1)')))
    ->toBeExecutable()
    ->toBeMysql('(abs(sum(1)))')
    ->toBePgsql('abs(sum(1))')
    ->toBeSqlite('(abs(sum(1)))')
    ->toBeSqlsrv('abs(sum(1))');
