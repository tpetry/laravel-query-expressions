<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Pest\Expectation;
use PHPUnit\Framework\Assert;
use Tpetry\QueryExpressions\Tests\TestCase;

uses(
    TestCase::class,
)->in(__DIR__);

expect()->extend('toBeExecutable', function (
    Closure $callback = null,
    array $options = [],
): Expectation {
    /** @var \Illuminate\Database\Connection $connection */
    $connection = DB::connection();

    $table = null;

    if (filled($callback)) {
        $table = 'example_'.mt_rand();

        Schema::create($table, function (Blueprint $table) use ($callback) {
            $callback($table);
        });
    }

    $position = $options[$connection->getDriverName()]['position'] ?? 'select';

    $expression = $this->value->getValue($connection->getQueryGrammar());
    $select = ($position === 'select') ? "select {$expression}" : 'select 1';
    $from = filled($table) ? "from {$table}" : '';
    $where = ($position === 'where') ? "where {$expression}" : '';
    $connection->select("{$select} {$from} {$where}");

    return $this;
});

expect()->extend('toBeMysql', function (string $expected): Expectation {
    if (DB::connection()->getDriverName() === 'mysql') {
        Assert::assertSame($expected, $this->value->getValue(DB::connection()->getQueryGrammar()));
    }

    return $this;
});

expect()->extend('toBePgsql', function (string $expected): Expectation {
    if (DB::connection()->getDriverName() === 'pgsql') {
        Assert::assertSame($expected, $this->value->getValue(DB::connection()->getQueryGrammar()));
    }

    return $this;
});

expect()->extend('toBeSqlite', function (string $expected): Expectation {
    if (DB::connection()->getDriverName() === 'sqlite') {
        Assert::assertSame($expected, $this->value->getValue(DB::connection()->getQueryGrammar()));
    }

    return $this;
});

expect()->extend('toBeSqlsrv', function (string $expected): Expectation {
    if (DB::connection()->getDriverName() === 'sqlsrv') {
        Assert::assertSame($expected, $this->value->getValue(DB::connection()->getQueryGrammar()));
    }

    return $this;
});

function skipOnMariaBefore(string $version)
{
    /** @var \Illuminate\Database\Connection $connection */
    $connection = DB::connection();

    if ($connection->getDriverName() !== 'mysql') {
        return;
    }

    $actual = $connection->scalar('select version()');
    if (str_contains($actual, 'MariaDB') && version_compare($actual, $version, '<')) {
        test()->markTestSkipped("The MariaDB version must be at least {$version}.");
    }
}

function skipOnMysqlBefore(string $version): void
{
    /** @var \Illuminate\Database\Connection $connection */
    $connection = DB::connection();

    if ($connection->getDriverName() !== 'mysql') {
        return;
    }

    $actual = $connection->scalar('select version()');
    if (! str_contains($actual, 'MariaDB') && version_compare($actual, $version, '<')) {
        test()->markTestSkipped("The MariaDB version must be at least {$version}.");
    }
}

function skipOnPgsqlBefore(string $version): void
{
    /** @var \Illuminate\Database\Connection $connection */
    $connection = DB::connection();

    if ($connection->getDriverName() !== 'pgsql') {
        return;
    }

    $actual = $connection->scalar('show server_version');
    if (version_compare($actual, $version, '<')) {
        test()->markTestSkipped("The PostgreSQL version must be at least {$version}.");
    }
}

function skipOnSqliteBefore(string $version): void
{
    /** @var \Illuminate\Database\Connection $connection */
    $connection = DB::connection();

    if ($connection->getDriverName() !== 'sqlite') {
        return;
    }

    $actual = $connection->scalar('select sqlite_version()');
    if (version_compare($actual, $version, '<')) {
        test()->markTestSkipped("The SQLite version must be at least {$version}.");
    }
}

function skipOnSqlsrvBefore(string $version): void
{
    /** @var \Illuminate\Database\Connection $connection */
    $connection = DB::connection();

    if ($connection->getDriverName() !== 'sqlsrv') {
        return;
    }

    $actual = $connection->scalar("select serverproperty('productversion')");
    if (version_compare($actual, $version, '<')) {
        test()->markTestSkipped("The SQL Server version must be at least {$version}.");
    }
}
