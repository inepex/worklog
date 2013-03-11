<?php
require_once '../../worklog-config.php';
	require_once '../classes/User.php';
	require_once '../classes/WorkPlace.php';
	require_once '../classes/Notification.php';
	require_once '../classes/Company.php';
	require_once '../classes/Category.php';
	require_once '../classes/AssociatedUser.php';
	require_once '../classes/AssociatedCategory.php';
	require_once '../classes/ProjectPlan.php';
	require_once '../classes/ProjectPlanEntry.php';
	require_once '../classes/Project.php';
	require_once '../classes/Log.php';

if(isset($_GET['user_id']) && $_GET['user_id'] != "" && User::is_exist($_GET['user_id']) && isset($_GET['password']) && $_GET['password'] != ""){
	$user = new User($_GET['user_id']);
	if($user->get_password() == $_GET['password']){
		$projects = $user->get_projects_where_user_have_planned_hour(1);
		$projects_to_json = array();
		foreach($projects as $project){
			/* @var $project Project */
			$project_to_json = array();
			$project_to_json['id'] = $project->get_id();
			$project_to_json['name'] = $project->get_name();
			$project_to_json['description'] = $project->get_description();
			array_push($projects_to_json, $project_to_json);
		}
		echo json_encode($projects_to_json);
	}
	else{
		header('HTTP/1.0 403 Forbidden');
	}
}
else{
	header('HTTP/1.0 403 Forbidden');
}
?>