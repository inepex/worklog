<?php
if(isset($_GET['delete_project']) && $_GET['delete_project'] != "" && Project::is_project_exist($_GET['delete_project'])){
	Project::delete_project($_GET['delete_project']);
	//Notification::notice("Deleted successfully!");
	echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=projects_list.php\">";
	exit();
}
$projects = Project::get_projects();

?>
<div class="worklog-container">
	<div class="subheader">

		<div class="titlebar">
			<div style="float: left;">
				<h4>Projects list</h4>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<hr>
<?php require_once 'include/notifications.php';?>
	<div style="clear: both;"></div>
	<div id="projects_list">
	<table class="table table-bordered">
	<tr>
	<th>ID</th><th>Name</th><th>Owner</th><th>Status</th><th>Start</th><th>End</th><th>Edit</th><th>Delete</th>
	<?php 
	foreach($projects as $project){
		/* @var $project Project */
		//$status = new ProjectStatus($project->get_status());
		echo '<tr>';
		echo 	'<td>'.$project->get_id().'</td>
			 	 <td><a href="project_view.php?project_id='.$project->get_id().'">'.$project->get_name().'</a></td>
			 	 <td>'.$project->get_user()->get_name().'</td>
			 	 <td>'.$project->get_status()->get_name().'</td>
			 	 <td>'.$project->get_start_date().'</td>
			 	 <td>'.$project->get_end_date().'</td>
			 	 <td><a href="project_edit.php?project_id='.$project->get_id().'"><img src="images/modify.png"</a></td>
			 	 <td> '.(!Project::is_project_used($project->get_id())?'<a href="projects_list.php?delete_project='.$project->get_id().'"><img src="images/delete.png"</a></td>':'');
		echo '</tr>';
	}
	?>
	</tr>
	</table>
	</div>
</div>