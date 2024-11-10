<?php

namespace Halo\Validator;

class Validator
{
	static protected $illegal = [
		'php',
		'rb',
		'sh',
		'exe',
		'js',
		'html',
		'css',
		'.htaccess'
	];

	/**
	 * @codeCoverageIgnore
	 */
	static public function validate($file): bool
	{
		$contentType = mime_content_type($file['tmp_name']);
		$extension = explode('/', $contentType)[1];

		if (in_array($extension, self::$illegal)) {
			return false;
		}

		return true;
	}
}
