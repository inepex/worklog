<?php
session_set_cookie_params(86400);
ini_set('session.gc_maxlifetime', 86400);
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
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
error_reporting(E_ALL ^ E_DEPRECATED);
require_once 'include/mail/class.phpmailer.php';
	
/* Daily alert email */

$user_id=null;
$user_name=null;
$success=false;
$message=null;

$sql = "SELECT * FROM worklog_users WHERE user_status > 0";
$select_result = mysql_query($sql);

while($row = mysql_fetch_assoc($select_result)){

	echo $row['worklog_user_id']."--".$row['username']."<br/>";

	$todayDate = new DateTime("now");
	
	$simpleDate = date("y-m-d");
	$currentYear = date("y");
	$day = date("w");
	$holidays = array ($currentYear.'-01-01',$currentYear.'-03-15',$currentYear.'-05-01',$currentYear.'-08-20',$currentYear.'-10-23',$currentYear.'-11-01',$currentYear.'-12-25',$currentYear.'-12-26');

	$sendmail = "false";
	if ($row['send_daily_alert']=='1') {$sendmail = "true";}

	echo "sendmail: ".$sendmail."<br/>";

	// LEVÉL KÜLDÉS

	if ($sendmail=="true" and ($day != 0 and $day != 6) and !in_array($simpleDate, $holidays, true)) {
		$mail = new PHPMailer();
		$mail->Mailer = 'smtp';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = '465';
		$mail->SMTPSecure = 'ssl';
		$mail->SMTPAuth = 'true';
		$mail->Username = 'noreply@inepex.com';
		$mail->Password = '8{yxfn"KUd4Q';
		$mail->SetFrom("noreply@inepex.com", "Worklog");
		$mail->AddAddress($row['email'], $row['name']);

		$mail->IsHTML(true);
		$mail->Subject = "Ne felejtsd el kitölteni a Worklogot!";

		$mail->Body = "Kedves ".$row['name']."! <br/><br/> Ezt a levelet azért kaptad, hogy emlékeztessünk a Worklog kitöltésére. <br/> Kérjük, ha ma még nem töltötted ki, látogass el a http://worklog.polgarhaz.hu oldalra, mert csak 4 napra visszamenőleg tudod ezt megtenni.   <br/><br/>Üdvözlettel:<br/>Worklog";

		$mail->CharSet = "UTF-8";
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		}

	}
	echo "<hr/>";

}

?>
