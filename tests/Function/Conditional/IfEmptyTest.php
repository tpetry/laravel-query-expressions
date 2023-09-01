<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Conditional\IfEmpty;

it('can use other column if main column is empty')
    ->expect(new IfEmpty('val1', 'val2'))
    ->toBeExecutable(['val1 varchar(255)', 'val2 varchar(255)'])
    ->toBeMysql('ifnull(nullif(`val1`,\'\'),`val2`)')
    ->toBePgsql('ifnull(nullif("val1",\'\'),"val2")')
    ->toBeSqlite('ifnull(nullif("val1",\'\'),"val2")')
    ->toBeSqlsrv('ifnull(nullif([val1],\'\'),[val2])');

it('can use other expression if main expression is empty')
    ->expect(new IfEmpty(new Expression('\'foo\''), new Expression('\'bar\'')))
    ->toBeExecutable()
    ->toBeMysql('ifnull(nullif(\'foo\',\'\'),\'bar\')')
    ->toBePgsql('ifnull(nullif(\'foo\',\'\'),\'bar\')')
    ->toBeSqlite('ifnull(nullif(\'foo\',\'\'),\'bar\')')
    ->toBeSqlsrv('ifnull(nullif(\'foo\',\'\'),\'bar\')');

it('can use expression if column is empty')
    ->expect(new IfEmpty('val', new Expression('\'foo\'')))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('ifnull(nullif(`val`,\'\'),\'foo\')')
    ->toBePgsql('ifnull(nullif("val",\'\'),\'foo\')')
    ->toBeSqlite('ifnull(nullif("val",\'\'),\'foo\')')
    ->toBeSqlsrv('ifnull(nullif([val],\'\'),\'foo\')');
