<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Bitwise\ShiftRight;

it('can bitwise shift right two columns')
    ->expect(new ShiftRight('val1', 'val2'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('floor(`val1` / power(2, `val2`))')
    ->toBePgsql('("val1" >> "val2")')
    ->toBeSqlite('("val1" >> "val2")')
    ->toBeSqlsrv('floor([val1] / power(2, [val2]))');

it('can bitwise shift right two expressions')
    ->expect(new ShiftRight(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('floor(1 / power(2, 2))')
    ->toBePgsql('(1 >> 2)')
    ->toBeSqlite('(1 >> 2)')
    ->toBeSqlsrv('floor(1 / power(2, 2))');

it('can bitwise shift right an expression and a column')
    ->expect(new ShiftRight('val', new Expression(0)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('floor(`val` / power(2, 0))')
    ->toBePgsql('("val" >> 0)')
    ->toBeSqlite('("val" >> 0)')
    ->toBeSqlsrv('floor([val] / power(2, 0))');

it('can bitwise shift right a column and an expression')
    ->expect(new ShiftRight(new Expression(0), 'val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('floor(0 / power(2, `val`))')
    ->toBePgsql('(0 >> "val")')
    ->toBeSqlite('(0 >> "val")')
    ->toBeSqlsrv('floor(0 / power(2, [val]))');
