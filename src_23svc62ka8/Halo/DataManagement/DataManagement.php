<?php

namespace Halo\DataManagement;

use Halo\Config\Config;
use Halo\Controllers\FilesController;

class DataManagement
{
	static public function getCmdResults(string $client_id): array
	{		
		$config = Config::getInstance();
		$result = [];

		$cmdResultsDir = $config->getClientCmdResultsFilePath($client_id);

		$files = array_diff(scandir($cmdResultsDir), array('.', '..'));
		foreach ($files as $file) {
			$result[$file] = FilesController::getFileContents($cmdResultsDir . '/' . $file);
		}

		return $result;
	}
}
