<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Function\String;

use Illuminate\Database\Grammar;
use Tpetry\QueryExpressions\Concerns\IdentifiesDriver;
use Tpetry\QueryExpressions\Function\Conditional\ManyArgumentsExpression;

class Concat extends ManyArgumentsExpression
{
    use IdentifiesDriver;

    public function getValue(Grammar $grammar): string
    {
        $expressions = $this->getExpressions($grammar);

        return match ($this->identify($grammar)) {
            'mariadb', 'mysql', 'sqlsrv' => sprintf('(concat(%s))', implode(',', $expressions)),
            'pgsql', 'sqlite' => sprintf('(%s)', implode('||', $expressions)),
        };
    }
}
