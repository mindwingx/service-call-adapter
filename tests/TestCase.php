<?php

namespace Mindwingx\ServiceCallAdapter\Tests;

use Mindwingx\ServiceCallAdapter\ServiceCallServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ServiceCallServiceProvider::class,
        ];
    }
}
