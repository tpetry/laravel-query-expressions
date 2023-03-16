<?php

declare(strict_types=1);

use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Query\Grammars\PostgresGrammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar;
use Illuminate\Database\Query\Grammars\SqlServerGrammar;
use Illuminate\Support\Facades\DB;
use Pest\Expectation;
use PHPUnit\Framework\Assert;
use Tpetry\QueryExpressions\Tests\TestCase;

uses(
    TestCase::class,
)->in(__DIR__);

expect()->extend('toBeExecutable', function (array $columns = [], array $options = []): Expectation {
    /** @var \Illuminate\Database\Connection $connection */
    $connection = DB::connection();

    $table = null;
    if (filled($columns)) {
        $table = 'example_'.mt_rand();
        $columns = implode(',', $columns);
        $connection->unprepared("CREATE TABLE {$table} ({$columns})");
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
    Assert::assertSame($expected, $this->value->getValue(new MySqlGrammar()));

    return $this;
});

expect()->extend('toBePgsql', function (string $expected): Expectation {
    Assert::assertSame($expected, $this->value->getValue(new PostgresGrammar()));

    return $this;
});

expect()->extend('toBeSqlite', function (string $expected): Expectation {
    Assert::assertSame($expected, $this->value->getValue(new SQLiteGrammar()));

    return $this;
});

expect()->extend('toBeSqlsrv', function (string $expected): Expectation {
    Assert::assertSame($expected, $this->value->getValue(new SqlServerGrammar()));

    return $this;
});
