<?php

class Application{

private function callToDatabase($q, $sqlParams = array()){
	$db = null;
	//strip tags
	$sqlParams = array_map('strip_tags', $sqlParams);

	//open database
	try {
		$db = new PDO("sqlite:db.db");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOEception $e) {
		die("Something went wrong -> " .$e->getMessage());
	}
	
	$result = '';
	try {
		$stm = $db->prepare($q);
		$stm->execute($sqlParams);
		$result = $stm->fetchAll();
	}
	catch(PDOException $e) {
		echo("Error creating query: " .$e->getMessage());
		return false;
	}
	return $result;
}	
	
/**
* Called from AJAX to add stuff to DB
*/
public function addToDB($message, $user) {

		$q = "INSERT INTO messages (message, name) VALUES(?, ?)";
		$this->callToDatabase($q, array($message, $user));
		
		$q = "SELECT * FROM users WHERE username = ?";
		$result = $this->callToDatabase($q, array($user));
		if($result != false) {
			echo "Message saved by user: " . json_encode($result);
		}else{
			echo "Could not find the user";
		}
		header("Location: test/debug.php");
	}

/*
Just som simple scripts for session handling
*/
public function sec_session_start() {
        $session_name = 'sec_session_id'; // Set a custom session name
        $secure = false; // Set to true if using https.
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies.
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params(3600, $cookieParams["path"], $cookieParams["domain"], $secure, false);
        $httponly = true; // This stops javascript being able to access the session id.
        session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(); // regenerated the session, delete the old one.
}

public function checkUser() {
	if(!session_id()) {
		$this->sec_session_start();
	}

	if(!isset($_SESSION["username"])) {header('HTTP/1.1 401 Unauthorized'); die();}

	if(isset($_SESSION['login_string'])) {
		if(!password_verify($_SESSION["username"] . $_SERVER['HTTP_USER_AGENT'], $_SESSION["login_string"])) {
			header('HTTP/1.1 401 Unauthorized'); die();
		}
	}
	else {
		header('HTTP/1.1 401 Unauthorized'); die();
	}
	return true;
}

public function loginUser($u, $p) {
	if(isset($u) && isset($p)) {
			$q = "SELECT id FROM users WHERE username = ? AND password = ?";
			$result = $this->callToDatabase($q, array($u, $p));
			if($result != false) {
				$_SESSION['username'] = $u;
				$_SESSION['login_string'] = password_hash($u . $_SERVER['HTTP_USER_AGENT'], PASSWORD_DEFAULT);
				header("Location: mess.php");
				die;
			}
		}

		header('HTTP/1.1 401 Unauthorized');
		die("Could not find the user");
}

public function getUser($user) {
	$q = "SELECT * FROM users WHERE username = ?";
		return $this->callToDatabase($q, array($user));
}

public function logout() {
	if(!session_id()) {
		$this->sec_session_start();
	}
	session_destroy();
	//session_end();
	header('Location: index.php');
}

// get the specific message
public function getMessages($id=0) {
	set_time_limit(0);
	while (true) {
		$return = array();
		$q = "SELECT * FROM messages WHERE serial > ?";
		$return = $this->callToDatabase($q, array($id));

		if (count($return) > 0 ) {
			$id = $return[count($return)-1][0];
			echo json_encode(array('id'=>$id,'messages'=>$return));
			break;
		}
	}
	
}


}