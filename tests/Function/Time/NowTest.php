<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Function\Time\Now;

it('can generate the current time')
    ->expect(new Now())
    ->toBeExecutable()
    ->toBeMysql('(current_timestamp)')
    ->toBePgsql('statement_timestamp()')
    ->toBeSqlite('(current_timestamp)')
    ->toBeSqlsrv('current_timestamp');
