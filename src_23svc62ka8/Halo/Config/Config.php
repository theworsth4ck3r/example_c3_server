<?php

namespace Halo\Config;

class Config
{
	static private $instance;
	private $mainPath;

	private function __construct(string $mainPath) {
		$this->mainPath = $mainPath;
	}
	private function __clone() {}

	static public function getInstance(string $mainPath = null): self
	{
		if (!self::$instance)
			self::$instance = new self($mainPath);

		return self::$instance;
	}

	public function getConfig(): array
	{
		return [

			/* Urls */
			'baseUrl' => 'http://localhost/c2/?login=admin&password=password',

			/* Dirs and files */
			'baseDir' => dirname(__FILE__),
			'clientTasksDir' => $this->mainPath . 'tasks',
			'clientTasksFile' => $this->mainPath . 'tasks/{:id:}.tasks',
			'dataDir' =>  $this->mainPath . 'data',
			'clientsFile' =>  $this->mainPath . 'clients.json',
			'imagesDir' =>  $this->mainPath . 'data/{:id:}/images',
			'otherDir' =>  $this->mainPath . 'data/{:id:}/other',
			'cmdResultsFile' =>  $this->mainPath . 'data/{:id:}/other/cmdresults',
			'installedFile' =>  $this->mainPath . '.installed'

		];
	}

	public function getFromConfig(string $key): string
	{
		if (isset($this->getConfig()[$key]))
			return $this->getConfig()[$key];

		throw new \Exception('No key: ' . $key . ' in config.');
	}

	public function getClientTasksFilePath(string $client_id): string
	{
		return str_replace('{:id:}', $client_id, $this->getFromConfig('clientTasksFile'));
	}

	public function getClientImagesDirPath(string $client_id): string
	{
		return str_replace('{:id:}', $client_id, $this->getFromConfig('imagesDir'));
	}

	public function getClientOtherDirPath(string $client_id): string
	{
		return str_replace('{:id:}', $client_id, $this->getFromConfig('otherDir'));
	}

	public function getClientCmdResultsFilePath(string $client_id): string
	{
		return str_replace('{:id:}', $client_id, $this->getFromConfig('cmdResultsFile'));
	}
}
