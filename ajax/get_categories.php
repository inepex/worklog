<?php
session_start();
require_once '../../worklog-config.php';
require_once '../classes/User.php';
require_once '../classes/WorkPlace.php';
require_once '../classes/Notification.php';
require_once '../classes/Company.php';
require_once '../classes/Category.php';
require_once '../classes/Efficiency.php';
require_once '../classes/AssociatedUser.php';
require_once '../classes/AssociatedCategory.php';
require_once '../classes/ProjectStatus.php';
require_once '../classes/ProjectPlan.php';
require_once '../classes/ProjectPlanEntry.php';
require_once '../classes/Project.php';
require_once '../classes/Log.php';
require_once '../classes/PhpConsole.php';
require_once '../include/login_functions.php';
	$user = new User($_SESSION['enterid']);
	if(isset($_POST['project_id']) && $_POST['project_id'] != ""){
		if(Project::is_project_exist($_POST['project_id'])){
			$project = new Project($_POST['project_id']);
			$categories = $project->get_categories_of_user_with_planned_hours($user);
			$categories_id_and_name = array();
			$category_id_and_name   = array();
			for($i=0; $i<count($categories); $i++){
				$category_id_and_name['assoc_id'] = $categories[$i]->get_assoc_id();
				$category_id_and_name['id'] = $categories[$i]->get_id();
				$category_id_and_name['name'] = "[ ".$categories[$i]->get_name()." ] -- ".$categories[$i]->get_description();
				$category_id_and_name['description'] = $categories[$i]->get_description();
				array_push($categories_id_and_name,$category_id_and_name);
			}
			echo json_encode($categories_id_and_name);		
		}
	}
?>