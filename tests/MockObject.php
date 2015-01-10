<?php

namespace Nijens\Utilities\Tests;

/**
 * MockObject
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Tests
 **/
class MockObject
{
    public $argument;

    public function __construct($argument = null)
    {
        $this->argument = $argument;
    }
}
