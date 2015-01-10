<?php

namespace Nijens\Utilities\Tests;

use Nijens\Utilities\AbstractSingleton;

/**
 * MockSingleton
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Tests
 **/
class MockSingletonConstructor extends AbstractSingleton
{
    public $argument;

    protected function __construct($argument)
    {
        $this->argument = $argument;
    }
}
