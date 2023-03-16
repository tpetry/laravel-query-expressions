<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Aggregate\Count;

it('can aggregate a column by COUNT')
    ->expect(new Count('val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('count(`val`)')
    ->toBePgsql('count("val")')
    ->toBeSqlite('count("val")')
    ->toBeSqlsrv('count([val])');

it('can aggregate an expression by COUNT')
    ->expect(new Count(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('count(1)')
    ->toBePgsql('count(1)')
    ->toBeSqlite('count(1)')
    ->toBeSqlsrv('count(1)');
