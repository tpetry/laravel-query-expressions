<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Language\Alias;
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
    ->toBeMysql('(case when (1 = 2) then 5 end)')
    ->toBePgsql('(case when (1 = 2) then 5 end)')
    ->toBeSqlite('(case when (1 = 2) then 5 end)')
    ->toBeSqlsrv('(case when (1 = 2) then 5 end)');

it('uses a caseBlock with multiple condition blocks')
    ->expect(
        new CaseBlock(when: [
            new CaseCondition(result: new Number(5), condition: new Equal(new Number(1), new Number(2))),
            new CaseCondition(result: new Number(6), condition: new Equal(new Number(2), new Number(2)))
        ],
            else: new Number(2)
        )
    )
    ->toBeExecutable()
    ->toBeMysql('(case when (1 = 2) then 5 when (2 = 2) then 5 end)')
    ->toBePgsql('(case when (1 = 2) then 5 when (2 = 2) then 5 end)')
    ->toBeSqlite('(case when (1 = 2) then 5 when (2 = 2) then 5 end)')
    ->toBeSqlsrv('(case when (1 = 2) then 5 when (2 = 2) then 5 end)');
