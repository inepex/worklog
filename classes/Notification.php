<?php 
class Notification{
	private $type;
	private $message;
	private $enter_id;
	/*
	 * $type:
	 * 		1 - notice
	 * 		2 - warn
	 * 		3 - error
	 * */
	public static function get_notifications(){
		$notifications = array();
		$query = "SELECT * FROM worklog_notifications WHERE enter_id='".$_SESSION['enterid']."'";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
				array_push($notifications, new Notification($row['notification_type'], $row['notification_message']));
		}
		return $notifications;
	}
	public static function clear_notifications(){
		$query = "DELETE FROM worklog_notifications";
		$select_result = mysql_query($query);
	}
	public static function error($message){
		Notification::new_notification("3", $message);
	}
	public static function warn($message){
		Notification::new_notification("2", $message);
	}
	public static function notice($message){
		Notification::new_notification("1", $message);
	}
	private static function new_notification($type, $message){
		$query = "INSERT INTO worklog_notifications (notification_type, notification_message, enter_id) VALUES ('".$type."','".$message."', '".$_SESSION['enterid']."')";
		mysql_query($query);
	}
	public function __construct($type, $message){
		$this->type = $type;
		$this->message = $message;	
	}
	public function get_notification(){
		switch($this->type){
			case '1':
				return $this->get_notice();
				break;
			case '2':
				return $this->get_warn();
				break;
			case '3':
				return $this->get_error();
				break;
		}
	}
	private function get_error(){
		return '<div class="alert alert-error"><img src="images/error.png"/>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Error! </strong> '.$this->message.'
		</div>';
	}
	private function get_warn(){
		return '<div class="alert"><img src="images/warning.png"/>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong> Warning!</strong> '.$this->message.'
				</div>';
	}
	private function get_notice(){
		return '<div class="alert alert-success"><img src="images/notice.png"/>
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Notice! </strong> '.$this->message.'
				</div>';
	}
}
?>