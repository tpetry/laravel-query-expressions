<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Conditional\Least;

it('can combine multiple columns')
    ->expect(new Least(['val1', 'val2', 'val3']))
    ->toBeExecutable(['val1 int', 'val2 int', 'val3 int'])
    ->toBeMysql('least(`val1`, `val2`, `val3`)')
    ->toBePgsql('least("val1", "val2", "val3")')
    ->toBeSqlite('min("val1", "val2", "val3")')
    ->toBeSqlsrv('(select min(n) from (values ([val1]), ([val2]), ([val3])) as v(n))');

it('can combine multiple expressions')
    ->expect(new Least([new Expression(1), new Expression(2), new Expression(3)]))
    ->toBeExecutable()
    ->toBeMysql('least(1, 2, 3)')
    ->toBePgsql('least(1, 2, 3)')
    ->toBeSqlite('min(1, 2, 3)')
    ->toBeSqlsrv('(select min(n) from (values (1), (2), (3)) as v(n))');

it('can combine multiple columns and expressions')
    ->expect(new Least(['val1', 'val2', new Expression(3)]))
    ->toBeExecutable(['val1 int', 'val2 int'])
    ->toBeMysql('least(`val1`, `val2`, 3)')
    ->toBePgsql('least("val1", "val2", 3)')
    ->toBeSqlite('min("val1", "val2", 3)')
    ->toBeSqlsrv('(select min(n) from (values ([val1]), ([val2]), (3)) as v(n))');
