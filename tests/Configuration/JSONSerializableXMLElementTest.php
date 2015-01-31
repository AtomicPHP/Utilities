<?php

namespace Nijens\Utilities\Tests;

use PHPUnit_Framework_TestCase;

/**
 * JSONSerializableXMLElementTest
 *
 * @author  Niels Nijens <nijens.niels@gmail.com>
 * @package Nijens\Utilities\Tests
 **/
class JSONSerializableXMLElementTest extends PHPUnit_Framework_TestCase {

	/**
	 * testGetWithDefaultConfiguration
	 *
	 * Tests if json_encode on a JSONSerializableXMLElement and json_decode returns the expected result from a $xmlString
	 *
	 * @dataProvider provideTestJsonSerialize
	 *
	 * @access public
	 * @param  string $xmlString
	 * @param  string $expectedResult
	 * @return void
	 **/
	public function testJsonSerialize($xmlString, $expectedResult) {
		$xml = simplexml_load_string($xmlString, "Nijens\\Utilities\\Configuration\\JSONSerializableXMLElement");
		$json = json_encode($xml);

		$this->assertSame($expectedResult, json_decode($json, true) );
	}

	/**
	 * provideTestJsonSerialize
	 *
	 * Returns an array with XML and the expected result
	 *
	 * @access public
	 * @return array
	 **/
	public function provideTestJsonSerialize() {
		return array(
			array("<node></node>", null),
			array("<node>Text</node>", "Text"),
			array("<node attribute='attributeValue'>Text</node>", array("attribute" => "attributeValue", "#value" => "Text") ),
			array("<node attribute='attributeValue'><childnode/>Text</node>", array("attribute" => "attributeValue", "childnode" => null, "#value" => "Text") ),
			array("<node attribute='attributeValue'><childnode attribute='attributeValue'/>Text</node>", array("attribute" => "attributeValue", "childnode" => array("attribute" => "attributeValue"), "#value" => "Text") ),
			array("<node attribute='attributeValue'><childnode attribute='attributeValue'/><childnode attribute='attributeValue'/>Text</node>", array("attribute" => "attributeValue", "childnode" => array(array("attribute" => "attributeValue"), array("attribute" => "attributeValue") ), "#value" => "Text") ),
		);
	}
}
