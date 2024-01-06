<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tpetry\QueryExpressions\Function\Date\DateFormat;

it('can format dates: [Y-m-d H:i:s]')
    ->expect(new DateFormat('created_at', format: 'Y-m-d H:i:s'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->dateTime('created_at');
    })
    ->toBePgsql('(TO_CHAR("created_at", \'YYYY\')||\'-\'||TO_CHAR("created_at", \'MM\')||\'-\'||TO_CHAR("created_at", \'DD\')||\' \'||TO_CHAR("created_at", \'HH24\')||\':\'||TO_CHAR("created_at", \'MI\')||\':\'||TO_CHAR("created_at", \'SS\'))')
    ->toBeSqlite('(STRFTIME(\'%Y\', "created_at")||\'-\'||STRFTIME(\'%m\', "created_at")||\'-\'||STRFTIME(\'%d\', "created_at")||\' \'||STRFTIME(\'%H\', "created_at")||\':\'||STRFTIME(\'%M\', "created_at")||\':\'||STRFTIME(\'%S\', "created_at"))')
    ->toBeMysql('(concat(DATE_FORMAT(`created_at`, \'%Y\'),\'-\',DATE_FORMAT(`created_at`, \'%m\'),\'-\',DATE_FORMAT(`created_at`, \'%d\'),\' \',DATE_FORMAT(`created_at`, \'%H\'),\':\',DATE_FORMAT(`created_at`, \'%i\'),\':\',DATE_FORMAT(`created_at`, \'%s\')))')
    ->toBeSqlsrv('(concat(FORMAT([created_at], \'yyyy\'),\'-\',FORMAT([created_at], \'MM\'),\'-\',FORMAT([created_at], \'dd\'),\' \',FORMAT([created_at], \'HH\'),\':\',FORMAT([created_at], \'mm\'),\':\',FORMAT([created_at], \'ss\')))');

it('can format dates: [Y] format character, no concat')
    ->expect(new DateFormat('created_at', format: 'Y'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->dateTime('created_at');
    })
    ->toBePgsql('TO_CHAR("created_at", \'YYYY\')')
    ->toBeSqlite('STRFTIME(\'%Y\', "created_at")')
    ->toBeMysql('DATE_FORMAT(`created_at`, \'%Y\')')
    ->toBeSqlsrv('FORMAT([created_at], \'yyyy\')');

it('can format dates: [U] emulated character, no concat')
    ->expect(new DateFormat('created_at', format: 'U'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->dateTime('created_at');
    })
    ->toBePgsql('EXTRACT(EPOCH FROM "created_at")::INTEGER')
    ->toBeSqlite('STRFTIME(\'%s\', "created_at")')
    ->toBeMysql('UNIX_TIMESTAMP(`created_at`)')
    ->toBeSqlsrv('DATEDIFF(SECOND, \'1970-01-01\', [created_at])');

it('can format dates', function () {

    if (
        DB::connection()->getDriverName() === 'mysql'
    ) {
        DB::statement('SET time_zone = \'+00:00\'');
    }

    $testData = [
        '2021-01-01 12:00:00' => [
            'g' => '12',
        ],
        '2021-01-01 00:00:00' => [
            'g' => '12',
            '\yy\\' => 'y21\\',
        ],
        '2021-01-01 09:00:00' => [
            'a' => 'am',
            'A' => 'AM',
            'd' => '01',
            'D' => 'Fri',
            'F' => 'January',
            'G' => '9',
            'g' => '9',
            'H' => '09',
            'h' => '09',
            'i' => '00',
            'j' => '1',
            'l' => 'Friday',
            'm' => '01',
            'M' => 'Jan',
            'n' => '1',
            'o' => '2020',
            's' => '00',
            't' => '31',
            'U' => '1609491600',
            'w' => '5',
            'W' => '53',
            'Y' => '2021',
            'y' => '21',
        ],
        '2021-12-09 19:02:37' => [
            'a' => 'pm',
            'A' => 'PM',
            'd' => '09',
            'D' => 'Thu',
            'F' => 'December',
            'G' => '19',
            'g' => '7',
            'h' => '07',
            'H' => '19',
            'i' => '02',
            'j' => '9',
            'l' => 'Thursday',
            'm' => '12',
            'M' => 'Dec',
            'n' => '12',
            'o' => '2021',
            's' => '37',
            't' => '31',
            'U' => '1639076557',
            'w' => '4',
            'W' => '49',
            'Y' => '2021',
            'y' => '21',
        ],
    ];

    foreach ($testData as $date => $expected) {
        Schema::create($table = 'example_'.mt_rand(), function (Blueprint $table) {
            $table->dateTime('created_at');
        });

        DB::table($table)->insert([
            'created_at' => $date,
        ]);

        foreach ($expected as $format => $expected) {
            expect(
                DB::table($table)->selectRaw(
                    new DateFormat('created_at', $format)
                )->value('created_at')
            )->toEqual($expected, "format: {$format}");
        }
    }
});

it('throws exception for unsupported characters', function () {
    Schema::create($table = 'example_'.mt_rand(), function (Blueprint $table) {
        $table->dateTime('created_at');
    });

    DB::table($table)->insert([
        'created_at' => '2021-01-01 09:00:00',
    ]);

    DB::table($table)->selectRaw(
        new DateFormat('created_at', 'N')
    )->value('created_at');

})->throws(InvalidArgumentException::class, 'Unsupported format character: N');

it('doesn\'t throw exception for escaped characters', function () {
    Schema::create($table = 'example_'.mt_rand(), function (Blueprint $table) {
        $table->dateTime('created_at');
    });

    DB::table($table)->insert([
        'created_at' => '2021-01-01 09:00:00',
    ]);

    expect(
        DB::table($table)->selectRaw(
            new DateFormat('created_at', '\N')
        )->value('created_at')
    )->toEqual('N');
});
