<?php
require_once 'classes/PhpConsole.php';
PhpConsole::start();
if ( isset($_GET['log']) && $_GET['log'] =="logout" ) {
	// kilépés
	$_SESSION['loggedin_worklog']="false";
	$_SESSION['enterstatus']=0;
	$_SESSION['enterid']=0;
	header('Location:index.php');
}

if (  $_SESSION['loggedin_worklog'] !="true" ) {

	// ha épp nincs belépve
	$_SESSION['loggedin_worklog']="false";

	if(isset($_POST['entername'])) {

		// épp most jön a bejelentkezés

		$a='SELECT username,password,user_status FROM worklog_users';
		$eredmeny=mysql_query($a);
		while ($line = mysql_fetch_row($eredmeny)) {
			$pwd=md5($_POST['enterpassword']);
			if ($_POST['entername']=="$line[0]" && $pwd == "$line[1]") {

				$_SESSION['loggedin_worklog']="true";
				$_SESSION['enterusername']=$_POST['entername'];

			}
		}

		if ($_SESSION['loggedin_worklog']=="true") {
			// ha be van lépve
			$nev=$_SESSION['enterusername'];
			$b="SELECT worklog_user_id,user_status,name FROM worklog_users where worklog_users.username='$nev'";
			$eredmeny=mysql_query($b);
			while ($lines = mysql_fetch_row($eredmeny)) {
				$_SESSION['enterid']=$lines[0];
				$_SESSION['enterstatus']=$lines[1];
				$_SESSION['entername']=$lines[2];
			}
			$enterdate = date("Y-m-d");
			$entertime=date("G:i:s");
			$login="UPDATE worklog_users SET enterdate='$enterdate $entertime'  WHERE worklog_user_id='$_SESSION[enterid]';";
			mysql_query($login);
		}

	}
}

 
?>