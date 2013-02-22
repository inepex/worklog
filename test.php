<?php
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);
require_once 'classes/PhpConsole.php';
require_once 'classes/Notification.php';
require_once 'classes/Company.php';
PhpConsole::start();
require_once '../worklog-config.php';
require_once 'classes/User.php';
require_once 'classes/WorkPlace.php';
require_once 'include/notifications.php';


$places = WorkPlace::get_places();
foreach($places as $place){
	/* @var $place WorkPlace */
	echo $place->get_name()."<br>";
}
$users = User::get_users();
foreach($users as $user){
	echo $user->get_name()."<br>";
}
$user = new User(9);
echo "Personal note: ".$user->get_personal_note();
echo "Personal note: ".$user->get_personal_note();
$company = new Company(2);
$place = new WorkPlace(10);
echo "is_in_use:";
var_dump($place->is_in_use());
?>