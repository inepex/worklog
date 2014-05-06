<?php
session_set_cookie_params(86400);
ini_set('session.gc_maxlifetime', 86400);
session_start();
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
require_once '../worklog-config.php';
require_once 'classes/User.php';
require_once 'classes/WorkPlace.php';
require_once 'classes/Notification.php';
require_once 'classes/Company.php';
require_once 'classes/Efficiency.php';
require_once 'classes/Category.php';
require_once 'classes/AssociatedUser.php';
require_once 'classes/AssociatedCategory.php';
require_once 'classes/ProjectPlan.php';
require_once 'classes/ProjectStatus.php';
require_once 'classes/ProjectPlanEntry.php';
require_once 'classes/Project.php';
require_once 'classes/StatusBar.php';
require_once 'classes/Tools.php';
require_once 'classes/Log.php';
require_once 'classes/Scrum.php';
include('include/login_functions.php');
//help for debug
require_once 'classes/PhpConsole.php';
PhpConsole::start();
error_reporting(E_ALL);
require_once 'include/mail/class.phpmailer.php';



$user_id=null;
$user_name=null;
$success=false;
$message=null;

$sql = "SELECT * FROM worklog_users WHERE user_status > 0";
$select_result = mysql_query($sql);

while($row = mysql_fetch_assoc($select_result)){
	
	echo $row['worklog_user_id']."--".$row['username']."<br/>";
	
	
	$todayDate = new DateTime("now");
	
	$sendmail = "true";
	
	for ($i=1;$i<4;$i++) {
	
		$sql2 = "SELECT * FROM worklog_log WHERE worklog_user_id=".$row['worklog_user_id']." AND log_date='".$todayDate->format("Y-m-d")."'";
	
		$select_result2 = mysql_query($sql2);
		
		if (mysql_num_rows($select_result2)!=0) {$sendmail = "false";}
		echo  $todayDate->format("Y-m-d")."--".mysql_num_rows($select_result2)."<br/>";
	
		$todayDate->modify("-1 day");
	}
	 
	echo "sendmail: ".$sendmail."<br/>";
	
	 
	
	// LEVÉL KÜLDÉS 

	if ($sendmail=="true") {
		$mail = new PHPMailer();
		$mail->Mailer = 'smtp';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '465';
		$mail->SMTPSecure = 'ssl';
		$mail->SMTPAuth = 'true';
		$mail->Username = 'worklog@inepex.com';
		$mail->Password = 'ine123pex';
		$mail->SetFrom("worklog@inepex.com", "Worklog");
		$mail->AddAddress($row['email'], $row['name']);
		
		$mail->IsHTML(true);
		$mail->Subject = "Ejnye ".$row['name'].", 3 napja nem töltöd a Worklogot!";
		
		$mail->Body = "Kedves ".$row['name']."! <br/><br/> Felhívjuk a figyelmed, hogy úgy látjuk, 3 napja nem töltötted ki a Worklogot. <br/> Kérjük, ezt mielőbb pótold a http://worklog.polgarhaz.hu oldalon, mert csak 4 napra visszamenőleg tudod ezt megtenni. <br/>Ha szabadságon vagy, kérjük tekintsd levelünket tárgytalannak. :) <br/><br/>Üdvözlettel:<br/>Worklog";
	 
		$mail->CharSet = "UTF-8";
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		}
		
	}
	echo "<hr/>";
	
}




 

?>