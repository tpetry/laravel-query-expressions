<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Concerns;

use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Grammars\MariaDbGrammar as MariaQueryGrammar;
use Illuminate\Database\Query\Grammars\MySqlGrammar as MysqlQueryGrammar;
use Illuminate\Database\Query\Grammars\PostgresGrammar as PgsqlQueryGrammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar as SqliteQueryGrammar;
use Illuminate\Database\Query\Grammars\SqlServerGrammar as SqlsrvQueryGrammar;
use Illuminate\Database\Schema\Grammars\MariaDbGrammar as MariaSchemaGrammar;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as MysqlSchemaGrammar;
use Illuminate\Database\Schema\Grammars\PostgresGrammar as PgsqlSchemaGrammar;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar as SqliteSchemaGrammar;
use Illuminate\Database\Schema\Grammars\SqlServerGrammar as SqlsrvSchemaGrammar;
use RuntimeException;

trait IdentifiesDriver
{
    /**
     * @return 'mariadb'|'mysql'|'pgsql'|'sqlite'|'sqlsrv'
     */
    protected function identify(Grammar $grammar): string
    {
        return match (true) {
            $this->isMaria($grammar) => 'mariadb',
            $this->isMysql($grammar) => 'mysql',
            $this->isPgsql($grammar) => 'pgsql',
            $this->isSqlite($grammar) => 'sqlite',
            $this->isSqlsrv($grammar) => 'sqlsrv',
            default => throw new RuntimeException("Unsupported grammar '".($grammar::class)."'."),
        };
    }

    protected function isMaria(Grammar $grammar): bool
    {
        return $grammar instanceof MariaQueryGrammar || $grammar instanceof MariaSchemaGrammar;
    }

    protected function isMysql(Grammar $grammar): bool
    {
        return $grammar instanceof MysqlQueryGrammar || $grammar instanceof MysqlSchemaGrammar;
    }

    protected function isPgsql(Grammar $grammar): bool
    {
        return $grammar instanceof PgsqlQueryGrammar || $grammar instanceof PgsqlSchemaGrammar;
    }

    protected function isSqlite(Grammar $grammar): bool
    {
        return $grammar instanceof SqliteQueryGrammar || $grammar instanceof SqliteSchemaGrammar;
    }

    protected function isSqlsrv(Grammar $grammar): bool
    {
        return $grammar instanceof SqlsrvQueryGrammar || $grammar instanceof SqlsrvSchemaGrammar;
    }
}
