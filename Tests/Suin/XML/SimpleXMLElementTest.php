<?php

namespace Suin\XML;

class SimpleXMLElementTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test adding child.
	 */
	public function testAddChild()
	{
		$sxe = new SimpleXMLElement('<root />');
		$actual = $sxe->addChild('foo');
		$this->assertInstanceOf('\Suin\XML\SimpleXMLElement', $actual);
	}

	/**
	 * Test adding child with string.
	 * @dataProvider data4testAddChild_With_string
	 */
	public function testAddChild_With_string($expect, $string)
	{
		$sxe = new SimpleXMLElement('<root />');
		$child = $sxe->addChild('foo', $string);
		$actual = strval($child);
		$this->assertSame($expect, $actual);
	}

	public static function data4testAddChild_With_string()
	{
		return array(
			array('abc', 'abc'),
			array('&', '&amp;'),
			array('<html></html>', '<html></html>'),
		);
	}

	/**
	 * Test returning indented XML.
	 */
	public function testAsFormatXML()
	{
		$sxe = new SimpleXMLElement('<root />');
		$sxe->addChild('foo', 'foo');
		
		$expect = '<?xml version="1.0" encoding="UTF-8"?>
<root>
	<foo>foo</foo>
</root>
';
		$actual = $sxe->asFormatXML();
		$this->assertSame($expect, $actual);
	}

	/**
	 * Test returning indented XML contains line break.
	 */
	public function testAsFormatXML_Contains_line_break()
	{
		$sxe = new SimpleXMLElement('<root />');
		$sxe->addChild('foo', "line1\nline2\nline3");
		$expect = '<?xml version="1.0" encoding="UTF-8"?>
<root>
	<foo>line1
line2
line3</foo>
</root>
';
		$actual = $sxe->asFormatXML();
		$this->assertSame($expect, $actual);
	}

	/**
	 * Test returning indented XML contains CDATA.
	 * @depends testAddChild_With_string
	 */
	public function testAsFormatXML_Contains_CDATA()
	{
		$sxe = new SimpleXMLElement('<root />');
		$sxe->addChild('foo', "<html></html>");
		$expect = '<?xml version="1.0" encoding="UTF-8"?>
<root>
	<foo><![CDATA[<html></html>]]></foo>
</root>
';
		$actual = $sxe->asFormatXML();
		$this->assertSame($expect, $actual);
	}

	/**
	 * Test converting to string.
	 */
	public function testToString()
	{
		$sxe = new SimpleXMLElement('<root>foot</root>');
		$actual = $sxe->toString();
		$expect = 'foot';
		$this->assertSame($expect, $actual);
	}

	/**
	 * @dataProvider data4testToArray
	 */
	public function testToArray($expect, $xml)
	{
		$sxe = new SimpleXMLElement($xml);
		$actual = $sxe->toArray();
		$this->assertSame($expect, $actual);
	}

	public static function data4testToArray()
	{
		$data = array();
		$data[0]['expect'] = array('child' => 'foo');
		$data[0]['xml']    = '<root><child>foo</child></root>';

		// Recursive structure.
		$data[1]['expect'] = array(
			'child' => array(
				'grandChild' => 'foo',
			),
		);
		$data[1]['xml']    = '<root><child><grandChild>foo</grandChild></child></root>';

		return $data;
	}

	/**
	 * Test getting attributes.
	 */
	public function testAttributesAssoc()
	{
		$sxe = new SimpleXMLElement('<root name="foo" />');
		$actual = $sxe->attributesAssoc();
		$this->assertInstanceOf('\Suin\XML\SimpleXMLElement', $actual);
	}

	/**
	 * Test getting attributes as array (no items).
	 */
	public function testAttributesAssoc_As_array_no_items()
	{
		$sxe = new SimpleXMLElement('<root />');
		$actual = $sxe->attributesAssoc(true);
		$expect = array();
		$this->assertSame($expect, $actual);
	}

	/**
	 * Test getting attributes as array.
	 */
	public function testAttributesAssoc_As_array()
	{
		$sxe = new SimpleXMLElement('<root name="foo" />');
		$actual = $sxe->attributesAssoc(true);
		$expect = array('name' => 'foo');
		$this->assertSame($expect, $actual);
	}
}
