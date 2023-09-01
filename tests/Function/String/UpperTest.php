<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\String\Upper;

it('can uppercase a column')
    ->expect(new Upper('val'))
    ->toBeExecutable(['val varchar(255)'])
    ->toBeMysql('(upper(`val`))')
    ->toBePgsql('upper("val")')
    ->toBeSqlite('(upper("val"))')
    ->toBeSqlsrv('upper([val])');

it('can uppercase an expression')
    ->expect(new Upper(new Expression("'foo'")))
    ->toBeExecutable()
    ->toBeMysql("(upper('foo'))")
    ->toBePgsql("upper('foo')")
    ->toBeSqlite("(upper('foo'))")
    ->toBeSqlsrv("upper('foo')");
