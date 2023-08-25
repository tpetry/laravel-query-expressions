<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\String\Upper;

it('can uppercase a column')
    ->expect(new Upper('val'))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('upper(`val`)')
    ->toBePgsql('upper("val")')
    ->toBeSqlite('upper("val")')
    ->toBeSqlsrv('upper([val])');

it('can uppercase an expression')
    ->expect(new Upper(new Expression('\'foo\'')))
    ->toBeExecutable()
    ->toBeMysql('upper(\'foo\')')
    ->toBePgsql('upper(\'foo\')')
    ->toBeSqlite('upper(\'foo\')')
    ->toBeSqlsrv('upper(\'foo\')');

it('can change collation of column before changing it to uppercase')
    ->expect(new Upper('val', 'utf8mb4_general_ci'))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('upper(convert `val` using utf8mb4_general_ci)')
    ->toBePgsql('upper("val" collate utf8mb4_general_ci)')
    ->toBeSqlite('upper("val" collate utf8mb4_general_ci)')
    ->toBeSqlsrv('upper([val] collate utf8mb4_general_ci)');

it('can change collation of expression before changing it to uppercase')
    ->expect(new Upper(new Expression('\'foo\''), 'utf8mb4_general_ci'))
    ->toBeExecutable()
    ->toBeMysql('upper(convert \'foo\' using utf8mb4_general_ci)')
    ->toBePgsql('upper(\'foo\' collate utf8mb4_general_ci)')
    ->toBeSqlite('upper(\'foo\' collate utf8mb4_general_ci)')
    ->toBeSqlsrv('upper(\'foo\' collate utf8mb4_general_ci)');
