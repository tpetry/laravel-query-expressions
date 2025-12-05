<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Time\ExtractYear;

it('can extract the year from a column')
    ->expect(new ExtractYear('val'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->date('val');
    })
    ->toBeMysql('year(`val`)')
    ->toBePgsql('extract(year from "val")::int')
    ->toBeSqlite('cast(strftime(\'%Y\', "val") as integer)')
    ->toBeSqlsrv('year([val])');

it('can extract the year from an expression')
    ->expect(new ExtractYear(new Expression('current_date')))
    ->toBeExecutable(function (Blueprint $table) {
        $table->date('val');
    })
    ->toBeMysql('year(current_date)')
    ->toBePgsql('extract(year from current_date)::int')
    ->toBeSqlite('cast(strftime(\'%Y\', current_date) as integer)')
    ->toBeSqlsrv('year(current_date)');
