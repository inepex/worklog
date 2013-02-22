<?php 
class User{
	protected $id;
	protected $user_name;
	protected $password;
	protected $status;
	protected $email;
	protected $enter_date;
	protected $name;
	protected $picture;
	protected $default_place;
	
	public static function get_users(){
		$users = array();
		$query = "SELECT worklog_user_id FROM worklog_users order by name";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($users, new User($row['worklog_user_id']));
		}
		return $users;
	}
	public static function is_exist($user_id){
		$query = "SELECT * FROM worklog_users WHERE worklog_user_id = ".$user_id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows()>0){
			return true;
		}
		else{
			return false;
		}
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_users WHERE worklog_user_id = ".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 0){
			Notification::error("Nincs ilyen user: id=".$id);
		}
		else{
			$this->id = $id;
			$row = mysql_fetch_assoc($select_result);
			$this->user_name = $row['username'];
			$this->password  = $row['password'];
			$this->status    = $row['user_status'];
			$this->email     = $row['email'];
			$this->enter_date= $row['enterdate'];
			$this->name      = $row['name'];
			$this->picture   = $row['picture'];
			$this->default_place = new WorkPlace($row['default_place_id']);		
		}
	}
	public function get_id(){
		return $this->id;
	}
	public function get_user_name(){
		return $this->user_name;
	}
	public function get_password(){
		return $this->password;
	}
	public function get_status(){
		return $this->status;
	}
	public function get_email(){
		return $this->email;
	}
	public function get_enter_date(){
		return $this->enter_date;
	}
	public function get_name(){
		return $this->name;
	}
	public function get_picture(){
		return $this->picture;
	}
	public function get_default_place(){
		return $this->default_place;
	}
	public function edit_user_name($user_name){
		if($user_name == ''){
			return false;
		}
		else{
			$query = "UPDATE worklog_users SET username='".$user_name."' WHERE worklog_user_id=".$this->id;
			$update_result = mysql_query($query);
			if(mysql_error() == ""){
				$this->user_name = $user_name;
				return true;
			}
			else{
				Notification::error(mysql_error());
				return false;
			}
		}
	}
	public function edit_default_workplace($default_workplace_id){
		if($default_workplace_id == ''){
			return false;
		}
		else{
			$query = "UPDATE worklog_users SET default_place_id='".$default_workplace_id."' WHERE worklog_user_id=".$this->id;
			$update_result = mysql_query($query);
			if(mysql_error() == ""){
				$this->default_place = new WorkPlace($default_workplace_id);
				return true;
			}
			else{
				Notification::error(mysql_error());
				return false;
			}
		}
	}
	public function edit_password($password){//folytatni
		if($password == ''){
			return false;
		}
		else{
			$query = "UPDATE worklog_users SET password='".md5($password)."' WHERE worklog_user_id=".$this->id;
			$update_result = mysql_query($query);
			if(mysql_error() == ""){
				$this->password = md5($password);
				return true;
			}
			else{
				Notification::warn(mysql_error());
				return false;
			}
		}
	}
	public function edit_profile_picture($picture_file){
		$target_path = "photos/";
		if(file_exists($target_path.$picture_file['name'])){
			Notification::warn("The picture name is already exists");
			return false;
		}
		else{
			if(move_uploaded_file($picture_file['tmp_name'], $target_path . basename( $picture_file['name']))) {
				unlink($target_path.$this->picture);
				$this->picture = $picture_file['name'];
				$query = "UPDATE worklog_users SET picture='".$this->picture."' WHERE worklog_user_id=".$this->id;
				$update_result = mysql_query($query);
				Notification::notice("The file ".basename($picture_file['name'])." has been uploaded");
			} else{
				Notification::warn("There was an error uploading the file, please try again!");
			}	
		}
	}
	public function update_personal_note($note_text){
		$query = "UPDATE worklog_users SET personal_note = '".$note_text."' WHERE worklog_user_id = ".$this->id;
		$update_result = mysql_query($query);
		if(mysql_error() == ""){
			Notification::notice("Personal note has been updated successfully!");
		}
		else{
			Notification::error(mysql_error());
		}
	}
	public function get_personal_note(){
		$query = "SELECT personal_note FROM worklog_users WHERE worklog_user_id = ".$this->id;
		$select_result = mysql_query($query);
		$row = mysql_fetch_assoc($select_result);
		return $row['personal_note'];
	}
	public function is_admin(){
		if($this->status == 2){
			return true;
		}
		else{
			return false;
		}
	}
}
?>