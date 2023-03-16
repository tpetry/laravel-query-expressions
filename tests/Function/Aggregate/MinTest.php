<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Aggregate\Min;

it('can aggregate a column by MIN')
    ->expect(new Min('val'))
    ->toBeExecutable(['val int'])
    ->toBeMysql('min(`val`)')
    ->toBePgsql('min("val")')
    ->toBeSqlite('min("val")')
    ->toBeSqlsrv('min([val])');

it('can aggregate an expression by MIN')
    ->expect(new Min(new Expression(1)))
    ->toBeExecutable()
    ->toBeMysql('min(1)')
    ->toBePgsql('min(1)')
    ->toBeSqlite('min(1)')
    ->toBeSqlsrv('min(1)');
