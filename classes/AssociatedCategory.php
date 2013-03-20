<?php
error_reporting(E_ALL);
class AssociatedCategory extends Category{
	public static $NUMBER_OF_LOGS_TO_LIST;
	private $assoc_id;
	private $project_id;
	private $description;
	//private $estimate_time;
	public static function new_associated_category($project_id, $category_id, $category_description){
		$query = "INSERT INTO worklog_projects_category_assoc (worklog_project_id, worklog_category_id, category_description) VALUES ('".$project_id."','".$category_id."','".$category_description."')";
		$select_result = mysql_query($query);
		if(mysql_error() != ""){
			Notification::error(mysql_error());
		}
		else{
			return new AssociatedCategory(mysql_insert_id());
		}
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_projects_category_assoc WHERE worklog_projects_category_assoc_id = ".$id;
		$select_result = mysql_query($query);
		if(mysql_error() != ""){
			Notification::error(mysql_error());
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			parent::__construct($row['worklog_category_id']);
			$this->description = $row['category_description'];
			$this->project_id  = $row['worklog_project_id'];
			$this->assoc_id    = $id;
		}
	}
	public function get_description(){
		return $this->description;
	}
	public function get_project_id(){
		return $this->project_id;
	}
	public function get_assoc_id(){
		return $this->assoc_id;
	}
	public function get_users_with_planned_hours(){
		$users = array();
		$query = "SELECT worklog_user_id FROM worklog_project_plan WHERE worklog_project_id=".$this->project_id." AND category_assoc_id=".$this->assoc_id." AND plan_value>0";
		$result = mysql_query($query);
		while($row = mysql_fetch_assoc($result)){
			array_push($users, new User($row['worklog_user_id']));
		}
		return $users;
	}
	public function get_sum_of_worked_hours($user_id=""){
		$query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from))) FROM worklog_log WHERE worklog_category_assoc_id = ".$this->assoc_id;
		if($user_id != "" && User::is_exist($user_id)){
			$query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from))) FROM worklog_log WHERE worklog_category_assoc_id = ".$this->assoc_id." AND worklog_user_id = ".$user_id;
		}
		$select_result = mysql_query($query);
		$row = mysql_fetch_array($select_result);
		if($row[0] == NULL){
			return "0:00";
		}
		return substr($row[0], 0, -3);
	}
	public function get_category_status_in_percent($user_id="", $category_assoc_id=""){
			$project = new Project($this->project_id);
			$percent = 0;
			$work_time = $this->get_sum_of_worked_hours($user_id);
			$pieces = explode(":", $work_time);
			$worked_hours   = $pieces[0];
			$worked_minutes = $pieces[1];
			$sum_for_category = $project->get_project_plan()->get_sum_for_category($this->assoc_id, $user_id);
			if($sum_for_category != 0){
				$percent = round(($worked_hours*60+$worked_minutes)/(($sum_for_category*60)/100), 2);
			}
			return $percent;
	}
	public function is_associated_category_in_use(){
		$query = "SELECT worklog_log_id from worklog_log WHERE worklog_category_assoc_id = ".$this->assoc_id;
		$result = mysql_query($query);
		if(mysql_affected_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
}
?>