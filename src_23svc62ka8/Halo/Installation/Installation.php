<?php

namespace Halo\Installation;

use Halo\Config\Config;
use Halo\Controllers\DirectoriesController;
use Halo\Controllers\FilesController;

class Installation
{
	static public function install(): void
	{
		$config = Config::getInstance();

		$dirs = ['clientTasksDir', 'dataDir'];
		$files = ['clientsFile', 'installedFile'];

		foreach ($dirs as $dir) DirectoriesController::createDirectory($config->getFromConfig($dir));

		foreach ($files as $file) FilesController::createFile($config->getFromConfig($file));

		self::writeEmptySerializedArrayToClientsFile();
	}

	static public function writeEmptySerializedArrayToClientsFile(): void
	{
		$config = Config::getInstance();
		FilesController::modifyFile($config->getFromConfig('clientsFile'), 'w', serialize([]));
	}
}
