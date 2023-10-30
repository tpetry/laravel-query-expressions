<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Tpetry\QueryExpressions\Function\Comparison\StrListContains;

it('can check for existence of a column within a column string list')
    ->expect(new StrListContains('haystack', 'needle'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('haystack');
        $table->string('needle');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql('FIND_IN_SET(`needle`, `haystack`) > 0')
    ->toBePgsql("(\"haystack\" like \"needle\" or \"haystack\" like ((\"needle\")||',%') or \"haystack\" like ('%,'||(\"needle\")||',%') or \"haystack\" like ('%,'||(\"needle\")))")
    ->toBeSqlite("(\"haystack\" like \"needle\" or \"haystack\" like ((\"needle\")||',%') or \"haystack\" like ('%,'||(\"needle\")||',%') or \"haystack\" like ('%,'||(\"needle\")))")
    ->toBeSqlsrv("([haystack] like [needle] or [haystack] like concat([needle],',%') or [haystack] like concat('%,',[needle],',%') or [haystack] like concat('%,',[needle]))");

it('can check for existence of an expression within an expression string list')
    ->expect(new StrListContains(new Expression("'a,b,c'"), new Expression("'a'")))
    ->toBeExecutable(options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("FIND_IN_SET('a', 'a,b,c') > 0")
    ->toBePgsql("('a,b,c' like 'a' or 'a,b,c' like (('a')||',%') or 'a,b,c' like ('%,'||('a')||',%') or 'a,b,c' like ('%,'||('a')))")
    ->toBeSqlite("('a,b,c' like 'a' or 'a,b,c' like (('a')||',%') or 'a,b,c' like ('%,'||('a')||',%') or 'a,b,c' like ('%,'||('a')))")
    ->toBeSqlsrv("('a,b,c' like 'a' or 'a,b,c' like concat('a',',%') or 'a,b,c' like concat('%,','a',',%') or 'a,b,c' like concat('%,','a'))");

it('can check for existence of a column within an expression string list')
    ->expect(new StrListContains(new Expression("'a,b,c'"), 'needle'))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('needle');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("FIND_IN_SET(`needle`, 'a,b,c') > 0")
    ->toBePgsql("('a,b,c' like \"needle\" or 'a,b,c' like ((\"needle\")||',%') or 'a,b,c' like ('%,'||(\"needle\")||',%') or 'a,b,c' like ('%,'||(\"needle\")))")
    ->toBeSqlite("('a,b,c' like \"needle\" or 'a,b,c' like ((\"needle\")||',%') or 'a,b,c' like ('%,'||(\"needle\")||',%') or 'a,b,c' like ('%,'||(\"needle\")))")
    ->toBeSqlsrv("('a,b,c' like [needle] or 'a,b,c' like concat([needle],',%') or 'a,b,c' like concat('%,',[needle],',%') or 'a,b,c' like concat('%,',[needle]))");

it('can check for existence of an expression within a column string list')
    ->expect(new StrListContains('haystack', new Expression("'a'")))
    ->toBeExecutable(function (Blueprint $table) {
        $table->string('haystack');
    }, options: [
        'sqlsrv' => ['position' => 'where'],
    ])
    ->toBeMysql("FIND_IN_SET('a', `haystack`) > 0")
    ->toBePgsql("(\"haystack\" like 'a' or \"haystack\" like (('a')||',%') or \"haystack\" like ('%,'||('a')||',%') or \"haystack\" like ('%,'||('a')))")
    ->toBeSqlite("(\"haystack\" like 'a' or \"haystack\" like (('a')||',%') or \"haystack\" like ('%,'||('a')||',%') or \"haystack\" like ('%,'||('a')))")
    ->toBeSqlsrv("([haystack] like 'a' or [haystack] like concat('a',',%') or [haystack] like concat('%,','a',',%') or [haystack] like concat('%,','a'))");
