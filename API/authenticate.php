<?php
require_once '../../worklog-config.php';
require_once '../classes/User.php';
if(isset($_GET['username']) && $_GET['username'] != "" && isset($_GET['password']) && $_GET['password'] != ""){
	$user_id = User::authenticate_user($_GET['username'], $_GET['password']);
	if($user_id){
		echo $user_id;
	}
	else{
		header('HTTP/1.0 403 Forbidden');
	}
}
else{
	header('HTTP/1.0 403 Forbidden');
}
?>