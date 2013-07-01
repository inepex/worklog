<input type="hidden" id="selected_date" value="<?php echo (isset($_POST['date']) && $_POST['date'])?$_POST['date']:"";?>">
<input type="hidden" id="selected_category_id" value="<?php echo (isset($_POST['category_assoc_id']) && $_POST['category_assoc_id'])?$_POST['category_assoc_id']:"";?>">
<form method="post">
	<tr>
		<td><select style="width: 100px !important;" id="project_select"
			name="project_id">
				<?php 
				$projects = Project::get_projects_which_contain_category($user_id);
				foreach($projects as $project){
					/* @var $project Project */
					if($project->get_status()->get_code() == 1){
						$selected = "";
						if(isset($_POST['project_id']) && $project->get_id() == $_POST['project_id']){
							$selected = 'selected';
						}
						echo '<option class="project_option" value="'.$project->get_id().'" '.$selected.'>'.$project->get_name().'</option>';
					}
				}
				?>
		</select></td>
		<td><select style="width: 120px !important;" id="category_select"
			name="category_assoc_id">
		</select></td>
		<td><select style="width: 115px;" id="date_select" name="date">
		</select></td>
		<td><input type="text" style="width: 40px;" id="time_from" name="from" value="<?php echo (isset($_POST['from'])?$_POST['from']:''); ?>">
		</td>
		<td><input type="text" style="width: 40px;" id="time_to" name="to" value="<?php echo (isset($_POST['to'])?$_POST['to']:''); ?>">
		</td>
		<td><img src="images/emotes/<?php echo date('h');?>.png" title="Jó munkát! :)" style="margin:5px;"></td>
		<td rowspan="2" class="editline"><textarea
				style="width: 210px; height: 60px;" name="log_entry"><?php echo (isset($_POST['log_entry'])?$_POST['log_entry']:'')?></textarea></td>
		<td><?php 
		$workplaces = WorkPlace::get_places();
		?> <select style="width: 80px;" name="work_place_id">
				<?php 
				foreach($workplaces as $workplace){
					/* @var $workplace Workplace */
					$selected = "";
					if(isset($_POST['work_place_id'])){
						if($workplace->get_id() == $_POST['work_place_id']){
							$selected = "selected";
						}
					}
					else if($workplace->get_id() ==  $user->get_default_place()->get_id()){
						$selected = "selected";
					}
					echo '<option value="'.$workplace->get_id().'" '.$selected.'>'.$workplace->get_name().'</option>';
				}
				?>
		</select></td>
		<td><input type="submit" value="OK" name="add_log"
			class="btn btn-primary"></td>
	</tr>

<tr class="editline">
			<td colspan="3"><img src="images/information.png"><span
				id="category_description"></span></td>
			<td><a href="" id="time_from_link">Now</a>&nbsp;/&nbsp;<a href="" id="last_time_link">Last</a></td>
			<td><a href="" id="time_to_link">Now</a></td>
			<td></td>
			<td><?php 
		$efficiencies = Efficiency::get_efficiencies();
		?> <select style="width: 80px;" name="efficiency_id">
				<?php 
				foreach($efficiencies as $efficiency){
					/* @var $workplace Workplace */
					$selected = "";
					if(isset($_POST['efficiency_id'])){
						if($efficiency->get_id() == $_POST['efficiency_id']){
							$selected = "selected";
						}
					}
					else if($efficiency->get_id() ==  $user->get_default_efficiency()->get_id()){
						$selected = "selected";
					}
					echo '<option value="'.$efficiency->get_id().'" '.$selected.'>'.$efficiency->get_name().'</option>';
				}
				?>
		</select></td>
			<td></td>
		</tr></form>