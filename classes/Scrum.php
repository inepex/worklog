<?php 
class Scrum {
	private $id;
	private $user_id;
	private $month;
	private $past;
	private $future;
	
	public static function is_scrum_exist($user_id,$month){
		$query = "SELECT worklog_scrum_id FROM worklog_scrum WHERE month = '".$month."' AND user_id='".$user_id."'";
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 1){
			return true;
		}
		else{
			return false;
		}
	}
	public static function get_scrum_by_user($user_id){
		$scrum = array();
		$query = "SELECT worklog_scrum_id FROM worklog_scrum WHERE  user_id='".$user_id."' ORDER BY month DESC";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($scrum, new Scrum($row['worklog_scrum_id']));
		}
		return $scrum;
	}
	
	public static function get_scrum_by_month($month){
		$scrum = array();
		$query = "SELECT worklog_scrum_id FROM worklog_scrum WHERE month = '".$month."'";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($scrum, new Scrum($row['worklog_scrum_id']));
		}
		return $scrum;
	}
	
	
	public static function new_scrum($user_id,$month,$past,$future){
		
		if (Scrum::is_scrum_exist($user_id,$month)) {
			Notification::notice("Scrum for this month is alredy exist!");
			return false;
		
		} else {
		
		$query = "INSERT INTO worklog_scrum (user_id,month,past,future) VALUES ('".$user_id."','".$month."','".strip_tags(mysql_real_escape_string($past))."','".strip_tags(mysql_real_escape_string($future))."')";
		$insert_result = mysql_query($query);
		if(mysql_error() != ""){
			trigger_error(mysql_error());
			return false;
		}
		else{
			return new Scrum(mysql_insert_id());
		}
		}
	}

	
	 
	
	
	
	public function __construct($id){
		$query = "SELECT * FROM worklog_scrum WHERE worklog_scrum_id=".$id;
		$select_result = mysql_query($query);
		 
			$row = mysql_fetch_assoc($select_result);
			$this->id   = $id;
			$this->user_id = $row['user_id'];
			$this->past = $row['past'];
			$this->future = $row['future'];
			$this->month = $row['month'];
		 
	}
	public function edit_scrum($past,$future){
		$query = "UPDATE worklog_scrum SET past='".strip_tags(mysql_real_escape_string($past))."', future='".strip_tags(mysql_real_escape_string($future))."' WHERE worklog_scrum_id=".$this->id;
		$update_result = mysql_query($query);
		$this->past = $past;
		$this->future = $future;
		if(mysql_error()!=''){
			trigger_error(mysql_error());
		}
		else{
			Notification::notice("Updated successfully!");
		}
	}
	public function get_id(){
		return $this->id;
	}
	public function get_user_id(){
		return $this->user_id;
	}
	public function get_past(){
		return $this->past;
	}
	public function get_future(){
		return $this->future;
	}
	public function get_month(){
		return $this->month;
	}
	
 
}
?>