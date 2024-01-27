<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Operator\Arithmetic\Divide;

it('can divide two columns')
    ->expect(new Divide('val1', 'val2'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val1');
        $table->integer('val2');
    })
    ->toBeMysql('(`val1` / `val2`)')
    ->toBePgsql('("val1" / "val2")')
    ->toBeSqlite('("val1" / "val2")')
    ->toBeSqlsrv('([val1] / [val2])');

it('can divide two expressions')
    ->expect(new Divide(new Expression(1), new Expression(2)))
    ->toBeExecutable()
    ->toBeMysql('(1 / 2)')
    ->toBePgsql('(1 / 2)')
    ->toBeSqlite('(1 / 2)')
    ->toBeSqlsrv('(1 / 2)');

it('can divide an expression and a column')
    ->expect(new Divide('val', new Expression(0)))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('(`val` / 0)')
    ->toBePgsql('("val" / 0)')
    ->toBeSqlite('("val" / 0)')
    ->toBeSqlsrv('([val] / 0)');

it('can divide a column and an expression')
    ->expect(new Divide(new Expression(0), 'val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->integer('val');
    })
    ->toBeMysql('(0 / `val`)')
    ->toBePgsql('(0 / "val")')
    ->toBeSqlite('(0 / "val")')
    ->toBeSqlsrv('(0 / [val])');

it('can divide many columns', function ($columns, $expectations) {
    expect(new Divide(...$columns))
        ->toBeExecutable(function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                $table->integer($column);
            }
        })
        ->toBeMysql($expectations['mysql'])
        ->toBePgsql($expectations['pgsql'])
        ->toBeSqlite($expectations['sqlite'])
        ->toBeSqlsrv($expectations['sqlsrv']);
})->with([
    'three' => [
        ['val1', 'val2', 'val3'],
        [
            'mysql' => '(`val1` / `val2` / `val3`)',
            'pgsql' => '("val1" / "val2" / "val3")',
            'sqlite' => '("val1" / "val2" / "val3")',
            'sqlsrv' => '([val1] / [val2] / [val3])',
        ],
    ],
    'eight' => [
        ['val1', 'val2', 'val3', 'val4', 'val5', 'val6', 'val7', 'val8'],
        [
            'mysql' => '(`val1` / `val2` / `val3` / `val4` / `val5` / `val6` / `val7` / `val8`)',
            'pgsql' => '("val1" / "val2" / "val3" / "val4" / "val5" / "val6" / "val7" / "val8")',
            'sqlite' => '("val1" / "val2" / "val3" / "val4" / "val5" / "val6" / "val7" / "val8")',
            'sqlsrv' => '([val1] / [val2] / [val3] / [val4] / [val5] / [val6] / [val7] / [val8])',
        ],
    ],
]);

it('can divide many expressions', function ($values, $expectations) {
    $expressions = array_map(fn ($value) => new Expression($value), $values);

    expect(new Divide(...$expressions))
        ->toBeExecutable()
        ->toBeMysql($expectations['mysql'])
        ->toBePgsql($expectations['pgsql'])
        ->toBeSqlite($expectations['sqlite'])
        ->toBeSqlsrv($expectations['sqlsrv']);
})->with([
    'three' => [
        [1, 2, 3],
        [
            'mysql' => '(1 / 2 / 3)',
            'pgsql' => '(1 / 2 / 3)',
            'sqlite' => '(1 / 2 / 3)',
            'sqlsrv' => '(1 / 2 / 3)',
        ],
    ],
    'eight' => [
        [1, 2, 3, 4, 5, 6, 7, 8],
        [
            'mysql' => '(1 / 2 / 3 / 4 / 5 / 6 / 7 / 8)',
            'pgsql' => '(1 / 2 / 3 / 4 / 5 / 6 / 7 / 8)',
            'sqlite' => '(1 / 2 / 3 / 4 / 5 / 6 / 7 / 8)',
            'sqlsrv' => '(1 / 2 / 3 / 4 / 5 / 6 / 7 / 8)',
        ],
    ],
]);

it('can divide many expressions and columns', function (array $values, array $expectations) {
    $expressions = array_map(
        fn ($value) => is_int($value)
            ? new Expression($value)
            : $value,
        $values
    );

    expect(new Divide(...$expressions))
        ->toBeExecutable(function (Blueprint $table) {
            $table->integer('val1');
            $table->integer('val2');
        })
        ->toBeMysql($expectations['mysql'])
        ->toBePgsql($expectations['pgsql'])
        ->toBeSqlite($expectations['sqlite'])
        ->toBeSqlsrv($expectations['sqlsrv']);
})->with([
    'expression/column/...' => [
        ['val1', 1, 'val2', 2],
        [
            'mysql' => '(`val1` / 1 / `val2` / 2)',
            'pgsql' => '("val1" / 1 / "val2" / 2)',
            'sqlite' => '("val1" / 1 / "val2" / 2)',
            'sqlsrv' => '([val1] / 1 / [val2] / 2)',
        ],
    ],
    'column/expression/...' => [
        [1, 'val1', 2, 'val2'],
        [
            'mysql' => '(1 / `val1` / 2 / `val2`)',
            'pgsql' => '(1 / "val1" / 2 / "val2")',
            'sqlite' => '(1 / "val1" / 2 / "val2")',
            'sqlsrv' => '(1 / [val1] / 2 / [val2])',
        ],
    ],
]);
