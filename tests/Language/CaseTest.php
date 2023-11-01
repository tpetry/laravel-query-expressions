<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Language\CaseBlock;
use Tpetry\QueryExpressions\Language\CaseCondition;
use Tpetry\QueryExpressions\Tests\ConditionExpression;

it('can create create a case-expression with a single branch')
    ->expect(
        new CaseBlock([
            new CaseCondition(new Expression(2), new ConditionExpression('1 = 1')),
        ])
    )
    ->toBeExecutable()
    ->toBeMysql('(case when 1 = 1 then 2 end)')
    ->toBePgsql('(case when 1 = 1 then 2 end)')
    ->toBeSqlite('(case when 1 = 1 then 2 end)')
    ->toBeSqlsrv('(case when 1 = 1 then 2 end)');

it('can create create a case-expression with multiple branches')
    ->expect(new CaseBlock([
        new CaseCondition(new Expression(2), new ConditionExpression('1 = 1')),
        new CaseCondition('val', new ConditionExpression('2 = 2')),
    ]))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(case when 1 = 1 then 2 when 2 = 2 then `val` end)')
    ->toBePgsql('(case when 1 = 1 then 2 when 2 = 2 then "val" end)')
    ->toBeSqlite('(case when 1 = 1 then 2 when 2 = 2 then "val" end)')
    ->toBeSqlsrv('(case when 1 = 1 then 2 when 2 = 2 then [val] end)');

it('can create create a case-expression with multiple branches and expression default')
    ->expect(new CaseBlock(
        [
            new CaseCondition(new Expression(2), new ConditionExpression('1 = 1')),
            new CaseCondition('val', new ConditionExpression('2 = 2')),
        ],
        new Expression('4'),
    ))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(case when 1 = 1 then 2 when 2 = 2 then `val` else 4 end)')
    ->toBePgsql('(case when 1 = 1 then 2 when 2 = 2 then "val" else 4 end)')
    ->toBeSqlite('(case when 1 = 1 then 2 when 2 = 2 then "val" else 4 end)')
    ->toBeSqlsrv('(case when 1 = 1 then 2 when 2 = 2 then [val] else 4 end)');

it('can create create a case-expression with multiple branches and column default')
    ->expect(new CaseBlock(
        [
            new CaseCondition(new Expression(2), new ConditionExpression('1 = 1')),
            new CaseCondition('val', new ConditionExpression('2 = 2')),
        ],
        'val',
    ))
    ->toBeExecutable(['val int'])
    ->toBeMysql('(case when 1 = 1 then 2 when 2 = 2 then `val` else `val` end)')
    ->toBePgsql('(case when 1 = 1 then 2 when 2 = 2 then "val" else "val" end)')
    ->toBeSqlite('(case when 1 = 1 then 2 when 2 = 2 then "val" else "val" end)')
    ->toBeSqlsrv('(case when 1 = 1 then 2 when 2 = 2 then [val] else [val] end)');
