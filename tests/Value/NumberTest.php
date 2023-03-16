<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Value\Number;

it('can store an integer')
    ->expect(new Number(10))
    ->toBeExecutable()
    ->toBeMysql('10')
    ->toBePgsql('10')
    ->toBeSqlite('10')
    ->toBeSqlsrv('10');

it('can store a float')
    ->expect(new Number(3.141592))
    ->toBeExecutable()
    ->toBeMysql('3.141592')
    ->toBePgsql('3.141592')
    ->toBeSqlite('3.141592')
    ->toBeSqlsrv('3.141592');
