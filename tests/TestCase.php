<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $application = require dirname(__DIR__).'/bootstrap/app.php';
        $application->make(Kernel::class)->bootstrap();

        return $application;
    }
}
