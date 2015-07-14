<?php

namespace Nijens\Utilities\Tests;

use PHPUnit_Framework_TestCase;

/**
 * AbstractSingletonTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Tests
 **/
class AbstractSingletonTest extends PHPUnit_Framework_TestCase
{
    /**
     * testGetInstanceReturnsInstanceOfSubclass
     *
     * Tests if AbstractSingleton::getInstance returns an instance of the subclass extending from AbstractSingleton
     *
     * @access public
     * @return void
     **/
    public function testGetInstanceReturnsInstanceOfSubclass()
    {
        $this->assertInstanceOf('Nijens\\Utilities\\Tests\\MockSingleton', MockSingleton::getInstance());
    }

    /**
     * testGetInstanceReturnsInstanceOfClass
     *
     * Tests if AbstractSingleton::getInstance always returns the same instance of the subclass extending from AbstractSingleton
     *
     * @depends testGetInstanceReturnsInstanceOfSubclass
     *
     * @access public
     * @return void
     **/
    public function testGetInstanceReturnsAlwaysTheSameInstanceOfSubclass()
    {
        $instance = MockSingleton::getInstance();

        $this->assertSame($instance, MockSingleton::getInstance());
    }

    /**
     * testGetInstanceCallsConstructor
     *
     * Tests if AbstractSingleton::getInstance calls the constructor of the subclass with arguments
     *
     * @access public
     * @return void
     **/
    public function testGetInstanceCallsConstructor()
    {
        $instance = MockSingletonConstructor::getInstance('test');

        $this->assertInstanceOf('Nijens\\Utilities\\Tests\\MockSingletonConstructor', $instance);
        $this->assertSame('test', $instance->argument);
    }

    /**
     * testGetInstanceTriggersNoticeOnPublicConstructor
     *
     * Tests if AbstractSingleton::getInstance triggers a E_USER_NOTICE when the subclass has a public constructor
     *
     * @expectedException        PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage Nijens\Utilities\Tests\MockSingletonPublicConstructor::__construct is public and could be instantiated as non-singleton.
     *
     * @access public
     * @return void
     **/
    public function testGetInstanceTriggersNoticeOnPublicConstructor()
    {
        MockSingletonPublicConstructor::getInstance();
    }
}
