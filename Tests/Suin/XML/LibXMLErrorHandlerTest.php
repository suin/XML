<?php

namespace Suin\XML;

use \libXMLError;

class LibXMLErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
	}

	/**
	 * Test construct.
	 */
	public function test__construct()
	{
		$error   = new libXMLError();
		$handler = new LibXMLErrorHandler($error);
		$this->assertAttributeSame($error, 'error', $handler);
	}

	/**
	 * Test getting message.
	 */
	public function testGetMessage()
	{
		$error = new libXMLError();
		$error->level = LIBXML_ERR_WARNING;
		$error->code  = 1;
		$error->message = 'message';
		$error->line = 123;
		$error->column = 10;
		$error->file = null;
		$handler = new LibXMLErrorHandler($error);
		$actual = $handler->getMessage();
		$expect = 'Warning (1) message at line 123 on column 10';
		$this->assertSame($expect, $actual);
	}

	/**
	 * Test getting message with file.
	 */
	public function testGetMessage_with_file()
	{
		$error = new libXMLError();
		$error->level = LIBXML_ERR_WARNING;
		$error->code  = 1;
		$error->message = 'message';
		$error->line = 123;
		$error->column = 10;
		$error->file = 'filename';
		$handler = new LibXMLErrorHandler($error);
		$actual = $handler->getMessage();
		$expect = 'Warning (1) message at line 123 on column 10 in file filename';
		$this->assertSame($expect, $actual);
	}

	/**
	 * Test converting to string.
	 */
	public function test__toString()
	{
		$error = new libXMLError();
		$error->level = LIBXML_ERR_WARNING;
		$error->code  = 1;
		$error->message = 'message';
		$error->line = 123;
		$error->column = 10;
		$error->file = null;
		$handler = new LibXMLErrorHandler($error);
		$actual = strval($handler);
		$expect = 'Warning (1) message at line 123 on column 10';
		$this->assertSame($expect, $actual);
	}

	/**
	 * Test getting messages.
	 */
	public function testGetMessages()
	{
		$error = new libXMLError();
		$error->level = LIBXML_ERR_WARNING;
		$error->code  = 1;
		$error->message = 'message';
		$error->line = 123;
		$error->column = 10;

		$errors = array(
			clone $error,
			clone $error,
		);

		$actual = LibXMLErrorHandler::getMessages($errors);
		$expect = "Warning (1) message at line 123 on column 10\nWarning (1) message at line 123 on column 10\n";
		$this->assertSame($expect, $actual);
	}
}
