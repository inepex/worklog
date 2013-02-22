<?php
error_reporting(E_ALL);
class AssociatedCategory extends Category{
	private $assoc_id;
	private $project_id;
	private $description;
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
}
?>