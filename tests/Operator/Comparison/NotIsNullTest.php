<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Comparison\NotIsNull;

it('can check an expression')
    ->expect(new NotIsNull(new Expression(42)))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(42 is not null)')
    ->toBePgsql('(42 is not null)')
    ->toBeSqlite('(42 is not null)')
    ->toBeSqlsrv('(42 is not null)');

it('can check a column')
    ->expect(new NotIsNull('val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` is not null)')
    ->toBePgsql('("val" is not null)')
    ->toBeSqlite('("val" is not null)')
    ->toBeSqlsrv('([val] is not null)');
