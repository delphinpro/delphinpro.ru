<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

namespace Tests;

use Illuminate\Foundation\Mix;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }
}
