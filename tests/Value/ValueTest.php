<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Value\Value;

it('can output an integer')
    ->expect(new Value(10))
    ->toBeExecutable()
    ->toBeMysql('10')
    ->toBePgsql('10')
    ->toBeSqlite('10')
    ->toBeSqlsrv('10');

it('can output a float')
    ->expect(new Value(3.141592))
    ->toBeExecutable()
    ->toBeMysql('3.141592')
    ->toBePgsql('3.141592')
    ->toBeSqlite('3.141592')
    ->toBeSqlsrv('3.141592');

it('can output a boolean (true)')
    ->expect(new Value(true))
    ->toBeExecutable()
    ->toBeMysql('1')
    ->toBePgsql('true')
    ->toBeSqlite('1')
    ->toBeSqlsrv('1');

it('can output a boolean (false)')
    ->expect(new Value(false))
    ->toBeExecutable()
    ->toBeMysql('0')
    ->toBePgsql('false')
    ->toBeSqlite('0')
    ->toBeSqlsrv('0');

it('can output a string')
    ->expect(new Value("Robert'); DROP TABLE students;--"))
    ->toBeExecutable()
    ->toBeMysql("'Robert\'); DROP TABLE students;--'")
    ->toBePgsql("'Robert''); DROP TABLE students;--'")
    ->toBeSqlite("'Robert''); DROP TABLE students;--'")
    ->toBeSqlsrv("'Robert''); DROP TABLE students;--'");

it('can output binary')
    ->expect(new Value(hex2bin('dead00beef'), true))
    ->toBeExecutable()
    ->toBeMysql("x'dead00beef'")
    ->toBePgsql("'\\xdead00beef'::bytea")
    ->toBeSqlite("x'dead00beef'")
    ->toBeSqlsrv('0xdead00beef');

it('can output null')
    ->expect(new Value(null))
    ->toBeExecutable()
    ->toBeMysql('null')
    ->toBePgsql('null')
    ->toBeSqlite('null')
    ->toBeSqlsrv('null');
