<?php

namespace Halo\Controllers;

use Halo\Config\Config;
use Halo\Controllers\FilesController;
use Halo\Controllers\DirectoriesController;

class ClientsController
{
	private $clients = [];

	public function __construct()
	{
		$this->getClientsFromFile();
	}

	public function getClients(): array
	{
		return $this->clients;
	}

	public function getClientByUuid(string $uuid): ?array
	{
		foreach ($this->clients as $client)
			if ($client['client_id'] === $uuid)
				return $client;

		return null;
	}

	public function addClient(array $client): void
	{
		$this->clients[] = $client;
		$this->createClientDirectoriesAndFiles($client['client_id']);
		$this->addClientsToFile();
	}

	public function addClientsToFile(): void
	{
		$config = Config::getInstance();

		FilesController::modifyFile(
			$config->getFromConfig('clientsFile'),
			'w',
			serialize($this->clients)
		);
	}

	private function getClientsFromFile(): void
	{
		$config = Config::getInstance();

		$fileContent = FilesController::getFileContents($config->getFromConfig('clientsFile'));
		$json = [];

		if ($fileContent) {
			$json = unserialize($fileContent);
		}

		$this->clients = $json;
	}

	private function createClientDirectoriesAndFiles(string $client_id): void
	{
		$config = Config::getInstance();

		DirectoriesController::createDirectory($config->getClientImagesDirPath($client_id));
		DirectoriesController::createDirectory($config->getClientOtherDirPath($client_id));
		DirectoriesController::createDirectory($config->getClientCmdResultsFilePath($client_id));
		FilesController::createFile($config->getClientTasksFilePath($client_id));

		FilesController::modifyFile(
			$config->getClientTasksFilePath($client_id),
			'w',
			serialize([])
		);
	}
}
