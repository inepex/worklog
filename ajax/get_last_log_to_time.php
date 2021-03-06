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
require_once '../include/login_functions.php';
$user = User::get($_SESSION['enterid']);
echo json_encode($user->get_last_log_to_time());