<?php

namespace Nijens\Utilities\Tests;

use Nijens\Utilities\UnregisterableCallback;

/**
 * UnregisterableCallbackTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Tests
 **/
class UnregisterableCallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testWithValidCallback
     *
     * Tests if the callable function is called by testing for the return value
     *
     * @access public
     * @return void
     **/
    public function testWithValidCallback()
    {
        $unregisterableCallback = new UnregisterableCallback(function() {
            return true;
        });

        $this->assertTrue($unregisterableCallback->call() );
    }

    /**
     * testWithValidCallbackNotCalledAfterUnregister
     *
     * Tests if the callable function is not called after unregistering
     *
     * @access public
     * @return void
     **/
    public function testWithValidCallbackNotCalledAfterUnregister()
    {
        $unregisterableCallback = new UnregisterableCallback(function() {
            return true;
        });
        $unregisterableCallback->unregister();

        $this->assertNull($unregisterableCallback->call() );
    }

    /**
     * testWithInvalidCallback
     *
     * Tests if UnregisterableCallback throws an InvalidArgumentException when the first argument is not callable
     *
     * @expectedException InvalidArgumentException
     * @access public
     * @return void
     **/
    public function testWithInvalidCallback()
    {
        new UnregisterableCallback(null);
    }
}
