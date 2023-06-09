<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Comparison\StrListContains;

it('can check for existence of a column within a column string list')
    ->expect(new StrListContains('haystack', 'needle'))
    ->toBeExecutable(['haystack varchar(255)', 'needle varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('FIND_IN_SET(`needle`, `haystack`) > 0')
    ->toBePgsql("(\"haystack\" like \"needle\" or \"haystack\" like concat(\"needle\",',%') or \"haystack\" like concat('%,',\"needle\",',%') or \"haystack\" like concat('%,',\"needle\"))")
    ->toBePgsql("(\"haystack\" like \"needle\" or \"haystack\" like concat(\"needle\",',%') or \"haystack\" like concat('%,',\"needle\",',%') or \"haystack\" like concat('%,',\"needle\"))")
    ->toBeSqlsrv("([haystack] like [needle] or [haystack] like concat([needle],',%') or [haystack] like concat('%,',[needle],',%') or [haystack] like concat('%,',[needle]))");

it('can check for existence of an expression within an expression string list')
    ->expect(new StrListContains(new Expression("'a,b,c'"), new Expression("'a'")))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("FIND_IN_SET('a', 'a,b,c') > 0")
    ->toBePgsql("('a,b,c' like 'a' or 'a,b,c' like concat('a',',%') or 'a,b,c' like concat('%,','a',',%') or 'a,b,c' like concat('%,','a'))")
    ->toBePgsql("('a,b,c' like 'a' or 'a,b,c' like concat('a',',%') or 'a,b,c' like concat('%,','a',',%') or 'a,b,c' like concat('%,','a'))")
    ->toBePgsql("('a,b,c' like 'a' or 'a,b,c' like concat('a',',%') or 'a,b,c' like concat('%,','a',',%') or 'a,b,c' like concat('%,','a'))");

it('can check for existence of a column within an expression string list')
    ->expect(new StrListContains(new Expression("'a,b,c'"), 'needle'))
    ->toBeExecutable(['needle varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("FIND_IN_SET(`needle`, 'a,b,c') > 0")
    ->toBePgsql("('a,b,c' like \"needle\" or 'a,b,c' like concat(\"needle\",',%') or 'a,b,c' like concat('%,',\"needle\",',%') or 'a,b,c' like concat('%,',\"needle\"))")
    ->toBeSqlite("('a,b,c' like \"needle\" or 'a,b,c' like concat(\"needle\",',%') or 'a,b,c' like concat('%,',\"needle\",',%') or 'a,b,c' like concat('%,',\"needle\"))")
    ->toBeSqlsrv("('a,b,c' like [needle] or 'a,b,c' like concat([needle],',%') or 'a,b,c' like concat('%,',[needle],',%') or 'a,b,c' like concat('%,',[needle]))");

it('can check for existence of an expression within a column string list')
    ->expect(new StrListContains('haystack', new Expression("'a'")))
    ->toBeExecutable(['haystack varchar(255)'], options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("FIND_IN_SET('a', `haystack`) > 0")
    ->toBePgsql("(\"haystack\" like 'a' or \"haystack\" like concat('a',',%') or \"haystack\" like concat('%,','a',',%') or \"haystack\" like concat('%,','a'))")
    ->toBeSqlite("(\"haystack\" like 'a' or \"haystack\" like concat('a',',%') or \"haystack\" like concat('%,','a',',%') or \"haystack\" like concat('%,','a'))")
    ->toBeSqlsrv("([haystack] like 'a' or [haystack] like concat('a',',%') or [haystack] like concat('%,','a',',%') or [haystack] like concat('%,','a'))");
