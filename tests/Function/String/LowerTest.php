<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\String\Lower;

it('can lowercase a column')
    ->expect(new Lower('val'))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('lower(`val`)')
    ->toBePgsql('lower("val")')
    ->toBeSqlite('lower("val")')
    ->toBeSqlsrv('lower([val])');

it('can lowercase an expression')
    ->expect(new Lower(new Expression('\'foo\'')))
    ->toBeExecutable()
    ->toBeMysql('lower(\'foo\')')
    ->toBePgsql('lower(\'foo\')')
    ->toBeSqlite('lower(\'foo\')')
    ->toBeSqlsrv('lower(\'foo\')');

it('can change collation of column before changing it to lowercase')
    ->expect(new Lower('val', 'utf8mb4_general_ci'))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('lower(convert `val` using utf8mb4_general_ci)')
    ->toBePgsql('lower("val" collate utf8mb4_general_ci)')
    ->toBeSqlite('lower("val" collate utf8mb4_general_ci)')
    ->toBeSqlsrv('lower([val] collate utf8mb4_general_ci)');

it('can change collation of expression before changing it to lowercase')
    ->expect(new Lower(new Expression('\'foo\''), 'utf8mb4_general_ci'))
    ->toBeExecutable()
    ->toBeMysql('lower(convert \'foo\' using utf8mb4_general_ci)')
    ->toBePgsql('lower(\'foo\' collate utf8mb4_general_ci)')
    ->toBeSqlite('lower(\'foo\' collate utf8mb4_general_ci)')
    ->toBeSqlsrv('lower(\'foo\' collate utf8mb4_general_ci)');
