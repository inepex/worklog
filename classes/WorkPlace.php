<?php
class WorkPlace{
	private $id;
	private $name;
	public static function add_new_work_place($place_name){
		$query = "INSERT INTO worklog_places (place_name) VALUES ('".$place_name."')";
		$insert_result = mysql_query($query);
		return new WorkPlace(mysql_insert_id());
	}
	public static function new_place($place_name){
		$query = "INSERT INTO worklog_places (place_name) VALUES ('".$place_name."')";
		$insert_result = mysql_query($query);
		if(mysql_error()!=''){
			Notification::error(mysql_error());
		}
		else{
			Notification::notice("Added successfully!");
		}
	}
	public static function  delete_work_place($place_id){
		$place = new WorkPlace($place_id);
		if(!$place->is_in_use()){
			$query = "DELETE FROM worklog_places WHERE worklog_place_id=".$place_id;
			$delete_result = mysql_query($query);
			if(mysql_error() != ""){
				Notification::error(mysql_error());
			}
			else{
				Notification::notice("Workplace deleted successfully!");
			}
		}
		else{
			Notification::warn("The place is in use!");
		}
	}
	public static function get_places(){
		$places = array();
		$query = "SELECT worklog_place_id FROM worklog_places order by place_name";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($places, new WorkPlace($row['worklog_place_id']));
		}
		return $places;
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_places WHERE worklog_place_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			debug("Warning: the id is not unique!");
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->id   = $id;
			$this->name = $row['place_name'];
		}
	}
	public function edit_name($new_name){
		$query = "UPDATE worklog_places SET place_name='".$new_name."' WHERE worklog_place_id=".$this->id;
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
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_place_id = ".$this->id;
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