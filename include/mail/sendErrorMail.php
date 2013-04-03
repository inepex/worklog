<?php
require_once 'class.phpmailer.php';
function send_error_mail($errno, $errstr, $errfile, $errline, $errcontext){
	$mail_body = '<font color="red">Worklog error!</font>';
	$mail_body .= '<font>File:'.$errfile.'</font>';
	$mail_body .= '<font>Line:'.$errline.'</font>';
	$mail_body .= '<font>Error:'.$errstr.'</font>';
	
	$mail = new PHPMailer();
	$mail->Mailer = 'smtp';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = '465';
	$mail->SMTPSecure = 'ssl';
	$mail->SMTPAuth = 'true';
	$mail->Username = 'sales@inepex.com';
	$mail->Password = 'ine123track';
	$mail->SetFrom("sales@inetrack.hu", "IneTrack");
	$mail->AddAddress("tibor.hidi@inepex.com", "Hidi Tibor");
	$mail->IsHTML(true);
	$mail->Subject = "Worklog error";
	$mail->Body = $mail_body;
	$mail->AltBody = 'File:'.$errfile.'. Line:'.$errline.'. Error:'.$errstr;
	$mail->CharSet = "UTF-8";
	if(!$mail->Send()) {
		echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	}
}
?>