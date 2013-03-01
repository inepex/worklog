<?php 
class Log{
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
	
	public static function add_log($project_id, $category_assoc_id, $user_id, $date, $from, $to, $entry, $working_place_id){
		$query = "INSERT INTO worklog_log (worklog_project_id, worklog_category_assoc_id, worklog_user_id, log_date, log_from, log_to, log_entry, worklog_place_id) VALUES ('".$project_id."','".$category_assoc_id."','".$user_id."','".$date."','".$from."','".$to."','".$entry."','".$working_place_id."')";
		$insert_result = mysql_query($query);
		$id = mysql_insert_id();
		if(mysql_error() == ''){
			Notification::notice("The log added successfully!");
			return new Log($id);
		}
		else{
			Notification::error(mysql_error());
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
		mysql_query("SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND log_from < '".$from.":00' AND log_to > '".$from.":00'");
		debug(mysql_affected_rows());
// 		$condition = "";
// 		if($log_id != "" && Log::is_log_exist($log_id)){
// 			$condition = " AND worklog_log_id != ".$log_id;
// 		}
// 		$number_of_rows = 0;	
// 		$query1 = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND log_from < '".date("H:i:s", strtotime($from))."' AND log_to > '".date("H:i:s", strtotime($from))."'".$condition;//inside 
// 		$select_result = mysql_query($query1);
// 		while($row = mysql_fetch_assoc($select_result)){
// 			debug($row['worklog_log_id']);
// 		}
// 		$query1 = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND log_from < '".date("H:i:s", strtotime($from))."' AND log_to > '".date("H:i:s", strtotime($from))."'".$condition;//inside 
// 		$select_result = mysql_query($query1);
// 		while($row = mysql_fetch_assoc($select_result)){
// 			debug($row['worklog_log_id']);
// 		}
// 		$number_of_rows += mysql_num_rows($select_result);
// 		$query2 = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND log_from > '".date("H:i:s", strtotime($from))."' AND log_to < '".date("H:i:s", strtotime($to))."'".$condition;//outside
// 		$select_result = mysql_query($query2);
// 		while($row = mysql_fetch_assoc($select_result)){
// 			debug($row['worklog_log_id']);
// 		}
// 		$number_of_rows += mysql_num_rows($select_result);
// 		$query3 = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND (log_from = '".date("H:i:s", strtotime($from))."' OR log_to = '".date("H:i:s", strtotime($to))."')".$condition;//same start or same end
// 		$select_result = mysql_query($query3);
// 		while($row = mysql_fetch_assoc($select_result)){
// 			debug($row['worklog_log_id']);
// 		}
// 		$number_of_rows += mysql_num_rows($select_result);
// 		$query4 = "SELECT worklog_log_id FROM worklog_log WHERE worklog_user_id = ".$user_id." AND log_date = '".$date."' AND ((log_from < '".date("H:i:s", strtotime($from))."' AND log_to > '".date("H:i:s", strtotime($from))."') OR (log_to > '".date("H:i:s", strtotime($to))."' AND log_from < '".date("H:i:s", strtotime($to))."'))".$condition;//fade
// 		$select_result = mysql_query($query4);
// 		while($row = mysql_fetch_assoc($select_result)){
// 			debug($row['worklog_log_id']);
// 		}
// 		debug('num rows:'.$number_of_rows);
// 		$number_of_rows += mysql_num_rows($select_result);
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
			debug(mysql_error());
			//Notification::error(mysql_error());
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
	public function __construct($id){
		$query = "SELECT * FROM worklog_log WHERE worklog_log_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			debug("Warning: the LOG id is not unique!");
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
		return $this->entry;
	}
	public function get_working_place_id(){
		return $this->working_place_id;
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
	public function edit_log($project_id, $category_assoc_id, $date, $from, $to, $entry,$workplace_id){
		$query = "UPDATE worklog_log SET worklog_category_assoc_id = ".$category_assoc_id.", log_date = '".$date."', log_from = '".$from."', log_to = '".$to."', log_entry = '".$entry."', worklog_place_id = ".$workplace_id." WHERE worklog_log_id = ".$this->id;
		$update_result = mysql_query($query);
		debug(mysql_error());
	}
}
?>