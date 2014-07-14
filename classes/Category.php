<?php 
class Category extends ObjectCache{
	protected $id;
	protected $name;
	protected $category_status;

	public static function get_categories(){
		$categories = array();
		$query = "SELECT * FROM worklog_categories order by category_name";
		$select_result = mysql_query($query);
		while($row = mysql_fetch_assoc($select_result)){
			$category = new Category($row['worklog_category_id'], $row['category_name'], $row['category_status']);
            $category->cache($category->get_id(), $category);
            array_push($categories, $category);
		}
		return $categories;
	}
	public static function is_exist($category_id){
		$query = "SELECT * FROM worklog_categories WHERE worklog_category_id = ".$category_id;
		$select_result = mysql_query($query);
		if(mysql_affected_rows()>0){
			return true;
		}
		else{
			return false;
		}
	}
	public static function new_category($category_name){
		$query = "INSERT INTO worklog_categories (category_name) VALUES ('".strip_tags(mysql_real_escape_string($category_name))."')";
		$insert_result = mysql_query($query);
		if(mysql_error() != ""){
			trigger_error(mysql_error());
		}
		else{
			Notification::notice("Added successfully!");
		}
	}
	public static function  delete_category($category_id){
		$category = Category::get($category_id);
		if(!$category->is_in_use()){
			$query = "DELETE FROM worklog_categories WHERE worklog_category_id=".$category_id;
			$delete_result = mysql_query($query);
			if(mysql_error() != ""){
				trigger_error(mysql_error());
			}
			else{
				trigger_error("Category deleted successfully!");
			}
		}
		else{
			Notification::warn("The category is in use!");
		}
	}
	public static function get($id){
		$category = null;
        if(Category::isCachedS($id, 'Category')){
            $category = Category::getCachedS($id, 'Category');
        }else{
            $query = "SELECT * FROM worklog_categories WHERE worklog_category_id=".$id;
            $select_result = mysql_query($query);
            if(mysql_affected_rows() != 1){
                trigger_error("Warning: the CATEGORY id is not exist or not unique! Called with:".$id);
            }
            else{
                $row = mysql_fetch_assoc($select_result);
                $category = new Category($id,$row['category_name'], $row['category_status']);
                $category->cache($id, $category);
            }
        }
        return $category;
	}
	public function __construct($id, $name, $status){
        $this->objectName = 'Category';
        $this->id   = $id;
        $this->name = $name;
        $this->category_status = $status;
	}
	public function edit_name($new_name){
		$query = "UPDATE worklog_categories SET category_name='".strip_tags(mysql_real_escape_string($new_name))."' WHERE worklog_category_id=".$this->id;
		$update_result = mysql_query($query);
		$this->name = $new_name;
		if(mysql_error()!=''){
			trigger_error(mysql_error());
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
	public function get_category_status(){
		return $this->category_status;
	}
	public function is_in_use(){
		$query = "SELECT worklog_projects_category_assoc_id FROM worklog_projects_category_assoc WHERE worklog_category_id = ".$this->id;
		$select_result = mysql_query($query);
		if(mysql_error()!=''){
			trigger_error(mysql_error());
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
?>