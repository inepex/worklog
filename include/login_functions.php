<?php
if (  $_GET['log'] =="logout" ) {
	// kilépés
	$_SESSION['loggedin_worklog_admin']="false";
	$_SESSION['enterstatus']=0;
	$_SESSION['enterid']=0;
	header('Location:index.php');
}

if (  $_SESSION['loggedin_worklog_admin'] !="true" ) {

	// ha épp nincs belépve
	$_SESSION['loggedin_worklog_admin']="false";

	if(isset($_POST['entername'])) {

		// épp most jön a bejelentkezés

		$a='SELECT username,password,user_status FROM worklog_users';
		$eredmeny=mysql_query($a);
		while ($line = mysql_fetch_row($eredmeny)) {
			$pwd=md5($_POST['enterpassword']);
			if ($_POST['entername']=="$line[0]" && $pwd == "$line[1]") {

				$_SESSION['loggedin_worklog_admin']="true";
				$_SESSION['entername']=$_POST['entername'];

			}
		}

		if ($_SESSION['loggedin_worklog_admin']=="true") {
			// ha be van lépve
			$nev=$_SESSION['entername'];
			$b="SELECT id,user_status FROM worklog_users where worklog_users.username='$nev'";
			$eredmeny=mysql_query($b);
			while ($lines = mysql_fetch_row($eredmeny)) {
				$_SESSION['enterid']=$lines[0];
				$_SESSION['enterstatus']=$lines[1];
			}
			$enterdate = date("Y-m-d");
			$entertime=date("G:i:s");
			$login="update worklog_users set enterdate='$enterdate $entertime'  where id='$_SESSION[enterid]';";
			mysql_query($login);
		}

	}
}

 
?>