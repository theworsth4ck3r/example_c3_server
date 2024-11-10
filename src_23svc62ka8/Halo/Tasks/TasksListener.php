<?php


namespace Halo\Tasks;

use Halo\Tasks\Tasks;
use Halo\Helpers\Helpers;

class TasksListener extends Tasks
{
	/**
	 * @codeCoverageIgnore
	 */
	static public function waitForAddTaskRequest(): void
	{
		if (isset($_POST['addtask'])) {
			self::addTask($_POST);
		}

		if (isset($_POST['removealltasks'])) {
			self::removeAllTasks($_POST);
		}
	}

	/**
	 * @codeCoverageIgnore
	 */
	static public function addTask($postRequest): void
	{
		if (!isset($postRequest['client_id']) || empty($postRequest['client_id'])) {
			return;
		}

		$client_id = $postRequest['client_id'];
		parent::addTaskToClient($client_id, Helpers::getUUID(), $postRequest['task']);
	}

	static public function removeAllTasks($postRequest): void
	{
		if (!isset($postRequest['client_id']) || empty($postRequest['client_id'])) {
			return;
		}

		parent::removeAllClientTasks($postRequest['client_id']);
	}
}
