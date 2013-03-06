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
require_once 'classes/Log.php';
require_once 'classes/WorkPlace.php';
require_once 'include/notifications.php';
new Log(2);
?>