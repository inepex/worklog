<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);
require_once 'classes/PhpConsole.php';
require_once 'classes/Notification.php';
require_once 'classes/Company.php';
PhpConsole::start();
require_once '../worklog-config.php';
require_once 'classes/User.php';
require_once 'classes/AssociatedUser.php';
require_once 'classes/Category.php';
require_once 'classes/AssociatedCategory.php';
require_once 'classes/Project.php';
require_once 'classes/ProjectPlan.php';
require_once 'classes/ProjectPlanEntry.php';
require_once 'classes/Log.php';
require_once 'classes/WorkPlace.php';
require_once 'include/notifications.php';
$duplicated_project = Project::duplicate_project(1, "első duplikálás");
header('location:project_edit.php?project_id='.$duplicated_project->get_id());
?>