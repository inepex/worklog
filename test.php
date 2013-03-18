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
require_once 'classes/ProjectStatus.php';
require_once 'classes/WorkPlace.php';
require_once 'include/notifications.php';
echo "first: ".Log::get_first_log_date();
$user = new User(9);
$ret = $user->get_worked_hours_in_projects('2013-02-01');
var_dump($ret);
echo "az ido:".gmdate("H:i:s", $ret[1]['worked_hours']);
echo "<br><br><br><br>";
$ret = $user->get_worked_hours_in_categories('2013-02-01');
var_dump($ret);
?>