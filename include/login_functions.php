<?php
function update_session_paramaters() {
	$enterdate = date("Y-m-d");
	$entertime=date("G:i:s");
	$login="UPDATE worklog_users SET enterdate='$enterdate $entertime', session_id='".session_id()."'  WHERE worklog_user_id='$_SESSION[enterid]';";
	setcookie("worklog_session_id", session_id(), time()+60*60*24*100, "/");
	mysql_query($login);
}

function get_session_parameters ($session_id){
	$a="SELECT worklog_user_id,username,password,user_status,session_id,name FROM worklog_users WHERE session_id='".$session_id."';";
	$eredmeny=mysql_query($a);
	if (mysql_numrows($eredmeny)) {
		while ($line = mysql_fetch_assoc($eredmeny)) {
			$_SESSION['loggedin_worklog']="true";
			$_SESSION['enterstatus']=$line['user_status'];
			$_SESSION['enterid']=$line['worklog_user_id'];
			$_SESSION['entername']=$line['name'];
			update_session_paramaters();
		}
	} else {
		$_SESSION['loggedin_worklog']="false";
		$_SESSION['enterstatus']=0;
		$_SESSION['enterid']=0;
		$_SESSION['entername']=0;
	}	
}


if (isset($_COOKIE['worklog_session_id'])) { 
	get_session_parameters($_COOKIE['worklog_session_id']);	
} else {
	$_SESSION['loggedin_worklog']="false";
	$_SESSION['enterstatus']=0;
	$_SESSION['enterid']=0;
	$_SESSION['entername']=0;	
}



if ( isset($_GET['log']) && $_GET['log'] =="logout" ) {
	// kilépés
	setcookie("worklog_session_id", '', time()+60*60*24*100, "/");
	$_SESSION['loggedin_worklog']="false";
	$_SESSION['enterstatus']=0;
	$_SESSION['enterid']=0;
	$_SESSION['entername']=0;
	
	$login="UPDATE worklog_users SET enterdate='$enterdate $entertime', session_id=''  WHERE worklog_user_id='$_SESSION[enterid]';";
	mysql_query($login);
	
	
	echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
	exit();
}

if(isset($_POST['entername'])) {
	login();
}
	
function login() {
		// épp most jön a bejelentkezés
		$a='SELECT username,password,user_status,worklog_user_id FROM worklog_users';
		$eredmeny=mysql_query($a);
		while ($line = mysql_fetch_row($eredmeny)) {
			$pwd=md5($_POST['enterpassword']);
			if ($_POST['entername']=="$line[0]" && $pwd == "$line[1]") {
				$_SESSION['enterid'] = $line[3];
				update_session_paramaters();
				
				get_session_parameters (session_id());

			}
		}

} 
 
?>