<?php 
class ProjectStatus extends ObjectCache{
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
            $project_status = new ProjectStatus($row['status_code'], $row['status_name']);
            array_push($all_status, $project_status);
            $project_status->cache($project_status->get_code(), $project_status);
		}
		return $all_status;
	}
    public static  function get($status_code){
        $status = null;
        if(ProjectStatus::isCachedS($status_code, 'ProjectStatus')){
            $status = ProjectStatus::getCachedS($status_code, 'ProjectStatus');
        }else{
            $query = 'SELECT * FROM worklog_project_status WHERE status_code = '.$status_code;
            $select_result = mysql_query($query);
            if(mysql_affected_rows() != 1){
                Notification::error('Status code is not unique! Called with:'.$status_code);
            }
            else{
                $row = mysql_fetch_assoc($select_result);
                $status = new ProjectStatus($status_code, $row['status_name']);
                $status->cache($status->get_code(), $status);
            }
        }
        return $status;
    }
	public function __construct($status_code, $status_name){
			$this->objectName = 'ProjectStatus';
			$this->status_code = $status_code;
			$this->status_name = $status_name;
	}
	
	public function get_name(){
		return $this->status_name;
	}
	
	public function get_code(){
		return $this->status_code;
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