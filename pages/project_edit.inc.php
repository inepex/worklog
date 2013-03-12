<?php
error_reporting(E_ALL);
if(isset($_GET['project_id']) && $_GET['project_id'] != "" && Project::is_project_exist($_GET['project_id'])){
	$project = new Project($_GET['project_id']);

	//update project informations
	if(isset($_POST['update_project'])){
		$error=false;
		if(!isset($_POST['project_name']) || $_POST['project_name'] ==""){
			$error = true;
			Notification::warn("Missing project name!");
		}
		if(!isset($_POST['company_id']) || $_POST['company_id'] ==""){
			$error = true;
			Notification::warn("Missing company!");
		}
		if(!isset($_POST['project_description']) || $_POST['project_description'] ==""){
			$error = true;
			Notification::warn("Missing project description!");
		}
		if(!isset($_POST['start']) || $_POST['start'] ==""){
			$error = true;
			Notification::warn("Missing start date!");
		}
		if(!isset($_POST['deadline']) || $_POST['deadline'] ==""){
			$error = true;
			Notification::warn("Missing deadline date!");
		}
		if(!isset($_POST['project_status']) || $_POST['project_status'] ==""){
			$error = true;
			Notification::warn("Missing status!");
		}
		if(isset($_POST['start']) && isset($_POST['deadline']) && $_POST['start']>$_POST['deadline']){
			$error = true;
			Notification::warn("Deadline is bigger then start date!");
		}
		if(!$error){
			$project->update($_POST['project_name'], $_POST['company_id'], $_POST['project_description'], $_POST['start'], $_POST['deadline'], $_POST['project_status'], $user->get_id());
			Notification::notice("Updated successfully!");
		}
	}
	//

	//add workmate
	if(isset($_POST['add_workmate']) && isset($_POST['workmate_id'])){
		if(User::is_exist($_POST['workmate_id'])){
			$project->add_workmate($_POST['workmate_id']);
			Notification::notice("Workmate added successfully!");
		}
		else{
			Notification::warn("User does not exist!");
		}
	}
	//

	//add category
	if(isset($_POST['add_category']) && isset($_POST['category_id'])){
		if(Category::is_exist($_POST['category_id'])){
			if(isset($_POST['category_description']) && $_POST['category_description']){
				$project->add_category($_POST['category_id'], $_POST['category_description']);
				Notification::notice("Category added successfully!");
			}
			else{
				Notification::warn("Category description can not be empty!");
			}
		}
		else{
			Notification::warn("Category does not exist!");
		}
	}
	//delete workmate
	if(isset($_GET['delete_workmate'])){
		$project->delete_workmate($_GET['delete_workmate']);
		Notification::notice("Workmated removed!");
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=project_edit.php?project_id=".$project->get_id()."\">";
		exit();
	}
	//
	
	//delete category
	if(isset($_GET['delete_category'])){
		$project->delete_category($_GET['delete_category']);
		Notification::notice("Category removed!");
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=project_edit.php?project_id=".$project->get_id()."\">";
		exit();
	}
	//
	//update project plan
	if(isset($_POST['update_project_plan'])){
		for($i=0; $i<count($_POST['plan_entry_value']); $i++){
			if(!preg_match('/[0-9]/', $_POST['plan_entry_value'][$i])){
					Notification::warn($_POST['plan_entry_value'][$i]." is not a number!");
			}
			else{
				$project->get_project_plan()->add_entry($_POST['plan_entry_user_id'][$i], $_POST['plan_entry_category_assoc_id'][$i], $_POST['plan_entry_value'][$i]);
			}
		}
		Notification::notice("Project plan updated successfully!");
	}
	//
}
else{
	Notification::warn("The requested project does not exist!");
	echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
	exit();
}

?>
<div class="worklog-container">
<input type="hidden" name="project_id" id="project_id" value="<?php echo $project->get_id();?>">
	<div class="subheader">

		<div class="titlebar">
			<div style="float: left;">
				<h4>Create New Project / Edit Project</h4>
			</div>
			<div style="float: right;">
				<span style="float: right;">
				<div class="dropdown">
					<a href="#" class="btn dropdown-toggle" data-toggle="dropdown">Duplicate
						project</a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td>Név:</td>
						<td><input type="text" name="duplicate" id="duplicate_name"/></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><a href="#" class="btn" id="duplicate">Duplikálás</a></td>
					</tr>
					</table>
					</ul>
				</span> 
				<a href="project_view.php?project_id=<?php echo $project->get_id();?>" class="btn ">Project Page</a> <a
					href="project_edit.php" class="btn btn-inverse">Edit Project</a>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<hr>
	<?php require_once 'include/notifications.php';?>
	<div style="clear: both;"></div>
	<form method="post">
		<table class="table table-bordered">
			<tr>
				<td width="120">Project name:</td>
				<td><input type="text" value="<?php echo $project->get_name();?>"
					name="project_name"></td>
			</tr>
			<tr>
				<td width="120">Company:</td>
				<td><select name="company_id">
						<?php
						$companies = Company::get_companies();
						foreach($companies as $company){
							/* @var $company Company */
							$selected = '';
							if($project->get_company()->get_id()==$company->get_id()){
								$selected = 'selected';
							}
							echo '<option value="'.$company->get_id().'" '.$selected.'>'.$company->get_name().'</option>';
						}
						?>
				</select>
				</td>
			</tr>
			<tr>
				<td>Description:</td>
				<td>
				<textarea style="width: 700px; height: 100px;"
						name="project_description"><?php echo $project->get_description();?></textarea>
						</td>
			</tr>
			<tr>
				<td>Start:</td>
				<td><input type="text" class="datepicker"
					value="<?php echo $project->get_start_date();?>" name="start"></td>
			</tr>
			<tr>
				<td>Deadline:</td>
				<td><input type="text" class="datepicker"
					value="<?php echo $project->get_end_date();?>" name="deadline"></td>
			</tr>
			<tr>
				<td>Status:</td>
				<td><input type="radio"
				<?php echo(($project->get_status() == 1)?'checked':''); ?>
					name="project_status" value="1"> Active <input type="radio"
					<?php echo(($project->get_status() == 0)?'checked':''); ?>
					name="project_status" value="0"> Closed <input type="radio"
					<?php echo(($project->get_status() == 2)?'checked':''); ?>
					name="project_status" value="2"> Archived</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" class="btn btn-primary" value="Save"
					name="update_project"></td>
			</tr>
		</table>
	</form>

	<hr>

	<div style="width: 30%; float: left;">
	<a name="workmates"></a>
		<h4>Workmates</h4>
		<form method="post" action="project_edit.php?project_id=<?php echo $project->get_id();?>#workmates">
			<table class="table table-bordered">
				<tr>
					<td><select name="workmate_id">
							<?php 
							$users = User::get_users();
							foreach ($users as $u){
								/* @var $u User */
								if(!$project->is_user_workmate($u->get_id())){
									echo '<option value="'.$u->get_id().'">'.$u->get_name().'</option>';
								}
							}
							?>
					</select>
					</td>
					<td><input type="submit" value="Add" class="btn"
						name="add_workmate">
					</td>
				</tr>
				<?php 
				$workmates = $project->get_workmates();
				foreach ($workmates as $workmate){
					/* @var $workmate AssociatedUser */
					echo '<tr>
					<td width="120"><img src="photos/'.$workmate->get_picture().'" width="20" height="20">
					'.$workmate->get_name().'</td>
					<td><a href="project_edit.php?project_id='.$project->get_id().'&delete_workmate='.$workmate->get_assoc_id().'"><img src="images/delete.png"></a></td>
					</tr>';
				}
				?>
			</table>
		</form>
	</div>

	<div style="width: 68%; float: right;">
	<a name="categories"></a>
		<h4>Categories</h4>
		<form method="post" action = "project_edit.php?project_id=<?php echo $project->get_id();?>#categories">
			<table class="table table-bordered">
				<tr>
					<td><select style="width: 120px;" name="category_id">
							<?php
							$categories = Category::get_categories();
							foreach($categories as $category){
								/* @var $category Category */
								echo '<option value="'.$category->get_id().'">'.$category->get_name().'</option>';
							}
							?>
					</select>
					</td>
					<td><input type="text" style="width: 450px;"
						name="category_description">
					</td>
					<td><input type="submit" value="Add" class="btn"
						name="add_category">
					</td>
				</tr>
				<?php 
				$associated_categories = $project->get_categories();
				foreach($associated_categories as $associated_category){
					/* @var $associated_category AssociatedCategory */
					echo   '<tr><td width="120">'.$associated_category->get_name().'</td>
					<td width="120">'.$associated_category->get_description().'</td>
					<td><a href="project_edit.php?project_id='.$project->get_id().'&delete_category='.$associated_category->get_assoc_id().'"><img src="images/delete.png"></a></td></tr>';
				}
				?>
			</table>
		</form>
	</div>

	<div style="clear: both;"></div>
	<hr>
	<a name="project_plan"></a>
	<h4>Project Plan</h4>
	<form method="post" action = "project_edit.php?project_id=<?php echo $project->get_id();?>#project_plan">
		<table class="table table-bordered" style="width: 0;">
			<tr>
				<th></th>
				<?php

				foreach($workmates as $workmate){
					/* @var $u AssociatedUser */
					echo '<th>'.$workmate->get_name().'</th>';
				}
				?>
				<th>SUM</th>
			</tr>
			<?php 
			foreach ($associated_categories as $associated_category){
				echo '<tr class="project-plan">';
				echo '<th>'.$associated_category->get_name().'</th>';
				foreach ($workmates as $workmate){
					//$entry['user_id'] = $workmate->get_id();
					//$entry['category_assoc_id'];         = 
					echo '<td><input type="text" class="project-plan-input" name="plan_entry_value[]" value="'.$project->get_project_plan()->get_sum_for_category_and_user($workmate->get_id(), $associated_category->get_assoc_id()).'"/></td>';
					echo '<input type="hidden" name="plan_entry_user_id[]" value="'.$workmate->get_id().'">';
					echo '<input type="hidden" name="plan_entry_category_assoc_id[]" value="'.$associated_category->get_assoc_id().'">';
				}
				echo '<td>'.$project->get_project_plan()->get_sum_for_category($associated_category->get_assoc_id()).'</td>';
				echo '</tr>';
			}
			echo '<tr class="project-plan">';
			echo '<th>SUM</th>';
			foreach ($workmates as $workmate){
				echo '<td>'.$project->get_project_plan()->get_sum_for_user($workmate->get_id()).'</td>';
			}
			echo '<td>'.$project->get_project_plan()->get_sum_of_entries().'</td>';
			echo '</tr>';
			?>
		</table>
		<input type="submit" class="btn btn-primary" name="update_project_plan" value="Save project plan">
	</form>

</div>
</div>
