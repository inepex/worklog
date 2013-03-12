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
echo "first: ".Log::get_first_log_date();
$date = new DateTime(Log::get_first_log_date());
$date->modify("first day of this month");
echo '<br>'.$date->format("Y-m-d");
$date->modify("first day of next month");
echo '<br>'.$date->format("Y-m-d");
echo "letezik-e:".Company::is_company_exist("2");
?>