<?php
require_once 'class.phpmailer.php';
function send_error_mail($errno, $errstr, $errfile, $errline, $errcontext){
	$active_user = User::get($_SESSION['enterid']);
	$mail_body = '<div>Felhasználó:'.$active_user->get_name().'</div>';
	$mail_body .= '<div>File:'.$errfile.'</div>';
	$mail_body .= '<div>Line:'.$errline.'</div>';
	$mail_body .= '<div>Error:'.$errstr.'</div>';
	
	$mail = new PHPMailer();
	$mail->Mailer = 'smtp';
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = '465';
	$mail->SMTPSecure = 'ssl';
	$mail->SMTPAuth = 'true';
	$mail->Username = 'noreply@inepex.com';
	$mail->Password = '8{yxfn"KUd4Q';
	$mail->SetFrom("noreply@inepex.com", "Worklog");
	$mail->AddAddress("dev@inepex.com", "Inepex Developers");
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
