<?php

namespace Nijens\Utilities\Tests;

use PHPUnit_Framework_TestCase;
use Nijens\Utilities\Configuration;

/**
 * ConfigurationTest
 *
 * @author  Niels Nijens <niels@connectholland.nl>
 * @package Nijens\Utilities\Tests
 **/
class ConfigurationTest extends PHPUnit_Framework_TestCase {

    /**
     * testLoadDefaultConfiguration
     *
     * Tests if the default configuration loads without errors
     *
     * @access public
     * @return void
     **/
    public function testLoadDefaultConfiguration() {
        $configuration = new Configuration(__DIR__ . "/Resources/configuration/default.xml", __DIR__ . "/Resources/xsd/default.xsd");
        $configuration->loadConfiguration(null);
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
    public function testLoadInvalidConfigurationTriggersErrors() {
        $configuration = new Configuration(__DIR__ . "/Resources/configuration/default.xml", __DIR__ . "/Resources/xsd/default.xsd");
        $configuration->loadConfiguration(__DIR__ . "/Resources/configuration/invalid.xml");
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
    public function testGetWithDefaultConfiguration($xpathExpression, $alwaysReturnArray, $expectedResult) {
        $configuration = new Configuration(__DIR__ . "/Resources/configuration/default.xml", __DIR__ . "/Resources/xsd/default.xsd");
        $configuration->loadConfiguration(null);

        $this->assertEquals($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray) );
        $this->assertSame($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray) );
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
    public function testLoadInvalidConfigurationLoadsDefaultConfigurationAfterTriggeringErrors() {
        $configuration = new Configuration(__DIR__ . "/Resources/configuration/default.xml", __DIR__ . "/Resources/xsd/default.xsd");
        @$configuration->loadConfiguration(__DIR__ . "/Resources/configuration/invalid.xml");

        $this->assertEquals("Text content", $configuration->get("/test/fuzzy") );
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
    public function testGetDOMDocumentReturnsLoadedDOMDocument() {
        $configuration = new Configuration(__DIR__ . "/Resources/configuration/default.xml", __DIR__ . "/Resources/xsd/default.xsd");
        $configuration->loadConfiguration(null);

        $this->assertInstanceOf("DOMDocument", $configuration->getDOMDocument() );
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
    public function testToBoolean($value, $expectedResult) {
        $this->assertSame($expectedResult, Configuration::toBoolean($value) );
    }

    /**
     * provideTestGetWithDefaultConfiguration
     *
     * Returns an array with XPath expressions and the expected result
     *
     * @access public
     * @return array
     **/
    public function provideTestGetWithDefaultConfiguration() {
        return array(
            array("/test/foo", false, array(
                    "bar" => "bar attribute",
                    "foo" => array(
                        "bar" => "more bar attribute"
                    )
                )
            ),
            array("/test/bar", false, array(
                    "fuzz" => array(
                        array(
                            "id" => "fuzzy"
                        ),
                        array(
                            "id" => "very fuzzy"
                        )
                    )
                )
            ),
            array("/test/bar/fuzz", false, array(
                    array(
                        "id" => "fuzzy"
                    ),
                    array(
                        "id" => "very fuzzy"
                    )
                )
            ),
            array("/test/fuzzy", false, "Text content"),
            array("/test/fuzzy", true, array("Text content") ),
            array("/test[]/non-existing", false, null),
            array("/test[]/non-existing", true, array() ),
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
    public function provideTestToBoolean() {
        return array(
            array(true, true),
            array("true", true),
            array(false, false),
            array("false", false),
            array("ja", false),
            array("nee", false),
            array(array(), false),
            array(new \stdClass(), false),
        );
    }
}
