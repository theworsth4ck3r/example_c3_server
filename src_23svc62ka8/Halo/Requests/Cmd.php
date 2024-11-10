<?php

namespace Halo\Requests;

use Halo\Requests\Request;
use Halo\Tasks\Tasks;
use Halo\Config\Config;
use Halo\Controllers\FilesController;

class Cmd extends Request
{
	public function __construct($requestData)
	{
		$this->data = $requestData;
	}

	public function processRequest(): void
	{
		$config = Config::getInstance();
		$filePath = $config->getClientCmdResultsFilePath($this->data['client_id']) . '/' . $this->data['date'];

		FilesController::createFile($filePath);
		FilesController::modifyFile($filePath, 'w', base64_decode($this->data['result']));

		Tasks::removeClientTask($this->data['client_id'], $this->data['task_id']);
	}
}
