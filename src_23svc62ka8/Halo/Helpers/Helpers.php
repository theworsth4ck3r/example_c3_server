<?php

namespace Halo\Helpers;

class Helpers
{
	static public function getUUID(): string
	{
		return sprintf(
			'%04x%04x-%04x-%04x%04x%04x',
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff)
		);
	}
}
