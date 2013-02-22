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
	require_once 'classes/AssociatedUser.php';
	require_once 'classes/AssociatedCategory.php';
	require_once 'classes/ProjectPlan.php';
	require_once 'classes/ProjectPlanEntry.php';
	require_once 'classes/Project.php';
	require_once 'classes/PhpConsole.php';
	include('include/login_functions.php');
	PhpConsole::start();
	error_reporting(E_ALL);
	$user_id = $_SESSION['enterid'];
	$user = new User($user_id);
	$user_name = $user->get_user_name();
	$user_picture = $user->get_picture();
	
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
	<link href="css/jquery/smoothness/jquery-ui-1.10.1.custom.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>   
	<script type="text/javascript" src="js/jquery.min.js"></script>   
	<script type="text/javascript" src="js/jquery-ui-1.10.1.custom.min.js"></script>   
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