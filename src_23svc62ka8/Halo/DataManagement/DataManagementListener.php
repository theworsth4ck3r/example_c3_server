<?php

namespace Halo\DataManagement;

class DataManagementListener
{
	static public function listenForDataRequest(): void
	{
		if (isset($_POST['data-request']) && !empty($_POST['data-request'])) {
			switch ($_POST['data-request']) {

				case 'get-cmd-results':
					print_r(json_encode(DataManagement::getCmdResults($_POST['client_id'])));
					die();
					break;

				default:
					return;
					break;
			}
		}
	}
}
