<?php 
	session_start();
	error_reporting(E_WARNING);
	header("Content-type: text/html; charset=utf-8");
	require_once '../worklog-config.php';
	require_once 'classes/User.php';
	require_once 'classes/WorkPlace.php';
	require_once 'classes/Notification.php';
	require_once 'classes/Company.php';
	require_once 'classes/Category.php';
	require_once 'classes/PhpConsole.php';
	include('include/login_functions.php');
	PhpConsole::start();
?>

<!DOCTYPE html>
<html>
<head>	
    <title>Worklog - <?php echo $title; ?></title>
    <link rel="icon" href="images/favicon.png" type="image/png">
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.css">
	<script type="text/javascript" src="js/jquery.min.js"></script>   
	<script src="js/bootstrap-dropdown.js"></script>
	<script src="js/globalFunctions.js"></script>
 	   
</head>
<body>
	<!-- PHP -->
	
	<?php foreach( $include_list[ 'head' ] as $inc_file ) include( $inc_file ) ?>
	<!-- PHP -->
	<?php
	if ($_SESSION['loggedin_worklog']=="true") {
		foreach( $include_list[ 'main' ] as $inc_file ) include( $inc_file );
	} else {
		include('pages/login.inc.php');
	}
	?>
	<!-- PHP -->
	<?php foreach( $include_list[ 'foot' ] as $inc_file ) include( $inc_file ) ?>
	
</body>