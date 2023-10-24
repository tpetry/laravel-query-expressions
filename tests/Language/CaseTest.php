<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Language\Alias;
use Tpetry\QueryExpressions\Language\CaseBlock;
use Tpetry\QueryExpressions\Language\CaseCondition;
use Tpetry\QueryExpressions\Operator\Comparison\Equal;
use Tpetry\QueryExpressions\Value\Number;

it('use case blocks')
    ->expect(
        new CaseBlock(when: [
            new CaseCondition(result: new Number(5), condition: new Equal(new Number(1), new Number(2)))
        ],
        else: new Number(2)
        )
    )
    ->toBeExecutable()
    ->toBeMysql('(case when 1 = 2 then 5 end)')
    ->toBePgsql('(case when 1 = 2 then 5 end)')
    ->toBeSqlite('(case when 1 = 2 then 5 end)')
    ->toBeSqlsrv('(case when 1 = 2 then 5 end)');
