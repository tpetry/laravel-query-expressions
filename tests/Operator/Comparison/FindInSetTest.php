<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Operator\Comparison\FindInSet;

it('can find a column within other column in comma-separated by find in set check')
    ->expect(new FindInSet('val1', 'val2'))
    ->toBeExecutable(['val1 int', 'val2 varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(FIND_IN_SET(`val1`, `val2`))')
    ->toBePgsql('("val1"::varchar = ANY(STRING_TO_ARRAY("val2", \',\')))')
    ->toBeSqlite('(\',\'||"val2"||\',\' like \'%,\'||"val1"||\',%\')')
    ->toBeSqlsrv('([val1] IN (SELECT [value] FROM STRING_SPLIT([val2], \',\')))');

it('can find an expression within another expression in comma-separated by find in set check')
    ->expect(new FindInSet(new Expression(1), new Expression("'1,2'")))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(1, '1,2'))")
    ->toBePgsql("(1::varchar = ANY(STRING_TO_ARRAY('1,2', ',')))")
    ->toBeSqlite("(','||'1,2'||',' like '%,'||1||',%')")
    ->toBeSqlsrv("(1 IN (SELECT [value] FROM STRING_SPLIT('1,2', ',')))");

it('can find a column within an expression in comma-separated by find in set check')
    ->expect(new FindInSet('val', new Expression("'1,2'")))
    ->toBeExecutable(['val int'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("(FIND_IN_SET(`val`, '1,2'))")
    ->toBePgsql("(\"val\"::varchar = ANY(STRING_TO_ARRAY('1,2', ',')))")
    ->toBeSqlite("(','||'1,2'||',' like '%,'||\"val\"||',%')")
    ->toBeSqlsrv("([val] IN (SELECT [value] FROM STRING_SPLIT('1,2', ',')))");

it('can find an expression within a column in comma-separated by find in set check')
    ->expect(new FindInSet(new Expression(1), 'val'))
    ->toBeExecutable(['val varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('(FIND_IN_SET(1, `val`))')
    ->toBePgsql("(1::varchar = ANY(STRING_TO_ARRAY(\"val\", ',')))")
    ->toBeSqlite("(','||\"val\"||',' like '%,'||1||',%')")
    ->toBeSqlsrv("(1 IN (SELECT [value] FROM STRING_SPLIT([val], ',')))");
