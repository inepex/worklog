<?php 
class User{
	private $id;
	private $user_name;
	private $password;
	private $status;
	private $email;
	private $enter_date;
	private $name;
	private $picture;
	private $default_place;
	
	public static function get_users(){
		$users = array();
		$query = "SELECT worklog_user_id FROM worklog_users order by name";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			array_push($users, new User($row['worklog_user_id']));
		}
		return $users;
	}
	public function __construct($id){
		$query = "SELECT * FROM worklog_users WHERE worklog_user_id = ".$id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows() == 0){
			debug("Nincs ilyen user: id=".$id);
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
}
?>