<?php
if(isset($_POST['new_project'])){
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
	if(isset($_POST['start']) && isset($_POST['deadline']) && $_POST['start']>$_POST['deadline']){
		$error = true;
		Notification::warn("Deadline is bigger then start date!");
	}	
	if(!$error){
		if($project = Project::new_project($_POST['project_name'], $_POST['company_id'], $_POST['project_description'], $_POST['beginning'], $_POST['destination'], $_POST['start'], $_POST['deadline'], $user->get_id())){
			echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=project_edit.php?project_id=".$project->get_id()."\">";
			exit();
		}
		else{
			Notification::warn("Something wrong with the new project  :/ !");
		}
	}
}
?>
<div class="worklog-container">
	<div class="subheader">

		<div class="titlebar">
			<div style="float: left;">
				<h4>Create New Project</h4>
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
					<td><input type="text" name="project_name" value="<?php echo ((isset($_POST['project_name']))?$_POST['project_name']:'')?>"></td>
				</tr>
				<tr>
					<td width="120">Company:</td>
					<td><select name="company_id">
							<?php
								$companies = Company::get_companies();
								foreach($companies as $company){
									/* @var $company Company */
									$selected = '';
									if(isset($_POST['company_id']) && $_POST['company_id']==$company->get_id()){
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
					<td><textarea style="width: 700px; height: 100px;" name="project_description"><?php echo ((isset($_POST['project_description']))?$_POST['project_description']:'')?></textarea></td>
				</tr>
				<tr>
					<td>Beginning:</td>
					<td><textarea style="width: 700px; height: 100px;" name="beginning"><?php echo ((isset($_POST['beginning']))?$_POST['beginning']:'')?></textarea></td>
				</tr>
				<tr>
					<td>Destination:</td>
					<td><textarea style="width: 700px; height: 100px;" name="destination"><?php echo ((isset($_POST['destination']))?$_POST['destination']:'')?></textarea></td>
				</tr>
				<tr>
					<td>Start:</td>
					<td><input type="text" name="start" class="datepicker" value="<?php echo ((isset($_POST['start']))?$_POST['start']:'')?>"/></td>
				</tr>
				<tr>
					<td>Deadline:</td>
					<td><input type="text" name="deadline" class="datepicker" value="<?php echo ((isset($_POST['deadline']))?$_POST['deadline']:'')?>"/></td>
				</tr>
				<!--<tr>
					<td>Status:</td>
					<td>
						<input type="radio" name="project_status" value="1" <?php echo ((isset($_POST['project_status']) && $_POST['project_status']=='1')?"checked":"")?>> Active
						<input type="radio" name="project_status" value="0" <?php echo ((isset($_POST['project_status']) && $_POST['project_status']=='0')?"checked":"")?>> Closed
						<input type="radio" name="project_status" value="2" <?php echo ((isset($_POST['project_status']) && $_POST['project_status']=='2')?"checked":"")?>> Requested
					</td>
				</tr>-->
				<tr>
					<td></td>
					<td><input type="submit" class="btn btn-primary" value="Save" name="new_project"></td>
				</tr>
			</table>
		</form>
	</div>
</div>
