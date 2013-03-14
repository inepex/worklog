<?php 
use gchart\gPie3DChart;

use gchart\gPieChart;

$selected_user = $user;
if(isset($_GET['user_id']) && $_GET['user_id'] != "" && User::is_exist($_GET['user_id'])){
	$selected_user = new User($_GET['user_id']);
}
$projects_status = 1;
if(isset($_GET['projects_status']) && $_GET['projects_status'] != "" && $_GET['projects_status'] >= 0 && $_GET['projects_status'] <= 3){
	$projects_status = $_GET['projects_status'];
}
?>
<div class="worklog-container">

	<div class="subheader">
		<div class="profile_photo">
			<img src="photos/<?php echo $selected_user->get_picture();?>">
		</div>
		<div class="titlebar">
			<h4>
				<?php echo $selected_user->get_name();?>
				's Worklog - ProjectStat View
			</h4>
			<form method="get">
				<select name="user_id">
					<?php 
					$users = User::get_users();
					foreach($users as $single_user){
						$selected = "";
						if($single_user->get_id() == $selected_user->get_id()){
							$selected = 'selected="selected"';
						}
						echo '<option value="'.$single_user->get_id().'" '.$selected.'>'.$single_user->get_name().'</option>';
					}
					?>
				</select> <select name="projects_status">
					<?php 
					$all_status = ProjectStatus::get_all_status();
					foreach($all_status as $status){
						echo '<option value="'.$status->get_code().'" '.($projects_status == $status->get_code()?'selected="selected"':'').'>'.$status->get_name().'</option>';
					}
					?>
					<option value="3"
					<?php echo ($projects_status == '3'?'selected="selected"':'');?>>All
						projects</option>
				</select> <input type="submit" value="OK" class="btn">
			</form>
		</div>
	</div>
	<hr>
	<?php 
	$user_worked_in_categories = array();
	$user_worked_time_in_categories = array();
	$projects = $selected_user->get_projects_where_user_have_planned_hour($projects_status);
	foreach($projects as $project){
		/* @var $project Project */

		echo '
		<h4 style="float: left;"><a href="project_view.php?project_id='.$project->get_id().'">'.$project->get_name().'</a></h4>
		<table class="table table-bordered">';
		$categories = $project->get_categories();
		foreach($categories as $category){
			/* @var $category AssociatedCategory */
			$planned_hours = $project->get_project_plan()->get_sum_for_category_and_user($selected_user->get_id(), $category->get_assoc_id());
			if($planned_hours >0){
				$total_hour_per_worked_hour = $category->get_category_status_in_percent($selected_user->get_id());
				echo '<tr>
				<td width="140px">'.$category->get_name().' <img src="images/information.png"
				title="'.$category->get_description().'">
				</td>
				<td>
				<div class="progress progress-info progress-striped" style=" width: 700px;">
				<div class="bar" style="width: '.$total_hour_per_worked_hour.'%"></div>
				</div>
				</td>
				<td>'.$category->get_sum_of_worked_hours($selected_user->get_id()).'/ '.$planned_hours.':00 ('.$total_hour_per_worked_hour.'%)</td>
				</tr>';
				$user_worked_time_in_category = array();
				array_push($user_worked_in_categories,$category->get_name());
				array_push($user_worked_time_in_categories,$category->get_sum_of_worked_hours($selected_user->get_id()));
				
			}
		}
		$sum_of_worked_hours = $project->get_sum_of_worked_hours($selected_user->get_id());
		$sum_of_planed_hours = $project->get_project_plan()->get_sum_of_entries($selected_user->get_id());
		echo '<tr><td>SUM</td><td><div class="progress progress-info progress-striped" style=" width: 700px;">
		<div class="bar" style="width: '.$project->get_worked_per_planned_hour_in_percent($selected_user->get_id()).'%"></div>
		</div>
		</td><td>'.$sum_of_worked_hours.'/ '.$sum_of_planed_hours.':00 ('.$project->get_worked_per_planned_hour_in_percent($selected_user->get_id()).'%)</td><tr>';
		echo '</table>';
	}
	?>
	
</div>
</div>
