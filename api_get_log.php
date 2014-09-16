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

$user_id=null;
$user_name=null;
$success=false;
$message=null;

 	if (isset($_GET["api_key"])) {
		  
		  $users =  User::get_users();

			foreach($users as $user) {
				if ($user->get_api_key()==$_GET["api_key"]) {
					$user_id=$user->get_id();
				}
			}
		  
			if ($user_id) {
		
				if(isset($_GET["date"])) {
				 
				 $user = User::get($user_id);
				 $logs = $user->get_logs($_GET["date"]);
				 
				 $json_logs = array();
				 
				 if ($logs) {
					 foreach($logs as $log){
	
					 	 $log_id = $log->get_id();
						 $project  = Project::get($log->get_project_id());
						 $category = AssociatedCategory::get($log->get_category_assoc_id());
						 $work_place = WorkPlace::get($log->get_working_place_id());
						 $efficiency = Efficiency::get($log->get_efficiency_id());
						 $datetime1 = new DateTime($log->get_from());
						 $datetime2 = new DateTime($log->get_to());
						 $interval = $datetime1->diff($datetime2);
						 $diff = $interval->format('%H:%I');
						 
						 $json_logs[] = array ("log_id" => $log_id, "log" =>  
						 		array( "project" => $project->get_name(), 
						 				"category" => $category->get_name(),
						 				"date" => $log->get_date(),
						 				"from" => date("H:i",strtotime($log->get_from())),
						 				"to" => date("H:i",strtotime($log->get_to())),
						 				"diff" => $diff,
						 				"entry" => $log->get_entry(),
						 				"work_place" => $work_place->get_name(),
						 				"efficiency" => $efficiency->get_name())
						 );
					 
					 }
					 
					 $success = true;
					 $message="OK";
				 } else {
				 	$success = false;
				 	$message="No logs found";
				 }
				 
				 
				} else {
					$success = false;
					$message="No date in format: 'YYYY-MM-01'";
				}
		
		
			} else {
				$success = false;
				$message="api_key is not valid.";
			}
		
		} else {
			$success = false;
			$message="No api_key found.";
		} 


	$arr = $json_logs;
	$json_response = array('success' => $success, 'message' => $message, 'response' => $arr);
	echo json_encode($json_response);

?>