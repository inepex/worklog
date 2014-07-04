<?php 
use gchart\gPie3DChart;

use gchart\gPieChart;

$selected_user = $user;
if(isset($_GET['user_id']) && $_GET['user_id'] != "" && User::is_exist($_GET['user_id'])){
	$selected_user = User::get($_GET['user_id']);
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
				's Worklog - Status
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
						echo '<option value="0" '.($projects_status == 0?'selected="selected"':'').'>Closed</option>';
						echo '<option value="1" '.($projects_status == 1?'selected="selected"':'').'>Active</option>';
						echo '<option value="3" '.($projects_status == 3?'selected="selected"':'').'>All projects</option>';
					?>
				</select> <input type="submit" value="OK" class="btn">
			</form>
		</div>
	</div>
	<hr>
	<?php 
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
				$status_bar = new StatusBar($total_hour_per_worked_hour, 'info');
				echo '<tr>
				<td width="180">'.$category->get_name().'  <br><span class="hint">
			 	'.$category->get_description().'</span>
				</td>
				<td width="670">';
				
				$status_bar->show_progress_bar();
				echo '</td> 
				<td width="100"><font '.(($total_hour_per_worked_hour>100)?'color="red"':'').')>'.$category->get_sum_of_worked_hours($selected_user->get_id()).'/ '.$planned_hours.':00 <br><span style="font-size:16px;font-weight:bold;">'.$total_hour_per_worked_hour.'%</span></font></td>
				</tr>';
				$user_worked_time_in_category = array();
			}
		}
		$sum_of_worked_hours = $project->get_sum_of_worked_hours($selected_user->get_id());
		$sum_of_planed_hours = $project->get_project_plan()->get_sum_of_entries($selected_user->get_id());
		$status_bar = new StatusBar($project->get_worked_per_planned_hour_in_percent($selected_user->get_id()), 'success');
		
		echo '<tr style="background:#efefef;"><td>SUM</td><td>';
		$status_bar->show_progress_bar();
		echo '</td><td><font '.(($project->get_worked_per_planned_hour_in_percent($selected_user->get_id())>100)?'color="red"':'').'>'.$sum_of_worked_hours.'/ '.$sum_of_planed_hours.':00 <br><span style="font-size:16px;font-weight:bold;">'.$project->get_worked_per_planned_hour_in_percent($selected_user->get_id()).'%</font></font></td><tr>';
		echo '</table>';
	}
	?>
	
</div>
</div>
