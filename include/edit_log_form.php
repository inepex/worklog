<form method="post">
	<tr>
		<input type="hidden" name="log_id"
			value="<?php echo $_GET['edit_log'];?>">
		<td><select style="width: 120px !important;" id="project_select"
			name="project_id">
				<?php 
				$projects = Project::get_projects_which_contain_category($user_id);
				foreach($projects as $project){
					/* @var $project Project */
					$selected = "";
					if($log->get_project_id() == $project->get_id()){
						$selected = "selected";
					}
					echo '<option class="project_option" value="'.$project->get_id().'" '.$selected.'>'.$project->get_name().'</option>';
				}
				?>
		</select>
		</td>
		<td><input type="hidden" id="selected_category_id"
			value="<?php echo $log->get_category_assoc_id();?>"> <select
			style="width: 120px !important;" id="category_select"
			name="category_assoc_id">
		</select>
		</td>
		<td><input type="hidden" id="selected_date"
			value="<?php echo $log->get_date();?>"> <select style="width: 120px;"
			id="date_select" name="date">
		</select>
		</td>
		<td><input type="text" style="width: 40px;" id="time_from" name="from"
			value="<?php echo date("H:i",strtotime($log->get_from()));?>">
		</td>
		<td><input type="text" style="width: 40px;" id="time_to" name="to"
			value="<?php echo date("H:i",strtotime($log->get_to()));?>">
		</td>
		<td rowspan="2" class="editline"><textarea
				style="width: 250px; height: 60px;" name="log_entry"><?php echo $log->get_entry();?></textarea>
		</td>
		<td><?php 
		$workplaces = WorkPlace::get_places();
		?> <select style="width: 80px;" name="work_place_id">
				<?php 
				foreach($workplaces as $workplace){
					/* @var $workplace Workplace */
					$selected = "";
					if($workplace->get_id() ==  $log->get_working_place_id()){
						$selected = "selected";
					}
					echo '<option value="'.$workplace->get_id().'" '.$selected.'>'.$workplace->get_name().'</option>';
				}
				?>
		</select>
		</td>
		<td><input type="submit" value="SAVE" name="edit_log"
			class="btn btn-primary">
		</td>
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
