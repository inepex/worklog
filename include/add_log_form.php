<form method="post">
	<tr>
		<td><select style="width: 120px !important;" id="project_select"
			name="project_id">
				<?php 
				$projects = Project::get_projects_which_contain_category($user_id);
				foreach($projects as $project){
					/* @var $project Project */
					echo '<option class="project_option" value="'.$project->get_id().'">'.$project->get_name().'</option>';
				}
				?>
		</select></td>
		<td><select style="width: 120px !important;" id="category_select"
			name="category_assoc_id">
		</select></td>
		<td><select style="width: 120px;" id="date_select" name="date">
		</select></td>
		<td><input type="text" style="width: 40px;" id="time_from" name="from">
		</td>
		<td><input type="text" style="width: 40px;" id="time_to" name="to"></td>
		<td rowspan="2" class="editline"><textarea
				style="width: 250px; height: 60px;" name="log_entry"></textarea></td>
		<td><?php 
		$workplaces = WorkPlace::get_places();
		?> <select style="width: 80px;" name="work_place_id">
				<?php 
				foreach($workplaces as $workplace){
					/* @var $workplace Workplace */
					$selected = "";
					if($workplace->get_id() ==  $user->get_default_place()->get_id()){
						$selected = "selected";
					}
					echo '<option value="'.$workplace->get_id().'" '.$selected.'>'.$workplace->get_name().'</option>';
				}
				?>
		</select></td>
		<td><input type="submit" value="OK" name="add_log"
			class="btn btn-primary"></td>
	</tr>
</form>
<tr class="editline">
			<td colspan="3"><img src="images/information.png"><span
				id="category_description"></span></td>
			<td><a href="" id="time_from_link">Now</a></td>
			<td><a href="" id="time_to_link">Now</a></td>
			<td></td>
			<td></td>
		</tr>