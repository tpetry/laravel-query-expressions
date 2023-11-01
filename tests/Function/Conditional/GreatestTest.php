<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Conditional\Greatest;

it('can combine multiple columns')
    ->expect(new Greatest(['val1', 'val2', 'val3']))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
        $table->integer('val3');
    })
    ->toBeMysql('greatest(`val1`, `val2`, `val3`)')
    ->toBePgsql('greatest("val1", "val2", "val3")')
    ->toBeSqlite('max("val1", "val2", "val3")')
    ->toBeSqlsrv('(select max(n) from (values ([val1]), ([val2]), ([val3])) as v(n))');

it('can combine multiple expressions')
    ->expect(new Greatest([new Expression(1), new Expression(2), new Expression(3)]))
    ->toBeExecutable()
    ->toBeMysql('greatest(1, 2, 3)')
    ->toBePgsql('greatest(1, 2, 3)')
    ->toBeSqlite('max(1, 2, 3)')
    ->toBeSqlsrv('(select max(n) from (values (1), (2), (3)) as v(n))');

it('can combine multiple columns and expressions')
    ->expect(new Greatest(['val1', 'val2', new Expression(3)]))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('greatest(`val1`, `val2`, 3)')
    ->toBePgsql('greatest("val1", "val2", 3)')
    ->toBeSqlite('max("val1", "val2", 3)')
    ->toBeSqlsrv('(select max(n) from (values ([val1]), ([val2]), (3)) as v(n))');
