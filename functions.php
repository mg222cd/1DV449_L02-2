<?php
require_once("Application.php");

$application = new Application();

$application->sec_session_start();

/*
* It's here all the ajax calls goes
*/
if(isset($_GET['function'])) {
	switch ($_GET['function']) {
		case 'logout':
			$application->logout();
			break;
		case 'add':
			$application->addToDB($_GET["message"],  $_GET["name"]);
			break;
		case 'getMessages':
			echo $application->getMessages();
			break;
	}
}
else{
	$application->loginUser($_POST['username'], $_POST['password']);
}
