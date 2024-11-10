<?php

namespace Halo\Requests;

use Halo\Requests\Request;
use Halo\Controllers\ClientsController;

class RegisterClient extends Request
{
	public function __construct($requestData)
	{
		$this->data = $requestData;
	}

	public function processRequest(): void
	{
		$clientsController = new ClientsController();
		$clientsController->addClient($this->data['client']);
	}
}
