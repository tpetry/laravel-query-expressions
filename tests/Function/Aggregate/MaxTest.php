<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Aggregate\Max;

it('can aggregate a column by MAX')
    ->expect(new Max('val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('max(`val`)')
    ->toBePgsql('max("val")')
    ->toBeSqlite('max("val")')
    ->toBeSqlsrv('max([val])');

it('can aggregate an expression by MAX')
    ->expect(new Max(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('max(1)')
    ->toBePgsql('max(1)')
    ->toBeSqlite('max(1)')
    ->toBeSqlsrv('max(1)');
