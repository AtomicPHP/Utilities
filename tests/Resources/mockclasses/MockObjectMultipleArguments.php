<?php

namespace Nijens\Utilities\Tests;

/**
 * MockObjectMultipleArguments
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Tests
 **/
class MockObjectMultipleArguments
{
    public $argument;

    public $secondArgument;

    public $thirdArgument;

    public function __construct($argument, $secondArgument, $thirdArgument = null)
    {
        $this->argument = $argument;
        $this->secondArgument = $secondArgument;
        $this->thirdArgument = $thirdArgument;
    }
}
