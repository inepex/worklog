<?php
error_reporting(E_ALL ^ E_DEPRECATED);
class  AssociatedCategory extends Category{
	public static $NUMBER_OF_LOGS_TO_LIST;
	private $assoc_id;
	private $project_id;
	private $description;
	public static function is_associated_category_exist($associated_category_id){
		$query = 'SELECT worklog_projects_category_assoc_id from worklog_projects_category_assoc WHERE worklog_projects_category_assoc_id = '.$associated_category_id;
		$result = mysql_query($query);
		if(mysql_affected_rows() == 1){
			return true;
		}
		else{
			return false;
		}
	}
	public static function new_associated_category($project_id, $category_id, $category_description){
		$query = "INSERT INTO worklog_projects_category_assoc (worklog_project_id, worklog_category_id, category_description) VALUES ('".$project_id."','".$category_id."','".$category_description."')";
		$select_result = mysql_query($query);
		if(mysql_error() != ""){
			trigger_error(mysql_error());
		}
		else{
			return new AssociatedCategory(mysql_insert_id(), Category::get($category_id), $category_description,$project_id);
		}
	}
    public static function get($id){
        $associated_category = null;
        if(AssociatedCategory::isCachedS($id,'AssociatedCategory')){
            $associated_category = AssociatedCategory::getCachedS($id, 'AssociatedCategory');
        }else{
            $query = "SELECT * FROM worklog_projects_category_assoc WHERE worklog_projects_category_assoc_id = ".$id;
            $select_result = mysql_query($query);
            if(mysql_error() != ""){
                trigger_error(mysql_error());
            }
            else{
                $row = mysql_fetch_assoc($select_result);
                $category = Category::get($row['worklog_category_id']);
                $associated_category = new AssociatedCategory($id, $category,$row['category_description'], $row['worklog_project_id']);
                $associated_category->cache($associated_category->get_id(), $associated_category);
            }
        }
        return $associated_category;
    }
	public function __construct($id, Category $category,$description, $project_id){
            $this->objectName = 'AssociatedCategory';
            $this->assoc_id    = $id;
			$this->description = $description;
			$this->project_id  = $project_id;
            $this->id   = $category->get_id();
            $this->name = $category->get_name();
            $this->category_status = $category->get_category_status();
	}
	public function get_description(){
		return $this->description;
	}
	public function update_description($new_description){
		$this->description = $new_description;
		$query = 'UPDATE worklog_projects_category_assoc SET category_description="'.strip_tags(mysql_real_escape_string($new_description)).'" WHERE worklog_projects_category_assoc_id = '.$this->assoc_id;
		$result = mysql_query($query);
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
			array_push($users, User::get($row['worklog_user_id']));
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
			$project = Project::get($this->project_id);
			$percent = 0;
			$work_time = $this->get_sum_of_worked_hours($user_id);
			$pieces = explode(":", $work_time);
			$worked_hours   = $pieces[0];
			$worked_minutes = $pieces[1];
			$sum_for_category = $project->get_project_plan()->get_sum_for_category($this->assoc_id, $user_id);
			if($sum_for_category != 0){
				$percent = round(($worked_hours*60+$worked_minutes)/(($sum_for_category*60)/100), 2);
			}
			elseif($sum_for_category == 0 && $worked_hours*60+$worked_minutes>0){
				$percent =100;
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
	public function is_user_have_planned_hours($user){
		foreach($this->get_users_with_planned_hours() as $u){
			if($u->get_id() == $user->get_id()){
				return true;
			}
		}
		return false;
	}
}
?>