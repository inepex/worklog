<?php
session_set_cookie_params(86400);
ini_set('session.gc_maxlifetime', 86400);
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
header("Content-type: text/json; charset=utf-8");
require_once '../worklog-config.php';
require_once 'classes/User.php';
require_once 'classes/WorkPlace.php';
require_once 'classes/Notification.php';
require_once 'classes/Company.php';
require_once 'classes/Efficiency.php';
require_once 'classes/Category.php';
require_once 'classes/AssociatedUser.php';
require_once 'classes/AssociatedCategory.php';
require_once 'classes/ProjectPlan.php';
require_once 'classes/ProjectStatus.php';
require_once 'classes/ProjectPlanEntry.php';
require_once 'classes/Project.php';
require_once 'classes/StatusBar.php';
require_once 'classes/Tools.php';
require_once 'classes/Log.php';
require_once 'classes/Scrum.php';
include('include/login_functions.php');
error_reporting(E_ALL ^ E_DEPRECATED);

$api_key = null;
$user_id=null;
$user_name=null;
$success=false;
$message=null;

if (isset($_GET["username"]) && isset($_GET["md5password"])) {

	$api_key = User::authenticate_user($_GET["username"],$_GET["md5password"]);	
	
	
	$users =  User::get_users();
 

	if ($api_key) {
	
	foreach($users as $user) {
		if ($user->get_api_key()==$api_key) {
			$user_id=$user->get_id();
			$user_name=$user->get_user_name();
			$success = true;
			$message = "OK";
		}
		
	}
	} else {
		$success = false;
		$message = "No user found";
		
	}
	
}
		

	
	$arr = array('api_key' => $api_key, 'user_id' => $user_id, 'user_name' => $user_name);
	$json_response = array('success' => $success, 'message' => $message, 'response' => $arr);
	echo json_encode($json_response);

?>