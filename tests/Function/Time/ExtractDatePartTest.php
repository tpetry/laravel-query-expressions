<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Time\ExtractDatePart;

test('fails for unimplemented date parts', function () {
    expect(function () {
        (new ExtractDatePart('val', 'day'))->getValue(DB::getQueryGrammar());
    })->toThrow("Invalid date part: 'day'.");
});

it('can extract the year from a column')
    ->expect(new ExtractDatePart('val', 'year'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->date('val');
    })
    ->toBeMysql('(year(`val`))')
    ->toBePgsql('extract(year from "val")::int')
    ->toBeSqlite('cast(strftime(\'%Y\', "val") as integer)')
    ->toBeSqlsrv('year([val])');

it('can extract the year from an expression')
    ->expect(new ExtractDatePart(new Expression('current_date'), 'year'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->date('val');
    })
    ->toBeMysql('(year(current_date))')
    ->toBePgsql('extract(year from current_date)::int')
    ->toBeSqlite('cast(strftime(\'%Y\', current_date) as integer)')
    ->toBeSqlsrv('year(current_date)');
