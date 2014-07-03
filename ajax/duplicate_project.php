<?php
session_start();
require_once '../../worklog-config.php';
require_once '../classes/User.php';
require_once '../classes/Efficiency.php';
require_once '../classes/WorkPlace.php';
require_once '../classes/Notification.php';
require_once '../classes/Company.php';
require_once '../classes/Category.php';
require_once '../classes/AssociatedUser.php';
require_once '../classes/AssociatedCategory.php';
require_once '../classes/ProjectPlan.php';
require_once '../classes/ProjectStatus.php';
require_once '../classes/ProjectPlanEntry.php';
require_once '../classes/Project.php';
require_once '../classes/Log.php';

if(isset($_POST['project_id']) && Project::is_project_exist($_POST['project_id'])  && isset($_POST['duplicate_name']) && $_POST['duplicate_name'] != ""){
	$duplicated_project = Project::duplicate_project($_POST['project_id'], $_POST['duplicate_name']);
	echo $duplicated_project->get_id();
}
?>