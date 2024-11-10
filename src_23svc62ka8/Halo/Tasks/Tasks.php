<?php

namespace Halo\Tasks;

use Halo\Config\Config;
use Halo\Controllers\FilesController;

class Tasks
{
	static public function getTasksFromFile(string $clientUuid): array
	{
		$config = Config::getInstance();
		$content = FilesController::getFileContents($config->getClientTasksFilePath($clientUuid));

		return unserialize($content);
	}

	static public function addTaskToClient(string $clientUuid, string $task_id, array $task): bool
	{
		$config = Config::getInstance();
		$tasks = self::getTasksFromFile($clientUuid);
		
		$task['task_id'] = $task_id;
		$tasks[] = $task;

		FilesController::modifyFile(
			$config->getClientTasksFilePath($clientUuid),
			'w',
			serialize($tasks)
		);

		return true;
	}

	static public function removeClientTask(string $clientUuid, string $task_id): void
	{
		$config = Config::getInstance();

		$tasks = self::getTasksFromFile($clientUuid);

		foreach ($tasks as $index => $task)
			if ($task['task_id'] == $task_id)
				unset($tasks[$index]);

		FilesController::modifyFile(
			$config->getClientTasksFilePath($clientUuid),
			'w',
			serialize(array_values($tasks))
		);
	}

	static public function removeAllClientTasks(string $clientUuid): void
	{
		$config = Config::getInstance();

		FilesController::modifyFile(
			$config->getClientTasksFilePath($clientUuid),
			'w',
			serialize([])
		);
	}
}
