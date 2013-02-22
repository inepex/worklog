<?php 
class Company{
	private $id;
	private $name;

	public static function get_companies(){
		$companies = array();
		$query = "SELECT worklog_company_id FROM worklog_companies order by company_name";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($companies, new Company($row['worklog_company_id']));
		}
		return $companies;
	}
	public static function new_company($company_name){
		$query = "INSERT INTO worklog_companies (company_name) VALUES ('".$company_name."')";
		$insert_result = mysql_query($query);
		if(mysql_error() != ""){
			Notification::error(mysql_error());
		}
		else{
			Notification::notice("Added successfully!");
		}
	}
	public static function  delete_company($company_id){
		$company = new Company($company_id);
		if(!$company->is_in_use()){
			$query = "DELETE FROM worklog_companies WHERE worklog_company_id=".$company_id;
			$delete_result = mysql_query($query);
			if(mysql_error() != ""){
				Notification::error(mysql_error());
			}
			else{
				Notification::notice("Deleted successfully!");
			}
		}
		else{
			Notification::warn("The company is in use!");
		}
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_companies WHERE worklog_company_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			debug("Warning: the COMPANY id is not unique!");
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->id   = $id;
			$this->name = $row['company_name'];
		}
	}
	public function edit_name($new_name){
		$query = "UPDATE worklog_companies SET company_name='".$new_name."' WHERE worklog_company_id=".$this->id;
		$update_result = mysql_query($query);
		$this->name = $new_name;
		if(mysql_error()!=''){
			Notification::error(mysql_error());
		}
		else{
			Notification::notice("Updated successfully!");
		}
	}
	public function get_id(){
		return $this->id;
	}
	public function get_name(){
		return $this->name;
	}
	public function is_in_use(){
		$query = "SELECT worklog_project_id FROM worklog_projects WHERE worklog_company_id = ".$this->id;
		$select_result = mysql_query($query);
		if(mysql_error()!=''){
			Notification::error(mysql_error());
		}
		else{
			if(mysql_affected_rows() == 0){
				return false;
			}
			else{
				return true;
			}
		}
	}
}
?>