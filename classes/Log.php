<?php 
class Log{
	public static $LISTING_LIMIT = 20;
	private $id;
	private $create_date;
	private $project_id;
	private $category_assoc_id;
	private $user_id;
	private $date;
	private $from;
	private $to;
	private $entry;
	private $working_place_id;
	private $efficiency_id;

	public static function get_sum_time_of_logs($user_id="", $date="",$company_id=""){
		$user_condition    = "";
		$date_condition    = "";
		$company_condition = "";
		if($user_id != "" && User::is_exist($user_id)){
			$user_condition = " AND worklog_log.worklog_user_id = ".$user_id;
		}
		if($date != ""){
			$date_array = date_parse($date);
			if($date_array['year'] && $date_array['month'] && $date_array['day']){
				$date = new DateTime($date_array['year'].'-'.$date_array['month'].'-'.$date_array['day']);
				$date->modify("first day of this month");
				$from_date = $date->format("Y-m-d");
				$date->modify("last day of this month");
				$to_date = $date->format("Y-m-d");
				$date_condition = " AND worklog_log.log_date >= '".$from_date."' AND worklog_log.log_date <= '".$to_date."'";
			}
		}
		if($company_id != "" && Company::is_company_exist($company_id)){
			$company_condition = " AND worklog_projects.worklog_company_id = ".$company_id;
		}
		//$query ='select SEC_TO_TIME(SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from))) sum_time, connected.worklog_user_id, connected.worklog_company_id from (SELECT worklog_log.worklog_user_id,worklog_log.log_from, worklog_log.log_to, worklog_projects.worklog_company_id FROM `worklog_log`, worklog_projects WHERE worklog_log.worklog_project_id = worklog_projects.worklog_project_id'.$user_condition.$company_condition.$date_condition.') as connected group by connected.worklog_user_id, connected.worklog_company_id';
		$query ='select SEC_TO_TIME(SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from))) sum_time, connected.worklog_user_id, connected.worklog_company_id, connected.log_date, YEAR(connected.log_date) log_year, MONTH(connected.log_date) log_month from (SELECT worklog_log.worklog_user_id,worklog_log.log_from, worklog_log.log_to, worklog_projects.worklog_company_id, worklog_log.log_date FROM `worklog_log`, worklog_projects WHERE worklog_log.worklog_project_id = worklog_projects.worklog_project_id'.$user_condition.$company_condition.$date_condition.' order by worklog_log.worklog_user_id ASC, log_date ASC) as connected group by connected.worklog_user_id, connected.worklog_company_id,YEAR(connected.log_date), MONTH(connected.log_date)';
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 0){
			return false;
		}
		else{
			$summary = array();
			while($row = mysql_fetch_assoc($select_result)){
				array_push($summary, $row);
			}
			return $summary;
		}

	}


	public static function get_sum_time_of_logs_on_a_selected_day($user_id="",$date="",$company_id=""){
		$company_condition = "";
		$user_condition    = "";
		if($user_id != "" && User::is_exist($user_id)){
			$user_condition = " AND worklog_log.worklog_user_id = ".$user_id;
		}
		$date_condition = "";
		if($date != ""){
			$date_condition = " AND worklog_log.log_date = '".$date."' ";
		}
		if($company_id != "" && Company::is_company_exist($company_id)){
			$company_condition = " AND worklog_projects.worklog_company_id = ".$company_id;
		}
		$query ='select SEC_TO_TIME(SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from))) sum_time from (SELECT worklog_log.log_from, worklog_log.log_to, worklog_log.log_date FROM `worklog_log`, worklog_projects WHERE worklog_log.worklog_project_id = worklog_projects.worklog_project_id'.$date_condition.$user_condition.$company_condition.' order by log_date ASC) as connected';
		$select_result = mysql_query($query);
		if(mysql_error() != ''){
			trigger_error(mysql_error());
		}
		if(mysql_affected_rows() == 0){
			return false;
		}
		else{
			$summary = mysql_fetch_assoc($select_result);
			if($summary['sum_time'] == null){
				return '00:00:00';
			}else{
				return $summary['sum_time'];
			}
		}
	}

	public static function get_sum_time_of_logs_in_a_selected_month($user_id="",$date="",$company_id=""){
		$company_condition = "";
		$user_condition    = "";
		if($user_id != "" && User::is_exist($user_id)){
			$user_condition = " AND worklog_log.worklog_user_id = ".$user_id;
		}
		if($company_id != "" && Company::is_company_exist($company_id)){
			$company_condition = " AND worklog_projects.worklog_company_id = ".$company_id;
		}

		if($date != ""){
			$date_array = date_parse($date);
			if($date_array['year'] && $date_array['month'] && $date_array['day']){
				$date = new DateTime($date_array['year'].'-'.$date_array['month'].'-'.$date_array['day']);
				$date->modify("first day of this month");
				$from_date = $date->format("Y-m-d");
				$date->modify("last day of this month");
				$to_date = $date->format("Y-m-d");
				$date_condition = " AND worklog_log.log_date >= '".$from_date."' AND worklog_log.log_date <= '".$to_date."'";
			}
		}
		$query ='select SEC_TO_TIME(SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from))) sum_time from (SELECT worklog_log.log_from, worklog_log.log_to, worklog_log.log_date FROM `worklog_log`, worklog_projects WHERE worklog_log.worklog_project_id = worklog_projects.worklog_project_id'.$date_condition.$user_condition.$company_condition.' order by log_date ASC) as connected';
		
		
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 0){
			return '00:00:00';
		}
		else{
			$summary = mysql_fetch_assoc($select_result);
			if($summary['sum_time'] == null){
				return '00:00:00';
			}else{
				return $summary['sum_time'];
			}
		}

	}

	public static function add_log($project_id, $category_assoc_id, $user_id, $date, $from, $to, $entry, $working_place_id, $efficiency_id){
		$query = "INSERT INTO worklog_log (worklog_project_id, worklog_category_assoc_id, worklog_user_id, log_date, log_from, log_to, log_entry, worklog_place_id, worklog_efficiency_id) VALUES ('".$project_id."','".$category_assoc_id."','".$user_id."','".$date."','".$from."','".$to."','".strip_tags(mysql_real_escape_string($entry))."','".$working_place_id."','".$efficiency_id."')";
		$insert_result = mysql_query($query);
		$id = mysql_insert_id();
		if(mysql_error() == ''){
			Notification::notice("The log added successfully!");
			return new Log($id);
		}
		else{
			trigger_error(mysql_error());
		}
	}
	public static function is_log_exist($log_id){
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_log_id = ".$log_id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows()>0){
			return true;
		}
		else{
			return false;
		}
	}
	public static function is_overlap($user_id ,$date, $from, $to,$log_id=""){
		$condition = "";
		if($log_id != "" && Log::is_log_exist($log_id)){
			$condition = " AND worklog_log_id != ".$log_id;
		}
		$number_of_rows = 0;
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND log_from < '".date("H:i:s", strtotime($from))."' AND log_to > '".date("H:i:s", strtotime($from))."'".$condition;//inside
		$select_result = mysql_query($query);
		$number_of_rows += mysql_num_rows($select_result);
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND log_from > '".date("H:i:s", strtotime($from))."' AND log_to < '".date("H:i:s", strtotime($to))."'".$condition;//outside
		$select_result = mysql_query($query);
		$number_of_rows += mysql_num_rows($select_result);
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND (log_from = '".date("H:i:s", strtotime($from))."' OR log_to = '".date("H:i:s", strtotime($to))."')".$condition;//same start or same end
		$select_result = mysql_query($query);
		$number_of_rows += mysql_num_rows($select_result);
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND ((log_from < '".date("H:i:s", strtotime($from))."' AND log_to > '".date("H:i:s", strtotime($from))."') OR (log_to > '".date("H:i:s", strtotime($to))."' AND log_from < '".date("H:i:s", strtotime($to))."'))".$condition;//fade
		$select_result = mysql_query($query);
		$number_of_rows += mysql_num_rows($select_result);
		if($number_of_rows>0){
			return true;
		}
		else{
			return false;
		}
	}
	public static function delete_log($log_id){
		$query = "DELETE FROM worklog_log WHERE worklog_log_id = ".$log_id;
		$delete_result = mysql_query($query);
		if(mysql_error() == ""){
			return true;
		}
		else{
			trigger_error(mysql_error());
		}
	}
	public static function get_first_log_date(){
		$query = "SELECT min(log_date) min_date FROM worklog_log";
		$select_result = mysql_query($query);
		if(mysql_affected_rows()  == 0){
			return '0000-00-00';
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			return $row['min_date'];
		}
	}
	public static function get_logs($user_id, $date){

		$logs = array();
		$result_array = date_parse($date);
		if($result_array['year'] && $result_array['month'] && $result_array['day']){
			$to = new DateTime($result_array['year']."-".$result_array['month']."-".$result_array['day']);
			$from = new DateTime($result_array['year']."-".$result_array['month']."-".$result_array['day']);
			$from->modify("first day of this month");
			$to->modify("last day of this month");
			$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date >= '".$from->format("Y-m-d")."' AND log_date <= '".$to->format("Y-m-d")."' order by log_date DESC, log_from DESC";
		}
		else{
			$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." order by log_date DESC, log_from DESC";
		}
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($logs, new Log($row['worklog_log_id']));
		}


		return $logs;


	}

	public static function export_logs($user_id, $date_from, $date_to){

		if ($user_id!=0) {
			$user_condition = "worklog_user_id = ".$user_id." AND";
		} else { $user_condition='';
		}

		$logs = array();

		$query = "SELECT worklog_log_id FROM worklog_log WHERE ".$user_condition." log_date >= '".$date_from."' AND log_date <= '".$date_to."' order by log_date DESC, log_from DESC";
			
			
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($logs, new Log($row['worklog_log_id']));
		}

			
		return $logs;

	}


	public function __construct($id){
		$query = "SELECT * FROM worklog_log WHERE worklog_log_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			trigger_error("Warning: the LOG id is not unique! Called with:".$id);
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->id                = $id;
			$this->create_date       = $row['create_date'];
			$this->project_id        = $row['worklog_project_id'];
			$this->category_assoc_id = $row['worklog_category_assoc_id'];
			$this->user_id           = $row['worklog_user_id'];
			$this->date              = $row['log_date'];
			$this->from              = $row['log_from'];
			$this->to                = $row['log_to'];
			$this->entry             = $row['log_entry'];
			$this->working_place_id  = $row['worklog_place_id'];
			$this->efficiency_id  = $row['worklog_efficiency_id'];
		}
	}
	public function get_project_id(){
		return $this->project_id;
	}
	public function get_id(){
		return $this->id;
	}
	public function get_create_date(){
		return $this->create_date;
	}
	public function get_category_assoc_id(){
		return $this->category_assoc_id;
	}
	public function get_user_id(){
		return $this->user_id;
	}
	public function get_date(){
		return $this->date;
	}
	public function get_from(){
		return $this->from;
	}
	public function get_to(){
		return $this->to;
	}
	public function get_entry(){
		return stripcslashes($this->entry);
	}
	public function get_working_place_id(){
		return $this->working_place_id;
	}
	public function get_efficiency_id(){
		return $this->efficiency_id;
	}
	public function is_editable($use_id){
		$date = new DateTime($this->date);
		$now = new DateTime();
		$interval = $now->diff($date);
		$difference = $interval->format('%R%a');
		if($this->user_id == $use_id && $difference <= 0 && $difference >= -4){
			return true;
		}
		else{
			return false;
		}
	}
	public function edit_log($project_id, $category_assoc_id, $date, $from, $to, $entry,$workplace_id,$efficiency_id){
		$query = "UPDATE worklog_log SET worklog_project_id = ".$project_id.",worklog_category_assoc_id = ".$category_assoc_id.", log_date = '".$date."', log_from = '".$from."', log_to = '".$to."', log_entry = '".strip_tags(mysql_real_escape_string($entry))."', worklog_place_id = ".$workplace_id." , worklog_efficiency_id = ".$efficiency_id." WHERE worklog_log_id = ".$this->id;
		$update_result = mysql_query($query);
	}
}
?>