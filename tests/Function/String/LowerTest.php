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
    ->expect(new Lower('val', 'utf8'))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('lower(convert(`val` using utf8))')
    ->toBePgsql('lower("val" collate utf8)')
    ->toBeSqlite('lower("val" collate utf8)')
    ->toBeSqlsrv('lower([val] collate utf8)');

it('can change collation of expression before changing it to lowercase')
    ->expect(new Lower(new Expression('\'foo\''), 'utf8'))
    ->toBeExecutable()
    ->toBeMysql('lower(convert(\'foo\' using utf8))')
    ->toBePgsql('lower(\'foo\' collate utf8)')
    ->toBeSqlite('lower(\'foo\' collate utf8)')
    ->toBeSqlsrv('lower(\'foo\' collate utf8)');
