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
    ->expect(new Upper('val', 'utf8'))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('upper(convert `val` using utf8)')
    ->toBePgsql('upper("val" collate utf8)')
    ->toBeSqlite('upper("val" collate utf8)')
    ->toBeSqlsrv('upper([val] collate utf8)');

it('can change collation of expression before changing it to uppercase')
    ->expect(new Upper(new Expression('\'foo\''), 'utf8'))
    ->toBeExecutable()
    ->toBeMysql('upper(convert \'foo\' using utf8)')
    ->toBePgsql('upper(\'foo\' collate utf8)')
    ->toBeSqlite('upper(\'foo\' collate utf8)')
    ->toBeSqlsrv('upper(\'foo\' collate utf8)');
