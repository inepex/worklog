<?php

require_once(dirname(__FILE__) . '/ObjectCache.php');

class Efficiency extends ObjectCache {

	private $id;
	private $name;

	public static function is_efficiency_exist($efficiency_id) {
		$query = "SELECT worklog_efficiency_id FROM worklog_efficiency WHERE worklog_efficiency_id = " . $efficiency_id;
		$select_result = mysql_query($query);
		if (mysql_affected_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	public static function get_efficiencies() {
		$efficiencies = array();
		$query = "SELECT * FROM worklog_efficiency order by worklog_efficiency_id ";
		$select_result = mysql_query($query);
		while ($row = mysql_fetch_assoc($select_result)) {
            $efficiency = new efficiency($row['worklog_efficiency_id'], $row['efficiency_name']);
            $efficiency->cache($efficiency->get_id(), $efficiency);
			array_push($efficiencies, $efficiency);
		}
		return $efficiencies;
	}

	public static function new_efficiency($efficiency_name) {
		$query = "INSERT INTO worklog_efficiency (efficiency_name) VALUES ('" . strip_tags(mysql_real_escape_string($efficiency_name)) . "')";
		$insert_result = mysql_query($query);
		if (mysql_error() != "") {
			trigger_error(mysql_error());
		} else {
			Notification::notice("Added successfully!");
		}
	}

	public static function  delete_efficiency($efficiency_id) {
		$efficiency = Efficiency::get($efficiency_id);
		if (!$efficiency->is_in_use()) {
			$query = "DELETE FROM worklog_efficiency WHERE worklog_efficiency_id=" . $efficiency_id;
			$delete_result = mysql_query($query);
			if (mysql_error() != "") {
				trigger_error(mysql_error());
			} else {
				Notification::notice("efficiency deleted successfully!");
			}
		} else {
			Notification::warn("The efficiency is in use!");
		}
	}

    public static function get($id) {
        $efficiency = null;
        if (Efficiency::isCachedS($id, 'Efficiency')) {
            $efficiency = Efficiency::getCachedS($id, 'Efficiency');
        } else {
            $query = "SELECT * FROM worklog_efficiency WHERE worklog_efficiency_id=" . $id;
            $select_result = mysql_query($query);
            if (mysql_affected_rows() != 1) {
                trigger_error("Warning: the efficiency id is not unique! Called with:" . $id);
            } else {
                $row = mysql_fetch_assoc($select_result);
                $efficiency = new Efficiency($id, $row['efficiency_name']);
                $efficiency->cache($efficiency->get_id(), $efficiency);
            }
        }
        return $efficiency;
    }
	public function __construct($id, $name) {
		$this->objectName = 'Efficiency';
		$this->id = $id;
        $this->name = $name;
	}

	public function edit_name($new_name) {
		$query = "UPDATE worklog_efficiency SET efficiency_name='" . strip_tags(mysql_real_escape_string($new_name)) . "' WHERE worklog_efficiency_id=" . $this->id;
		$update_result = mysql_query($query);
		$this->name = $new_name;
		if (mysql_error() != '') {
			trigger_error(mysql_error());
		} else {
			Notification::notice("Updated successfully!");
		}
	}

	public function get_id() {
		return $this->id;
	}

	public function get_name() {
		return $this->name;
	}

	public function is_in_use() {
		$query = "SELECT worklog_project_id FROM worklog_log WHERE worklog_efficiency_id = " . $this->id;
		$select_result = mysql_query($query);
		if (mysql_error() != '') {
			trigger_error(mysql_error());
		} else {
			if (mysql_affected_rows() == 0) {
				return false;
			} else {
				return true;
			}
		}
	}

	protected function setFromCache($object) {
		$this->id = $object->id;
		$this->name = $object->name;
	}
}
