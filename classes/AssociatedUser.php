<?php

class AssociatedUser extends User {
	private $assoc_id;
	private $project_id;
    public static function get($assoc_id){
        $associated_user = null;
        if(AssociatedUser::isCachedS($assoc_id, 'AssociatedUser')){
            $associated_user = AssociatedUser::getCachedS($assoc_id, 'AssociatedUser');
        } else {
            $query = "SELECT * FROM worklog_projects_user_assoc WHERE worklog_projects_user_assoc_id = " . $assoc_id;
            $select_result = mysql_query($query);
            $row = mysql_fetch_assoc($select_result);
            $associated_user = new AssociatedUser($assoc_id, $row['worklog_project_id'], User::get($row['worklog_user_id']));
            $associated_user->cache($assoc_id, $associated_user);
        }
        return $associated_user;
    }
	public function __construct($assoc_id, $project_id, User $user) {
		$this->objectName = 'AssociatedUser';
		$this->assoc_id = $assoc_id;
        $this->project_id = $project_id;
        $this->id = $user->get_id();
        $this->user_name = $user->get_user_name();
        $this->password = $user->get_password();
        $this->status = $user->get_status();
        $this->email = $user->get_email();
        $this->enter_date = $user->get_enter_date();
        $this->name = $user->get_name();
        if ($user->get_picture() == "") {
            $this->picture = "nopic.png";
        } else {
            $this->picture = $user->get_picture();
        }
        $this->default_place = $user->get_default_place();
        $this->default_efficiency = $user->get_default_efficiency();
        $this->send_daily_alert = $user->get_send_daily_alert();
        $this->api_key = $user->get_api_key();
	}

	public function is_have_log_in_project() {
		$query = "SELECT worklog_log_id FROM worklog_log where worklog_user_id = " . $this->id . " AND worklog_project_id = " . $this->project_id;
		$result = mysql_query($query);
		if (mysql_affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_assoc_id() {
		return $this->assoc_id;
	}

	public function get_project_id() {
		return $this->project_id;
	}

	protected function setFromCache($object) {
		parent::setFromCache($object);
		$this->assoc_id = $object->assoc_id;
		$this->project_id = $object->project_id;
	}


}
