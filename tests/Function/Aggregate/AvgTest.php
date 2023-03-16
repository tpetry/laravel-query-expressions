<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Aggregate\Avg;

it('can aggregate a column by AVG')
    ->expect(new Avg('val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('avg(`val`)')
    ->toBePgsql('avg("val")')
    ->toBeSqlite('avg("val")')
    ->toBeSqlsrv('avg([val])');

it('can aggregate an expression by AVG')
    ->expect(new Avg(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('avg(1)')
    ->toBePgsql('avg(1)')
    ->toBeSqlite('avg(1)')
    ->toBeSqlsrv('avg(1)');
