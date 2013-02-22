<?php 
class ProjectPlanEntry{
	private $id;
	private $project_id;
	private $user_id;
	private $category_assoc_id;
	private $value;
	
	public static function add_entry($project_id, $user_id, $category_assoc_id, $value){
		$query = "INSERT INTO worklog_project_plan (worklog_project_id, worklog_user_id, category_assoc_id,plan_value) VALUES ('".$project_id."', '".$user_id."', '".$category_assoc_id."', '".$value."')";
		$insert_result = mysql_query($query);
		return new ProjectPlanEntry(mysql_insert_id());
	}
	public function __construct($entry_id){
		$query = "SELECT * FROM worklog_project_plan WHERE worklog_project_plan_id = ".$entry_id;
		$select_result = mysql_query($query);
		$row = mysql_fetch_assoc($select_result);
		$this->id = $entry_id;
		$this->project_id  = $row['worklog_project_id'];
		$this->category_assoc_id = $row['category_assoc_id'];
		$this->user_id     = $row['worklog_user_id'];
		$this->value       = $row['plan_value'];
	}
	public function update_value($value){
		$query = "UPDATE worklog_project_plan SET plan_value = ".$value." WHERE worklog_project_plan_id = ".$this->id;
		$update_result = mysql_query($query);
		$this->value = $value;	
	}
	public function get_id(){
		return $this->id;
	}
	public function get_value(){
		return $this->value;
	}
	public function get_user_id(){
		return $this->user_id;
	}
	public function get_category_assoc_id(){
		return $this->category_assoc_id;
	}
	public function get_project_id(){
		return $this->project_id;
	}
}
?>