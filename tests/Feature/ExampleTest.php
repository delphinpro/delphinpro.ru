<?php
/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->get(route('home'))->assertSuccessful();
    }
}
