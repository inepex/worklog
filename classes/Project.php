<?php 
class Project{
	private $id;
	private $company;
	private $user;
	private $name;
	private $description;
	private $start_date;
	private $end_date;
	private $status;
	
	public function __construct($id){
		$query = "SELECT * FROM worklog_projects WHERE worklog_project_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			debug("Warning: the id is not unique!");
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->id          = $id;
			$this->company     = new Company($row['worklog_company_id']) ;
			$this->user        = new User($row['worklog_user_id']);
			$this->name        = $row['project_name'];
			$this->description = $row['project_description'];
			$this->start_date  = $row['start_date'];
			$this->end_date    = $row['end_date'];
			$this->status      = $row['project_status'];
		}
	}
}
?>