<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\String\Concat;

it('can concat multiple columns')
    ->expect(new Concat(['val1', 'val2', 'val3']))
    ->toBeExecutable(['val1 varchar(255)', 'val2 varchar(255)', 'val3 varchar(255)'])
    ->toBeMysql('(concat(`val1`,`val2`,`val3`))')
    ->toBePgsql('("val1"||"val2"||"val3")')
    ->toBeSqlite('("val1"||"val2"||"val3")')
    ->toBeSqlsrv('(concat([val1],[val2],[val3]))');

it('can concat multiple expressions')
    ->expect(new Concat([new Expression("'a'"), new Expression("'b'"), new Expression("'c'")]))
    ->toBeExecutable()
    ->toBeMysql("(concat('a','b','c'))")
    ->toBePgsql("('a'||'b'||'c')")
    ->toBeSqlite("('a'||'b'||'c')")
    ->toBeSqlsrv("(concat('a','b','c'))");

it('can concat multiple columns and expressions')
    ->expect(new Concat(['val1', 'val2', new Expression("'c'")]))
    ->toBeExecutable(['val1 varchar(255)', 'val2 varchar(255)'])
    ->toBeMysql("(concat(`val1`,`val2`,'c'))")
    ->toBePgsql('("val1"||"val2"||\'c\')')
    ->toBeSqlite('("val1"||"val2"||\'c\')')
    ->toBeSqlsrv("(concat([val1],[val2],'c'))");
