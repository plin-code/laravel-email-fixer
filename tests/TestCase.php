<?php

namespace PlinCode\LaravelEmailFixer\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use PlinCode\LaravelEmailFixer\EmailFixerServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            EmailFixerServiceProvider::class,
        ];
    }
}
