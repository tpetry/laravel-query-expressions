<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Tpetry\QueryExpressions\Function\Time\TimestampBin;

it('can bin the time of a column')
    ->expect(new TimestampBin('val', DateInterval::createFromDateString('5 minutes')))
    ->toBeExecutable(function (Blueprint $table) {
        $table->timestamp('val');
    })
    ->toBeMysql('(from_unixtime(floor((unix_timestamp(`val`)-0)/300)*300+0))')
    ->toBePgsql('to_timestamp(floor((extract(epoch from "val")-0)/300)*300+0)')
    ->toBeSqlite('(datetime((strftime(\'%s\',"val")-0)/300*300+0,\'unixepoch\'))')
    ->toBeSqlsrv('dateadd(s,(datediff(s,\'1970-01-01\',[val])-0)/300*300+0,\'1970-01-01\')');

it('can bin the time of an expression')
    ->expect(new TimestampBin(new Expression('current_timestamp'), DateInterval::createFromDateString('1 minute')))
    ->toBeExecutable(function (Blueprint $table) {
        $table->timestamp('val');
    })
    ->toBeMysql('(from_unixtime(floor((unix_timestamp(current_timestamp)-0)/60)*60+0))')
    ->toBePgsql('to_timestamp(floor((extract(epoch from current_timestamp)-0)/60)*60+0)')
    ->toBeSqlite('(datetime((strftime(\'%s\',current_timestamp)-0)/60*60+0,\'unixepoch\'))')
    ->toBeSqlsrv('dateadd(s,(datediff(s,\'1970-01-01\',current_timestamp)-0)/60*60+0,\'1970-01-01\')');

it('can bin the time of a column with an origin')
    ->expect(new TimestampBin('val', DateInterval::createFromDateString('90 seconds'), DateTime::createFromFormat('Y-m-d H:i:s', '2022-02-02 22:22:22')))
    ->toBeExecutable(function (Blueprint $table) {
        $table->timestamp('val');
    })
    ->toBeMysql('(from_unixtime(floor((unix_timestamp(`val`)-1643840542)/90)*90+1643840542))')
    ->toBePgsql('to_timestamp(floor((extract(epoch from "val")-1643840542)/90)*90+1643840542)')
    ->toBeSqlite('(datetime((strftime(\'%s\',"val")-1643840542)/90*90+1643840542,\'unixepoch\'))')
    ->toBeSqlsrv('dateadd(s,(datediff(s,\'1970-01-01\',[val])-1643840542)/90*90+1643840542,\'1970-01-01\')');

it('can bin the time of an expression with an origin')
    ->expect(new TimestampBin(new Expression('current_timestamp'), DateInterval::createFromDateString('1 hour'), DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:00')))
    ->toBeExecutable(function (Blueprint $table) {
        $table->timestamp('val');
    })
    ->toBeMysql('(from_unixtime(floor((unix_timestamp(current_timestamp)-946684800)/3600)*3600+946684800))')
    ->toBePgsql('to_timestamp(floor((extract(epoch from current_timestamp)-946684800)/3600)*3600+946684800)')
    ->toBeSqlite('(datetime((strftime(\'%s\',current_timestamp)-946684800)/3600*3600+946684800,\'unixepoch\'))')
    ->toBeSqlsrv('dateadd(s,(datediff(s,\'1970-01-01\',current_timestamp)-946684800)/3600*3600+946684800,\'1970-01-01\')');

it('does not support millisecond steps', function () {
    $expression = new TimestampBin(
        expression: new Expression('current_timestamp'),
        step: DateInterval::createFromDateString('500 milliseconds'),
    );

    $expression->getValue(DB::connection()->getQueryGrammar());
})->throws(RuntimeException::class, 'timestamp binning with millisecond resolution is not supported');

it('does not support origins before 1970-01-01', function () {
    $expression = new TimestampBin(
        expression: new Expression('current_timestamp'),
        step: DateInterval::createFromDateString('1 second'),
        origin: DateTime::createFromFormat('Y-m-d', '1900-01-01'),
    );

    $expression->getValue(DB::connection()->getQueryGrammar());
})->throws(RuntimeException::class, 'timestamp binning with an origin before 1970-01-01 is not supported');
