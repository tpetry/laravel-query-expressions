<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Aggregate\Sum;

it('can aggregate a column by SUM')
    ->expect(new Sum('val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('sum(`val`)')
    ->toBePgsql('sum("val")')
    ->toBeSqlite('sum("val")')
    ->toBeSqlsrv('sum([val])');

it('can aggregate an expression by SUM')
    ->expect(new Sum(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('sum(1)')
    ->toBePgsql('sum(1)')
    ->toBeSqlite('sum(1)')
    ->toBeSqlsrv('sum(1)');
