<?php

namespace Halo\Requests;

use Halo\Requests\Request;
use Halo\Tasks\Tasks;

class ControlRequest extends Request
{
	public function __construct($requestData)
	{
		$this->data = $requestData;
	}

	public function processRequest(): void
	{
		$tasks = Tasks::getTasksFromFile($this->data['client_id']);

		$client = [
			'client_id' => $this->data['client_id'],
			'tasks' => $tasks
		];

		echo json_encode($client);
		die();
	}
}
