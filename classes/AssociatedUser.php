<?php 
class AssociatedUser extends User{
	private $assoc_id;
	private $project_id;
	
	public function __construct($assoc_id){
		$query = "SELECT * FROM worklog_projects_user_assoc WHERE worklog_projects_user_assoc_id = ".$assoc_id;
		$select_result = mysql_query($query);
		$row = mysql_fetch_assoc($select_result);
		parent::__construct($row['worklog_user_id']);
		$this->project_id = $row['worklog_project_id'];
		$this->assoc_id = $assoc_id;
	}
	public function is_have_log_in_project(){
		$query = "SELECT worklog_log_id FROM worklog_log where worklog_user_id = ".$this->id." AND worklog_project_id = ".$this->project_id;
		$result = mysql_query($query);
		if(mysql_affected_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	public function get_assoc_id(){
		return $this->assoc_id;
	}
	public function get_project_id(){
		return $this->project_id;
	}
}

?>