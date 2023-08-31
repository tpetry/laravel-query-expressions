<?php

declare(strict_types=1);

use Illuminate\Database\Query\Expression;
use Tpetry\QueryExpressions\Function\Time\ConvertTimezone;
use Tpetry\QueryExpressions\Function\Time\Now;

it('can convert timezone of datetime expression with timezone expressions')
    ->expect(new ConvertTimezone(new Expression('2023-01-01 00:00:00'), new Expression('UTC'), new Expression('Europe/Brussels')))
    ->toBeExecutable()
    ->toBeMysql('convert_tz(\'2023-01-01 00:00:00\',\'UTC\',\'Europe/Brussels\')')
    ->toBePgsql('((\'2023-01-01 00:00:00\' at time zone \'UTC\') at time zone \'Europe/Brussels\')')
    ->toBeSqlsrv('((\'2023-01-01 00:00:00\' at time zone \'UTC\') at time zone \'Europe/Brussels\')')
    ->toBeSqlite('datetime(\'2023-01-01 00:00:00\',\'UTC\',\'Europe/Brussels\')');

it('can convert timezone of datetime column with timezone columns')
    ->expect(new ConvertTimezone('val', 'start_timezone', 'target_timezone'))
    ->toBeExecutable(['val datetime', 'start_timezone varchar(255)', 'target_timezone varchar(255)'])
    ->toBeExecutable()
    ->toBeMysql('convert_tz(`val`,`start_timezone`,`target_timezone`)')
    ->toBePgsql('(("val" at time zone "start_timezone") at time zone "target_timezone")')
    ->toBePgsql('(([val] at time zone [start_timezone]) at time zone [target_timezone])')
    ->toBeSqlite('datetime("val","start_timezone","target_timezone")');
