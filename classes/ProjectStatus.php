<?php 
class ProjectStatus{
	private $status_code;
	private $status_name;
	public static function is_status_exist($status_code){
		$query = 'SELECT worklog_project_status_id FROM worklog_project_status WHERE status_code='.$status_code;
		$result = mysql_query($query);
		if(mysql_affected_rows() == 1){
			return true;
		}
		else{
			return false;
		}
	}
	public static function get_all_status(){
		$all_status = array();
		$query = 'SELECT * FROM worklog_project_status';
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($all_status, new ProjectStatus($row['status_code']));
		}
		return $all_status;
	}
	
	public function __construct($status_code){
		$query = 'SELECT * FROM worklog_project_status WHERE status_code = '.$status_code;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			Notification::error('Status code is not unique! Called with:'.$status_code);
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->status_code = $row['status_code'];
			$this->status_name = $row['status_name'];
		}		
	}
	
	public function get_name(){
		return $this->status_name;
	}
	
	public function get_code(){
		return $this->status_code;
	}
}
?>