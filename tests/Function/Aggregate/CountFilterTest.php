<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Aggregate\CountFilter;

it('can aggregate a column by COUNT with a filter')
    ->expect(new CountFilter(new Expression('val = 1')))
    ->toBeExecutable(['val int'])
    ->toBeMysql('sum(val = 1)')
    ->toBePgsql('count(*) filter (where val = 1)')
    ->toBeSqlite('count(*) filter (where val = 1)')
    ->toBeSqlsrv('sum(case when val = 1 then 1 else 0 end)');
