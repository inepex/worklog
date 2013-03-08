<?php 
error_reporting(E_ALL);
class Project{
	private $id;
	private $company;
	private $user;
	private $name;
	private $description;
	private $start_date;
	private $end_date;
	private $status;
	private $workmates  = array();
	private $categories = array();
	private $project_plan;
	public static function is_project_used($project_id){ //are logs in the project?
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_project_id = ".$project_id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 0){
			return false;
		}
		else{
			return true;
		}
	}
	public static function delete_project($project_id){
		if(!Project::is_project_used($project_id)){
			$project = new Project($project_id);
			$workmates = $project->get_workmates();
			foreach ($workmates as $workmate){
				$project->delete_workmate($workmate->get_assoc_id());
			}
			$categories = $project->get_categories();
			foreach ($categories as $category){
				$project->delete_category($category->get_assoc_id());
			}
			$query  = "DELETE FROM worklog_projects WHERE worklog_project_id = ".$project->get_id();
			$delete_result = mysql_query($query);
			unset($project);
			Notification::notice("Deleted successfully");
		}
		else{
			Notification::warn("There are logs in this project!");
		}
	}
	public static function get_projects(){
		$projects = array();
		$query = "SELECT worklog_project_id FROM worklog_projects";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($projects,new Project($row['worklog_project_id']));
		}
		return $projects;
	}
	public static function duplicate_project($project_id, $duplicate_name){
		$project = new Project($project_id);//befejezni
		$duplicated_project =  Project::new_project($duplicate_name, $project->get_company()->get_id(), $project->get_description(), $project->get_start_date(), $project->get_end_date(), $project->get_status(), $project->get_user()->get_id());
		$project_categories = $project->get_categories();
		foreach($project_categories as $project_category){
			/* @var $project_category AssociatedCategory */
			$duplicated_project->add_category($project_category->get_id(), $project_category->get_description());
		}
		$project_workmates = $project->get_workmates();
		foreach($project_workmates as $project_workmate){
			/* @var $project_workmate AssociatedUser */
			$duplicated_project->add_workmate($project_workmate->get_id());
		}
		//		$project_plan = $project->get_project_plan();//folytatni
		//		$entries = $project_plan->get_entries();
		// 		foreach($entries as $entry){
		// 			/* @var $entry ProjectPlanEntry */
		// 			$duplicated_project->project_plan->add_entry($entry->get_user_id(),$entry-> $category_assoc_id, $entry->get_value());
		// 		}
		return $duplicated_project;
	}
	public static function get_projects_which_contain_category($user_id){
		$projects = array();
		$query = "SELECT worklog_project_id FROM worklog_projects";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			$project = new Project($row['worklog_project_id']);
			$user = $project->get_user();
			$workmates = $project->get_workmates();
			if(count($project->get_categories()) > 0 && ($user->get_id() == $user_id || $project->is_user_workmate($user_id))){
				array_push($projects,$project);
			}
		}
		return $projects;
	}
	public static function is_project_exist($project_id){
		$query = "SELECT worklog_project_id FROM worklog_projects WHERE worklog_project_id = ".$project_id;
		$select_result = mysql_query($query);
		if(mysql_error() != ""){
			Notification::error(mysql_error());
			return false;
		}
		else{
			if(mysql_affected_rows() > 0){
				return true;
			}
			else{
				return false;
			}
		}
	}
	public static function new_project($name,$company_id,$description,$start,$deadline,$status,$user_id){
		$query = "INSERT INTO worklog_projects (worklog_company_id, worklog_user_id, project_name, project_description, start_date, end_date, project_status) VALUES ('".$company_id."','".$user_id."','".$name."','".$description."','".$start."','".$deadline."','".$status."')";
		$insert_result = mysql_query($query);
		if(mysql_error() != ""){
			Notification::error(mysql_error());
			return false;
		}
		else{

			return new Project(mysql_insert_id());
		}
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_projects WHERE worklog_project_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			debug("Warning: the id is not unique!");
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->id           = $id;
			$this->company      = new Company($row['worklog_company_id']) ;
			$this->user         = new User($row['worklog_user_id']);
			$this->name         = $row['project_name'];
			$this->description  = $row['project_description'];
			$this->start_date   = $row['start_date'];
			$this->end_date     = $row['end_date'];
			$this->status       = $row['project_status'];
			$this->project_plan = new ProjectPlan($this->id);
		}
		//workmates
		$query = "SELECT * FROM worklog_projects_user_assoc WHERE worklog_project_id=".$this->id;
		$select_result = mysql_query($query);

		while($row = mysql_fetch_assoc($select_result)){
			array_push($this->workmates, new AssociatedUser($row['worklog_projects_user_assoc_id']));
		}
		//categories
		$query = "SELECT worklog_projects_category_assoc_id FROM worklog_projects_category_assoc WHERE worklog_project_id=".$this->id;
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($this->categories, new AssociatedCategory($row['worklog_projects_category_assoc_id']));
		}
	}
	public function get_id(){
		return $this->id;
	}
	public function get_company(){
		return $this->company;
	}
	public function get_user(){
		return $this->user;
	}
	public function get_name(){
		return $this->name;
	}
	public function get_description(){
		return $this->description;
	}
	public function get_start_date(){
		return $this->start_date;
	}
	public function get_end_date(){
		return $this->end_date;
	}
	public function get_workmates(){
		return $this->workmates;
	}
	public function get_categories(){
		return $this->categories;
	}
	public function get_status(){
		return $this->status;
	}
	public function get_project_plan(){
		return $this->project_plan;
	}
	public function update($name,$company_id,$description,$start,$deadline,$status,$user_id){
		$user = new User($user_id);
		if($user_id != $this->get_user()->get_id() && !$user->is_admin()){
			Notification::warn("You do not have permission to do this operation!");
			return false;
		}
		else{
			$query = "UPDATE worklog_projects SET worklog_company_id=".$company_id.", project_name='".$name."', project_description='".$description."', start_date='".$start."', end_date='".$deadline."', project_status=".$status." WHERE worklog_project_id = ".$this->id;
			$update_result = mysql_query($query);
			if(mysql_error() != ""){
				Notification::error(mysql_error());
				return false;
			}
			else{
				$this->company      = new Company($company_id);
				$this->name         = $name;
				$this->description  = $description;
				$this->start_date   = $start;
				$this->end_date     = $deadline;
				$this->status       = $status;
				return true;
			}
		}
	}
	public function is_user_workmate($user_id){
		$query = "SELECT * FROM worklog_projects_user_assoc WHERE worklog_user_id = ".$user_id." AND worklog_project_id = ".$this->id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows()>0){
			return true;
		}
		else{
			return false;
		}
	}
	public function add_workmate($wormate_id){
		if(!$this->is_user_workmate($wormate_id)){
			$query = "INSERT INTO worklog_projects_user_assoc (worklog_project_id, worklog_user_id) VALUES ('".$this->id."', '".$wormate_id."')";
			$insert_result = mysql_query($query);
			array_push($this->workmates, new AssociatedUser(mysql_insert_id()));
		}
		else{
			Notification::warn("User already workmate!");
		}
	}
	public function delete_workmate($workmate_assoc_id){
		$associated_user = new AssociatedUser($workmate_assoc_id);
		$this->project_plan->delete_entry_of_user($associated_user->get_id());
		$query = "DELETE FROM worklog_projects_user_assoc WHERE worklog_projects_user_assoc_id = ".$workmate_assoc_id;
		$delete_result = mysql_query($query);
		//
		foreach($this->workmates as $key =>$workmate){
			if($workmate->get_assoc_id() == $workmate_assoc_id)	{
				unset($this->workmates[$key]);
			}
		}
		//
	}
	public function add_category($category_id, $description){
		$query = "INSERT INTO worklog_projects_category_assoc (worklog_project_id, worklog_category_id, category_description) VALUES ('".$this->id."', '".$category_id."', '".$description."')";
		$insert_result = mysql_query($query);
		array_push($this->categories, new AssociatedCategory(mysql_insert_id()));
	}
	public function delete_category($category_assoc_id){
		$associated_category = new AssociatedCategory($category_assoc_id);
		$this->project_plan->delete_entries_of_category($category_assoc_id);
		$query = "DELETE FROM worklog_projects_category_assoc WHERE worklog_projects_category_assoc_id = ".$category_assoc_id;
		$delete_result = mysql_query($query);
		foreach($this->categories as $key =>$category){
			if($category->get_assoc_id() == $category_assoc_id)	{
				unset($this->categories[$key]);
			}
		}
	}
	public function is_associated_category_in_project($category_assoc_id){
		foreach($this->categories as $category){
			/* @var $category AssociatedCategory */
			if($category->get_assoc_id() == $category_assoc_id){
				return true;
			}
		}
		return false;
	}
	public function get_logs_of_project($user_id = "", $category_assoc_id = "",$date_from, $date_to, $logs_from = 0){
		$user_condition = "";
		if($user_id != "" && User::is_exist($user_id)){
			$user_condition = " AND worklog_user_id = ".$user_id;
		}
		$category_condition = "";
		if($category_assoc_id != ""){
			$category_condition = " AND worklog_category_assoc_id = ".$category_assoc_id;
		}
		$limit_condition = "";
		if($logs_from != 0){
			$limit_condition = " limit ".$logs_from.", ".($logs_from+Log::$LISTING_LIMIT);
		}
		$date_from_condition = "";
		if($date_from != 0 && date_parse($date_from)){
			$date_from_condition = " AND log_date>='".$date_from."'";
		}
		$date_to_condition = "";
		if($date_to != 0 && date_parse($date_to)){
			$date_to_condition = " AND log_date<='".$date_to."'";
		}
		$query  = "SELECT worklog_log_id FROM worklog_log WHERE worklog_project_id = ".$this->id.$category_condition.$user_condition.$limit_condition.$date_from_condition.$date_to_condition;
		$select_result = mysql_query($query);
		$logs = array();
		while($row = mysql_fetch_assoc($select_result)){
			array_push($logs, new Log($row['worklog_log_id']));
		}
		return $logs;
	}
}
?>