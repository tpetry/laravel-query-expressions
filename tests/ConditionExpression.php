<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Tests;

use Illuminate\Contracts\Database\Query\ConditionExpression as ConditionExpressionContract;
use Illuminate\Database\Query\Expression;

class ConditionExpression extends Expression implements ConditionExpressionContract {}
