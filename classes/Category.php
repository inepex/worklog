<?php 
class Category{
	private $id;
	private $name;
	
	public static function get_categories(){
		$categories = array();
		$query = "SELECT worklog_category_id FROM worklog_categories order by category_name";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($categories, new Category($row['worklog_category_id']));
		}
		return $categories;
	}
	public static function new_category($category_name){
		$query = "INSERT INTO worklog_categories (category_name) VALUES ('".$category_name."')";
		$insert_result = mysql_query($query);
		if(mysql_error() != ""){
			Notification::error(mysql_error());
		}
		else{
			Notification::notice("Added successfully!");
		}
	}
	public static function  delete_category($category_id){
		$query = "DELETE FROM worklog_categories WHERE worklog_category_id=".$category_id;
		$delete_result = mysql_query($query);
		if(mysql_error() != ""){
			Notification::error(mysql_error());
		}
		else{
			Notification::notice("Deleted successfully!");
		}
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_categories WHERE worklog_category_id=".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() != 1){
			debug("Warning: the CATEGORY id is not exist or not unique!");
		}
		else{
			$row = mysql_fetch_assoc($select_result);
			$this->id   = $id;
			$this->name = $row['category_name'];
		}
	}
	public function edit_name($new_name){
		$query = "UPDATE worklog_categories SET category_name='".$new_name."' WHERE worklog_category_id=".$this->id;
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
}
?>