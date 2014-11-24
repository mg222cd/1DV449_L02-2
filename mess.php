<?php
require_once("application.php");
$application = new application();
$application->checkUser();
?>
<!DOCTYPE html>
<html lang="sv">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="pic/favicon.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/dyn.css">
	<title>Messy Labbage</title>
  </head>
  <body>
        <div id="container">
            <div id="messageboard">
                <input class="btn btn-danger" type="button" id="buttonLogout" value="Logout" />
                <div id="messagearea"></div>
                <p id="numberOfMess">Antal meddelanden: <span id="nrOfMessages">0</span></p>
                Name:<br /> <input id="inputName" type="text" name="name" /><br />
                Message: <br />
                <textarea name="mess" id="inputText" cols="55" rows="6"></textarea>
                <input class="btn btn-primary" type="button" id="buttonSend" value="Write your message" />
                <span class="clear">&nbsp;</span>
            </div>
        </div>
            <script src="js/script.js"></script>
            <script type="text/javascript" src="js/jquery.js"></script>
            <script src="MessageBoard.js"></script>
            <script src="Message.js"></script>
	</body>
	</html>