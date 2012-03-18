<?php

namespace Suin\XML;

use \DOMDocument;

class SimpleXMLElement extends \SimpleXMLElement
{
	/**
	 * Add child.
	 * @param string $name
	 * @param mixed $value
	 * @param mixed $ns
	 * @return $this
	 */
	public function addChild($name, $value = null, $ns = null)
	{
		if ( preg_match('/[<>]/', $value) == true )
		{
			$child = parent::addChild($name);
			$dom = dom_import_simplexml($child);
			$cdata = $dom->ownerDocument->createCDATASection($value);
			$dom->appendChild($cdata);
			return $child;
		}

		if ( is_string($value) === true )
		{
			$value = str_replace('&', '&amp;', $value); // TODO >> this value is used?
		}

		return call_user_func_array(array('parent', 'addChild'), func_get_args());
	}

	/**
	 * Return formatted XML.
	 * @return string
	 */
	public function asFormatXML()
	{
		$xml = dom_import_simplexml($this);
		$dom = new DOMDocument('1.0', 'UTF-8');
		$xml = $dom->importNode($xml, true);
		$dom->appendChild($xml);
		$dom->formatOutput = true;
		$xml = $dom->saveXML();
		$xml = preg_replace("/\n([ ]+)</e", '"\n".str_repeat("\t", strlen("$1") / 2)."<"', $xml);
		return $xml;
	}

	public function toString()
	{
		return strval($this);
	}

	public function toArray()
	{
		$array = (array) $this;

		foreach ( $array as &$property )
		{
			if ( is_object($property) === true and method_exists($property, 'toArray') === true )
			{
				$property = $property->toArray();
			}
		}
		
		return $array;
	}

	public function attributesAssoc($assoc = false)
	{
		if ( $assoc === false )
		{
			return parent::attributes();
		}

		$attributes = array();

		foreach ( $this->attributes() as $name => $value )
		{
			$attributes[$name] = $value->toString();
		}

		return $attributes;
	}
}
