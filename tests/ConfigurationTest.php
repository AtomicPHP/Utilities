<?php

namespace Nijens\Utilities\Tests;

use DOMDocument;
use Nijens\Utilities\Configuration;
use PHPUnit_Framework_Error_Warning;
use PHPUnit_Framework_TestCase;

/**
 * ConfigurationTest
 *
 * @author  Niels Nijens <niels@connectholland.nl>
 * @package Nijens\Utilities\Tests
 **/
class ConfigurationTest extends PHPUnit_Framework_TestCase
{
    /**
     * testLoadDefaultConfiguration
     *
     * Tests if the default configuration loads without errors
     *
     * @access public
     * @return void
     **/
    public function testLoadDefaultConfiguration()
    {
        $configuration = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd');
        $configuration->loadConfiguration(null);
    }

    /**
     * testLoadInvalidDefaultConfigurationDoesNotLoopLoadingAttempts
     *
     * Tests if loading an invalid default configuration does not create a loadConfiguration call loop
     *
     * @depends testLoadDefaultConfiguration
     *
     * @access public
     * @return void
     **/
    public function testLoadInvalidDefaultConfigurationDoesNotLoopLoadingAttempts()
    {
        $configurationMock = $this->getMockBuilder('Nijens\\Utilities\\Configuration')
            ->setConstructorArgs(array(__DIR__ . '/Resources/configuration/invalid.xml', __DIR__ . '/Resources/xsd/default.xsd'))
            ->setMethods(array('loadConfiguration'))
            ->getMock();

        $configurationMock->expects($this->never())->method('loadConfiguration');

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->load(__DIR__ . '/Resources/configuration/invalid.xml');

        @$configurationMock->setDOMDocument($dom);
    }

    /**
     * testLoadInvalidConfigurationTriggersErrors
     *
     * Tests if loading an invalid configuration triggers errors (warnings)
     *
     * @expectedException PHPUnit_Framework_Error_Warning
     *
     * @access public
     * @return void
     **/
    public function testLoadInvalidConfigurationTriggersErrors()
    {
        $configuration = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd');
        $configuration->loadConfiguration(__DIR__ . '/Resources/configuration/invalid.xml');
    }

    /**
     * testGetWithDefaultConfiguration
     *
     * Tests if Configuration::get returns the expected result from the default configuration
     *
     * @dataProvider provideTestGetWithDefaultConfiguration
     *
     * @access public
     * @param  string       $xpathExpression
     * @param  boolean      $alwaysReturnArray
     * @param  array|string $expectedResult
     * @return void
     **/
    public function testGetWithDefaultConfiguration($xpathExpression, $alwaysReturnArray, $expectedResult)
    {
        $configuration = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd');
        $configuration->loadConfiguration(null);

        $this->assertEquals($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray));
        $this->assertSame($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray));
    }

    /**
     * testGetWithDefaultConfigurationWithoutCache
     *
     * Tests if Configuration::get returns the expected result from the default configuration (without caching)
     *
     * @dataProvider provideTestGetWithDefaultConfiguration
     *
     * @access public
     * @param  string       $xpathExpression
     * @param  boolean      $alwaysReturnArray
     * @param  array|string $expectedResult
     * @return void
     **/
    public function testGetWithDefaultConfigurationWithoutCache($xpathExpression, $alwaysReturnArray, $expectedResult)
    {
        $configuration = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd', false);
        $configuration->loadConfiguration(null);

        $this->assertEquals($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray));
        $this->assertSame($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray));
    }

    /**
     * testGetDoesNotReturnCacheWhenOtherConfigurationIsLoaded
     *
     * Tests if Configuration::get does not return a cached XPath result from the previously loaded configuration
     *
     * @access public
     * @return void
     **/
    public function testGetDoesNotReturnCacheWhenOtherConfigurationIsLoaded()
    {
        $configuration = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd');
        $configuration->loadConfiguration(null);
        $this->assertEquals('Text content', $configuration->get('/test/fuzzy'));

        $configuration->loadConfiguration(__DIR__ . '/Resources/configuration/valid.xml');
        $this->assertEquals('Fuzzy text content', $configuration->get('/test/fuzzy'));
    }

    /**
     * testLoadInvalidConfigurationLoadsDefaultConfigurationAfterTriggeringErrors
     *
     * Tests if loading an invalid configuration loads the default configuration after triggering errors (warnings)
     *
     * @depends testLoadInvalidConfigurationTriggersErrors
     * @depends testGetWithDefaultConfiguration
     *
     * @access public
     * @return void
     **/
    public function testLoadInvalidConfigurationLoadsDefaultConfigurationAfterTriggeringErrors()
    {
        $configuration = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd');
        @$configuration->loadConfiguration(__DIR__ . '/Resources/configuration/invalid.xml');

        $this->assertEquals('Text content', $configuration->get('/test/fuzzy'));
    }

    /**
     * testLoadWithoutSchemaTriggersWarning
     *
     * Tests if Configation::loadConfiguration triggers a E_USER_WARNING when a XML schema is not supplied
     *
     * @expectedException        PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage A valid schema file must be provided.
     *
     * @access public
     * @return null
     **/
    public function testLoadWithoutSchemaTriggersWarning()
    {
        $configuration = new Configuration();
        $configuration->loadConfiguration(__DIR__ . '/Resources/configuration/default.xml');
    }

    /**
     * testGetWithoutSchemaDoesNotTriggerAFatalError
     *
     * Tests if Configuration::get does not trigger a fatal error when a XML schema is not supplied
     *
     * @access public
     * @return null
     **/
    public function testGetWithoutSchemaDoesNotTriggerAFatalError()
    {
        $configuration = new Configuration();
        @$configuration->loadConfiguration(__DIR__ . '/Resources/configuration/default.xml');

        $this->assertNull($configuration->get('/test/fuzzy'));
    }

    /**
     * testGetDOMDocumentReturnsLoadedDOMDocument
     *
     * Tests if Configuration::getDOMDocument returns the loaded DOMDocument instance
     *
     * @depends testGetWithDefaultConfiguration
     *
     * @access public
     * @return void
     **/
    public function testGetDOMDocumentReturnsLoadedDOMDocument()
    {
        $configuration = new Configuration(__DIR__ . '/Resources/configuration/default.xml', __DIR__ . '/Resources/xsd/default.xsd');
        $configuration->loadConfiguration(null);

        $this->assertInstanceOf('DOMDocument', $configuration->getDOMDocument());
    }

    /**
     * testToBoolean
     *
     * Tests if Configuration::toBoolean returns the expected result
     *
     * @dataProvider provideTestToBoolean
     *
     * @access public
     * @param  mixed   $value
     * @param  boolean $expectedResult
     * @return void
     **/
    public function testToBoolean($value, $expectedResult)
    {
        $this->assertSame($expectedResult, Configuration::toBoolean($value));
    }

    /**
     * provideTestGetWithDefaultConfiguration
     *
     * Returns an array with XPath expressions and the expected result
     *
     * @access public
     * @return array
     **/
    public function provideTestGetWithDefaultConfiguration()
    {
        return array(
            array('/test/foo', false, array(
                    'bar' => 'bar attribute',
                    'foo' => array(
                        'bar' => 'more bar attribute',
                    ),
                ),
            ),
            array('/test/bar', false, array(
                    'fuzz' => array(
                        array(
                            'id' => 'fuzzy',
                        ),
                        array(
                            'id' => 'very fuzzy',
                        ),
                    ),
                ),
            ),
            array('/test/bar/fuzz', false, array(
                    array(
                        'id' => 'fuzzy',
                    ),
                    array(
                        'id' => 'very fuzzy',
                    ),
                ),
            ),
            array('/test/fuzzy', false, 'Text content'),
            array('/test/fuzzy', true, array('Text content')),
            array('/test[]/non-existing', false, null),
            array('/test[]/non-existing', true, array()),
        );
    }

    /**
     * provideTestToBoolean
     *
     * Returns an array with values and the expected result
     *
     * @access public
     * @return array
     **/
    public function provideTestToBoolean()
    {
        return array(
            array(true, true),
            array('true', true),
            array(false, false),
            array('false', false),
            array('ja', false),
            array('nee', false),
            array(array(), false),
            array(new \stdClass(), false),
        );
    }
}
