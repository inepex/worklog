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
	public function get_id(){
		return $this->id;
	}
	public function get_name(){
		return $this->name;
	}
}
?>