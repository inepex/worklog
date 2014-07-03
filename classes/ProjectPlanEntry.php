<?php 
class ProjectPlanEntry extends ObjectCache{
	private $id;
	private $project_id;
	private $user_id;
	private $category_assoc_id;
	private $value;
	
	public static function add_entry($project_id, $user_id, $category_assoc_id, $value){
		$query = "INSERT INTO worklog_project_plan (worklog_project_id, worklog_user_id, category_assoc_id,plan_value) VALUES ('".$project_id."', '".$user_id."', '".$category_assoc_id."', '".$value."')";
		$insert_result = mysql_query($query);
		return new ProjectPlanEntry(mysql_insert_id(),$project_id, $user_id, $category_assoc_id, $value);
	}
    public static function get($entry_id){
        $project_plan_entry = null;
        if(ProjectPlanEntry::isCachedS($entry_id, 'ProjectPlanEntry')){
            $project_plan_entry = ProjectPlanEntry::getCachedS($entry_id, 'ProjectPlanEntry');
        }else{
            $query = "SELECT * FROM worklog_project_plan WHERE worklog_project_plan_id = ".$entry_id;
            $select_result = mysql_query($query);
            $row = mysql_fetch_assoc($select_result);
            $project_plan_entry = new ProjectPlanEntry($entry_id,
                                                       $row['worklog_project_id'],
                                                       $row['worklog_user_id'],
                                                       $row['category_assoc_id'],
                                                       $row['plan_value']);
            $project_plan_entry->cache($entry_id, $project_plan_entry);
        }
        return $project_plan_entry;
    }
    public function __construct($entry_id, $project_id, $user_id, $category_assoc_id, $plan_value){
		$this->objectName = 'ProjectPlanEntry';
        $this->id                = $entry_id;
		$this->project_id        = $project_id;
		$this->category_assoc_id = $category_assoc_id;
		$this->user_id           = $user_id;
		$this->value             = $plan_value;
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