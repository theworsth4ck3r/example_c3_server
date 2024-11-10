<?php

namespace Halo\Requests;

abstract class Request
{
	protected $data = [];
	protected $files = [];

	abstract public function processRequest();
}
