<?php

namespace Nijens\Utilities\Tests;

use PHPUnit_Framework_TestCase;
use Nijens\Utilities\ObjectFactory;

/**
 * ObjectFactoryTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Tests
 **/
class ObjectFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * testGetInstance
     *
     * Tests if ObjectFactory::getInstance returns an ObjectFactory instance
     *
     * @access public
     * @return void
     **/
    public function testGetInstance()
    {
        $this->assertInstanceOf("Nijens\\Utilities\\ObjectFactory", ObjectFactory::getInstance() );
    }

    /**
     * testNewInstance
     *
     * Tests if ObjectFactory::newInstance creates a new instance
     *
     * @access public
     * @return void
     **/
    public function testNewInstance()
    {
        $objectFactory = ObjectFactory::getInstance();

        $this->assertInstanceOf("Nijens\\Utilities\\Tests\\MockObject", $objectFactory->newInstance("Nijens\\Utilities\\Tests\\MockObject") );
    }

    /**
     * testNewInstanceWithArgumentArray
     *
     * Tests if ObjectFactory::newInstance creates a new instance with argument as constructor argument
     *
     * @depends testNewInstance
     *
     * @access public
     * @return void
     **/
    public function testNewInstanceWithArgumentArray()
    {
        $argumentValue = "test";

        $objectFactory = ObjectFactory::getInstance();
        $instance = $objectFactory->newInstance("Nijens\\Utilities\\Tests\\MockObject", array("argument" => $argumentValue) );

        $this->assertSame($argumentValue, $instance->argument);
    }

    /**
     * testNewInstanceWithNonExistingClassNameReturnsNull
     *
     * Tests if ObjectFactory::newInstance for a non-existing class name returns null
     *
     * @depends testNewInstance
     *
     * @access public
     * @return void
     **/
    public function testNewInstanceWithNonExistingClassNameReturnsNull()
    {
        $objectFactory = ObjectFactory::getInstance();

        $this->assertNull($objectFactory->newInstance("Nijens\\Utilities\\Tests\\NonExistingClass") );
    }
}