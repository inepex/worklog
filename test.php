<?php
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ALL);
require_once 'classes/PhpConsole.php';
PhpConsole::start();
require_once '../worklog-config.php';
require_once 'classes/User.php';
require_once 'classes/WorkPlace.php';


$places = WorkPlace::get_places();
foreach($places as $place){
	/* @var $place WorkPlace */
	echo $place->get_name()."<br>";
}
$users = User::get_users();
foreach($users as $user){
	echo $user->get_name()."<br>";
}
?>