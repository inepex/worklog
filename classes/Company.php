<?php 
class Company extends ObjectCache{
	private $id;
	private $name;
	public static function is_company_exist($company_id){
		$query = "SELECT worklog_company_id FROM worklog_companies WHERE worklog_company_id = ".$company_id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 1){
			return true;
		}
		else{
			return false;
		}
	}
	public static function get_companies(){
		$companies = array();
		$query = "SELECT * FROM worklog_companies order by company_name";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
            $company = new Company($row['worklog_company_id'], $row['company_name']);
			array_push($companies, $company);
            $company->cache($company->get_id(), $company);
		}
		return $companies;
	}
	public static function new_company($company_name){
		$query = "INSERT INTO worklog_companies (company_name) VALUES ('".strip_tags(mysql_real_escape_string($company_name))."')";
		$insert_result = mysql_query($query);
		if(mysql_error() != ""){
			trigger_error(mysql_error());
		}
		else{
			Notification::notice("Added successfully!");
		}
	}
	public static function  delete_company($company_id){
		$company = Company::get($company_id);
		if(!$company->is_in_use()){
			$query = "DELETE FROM worklog_companies WHERE worklog_company_id=".$company_id;
			$delete_result = mysql_query($query);
			if(mysql_error() != ""){
				trigger_error(mysql_error());
			}
			else{
				Notification::notice("Company deleted successfully!");
			}
		}
		else{
			Notification::warn("The company is in use!");
		}
	}
    public static function get($id){
        $company = null;
        if(Company::isCachedS($id, 'Company')){
            $company = Company::getCachedS($id, 'Company');
        }else{
            $query = "SELECT * FROM worklog_companies WHERE worklog_company_id=".$id;
            $select_result = mysql_query($query);
            if(mysql_affected_rows() != 1){
                trigger_error("Warning: the COMPANY id is not unique! Called with:".$id);
            }
            else{
                $row = mysql_fetch_assoc($select_result);
                $company = new Company($id, $row['company_name']);
                $company->cache($company->get_id(), $company);
            }
        }
        return $company;
    }
	public function __construct($id, $name){
            $this->objectName = 'Company';
			$this->id   = $id;
			$this->name = $name;
	}
	public function edit_name($new_name){
		$query = "UPDATE worklog_companies SET company_name='".strip_tags(mysql_real_escape_string($new_name))."' WHERE worklog_company_id=".$this->id;
		$update_result = mysql_query($query);
		$this->name = $new_name;
		if(mysql_error()!=''){
			trigger_error(mysql_error());
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
			trigger_error(mysql_error());
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