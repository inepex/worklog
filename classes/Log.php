<?php 
class Log{
	private $id;
	private $create_date;
	private $project;
	private $category;
	private $user;
	private $date;
	private $from;
	private $to;
	private $entry;
	private $working_place;
	
	public function get_logs(){
		$logs = array();
		$query = "SELECT worklog_log_id FROM worklog_log";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($logs, new Log($row['working_log_id']));
		}
		return $logs;
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_log WHERE worklog_log_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			debug("Warning: the LOG id is not unique!");
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->id            = $id;
			$this->create_date   = $row['create_date'];
			$this->project       = new Project($row['worklog_project_id']);
			$this->category      = new Category($row['worklog_category_id']);
			$this->user          = new User($row['worklog_user_id']);
			$this->date          = $row['log_date'];
			$this->from          = $row['log_from'];
			$this->to            = $row['log_to'];
			$this->entry         = $row['entry'];
			$this->working_place = new WorkPlace($row['worklog_place_id']);
		}
	}
}
?>