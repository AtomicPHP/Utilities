<?php
namespace Nijens\Utilities\Tests;

use Nijens\Utilities\CachedConfiguration;
use PHPUnit_Framework_TestCase;

/**
 * Unit test that extends the Configuration test because the CachedConfiguration should simply work exactly as the Configuration (only faster)
 *
 * @author Ron Rademaker
 * @since Thu Jui 2 2015
 */
class CachedConfigurationTest extends PHPUnit_Framework_TestCase {
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
        $configuration = new CachedConfiguration(__DIR__ . "/Resources/configuration/default.xml", __DIR__ . "/Resources/xsd/default.xsd");
        $configuration->loadConfiguration(null);

        $this->assertEquals($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray) );
        $this->assertSame($expectedResult, $configuration->get($xpathExpression, $alwaysReturnArray) );
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
			array("/test/fuzzy", true, array("Text content") ),
            array("/test[]/non-existing", false, null),
			array("/test[]/non-existing", false, null),
            array("/test[]/non-existing", true, array() ),
        );
    }

}
