<?php

require_once(dirname(__FILE__) . '/ObjectCache.php');


class User extends ObjectCache {
    protected static $object_name = 'User';
	protected $id;
	protected $user_name;
	protected $password;
	protected $status;
	protected $email;
	protected $enter_date;
	protected $name;
	protected $picture;
	protected $default_place;
	protected $default_efficiency;
	protected $send_daily_alert;
	protected $api_key;

	public static function authenticate_user($user_name, $md5password) {
		$query = "SELECT * FROM worklog_users WHERE username ='" . strip_tags(mysql_real_escape_string($user_name)) . "'";
		$select_result = mysql_query($query);
		if (mysql_affected_rows() == 0) {
			return false;
		} else {
			$row = mysql_fetch_assoc($select_result);
			if ($row['password'] == $md5password) {
				return $row['api_key'];
			}
		}
	}
    public static function get($id){
        $user = null;
            if(User::isCachedS($id, User::$object_name)){
                $user = User::getCachedS($id, 'User');
            }else {
                $query = "SELECT * FROM worklog_users WHERE worklog_user_id = " . $id;
                $select_result = mysql_query($query);
                if (mysql_affected_rows() == 0) {
                    Notification::error("Nincs ilyen user: id=" . $id);
                    try {
                        throw new Exception;
                    } catch(Exception $e) {
                        var_dump($e->getTrace());
                    }
                } else {
                    $row = mysql_fetch_assoc($select_result);
                    $user = new  User($id
                                     ,$row['username']
                                     ,$row['password']
                                     ,$row['user_status']
                                     ,$row['email']
                                     ,$row['enterdate']
                                     ,$row['name']
                                     ,$row['picture']
                                     ,WorkPlace::get($row['default_place_id'])
                                     ,Efficiency::get($row['default_efficiency_id'])
                                     ,$row['send_daily_alert']
                                     ,$row['api_key']);
                    $user->cache($user->get_id(), $user);
                }
            }
        return $user;
    }
	
	public static function get_users_by_state($require_old_users) {
		$users = array();
		if($require_old_users) {
			$query = "SELECT * FROM worklog_users order by name";
		} else{
			$query = "SELECT * FROM worklog_users WHERE user_status > 0 order by name";
		}
        $select_result = mysql_query($query);
        while ($row = mysql_fetch_assoc($select_result)) {
            $user = new User($row['worklog_user_id']
                            ,$row['username']
                            ,$row['password']
                            ,$row['user_status']
                            ,$row['email']
                            ,$row['enterdate']
                            ,$row['name']
                            ,$row['picture']
                            ,WorkPlace::get($row['default_place_id'])
                            ,Efficiency::get($row['default_efficiency_id'])
                            ,$row['send_daily_alert']
                            ,$row['api_key']);
            $user->cache($user->get_id(), $user);
            array_push($users, $user);
		}
		return $users;
	}
	
	public static function get_users() {
		return User::get_users_by_state(true);
	}
	
	public static function get_active_users() {
		return User::get_users_by_state(false);
	}

	public static function is_exist($user_id) {
		$query = "SELECT * FROM worklog_users WHERE worklog_user_id = " . $user_id;
		$select_result = mysql_query($query);
		if (mysql_affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
    public function __construct($id, $user_name, $password, $status, $email, $enter_date, $name, $picture, WorkPlace $default_place, Efficiency $default_efficiency, $send_daily_alert, $api_key) {
        $this->objectName = User::$object_name;
        $this->id = $id;
        $this->user_name = $user_name;
        $this->password = $password;
        $this->status = $status;
        $this->email = $email;
        $this->enter_date = $enter_date;
        $this->name = $name;
        if ($picture == "") {
            $this->picture = "nopic.png";
        } else {
            $this->picture = $picture;
        }
        $this->default_place = $default_place;
        $this->default_efficiency = $default_efficiency;
        $this->send_daily_alert = $send_daily_alert;
        $this->api_key = $api_key;
    }

	public function get_id() {
		return $this->id;
	}

	public function get_user_name() {
		return $this->user_name;
	}

	public function get_password() {
		return $this->password;
	}

	public function get_status() {
		return $this->status;
	}

	public function get_email() {
		return $this->email;
	}

	public function get_enter_date() {
		return $this->enter_date;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_picture() {
		return $this->picture;
	}

	public function get_default_place() {
		return $this->default_place;
	}

	public function get_default_efficiency() {
		return $this->default_efficiency;
	}

	public function get_send_daily_alert() {
		return $this->send_daily_alert;
	}
	
	public function get_api_key() {
		return $this->api_key;
	}

	public function edit_user_name($user_name) {
		if ($user_name == '') {
			return false;
		} else {
			$query = "SELECT worklog_user_id FROM worklog_users WHERE username = '" . strip_tags(mysql_real_escape_string($user_name)) . "'";
			$result = mysql_query($query);
			if (mysql_affected_rows() != 0 && $user_name != $this->user_name) {
				Notification::error('User name already exists');
				return false;
			} else {
				$query = "UPDATE worklog_users SET username='" . strip_tags(mysql_real_escape_string($user_name)) . "' WHERE worklog_user_id=" . $this->id;
				$update_result = mysql_query($query);
				if (mysql_error() == "") {
					$this->user_name = $user_name;
					return true;
				} else {
					trigger_error(mysql_error());
					return false;
				}
			}
		}
	}

	public function edit_default_workplace($default_workplace_id) {
		if ($default_workplace_id == '') {
			return false;
		} else {
			$query = "UPDATE worklog_users SET default_place_id='" . $default_workplace_id . "' WHERE worklog_user_id=" . $this->id;
			$update_result = mysql_query($query);
			if (mysql_error() == "") {
				$this->default_place = WorkPlace::get($default_workplace_id);
				return true;
			} else {
				trigger_error(mysql_error());
				return false;
			}
		}
	}
	
	public function edit_send_daily_alert($send_daily_alert) {
		 
			$query = "UPDATE worklog_users SET send_daily_alert='" . $send_daily_alert . "' WHERE worklog_user_id=" . $this->id;
			$update_result = mysql_query($query);
			if (mysql_error() == "") {
				$this->send_daily_alert = $send_daily_alert;
				return true;
			} else {
				trigger_error(mysql_error());
				return false;
			}
		 
	}
	

	public function edit_default_efficiency($default_efficiency_id) {
		if ($default_efficiency_id == '') {
			return false;
		} else {
			$query = "UPDATE worklog_users SET default_efficiency_id='" . $default_efficiency_id . "' WHERE worklog_user_id=" . $this->id;
			$update_result = mysql_query($query);
			if (mysql_error() == "") {
				$this->default_efficiency = Efficiency::get($default_efficiency_id);
				return true;
			} else {
				trigger_error(mysql_error());
				return false;
			}
		}
	}

	public function edit_password($password) { //folytatni
		if ($password == '') {
			return false;
		} else {
			$query = "UPDATE worklog_users SET password='" . md5($password) . "' WHERE worklog_user_id=" . $this->id;
			$update_result = mysql_query($query);
			if (mysql_error() == "") {
				$this->password = md5($password);
				return true;
			} else {
				trigger_error(mysql_error());
				return false;
			}
		}
	}

	public function edit_profile_picture($picture_file) {
		$target_path = "photos/";
		$new_path_parts = pathinfo($picture_file["name"]);
		$new_extension = $new_path_parts['extension'];
		$new_filename = $new_path_parts['filename'];
		if ($new_extension != "jpg" && $new_extension != "jpeg" && $new_extension != "png" && $new_extension != "gif") {
			Notification::warn("Wrong picture format!");
			return false;
		} else {
			$old_path_parts = pathinfo($target_path . $this->picture);
			$old_extension = $old_path_parts['extension'];
			$old_filename = $old_path_parts['filename'];
			rename($target_path . $this->picture, $target_path . $old_filename . "temp");
			if (move_uploaded_file($picture_file['tmp_name'], $target_path . basename($new_filename . "-" . $this->id . "." . $new_extension))) {
				unlink($target_path . $old_filename . "temp");
				$this->picture = $new_filename . "-" . $this->id . "." . $new_extension;
				$query = "UPDATE worklog_users SET picture='" . $this->picture . "' WHERE worklog_user_id=" . $this->id;
				$update_result = mysql_query($query);
				Notification::notice("The file " . basename($picture_file['name']) . " has been uploaded");
				return true;
			} else {
				rename($target_path . $old_filename . "temp", $target_path . $this->picture);
				Notification::warn("There was an error uploading the file, please try again!");
				return false;
			}
		}
	}

	public function update_personal_note($note_text) {
		$query = "UPDATE worklog_users SET personal_note = '" . strip_tags(mysql_real_escape_string($note_text)) . "' WHERE worklog_user_id = " . $this->id;
		$update_result = mysql_query($query);
		if (mysql_error() == "") {
			Notification::notice("Personal note has been updated successfully!");
		} else {
			trigger_error(mysql_error());
		}
	}

	public function get_personal_note() {
		$query = "SELECT personal_note FROM worklog_users WHERE worklog_user_id = " . $this->id;
		$select_result = mysql_query($query);
		$row = mysql_fetch_assoc($select_result);
		return $row['personal_note'];
	}

	public function is_admin() {
		if ($this->status == 2) {
			return true;
		} else {
			return false;
		}
	}

	public function get_projects_where_user_have_planned_hour($status = "") {
		$projects = array();
		$query = 'SELECT worklog_projects.worklog_project_id FROM `worklog_projects`, worklog_project_plan WHERE worklog_projects.worklog_project_id = worklog_project_plan.worklog_project_id AND worklog_project_plan.worklog_user_id = ' . $this->id . ' AND plan_value != 0 group by worklog_projects.worklog_project_id order by start_date desc';
		$select_result = mysql_query($query);
		while ($row = mysql_fetch_assoc($select_result)) {
			if ((int)$status >= 0 && (int)$status <= 2) {
				$project = Project::get($row['worklog_project_id']);
				if ($project->get_status()->get_code() == $status) {
					array_push($projects, $project);
				}
			} else {
				array_push($projects, Project::get($row['worklog_project_id']));
			}

		}
		return $projects;
	}

	public function get_logs($date) {
		return Log::get_logs($this->id, $date);
	}

	public function get_earlies_log_date() {
		$query = "SELECT log_date FROM worklog_log WHERE worklog_user_id = " . $this->id . " order by log_date ASC";
		$select_result = mysql_query($query);
		if (mysql_affected_rows() == 0) {
			return false;
		} else {
			$row = mysql_fetch_assoc($select_result);
			return $row['log_date'];
		}
	}

	public function get_worked_hours_in_associated_category($associated_category) {
		return $associated_category->get_sum_of_worked_hours($this->id);
	}

	public function get_planned_hours_in_associated_category($associated_category) {
		$query = "SELECT plan_value FROM worklog_project_plan WHERE category_assoc_id=" . $associated_category->get_assoc_id() . " AND worklog_user_id = " . $this->get_id();
		$result = mysql_query($query);
		if (mysql_affected_rows() == 0) {
			return 0;
		} else {
			$row = mysql_fetch_assoc($result);
			return $row['plan_value'] . ':00';
		}
	}

	public function get_worked_hours_in_categories($date) {
		$worked_hours_in_categories = array();
		$result_array = date_parse($date);
		if ($result_array['year'] && $result_array['month'] && $result_array['day']) {
			$to = new DateTime($result_array['year'] . "-" . $result_array['month'] . "-" . $result_array['day']);
			$from = new DateTime($result_array['year'] . "-" . $result_array['month'] . "-" . $result_array['day']);
			$from->modify("first day of this month");
			$to->modify("last day of this month");
			$query = "select SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from)) sum_time, s.worklog_category_id from (select worklog_log.worklog_user_id, log_from, log_to, worklog_projects_category_assoc.worklog_category_id from worklog_log, worklog_projects_category_assoc where worklog_projects_category_assoc.worklog_projects_category_assoc_id = worklog_log.worklog_category_assoc_id AND worklog_log.worklog_user_id = " . $this->id . " AND worklog_log.log_date >= '" . $from->format('Y-m-d') . "'  AND worklog_log.log_date <= '" . $to->format('Y-m-d') . "') as s group by s.worklog_category_id";
			$select_result = mysql_query($query);

			while ($row = mysql_fetch_assoc($select_result)) {
				$worked_hours_in_category = array();
				$worked_hours_in_category['category_id'] = $row['worklog_category_id'];
				$worked_hours_in_category['worked_hours'] = $row['sum_time'];
				array_push($worked_hours_in_categories, $worked_hours_in_category);
			}
			return $worked_hours_in_categories;
		}
		return false;
	}

	public function get_worked_hours_in_projects($date) {
		$worked_hours_in_projects = array();
		$result_array = date_parse($date);
		if ($result_array['year'] && $result_array['month'] && $result_array['day']) {
			$to = new DateTime($result_array['year'] . "-" . $result_array['month'] . "-" . $result_array['day']);
			$from = new DateTime($result_array['year'] . "-" . $result_array['month'] . "-" . $result_array['day']);
			$from->modify("first day of this month");
			$to->modify("last day of this month");
			$query = 'select SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from)) sum_time, worklog_project_id from (select * from worklog_log where worklog_user_id = ' . $this->id . ' AND log_date>="' . $from->format('Y-m-d') . '" AND log_date<="' . $to->format('Y-m-d') . '") as s group by worklog_project_id, worklog_user_id';
			$select_result = mysql_query($query);

			while ($row = mysql_fetch_assoc($select_result)) {
				$worked_hours_in_project = array();
				$worked_hours_in_project['project_id'] = $row['worklog_project_id'];
				$worked_hours_in_project['worked_hours'] = $row['sum_time'];
				array_push($worked_hours_in_projects, $worked_hours_in_project);
			}
			return $worked_hours_in_projects;
		}
		return false;
	}
    /**
     *Get the date and end time of the user's last log
     * @return array
     * array elements: 'time', 'date'
     */
	public function get_last_log_to_time() {
		$query = "SELECT log_date, log_to FROM worklog_log where worklog_user_id =" . $this->id . "  order by worklog_log_id desc limit 1";
		$result = mysql_query($query);
		$date = array();
        if (mysql_affected_rows() == 0) {

		} else {
			$row = mysql_fetch_assoc($result);
			$date['time'] = substr($row['log_to'], 0, 5);
			$date['date'] = $row['log_date'];
		}
        return $date;
	}

	public function get_last_log() {
		$query = "SELECT worklog_log_id FROM worklog_log where worklog_user_id =" . $this->id . "  order by worklog_log_id desc limit 1";
		$result = mysql_query($query);
		$date = array();
        if (mysql_affected_rows() == 0) {
            return false;
		} else {
			$row = mysql_fetch_assoc($result);
			return Log::get($row['worklog_log_id']);
		}
	}

	/**
	 * Set the current object from a cached copy.
	 * @param $object mixed The cached object.
	 * @return mixed Nothing.
	 */
	protected function setFromCache($object) {
		$this->id = $object->id;
		$this->user_name = $object->user_name;
		$this->password = $object->password;
		$this->status = $object->status;
		$this->email = $object->email;
		$this->enter_date = $object->enter_date;
		$this->name = $object->name;
		$this->picture = $object->picture;
		$this->default_place = $object->default_place;
		$this->default_efficiency = $object->default_efficiency;
		$this->send_daily_alert = $object->send_daily_alert;
		$this->api_key = $object->api_key;
	}
}

?>
