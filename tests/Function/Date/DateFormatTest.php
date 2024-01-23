<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tpetry\QueryExpressions\Function\Date\DateFormat;

it('can format dates: [Y-m-d H:i:s] direct character, no concat')
    ->expect(new DateFormat('created_at', format: 'Y-m-d H:i:s'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->dateTime('created_at');
    })
    ->toBePgsql('TO_CHAR("created_at", \'YYYY-MM-DD HH24:MI:SS\')')
    ->toBeSqlite('STRFTIME(\'%Y-%m-%d %H:%M:%S\', "created_at")')
    ->toBeMysql('DATE_FORMAT(`created_at`, \'%Y-%m-%d %H:%i:%s\')')
    ->toBeSqlsrv('FORMAT([created_at], \'yyyy-MM-dd HH:mm:ss\')');

it('can format dates: [Y] direct character, no concat')
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

it('can format dates: [U-Y-m] emulated and direct characters, no concat for sqlite')
    ->expect(new DateFormat('created_at', format: 'U-Y-m'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->dateTime('created_at');
    })
    ->toBePgsql('(EXTRACT(EPOCH FROM "created_at")::INTEGER||TO_CHAR("created_at", \'-YYYY-MM\'))')
    ->toBeSqlite('STRFTIME(\'%s-%Y-%m\', "created_at")')
    ->toBeMysql('(concat(UNIX_TIMESTAMP(`created_at`),DATE_FORMAT(`created_at`, \'-%Y-%m\')))')
    ->toBeSqlsrv('(concat(DATEDIFF(SECOND, \'1970-01-01\', [created_at]),FORMAT([created_at], \'-yyyy-MM\')))');

it('can format dates correctly', function () {

    if (
        DB::connection()->getDriverName() === 'mysql'
    ) {
        DB::statement('SET time_zone = \'+00:00\'');
    }

    $testFormats = [
        '\yy',
        'a j-n-o F w W g G h H i s',
        'Y-m-d\TH:i:s',
        'a',
        'A',
        'd',
        'D',
        'F',
        'G',
        'g',
        'h',
        'H',
        'i',
        'j',
        'l',
        'm',
        'M',
        'n',
        'o',
        's',
        't',
        'U',
        'w',
        'W',
        'Y',
        'y',
    ];

    $testDates = [
        '1970-01-01 00:00:00',
        '1970-06-01 00:00:00',
        '1970-12-31 23:59:59',

        '2023-01-01 00:00:00',
        '2023-06-01 00:00:00',
        '2023-12-31 23:59:59',

        '2024-01-01 00:00:00',
        '2024-06-01 00:00:00',
        '2024-12-31 23:59:59',

        '2025-01-01 00:00:00',
        '2025-06-01 00:00:00',
        '2025-12-31 23:59:59',

        '2026-01-01 00:00:00',
        '2026-06-01 00:00:00',
        '2026-12-31 23:59:59',

        '2037-01-01 00:00:00',
        '2037-06-01 00:00:00',
        '2037-12-31 23:59:59',
    ];

    foreach ($testDates as $date) {
        Schema::create($table = 'example_'.mt_rand(), function (Blueprint $table) {
            $table->dateTime('created_at');
        });

        DB::table($table)->insert([
            'created_at' => $date,
        ]);

        $date = new DateTime($date);

        $grammar = DB::connection()->getQueryGrammar();

        foreach ($testFormats as $format) {
            $sql = new DateFormat('created_at', $format);

            expect(
                $value = DB::table($table)->selectRaw(
                    $sql
                )->value('created_at')
            )->toEqual(
                $expected = $date->format($format),
                "expected: {$expected}, value: {$value}, format: {$format}, date: {$date->format('Y-m-d H:i:s')}"
                .PHP_EOL
                .'SQL: '
                .$sql->getValue(
                    $grammar
                )
            );
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

it('can change locale for sqlite', function () {
    if (
        DB::connection()->getDriverName() !== 'sqlite'
    ) {
        $this->markTestSkipped('Only for sqlite');
    }

    Carbon::setLocale('de');

    Schema::create($table = 'example_'.mt_rand(), function (Blueprint $table) {
        $table->dateTime('created_at');
    });

    DB::table($table)->insert([
        'created_at' => '2021-01-01 09:00:00',
    ]);

    $format = 'a-A-D-F-l-M';

    $expected = Carbon::make('2021-01-01 09:00:00')->translatedFormat($format);

    $value = DB::table($table)->selectRaw(
        new DateFormat('created_at', $format)
    )->value('created_at');

    expect($value)->toEqual($expected);

    Carbon::setLocale('en');
});
