<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Function\String\Uuid4;

it('can generate UUIDv4 values')
    ->skipOnMariaBefore('10.10')
    ->skipOnPgsqlBefore('13')
    ->expect(new Uuid4())
    ->toBeExecutable()
    ->toBeMysql("(lower(concat(hex(random_bytes(4)),'-',hex(random_bytes(2)),'-4',substr(hex(random_bytes(2)), -3),'-',hex((ascii(random_bytes(1))>>6)+8),substr(hex(random_bytes(2)),-3),'-',hex(random_bytes(6)))))")
    ->toBePgsql('gen_random_uuid()')
    ->toBeSqlite("(lower(hex(randomblob(4))||'-'||hex(randomblob(2))||'-4'||substr(hex(randomblob(2)), -3)||'-8'||substr(hex(randomblob(2)),-3)||'-'||hex(randomblob(6))))")
    ->toBeSqlsrv('lower(newid())');
