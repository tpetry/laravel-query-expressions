<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Comparison\IsNull;

it('can check an expression')
    ->expect(new IsNull(new Expression(42)))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(42 is null)')
    ->toBePgsql('(42 is null)')
    ->toBeSqlite('(42 is null)')
    ->toBeSqlsrv('(42 is null)');

it('can check a column')
    ->expect(new IsNull('val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(`val` is null)')
    ->toBePgsql('("val" is null)')
    ->toBeSqlite('("val" is null)')
    ->toBeSqlsrv('([val] is null)');
