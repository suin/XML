<?php

namespace Suin\XML;

use \libXMLError;

class LibXMLErrorHandler
{
	protected static $level = array(
		LIBXML_ERR_WARNING => 'Warning',
		LIBXML_ERR_ERROR   => 'Error',
		LIBXML_ERR_FATAL   => 'Fatal',
	);
	protected $error = null;

	/**
	 * Return new LibXMLErrorHandler object.
	 * @param \libXMLError $error
	 */
	public function __construct(libXMLError $error)
	{
		$this->error = $error;
	}

	/**
	 * Return the error message.
	 * @return string
	 */
	public function __toString()
	{
		return $this->getMessage();
	}

	/**
	 * Return the error message.
	 * @return string
	 */
	public function getMessage()
	{
		$level = static::$level[$this->error->level];
		
		$message = sprintf('%s (%s) %s at line %u on column %u', $level, $this->error->code, trim($this->error->message), $this->error->line, $this->error->column);

		if ( isset($this->error->file) === true )
		{
			$message .= ' in file '.$this->error->file;
		}

		return $message;
	}

	/**
	 * Return the error message.
	 * @static
	 * @param array $errors
	 * @return string
	 */
	public static function getMessages(array $errors)
	{
		$messages = '';

		foreach ( $errors as $error )
		{
			$messages .= new static($error)."\n";
		}

		return $messages;
	}
}
