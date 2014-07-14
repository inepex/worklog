<?php 
class ProjectPlan extends ObjectCache{
	private $project_id;
	private $entries = array();

    public static function getByProject(Project $project){
        $query = "SELECT * FROM worklog_project_plan WHERE worklog_project_id = ".$project->get_id();
        $select_result = mysql_query($query);
        $project_plan_entries = array();
        while($row = mysql_fetch_assoc($select_result)){
            array_push($project_plan_entries, ProjectPlanEntry::get($row['worklog_project_plan_id']));
        }
        return new ProjectPlan($project, $project_plan_entries);
    }
	public function __construct(Project $project, array $project_plan_entries){
        $this->objectName = 'ProjectPlan';
        $this->project_id = $project->get_id();
		$this->entries = $project_plan_entries;
	}
	public function get_entries(){
		return $this->entries;
	}
	public function get_entries_for_user(){
		//TODO:befejezni
	}
	public function get_entries_for_category(){
		//TODO:befejezni
	}
	public function add_entry($user_id, $category_assoc_id, $value){
		$query = "SELECT worklog_project_plan_id FROM worklog_project_plan WHERE worklog_project_id = ".$this->project_id." AND worklog_user_id = ".$user_id." AND category_assoc_id = ".$category_assoc_id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 1){
			for($i=0; $i<count($this->entries); $i++){
				if($this->entries[$i]->get_user_id() == $user_id && $this->entries[$i]->get_category_assoc_id() == $category_assoc_id){
					$this->entries[$i]->update_value($value);
				}
			}
		}
		else if(mysql_affected_rows() == 0){
			array_push($this->entries, ProjectPlanEntry::add_entry($this->project_id, $user_id, $category_assoc_id, $value));
		}
		else{
			Notification::error("Inconsistent project_plan table!");
		}
	}
	public function get_sum_for_category_and_user($user_id, $category_assoc_id){
		$sum = 0;
		foreach($this->entries as $entry){
			/* @var $entry ProjectPlanEntry */
			if($entry->get_user_id() == $user_id && $entry->get_category_assoc_id() == $category_assoc_id){
				$sum += $entry->get_value();
			}
		}
		return $sum;
	}
	public function get_sum_for_user($user_id){
		$sum = 0;
		foreach($this->entries as $entry){
			/* @var $entry ProjectPlanEntry */
			if($entry->get_user_id() == $user_id){
				$sum += $entry->get_value();
			}
		}
		return $sum;
	}
	public function get_sum_for_category($category_assoc_id, $user_id=""){
		$sum = 0;
		foreach($this->entries as $entry){
			/* @var $entry ProjectPlanEntry */
			if($entry->get_category_assoc_id() == $category_assoc_id ){
				if($user_id != "" && User::is_exist($user_id)){
					if($entry->get_user_id()== $user_id){
						$sum += $entry->get_value();
					}
				}
				else{
					$sum += $entry->get_value();
				}	
			}
		}
		return $sum;
	}
	public function get_sum_of_entries($user_id=""){
		$sum = 0;
		foreach($this->entries as $entry){
			/* @var $entry ProjectPlanEntry */
			if($user_id != "" && User::is_exist($user_id)){
				if($entry->get_user_id() == $user_id){
					$sum += $entry->get_value();
				}	
			}
			else{
				$sum += $entry->get_value();
			}
		}
		return $sum;
	}
	public function delete_entry_of_user($user_id){
		$query = "DELETE FROM worklog_project_plan WHERE worklog_user_id = ".$user_id." AND worklog_project_id = ".$this->project_id;
		$delete_result = mysql_query($query);
		for($i=0; $i<count($this->entries); $i++){
			if(isset($this->entries[$i]) && $this->entries[$i]->get_user_id() == $user_id){
				unset($this->entries[$i]);
			}
		}
	}
	public function delete_entries_of_category($category_assoc_id){
		$query = "DELETE FROM worklog_project_plan WHERE category_assoc_id = ".$category_assoc_id." AND worklog_project_id = ".$this->project_id;
		$delete_result = mysql_query($query);
		for($i=0; $i<count($this->entries); $i++){
			if(isset($this->entries[$i]) && $this->entries[$i]->get_category_assoc_id() == $category_assoc_id){
				unset($this->entries[$i]);
			}
		}
	}
    public function set_from_cache(){

    }
    /**
     * Set the current object from a cached copy.
     * @param $object mixed The cached object.
     * @return mixed Nothing.
     */
    protected function setFromCache($object)
    {
        // TODO: Implement setFromCache() method.
    }
}
?>