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
echo "<br>";
echo date("Y-m-d")-date("Y-m-d", strtotime("-25 day"));
echo "-----------------------------------------";
$todayDate = date('2013-03-30');// current date
echo "Today: ".$todayDate."<br>";

//Add one day to today
$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "-1 month");

echo "After adding one month: ".date('Y-m-d', $dateOneMonthAdded)."<br>";
echo "-----------------------------------------";
$date = new DateTime("now");
echo $date->format("Y-m-d")."<br>";
$form  = $date;
$to  = $date;
//echo $form;
echo $form->modify( 'first day of this month' )->format("Y-m-d");
echo $to->modify( 'last day of this month' )->format("Y-m-d");
echo "---------------------------------------";
?>