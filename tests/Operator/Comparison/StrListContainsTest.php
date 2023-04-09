<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Comparison\StrListContains;

it('can find a column within other column in comma-separated with case for case-insensitive by str list contains check')
    ->expect(new StrListContains('val1', 'val2'))
    ->toBeExecutable(['val1 int', 'val2 varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(FIND_IN_SET(`val1`, `val2`))')
    ->toBePgsql('(lower("val1"::varchar) = ANY(STRING_TO_ARRAY(lower("val2"), \',\')))')
    ->toBeSqlite('(\',\'||"val2"||\',\' LIKE \'%,\'||"val1"||\',%\')')
    ->toBeSqlsrv('([val1] IN (SELECT [value] FROM STRING_SPLIT([val2], \',\')))');

it('can find a column within other column in comma-separated with case for case-sensitive by str list contains check')
    ->expect(new StrListContains('val1', 'val2', true))
    ->toBeExecutable(['val1 int', 'val2 varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(FIND_IN_SET(binary `val1`, `val2`))')
    ->toBePgsql('("val1"::varchar = ANY(STRING_TO_ARRAY("val2", \',\')))')
    ->toBeSqlite('(\',\'||"val2"||\',\' GLOB \'*,\'||"val1"||\',*\')')
    ->toBeSqlsrv('([val1] COLLATE SQL_LATIN1_GENERAL_CP1_CS_AS IN (SELECT [value] FROM STRING_SPLIT([val2], \',\')))');

it('can find an expression within another expression in comma-separated for case-insensitive by str list contains check')
    ->expect(new StrListContains(new Expression('"foo"'), new Expression('"foo,bar"')))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(\"foo\", \"foo,bar\"))")
    ->toBePgsql("(lower(\"foo\"::varchar) = ANY(STRING_TO_ARRAY(lower(\"foo,bar\"), ',')))")
    ->toBeSqlite("(','||\"foo,bar\"||',' LIKE '%,'||\"foo\"||',%')")
    ->toBeSqlsrv("(\"foo\" IN (SELECT [value] FROM STRING_SPLIT(\"foo,bar\", ',')))");

it('can find an expression within another expression in comma-separated for case-sensitive by str list contains check')
    ->expect(new StrListContains(new Expression('"foo"'), new Expression('"foo,bar"'), true))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(binary \"foo\", \"foo,bar\"))")
    ->toBePgsql("(\"foo\"::varchar = ANY(STRING_TO_ARRAY(\"foo,bar\", ',')))")
    ->toBeSqlite("(','||\"foo,bar\"||',' GLOB '*,'||\"foo\"||',*')")
    ->toBeSqlsrv("(\"foo\" COLLATE SQL_LATIN1_GENERAL_CP1_CS_AS IN (SELECT [value] FROM STRING_SPLIT(\"foo,bar\", ',')))");

it('can find a column within an expression in comma-separated for case-insensitive by str list contains check')
    ->expect(new StrListContains('val', new Expression('"foo,bar"')))
    ->toBeExecutable(['val int'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(`val`, \"foo,bar\"))")
    ->toBePgsql("(lower(\"val\"::varchar) = ANY(STRING_TO_ARRAY(lower(\"foo,bar\"), ',')))")
    ->toBeSqlite("(','||\"foo,bar\"||',' LIKE '%,'||\"val\"||',%')")
    ->toBeSqlsrv("([val] IN (SELECT [value] FROM STRING_SPLIT(\"foo,bar\", ',')))");

it('can find a column within an expression in comma-separated for case-sensitive by str list contains check')
    ->expect(new StrListContains('val', new Expression('"foo,bar"'), true))
    ->toBeExecutable(['val int'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(binary `val`, \"foo,bar\"))")
    ->toBePgsql("(\"val\"::varchar = ANY(STRING_TO_ARRAY(\"foo,bar\", ',')))")
    ->toBeSqlite("(','||\"foo,bar\"||',' GLOB '*,'||\"val\"||',*')")
    ->toBeSqlsrv("([val] COLLATE SQL_LATIN1_GENERAL_CP1_CS_AS IN (SELECT [value] FROM STRING_SPLIT(\"foo,bar\", ',')))");

it('can find an expression within a column in comma-separated for case-insensitive by str list contains check')
    ->expect(new StrListContains(new Expression('"foo"'), 'val'))
    ->toBeExecutable(['val varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(\"foo\", `val`))")
    ->toBePgsql("(lower(\"foo\"::varchar) = ANY(STRING_TO_ARRAY(lower(\"val\"), ',')))")
    ->toBeSqlite("(','||\"val\"||',' LIKE '%,'||\"foo\"||',%')")
    ->toBeSqlsrv("(\"foo\" IN (SELECT [value] FROM STRING_SPLIT([val], ',')))");

it('can find an expression within a column in comma-separated for case-sensitive by str list contains check')
    ->expect(new StrListContains(new Expression('"foo"'), 'val', true))
    ->toBeExecutable(['val varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(binary \"foo\", `val`))")
    ->toBePgsql("(\"foo\"::varchar = ANY(STRING_TO_ARRAY(\"val\", ',')))")
    ->toBeSqlite("(','||\"val\"||',' GLOB '*,'||\"foo\"||',*')")
    ->toBeSqlsrv("(\"foo\" COLLATE SQL_LATIN1_GENERAL_CP1_CS_AS IN (SELECT [value] FROM STRING_SPLIT([val], ',')))");
