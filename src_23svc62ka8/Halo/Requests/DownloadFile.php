<?php

namespace Halo\Requests;

use Halo\Requests\Request;
use Halo\Tasks\Tasks;
use Halo\Config\Config;
use Halo\Validator\Validator;

class DownloadFile extends Request
{
	public function __construct($requestData, $files)
	{
		$this->data = $requestData;
		$this->files = $files;
	}

	public function processRequest(): void
	{
		if (Validator::validate($this->files['file']))
			$this->saveFile();

		Tasks::removeClientTask($this->data['client_id'], $this->data['task_id']);
	}

	private function saveFile(): void
	{
		$config = Config::getInstance();
		$mimeType = mime_content_type($this->files['file']['tmp_name']);
		$images = ['image/gif', 'image/jpeg', 'image/tiff', 'image/bmp', 'image/png', 'image/jpg'];

		if (in_array($mimeType, $images)) {
			$dirPath = $config->getClientImagesDirPath($this->data['client_id']);
		} else {
			$dirPath = $config->getClientOtherDirPath($this->data['client_id']);
		}

		move_uploaded_file($this->files['file']['tmp_name'], $dirPath . '/' . $this->files['file']['name']);
	}
}
