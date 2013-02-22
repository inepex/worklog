<?php
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
	<th>ID</th><th>Name</th><th>Delete</th>
	<?php 
	foreach($projects as $project){
		/* @var $project Project */
		echo '<tr>';
		echo '<td>'.$project->get_id().'</td><td><a href="project_edit.php?project_id='.$project->get_id().'">'.$project->get_name().'</a></td><td><a href=""><img src="images/delete.png"</a></td>';
		echo '</tr>';
	}
	?>
	</tr>
	</table>
	</div>
</div>