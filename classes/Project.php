<?php
error_reporting(E_ALL);

class Project extends ObjectCache{
	public static $number_of_projects_per_page = 30;
	private $id;
	private $company;
	private $user;
	private $name;
	private $description;
	private $beginning;
	private $destination;
	private $start_date;
	private $end_date;
	private $status;
	private $workmates = array();
	private $categories = array();
	private $project_plan;

	public static function is_project_used($project_id) { //are logs in the project?
		$query = "SELECT worklog_log_id FROM worklog_log WHERE worklog_project_id = " . $project_id;
		$select_result = mysql_query($query);
		if (mysql_affected_rows() == 0) {
			return false;
		} else {
			return true;
		}
	}

	public static function delete_project($project_id) {
		if (!Project::is_project_used($project_id)) {
			$project = Project::get($project_id);
			$workmates = $project->get_workmates();
			foreach ($workmates as $workmate) {
				$project->delete_workmate($workmate->get_assoc_id());
			}
			$categories = $project->get_categories();
			foreach ($categories as $category) {
				$project->delete_category($category->get_assoc_id());
			}
			$query = "DELETE FROM worklog_projects WHERE worklog_project_id = " . $project->get_id();
			$delete_result = mysql_query($query);
			unset($project);
			Notification::notice("Deleted successfully");
		} else {
			Notification::warn("There are logs in this project!");
		}
	}

	public static function get_projects($keyword = "", $order = "", $order_by = "", $project_status = "", $company_id = "", $owner_id = "", $page = "") {
		$projects = array();
		$project_status_condicion = "";
		if ($project_status != "") {
			$project_status_condicion = " AND project_status=" . $project_status;
		}

		$project_company_condicion = "";
		if ($company_id != "") {
			$project_company_condicion = " AND worklog_company_id=" . $company_id;
		}

		$owner_condicion = "";
		if ($owner_id != "") {
			$owner_condicion = " AND worklog_user_id=" . $owner_id;
		}

		$search_condition = " WHERE 1";
		if ($keyword != "") {
			$search_condition = ' WHERE project_name like "%' . strip_tags(mysql_real_escape_string($keyword)) . '%" OR project_description like "%' . strip_tags(mysql_real_escape_string($keyword)) . '%" OR beginning like "%' . strip_tags(mysql_real_escape_string($keyword)) . '%" OR destination like "%' . strip_tags(mysql_real_escape_string($keyword)) . '%"';
		}
		$page_condition = "";
		if ($page !== "") {
			$page_condition = ' limit ' . ($page * Project::$number_of_projects_per_page) . ', ' . Project::$number_of_projects_per_page;
		}
        $order_condition = "";
		if ($order_by != "") {
			$order_condition = " order by " . $order_by . "";
			if ($order == 'desc' || $order == 'asc') {
				$order_condition .= " " . $order;
			}
		}
		$query = "SELECT * FROM worklog_projects" . $search_condition . $project_status_condicion . $owner_condicion . $project_company_condicion . $order_condition . $page_condition;
		$select_result = mysql_query($query);
		while ($row = mysql_fetch_assoc($select_result)) {
            $project = new Project($row['worklog_project_id'],
                Company::get($row['worklog_company_id']),
                User::get($row['worklog_user_id']),
                $row['project_name'],
                $row['project_description'],
                $row['beginning'],
                $row['destination'],
                $row['start_date'],
                $row['end_date'],
                ProjectStatus::get($row['project_status'])
            );
			array_push($projects,$project);
            $project->cache($project->get_id(), $project);
		}
		return $projects;
	}

	public static function duplicate_project($project, $duplicate_name) {
		$duplicated_project = Project::new_project($duplicate_name, $project->get_company()->get_id(), $project->get_description(), $project->get_beginning(), $project->get_destination(), $project->get_start_date(), $project->get_end_date(), $project->get_user()->get_id());
		$project_categories = $project->get_categories();
		foreach ($project_categories as $project_category) {

			$duplicated_project->add_category($project_category->get_id(), $project_category->get_description());
		}
		$project_workmates = $project->get_workmates();
		foreach ($project_workmates as $project_workmate) {
			$duplicated_project->add_workmate($project_workmate->get_id());
		}
		return $duplicated_project;

	}

	public static function get_projects_which_contain_category($user_id) {
		$projects = array();
		$query = "SELECT worklog_project_id FROM worklog_project_plan WHERE worklog_user_id = " . $user_id . " AND plan_value > 0 GROUP BY worklog_project_id ORDER BY worklog_project_id ASC";
		$select_result = mysql_query($query);
		while ($row = mysql_fetch_assoc($select_result)) {
            $project = Project::get($row['worklog_project_id']);
			array_push($projects, $project);
		}
		return $projects;
	}
	public static function get_active_projects_which_contain_category($user_id) {
		$projects = array();
		$query = "SELECT worklog_project_id FROM worklog_projects WHERE worklog_project_id IN (SELECT worklog_project_id FROM worklog_project_plan WHERE worklog_user_id = " . $user_id . " AND plan_value > 0 GROUP BY worklog_project_id ORDER BY worklog_project_id ASC) AND project_status = 1";
		$select_result = mysql_query($query);
		while ($row = mysql_fetch_assoc($select_result)) {
            $project = Project::get($row['worklog_project_id']);
			array_push($projects, $project);
		}
		return $projects;
	}

	public static function is_project_exist($project_id) {

		$query = "SELECT worklog_project_id FROM worklog_projects WHERE worklog_project_id = " . $project_id;

		$select_result = mysql_query($query);

		if (mysql_error() != "") {
			trigger_error(mysql_error());
			return false;
		} else {
			if (mysql_num_rows($select_result) > 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	public static function new_project($name, $company_id, $description, $beginning, $destination, $start, $deadline, $user_id) {
		$query = "INSERT INTO worklog_projects (worklog_company_id, worklog_user_id, project_name, project_description,beginning, destination, start_date, end_date, project_status) VALUES ('" . $company_id . "','" . $user_id . "','" . strip_tags(mysql_real_escape_string($name)) . "','" . strip_tags(mysql_real_escape_string($description)) . "','" . strip_tags(mysql_real_escape_string($beginning)) . "','" . strip_tags(mysql_real_escape_string($destination)) . "','" . $start . "','" . $deadline . "','2')";
		$insert_result = mysql_query($query);
		if (mysql_error() != "") {
			trigger_error(mysql_error());
			return false;
		} else {

			return Project::get(mysql_insert_id());
		}
	}
    public static function get($id){
        $project = null;
        if(Project::isCachedS($id, 'Project')){
            $project = Project::getCachedS($id, 'Project');
        }else{
            $query = "SELECT * FROM worklog_projects WHERE worklog_project_id=" . $id;
            $select_result = mysql_query($query);
            if (mysql_affected_rows() != 1) {
                trigger_error("Warning: the id is not unique! Called with project_id:" . $id);
            } else {
                $row = mysql_fetch_assoc($select_result);
                $project = new Project( $id,
                                        Company::get($row['worklog_company_id']),
                                        User::get($row['worklog_user_id']),
                                        $row['project_name'],
                                        $row['project_description'],
                                        $row['beginning'],
                                        $row['destination'],
                                        $row['start_date'],
                                        $row['end_date'],
                                        ProjectStatus::get($row['project_status'])
                                      );
                $project->cache($id, $project);
            }
        }
        return $project;
    }
    public function __construct($id, Company $company, $user, $name, $description, $beginning, $destination, $start_date, $end_date, ProjectStatus $status){
            $this->objectName = 'Project';
            $this->id = $id;
            $this->company = $company;
            $this->user = $user;
            $this->name = $name;
            $this->description = $description;
            $this->beginning = $beginning;
            $this->destination = $destination;
            $this->start_date = $start_date;
            $this->end_date = $end_date;
            $this->status = $status;
            $this->project_plan = null; //lazy fill, because the data is too big
            $this->workmates = null;    //lazy fill, because the data is too big
            $this->categories = null;    //lazy fill, because the data is too big
    }

	public function get_id() {
		return $this->id;
	}

	public function get_company() {
		return $this->company;
	}

	public function get_user() {
		return $this->user;
	}

	public function get_name() {
		return $this->name;
	}

	public function get_description() {
		return stripcslashes($this->description);
	}

	public function get_beginning() {
		return stripcslashes($this->beginning);
	}

	public function get_destination() {
		return stripcslashes($this->destination);
	}

	public function get_start_date() {
		return $this->start_date;
	}

	public function get_end_date() {
		return $this->end_date;
	}

	public function get_workmates() {
        if($this->workmates == null){
            $query = "SELECT * FROM worklog_projects_user_assoc WHERE worklog_project_id=" . $this->id;
            $select_result = mysql_query($query);

            while ($row = mysql_fetch_assoc($select_result)) {
                array_push($this->workmates, new AssociatedUser($row['worklog_projects_user_assoc_id'], $row['worklog_project_id'], User::get($row['worklog_user_id'])));
            }
        }
		return $this->workmates;
	}

	public function get_categories() {
        if($this->categories == null){
            $this->categories = array();
            $query = "SELECT worklog_projects_category_assoc_id FROM worklog_projects_category_assoc WHERE worklog_project_id=" . $this->id;
            $select_result = mysql_query($query);
            while ($row = mysql_fetch_assoc($select_result)) {
                array_push($this->categories, AssociatedCategory::get($row['worklog_projects_category_assoc_id']));
            }
        }
		return $this->categories;
	}

	public function update_category_description($category_assoc_id, $new_description) {

		foreach ($this->get_categories() as $category) {
			/* @var  $category AssociatedCategory */
			if ($category->get_assoc_id() == $category_assoc_id) {
				$category->update_description($new_description);
			}
		}
	}

	public function get_categories_of_user_with_planned_hours($user) {
		$categories = array();
		foreach ($this->get_categories() as $category) {
			/* @var $category AssociatedCategory */
			if ($category->is_user_have_planned_hours($user)) {
				array_push($categories, $category);
			}
		}
		return $categories;
	}

	public function get_status() {
		return $this->status;
	}

	public function get_project_plan() {
        if($this->project_plan == null){
            $this->project_plan = ProjectPlan::getByProject($this);
        }
		return $this->project_plan;
	}

	public function update($name, $company_id, $owner_id, $description, $beginning, $destination, $start, $deadline, $status, $user_id) {
		$user = User::get($user_id);
		if ($user_id != $this->get_user()->get_id() && !$user->is_admin()) {
			Notification::warn("You do not have permission to do this operation!");
			return false;
		} else {
			$query = "UPDATE worklog_projects SET worklog_company_id=" . $company_id . ",worklog_user_id='" . $owner_id . "', project_name='" . strip_tags(mysql_real_escape_string($name)) . "', project_description='" . strip_tags(mysql_real_escape_string($description)) . "', beginning='" . strip_tags(mysql_real_escape_string($beginning)) . "', destination='" . strip_tags(mysql_real_escape_string($destination)) . "', start_date='" . $start . "', end_date='" . $deadline . "', project_status=" . $status . " WHERE worklog_project_id = " . $this->id;
			$update_result = mysql_query($query);
			if (mysql_error() != "") {
				trigger_error(mysql_error());
				return false;
			} else {
				$this->company = Company::get($company_id);
				$this->name = $name;
				$this->description = $description;
				$this->beginning = $beginning;
				$this->destination = $destination;
				$this->start_date = $start;
				$this->end_date = $deadline;
				$this->status = ProjectStatus::get($status);
				$this->user = User::get($owner_id);
				return true;
			}
		}
	}

	public function is_user_workmate($user_id) {
		$query = "SELECT * FROM worklog_projects_user_assoc WHERE worklog_user_id = " . $user_id . " AND worklog_project_id = " . $this->id;
		$select_result = mysql_query($query);
		if (mysql_affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function add_workmate($wormate_id) {
		if (!$this->is_user_workmate($wormate_id)) {
			$query = "INSERT INTO worklog_projects_user_assoc (worklog_project_id, worklog_user_id) VALUES ('" . $this->id . "', '" . $wormate_id . "')";
			$insert_result = mysql_query($query);
			array_push($this->workmates, AssociatedUser::get(mysql_insert_id()));
		} else {
			Notification::warn("User already workmate!");
		}
	}

	public function delete_workmate($workmate_assoc_id) {
		$associated_user = AssociatedUser::get($workmate_assoc_id);
		$this->get_project_plan()->delete_entry_of_user($associated_user->get_id());
		$query = "DELETE FROM worklog_projects_user_assoc WHERE worklog_projects_user_assoc_id = " . $workmate_assoc_id;
		$delete_result = mysql_query($query);
		//
		foreach ($this->workmates as $key => $workmate) {
			if ($workmate->get_assoc_id() == $workmate_assoc_id) {
				unset($this->workmates[$key]);
			}
		}
		//
	}

	public function add_category($category_id, $description) {
		$query = "INSERT INTO worklog_projects_category_assoc (worklog_project_id, worklog_category_id, category_description) VALUES ('" . $this->id . "', '" . $category_id . "', '" . $description . "')";
		$insert_result = mysql_query($query);
		array_push($this->get_categories(), AssociatedCategory::get(mysql_insert_id()));
	}

	public function delete_category($category_assoc_id) {
		$associated_category = AssociatedCategory::get($category_assoc_id);
		if (!$associated_category->is_associated_category_in_use()) {
			$this->get_project_plan()->delete_entries_of_category($category_assoc_id);
			$query = "DELETE FROM worklog_projects_category_assoc WHERE worklog_projects_category_assoc_id = " . $category_assoc_id;
			$delete_result = mysql_query($query);
			foreach ($this->get_categories() as $key => $category) {
				if ($category->get_assoc_id() == $category_assoc_id) {
					unset($this->get_categories()[$key]);
				}
			}
		} else {
			Notification::warn("Already have log with this category!!!");
		}

	}

	public function is_associated_category_in_project($category_assoc_id) {
		foreach ($this->get_categories() as $category) {
			/* @var $category AssociatedCategory */
			if ($category->get_assoc_id() == $category_assoc_id) {
				return true;
			}
		}
		return false;
	}

	public function get_logs_of_project($user_id = "", $category_assoc_id = "", $date_from, $date_to, $logs_from = 0) {
		$user_condition = "";
		if ($user_id != "" && User::is_exist($user_id)) {
			$user_condition = " AND worklog_user_id = " . $user_id;
		}
		$category_condition = "";
		if ($category_assoc_id != "") {
			$category_condition = " AND worklog_category_assoc_id = " . $category_assoc_id;
		}
		$limit_condition = "";
		if ($logs_from !== "") {
			$limit_condition = " limit " . $logs_from . ", " . ($logs_from + Log::$LISTING_LIMIT);
		}
		$date_from_condition = "";
		if ($date_from != 0 && date_parse($date_from)) {
			$date_from_condition = " AND log_date>='" . $date_from . "'";
		}
		$date_to_condition = "";
		if ($date_to != 0 && date_parse($date_to)) {
			$date_to_condition = " AND log_date<='" . $date_to . "'";
		}
		$query = "SELECT * FROM worklog_log WHERE worklog_project_id = " . $this->id . $category_condition . $user_condition . $date_from_condition . $date_to_condition . " order by worklog_log_id desc " . $limit_condition;
		$select_result = mysql_query($query);
		$logs = array();
		while ($row = mysql_fetch_assoc($select_result)) {
			array_push($logs, new Log(  $row['worklog_log_id'],
                                        $row['create_date'],
                                        $row['worklog_project_id'],
                                        $row['worklog_category_assoc_id'],
                                        $row['worklog_user_id'],
                                        $row['log_date'],
                                        $row['log_from'],
                                        $row['log_to'],
                                        $row['log_entry'],
                                        $row['worklog_place_id'],
                                        $row['worklog_efficiency_id'])
                                    );
		}
		return $logs;
	}

	public function get_sum_of_worked_hours($user_id = "", $category_assoc_id = "") {
		$user_condition = "";
		$assoc_category_condition = "";
		if ($category_assoc_id != "") {
			$assoc_category_condition = ' AND worklog_category_assoc_id = ' . $category_assoc_id;
		}
		if ($user_id != "") {
			$user_condition = ' AND worklog_user_id = ' . $user_id;
		}
		$query = 'SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(log_to)-TIME_TO_SEC(log_from))) sum_time FROM worklog_log WHERE worklog_project_id = ' . $this->id . $user_condition . $assoc_category_condition;
		$selected_result = mysql_query($query);
		$row = mysql_fetch_assoc($selected_result);
		if ($row['sum_time'] == NULL) {
			return '00:00';
		}
		return substr($row['sum_time'], 0, -3);
	}

	public function get_worked_per_planned_hour_in_percent($user_id = "") {
		if ($this->get_sum_of_worked_hours($user_id) == "00:00") {
			return '0';
		} else {
			$sum_of_worked_hours_parts = explode(':', $this->get_sum_of_worked_hours($user_id));
			if ($this->get_project_plan()->get_sum_of_entries($user_id) == 0) {
				$sum_percent = 100;
			} else {
				$sum_percent = ($sum_of_worked_hours_parts[0] * 60 + $sum_of_worked_hours_parts[1]) / ($this->get_project_plan()->get_sum_of_entries($user_id) * 60 / 100);
			}
			return round($sum_percent, 2);
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
