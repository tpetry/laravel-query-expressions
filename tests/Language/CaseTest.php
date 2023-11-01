<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Language\CaseBlock;
use Tpetry\QueryExpressions\Language\CaseCondition;
use Tpetry\QueryExpressions\Operator\Comparison\Equal;
use Tpetry\QueryExpressions\Value\Number;

it('uses a caseBlock with one condition blocks')
    ->expect(
        new CaseBlock(when: [
            new CaseCondition(result: new Number(5), condition: new Equal(new Number(1), new Number(2))),
        ],
            else: new Number(2)
        )
    )
    ->toBeExecutable()
    ->toBeMysql('(case when (1 = 2) then 5 else 2 end)')
    ->toBePgsql('(case when (1 = 2) then 5 else 2 end)')
    ->toBeSqlite('(case when (1 = 2) then 5 else 2 end)')
    ->toBeSqlsrv('(case when (1 = 2) then 5 else 2 end)');

it('uses a caseBlock with multiple condition blocks')
    ->expect(
        new CaseBlock(when: [
            new CaseCondition(result: new Number(5), condition: new Equal(new Number(1), new Number(2))),
            new CaseCondition(result: new Number(6), condition: new Equal(new Number(2), new Number(2))),
        ],
            else: new Number(2)
        )
    )
    ->toBeExecutable()
    ->toBeMysql('(case when (1 = 2) then 5 when (2 = 2) then 6 else 2 end)')
    ->toBePgsql('(case when (1 = 2) then 5 when (2 = 2) then 6 else 2 end)')
    ->toBeSqlite('(case when (1 = 2) then 5 when (2 = 2) then 6 else 2 end)')
    ->toBeSqlsrv('(case when (1 = 2) then 5 when (2 = 2) then 6 else 2 end)');

it('uses a caseBlock with multiple condition and value')
    ->expect(
        new CaseBlock(when: [
            new CaseCondition(result: new Number(5), condition: new Equal(new Number(1), new Number(2))),
            new CaseCondition(result: new Number(6), condition: new Equal(new Number(2), new Number(2))),
        ],
            else: 'val'
        )
    )
    ->toBeExecutable(['val int'])
    ->toBeMysql('(case when (1 = 2) then 5 when (2 = 2) then 6 else `val` end)')
    ->toBePgsql('(case when (1 = 2) then 5 when (2 = 2) then 6 else "val" end)')
    ->toBeSqlite('(case when (1 = 2) then 5 when (2 = 2) then 6 else "val" end)')
    ->toBeSqlsrv('(case when (1 = 2) then 5 when (2 = 2) then 6 else [val] end)');

it('uses a caseBlock with multiple condition and value aliased')
    ->expect(
        new \Tpetry\QueryExpressions\Language\Alias(new CaseBlock(when: [
            new CaseCondition(result: new Number(5), condition: new Equal(new Number(1), new Number(2))),
            new CaseCondition(result: new Number(6), condition: new Equal(new Number(2), new Number(2))),
        ],
            else: 'val'
        ), 'name')
    )
    ->toBeExecutable(['val int'])
    ->toBeMysql('(case when (1 = 2) then 5 when (2 = 2) then 6 else `val` end) as `name`')
    ->toBePgsql('(case when (1 = 2) then 5 when (2 = 2) then 6 else "val" end) as "name"')
    ->toBeSqlite('(case when (1 = 2) then 5 when (2 = 2) then 6 else "val" end) as "name"')
    ->toBeSqlsrv('(case when (1 = 2) then 5 when (2 = 2) then 6 else [val] end) as [name]');
