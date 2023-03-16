<?php

declare(strict_types=1);

namespace Tpetry\QueryExpressions\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // The RefreshDatabase trait did not work for some reason. As I don't have to run any migrations, the simplified
        // approach of dropping tables is even better. Less work to do when setting up the test environments results in
        // better performance.
        $this->getConnection()->getSchemaBuilder()->dropAllTables();
    }
}
