<?php

declare(strict_types=1);

use Tpetry\QueryExpressions\Function\Date\Month;

it('can format the date of a column to a month')
    ->expect(new Month('created_at'))
    ->toBeExecutable()
    ->toBePgsql('to_char("created_at", \'YYYY-MM\')')
    ->toBeSqlite('strftime(\'%Y-%m\', "created_at")')
    ->toBeMysql('date_format(`created_at`, \'%Y-%m\')')
    ->toBeSqlsrv('format([created_at], \'yyyy-MM\')');
