<?php
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);
//save personal note
if(isset($_POST['save-personal-note']) && isset($_POST['personal-note']) && $_POST['personal-note'] != $user->get_personal_note()){
	$user->update_personal_note($_POST['personal-note']);
	echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
	exit();
}
//
//edit log
if(isset($_POST['log_id'])){
	$error = false;
	if(!Project::is_project_exist($_POST['project_id'])){
		Notification::warn("Project doesnt exist!");
		$error = true;
	}
	else{
		$project = Project::get($_POST['project_id']);
		if(!$project->is_associated_category_in_project($_POST['category_assoc_id'])){
			Notification::warn("The category is not in the project!");
			$error = true;
		}
	}
	$date = new DateTime($_POST['date']);
	$now = new DateTime();
	$interval = $now->diff($date);
	$difference = $interval->format('%R%a');
	if($difference<-4){
		Notification::warn("The date is too early!");
		$error = true;
	}
	else if($difference>0){
		Notification::warn("The date cannot be in the future");
		$error = true;
	}
	else{
		if(!isset($_POST['from']) || $_POST['from'] == ''){
			Notification::warn("FROM cannot be empty!");
			$error = true;
		}
		if(!isset($_POST['to']) || $_POST['to'] == ''){
			Notification::warn("TO cannot be empty!");
			$error = true;
		}
		$parsed_from = date_parse_from_format("H:i",$_POST['from']);
		if($parsed_from['warning_count']>0){
			Notification::warn("FROM value is not valid!");
			$error = true;
		}
		$parsed_to = date_parse_from_format("H:i",$_POST['to']);
		if($parsed_to['warning_count']>0){
			Notification::warn("To value is not valid!");
			$error = true;
		}
		if($parsed_from['warning_count'] == 0 && $parsed_to['warning_count'] == 0){
			if(isset($_POST['to']) && $_POST['to'] != '' && isset($_POST['from']) && $_POST['from'] != ''){
				$seconds_diff = strtotime($_POST['date']." ".date($_POST['to'])) - strtotime($_POST['date']." ".date($_POST['from']));
				if($seconds_diff == 0){
					Notification::warn("TO cannot be the same as FROM!");
					$error = true;
				}
				else if($seconds_diff < 0){
						
					Notification::warn("TO is smaller then FROM!");
					$error = true;
				}
				else{
					if(Log::is_overlap($user->get_id(), $_POST['date'], $_POST['from'], $_POST['to'],$_POST['log_id'])){
						Notification::warn("Time overlap!");
						$error = true;
					}
				}	
			}
		}
	}
	if(!isset($_POST['log_entry']) || $_POST['log_entry'] == ""){
		Notification::warn("Log entry cannot be empty!");
		$error = true;
	}
	if(!WorkPlace::is_workplace_exist($_POST['work_place_id'])){
		Notification::warn("Workplace doesnt exist!");
		$error = true;
	}
	if(!Efficiency::is_efficiency_exist($_POST['efficiency_id'])){
		Notification::warn("Efficiency doesnt exist!");
		$error = true;
	}
	if(!$error){
		Notification::notice("Log was updated successfully!");
		$log_to_edit = Log::get($_POST['log_id']);
		$log_to_edit->edit_log($_POST['project_id'], $_POST['category_assoc_id'], $_POST['date'], $_POST['from'], $_POST['to'], $_POST['log_entry'], $_POST['work_place_id'], $_POST['efficiency_id']);
		//TODO: edit
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
		exit();
	}
}
//
//add new log
if(isset($_POST['add_log'])){
	$error = false;
	if(!Project::is_project_exist($_POST['project_id'])){
		Notification::warn("Project doesnt exist!");
		$error = true;
	}
	else{
		$project = Project::get($_POST['project_id']);
		if($project->get_status()->get_code() != 1){
			Notification::warn("The project is not active!");
			$error = true;
		}
		else if(!$project->is_associated_category_in_project($_POST['category_assoc_id'])){
			Notification::warn("The category is not in the project!");
			$error = true;
		}
	}
	$date = new DateTime($_POST['date']);
	$now = new DateTime();
	$interval = $now->diff($date);
	$difference = $interval->format('%R%a');
	if($difference<-4){
		Notification::warn("The date is too early!");
		$error = true;
	}
	else if($difference>0){
		Notification::warn("The date cannot be in the future");
		$error = true;
	}
	else{
		if(!isset($_POST['from']) || $_POST['from'] == ''){
			Notification::warn("FROM cannot be empty!");
			$error = true;
		}
		if(!isset($_POST['to']) || $_POST['to'] == ''){
			Notification::warn("TO cannot be empty!");
			$error = true;
		}
		$parsed_from = date_parse_from_format("H:i",$_POST['from']);
		if($parsed_from['warning_count']>0){
			Notification::warn("FROM value is not valid!");
			$error = true;
		}
		$parsed_to = date_parse_from_format("H:i",$_POST['to']);
		if($parsed_to['warning_count']>0){
			Notification::warn("To value is not valid!");
			$error = true;
		}
		if($parsed_from['warning_count'] == 0 && $parsed_to['warning_count'] == 0){
			if(isset($_POST['to']) && $_POST['to'] != '' && isset($_POST['from']) && $_POST['from'] != ''){
				$seconds_diff = strtotime($_POST['date']." ".date($_POST['to'])) - strtotime($_POST['date']." ".date($_POST['from']));
				if($seconds_diff == 0){
					Notification::warn("TO cannot be the same as FROM!");
					$error = true;
				}
				else if($seconds_diff < 0){
						
					Notification::warn("TO is smaller then FROM!");
					$error = true;
				}
				else{
					if(Log::is_overlap($user->get_id(), $_POST['date'], $_POST['from'], $_POST['to'])){
						Notification::warn("Time overlap!");
						$error = true;
					}
				}
			
			}	
		}
	}
	if(!isset($_POST['log_entry']) || $_POST['log_entry'] == ""){
		Notification::warn("Log entry cannot be empty!");
		$error = true;
	}
	if(!WorkPlace::is_workplace_exist($_POST['work_place_id'])){
		Notification::warn("Workplace doesnt exist!");
		$error = true;
	}
	if(!Efficiency::is_efficiency_exist($_POST['efficiency_id'])){
		Notification::warn("Efficiency doesnt exist!");
		$error = true;
	}
	if(!$error){
		Log::add_log($_POST['project_id'], $_POST['category_assoc_id'], $user->get_id(), $_POST['date'], date("H:i",strtotime($_POST['from'])),date("H:i",strtotime($_POST['to'])), $_POST['log_entry'], $_POST['work_place_id'], $_POST['efficiency_id']);
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
		exit();
	}
}
//
//selected user

if(!isset($_GET['user_id'])){
	$selected_user = $user;
}
else if(isset($_GET['user_id']) && !User::is_exist($_GET['user_id'])){
	Notification::warn("User does not exist!");
	$selected_user = $user;
}
else{
	$selected_user = User::get($_GET['user_id']);
}
//
//selected date
if(!isset($_GET['date'])){
	$selected_date = new DateTime("now");
}
else{
	$result_array = date_parse($_GET['date']);
	if($result_array['year'] && $result_array['month'] && $result_array['day']){
		$selected_date = new DateTime($result_array['year']."-".$result_array['month']."-".$result_array['day']);
	}
	else{
		$selected_date = new DateTime("now");
	}
}
$selected_date->modify("first day of this month");
//
//delete log
if(isset($_GET['delete_log']) && Log::is_log_exist($_GET['delete_log'])){
	$log = Log::get($_GET['delete_log']);
	if($user->get_id() == $selected_user->get_id() && $log->is_editable($selected_user->get_id())){
		Log::delete_log($_GET['delete_log']);
		Notification::notice("Log entry deleted successfully!");
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
		exit();
	}
	else{
		Notification::warn("You dont have the permission to delete this log!");
	}
}
?>
<script type="text/javascript" src="js/index.js"></script>
<div class="worklog-container">
	<div class="subheader">
		<div class="profile_photo" style="margin-top: 10px;">
			<a href="user_edit.php"><img
				src="photos/<?php echo $selected_user->get_picture();?>"
				title="Click to edit profile"> </a>
		</div>
		<div class="titlebar" style="float: left;">
			<h4>
				<?php echo $selected_user->get_name();?>
				's Worklog - Logs
			</h4>
			<form method="GET">
				<select name="date">
					<?php 
					$todayDate = new DateTime("now");
					$todayDate->modify("first day of this month");
					$earliest_log_date =  new DateTime($selected_user->get_earlies_log_date());
					$earliest_log_date->modify("first day of this month");
					if($earliest_log_date){
						$date = $todayDate;
						for($i=1; $earliest_log_date->format("Y-m-d") <= $date->format("Y-m-d"); $i++){
							$selected = "";
							if($selected_date->format("Y F") == $date->format("Y F")){
								$selected = "selected";
							}
							echo '<option value="'.$date->format('Y-m-d').'" '.$selected.'>'.$date->format('Y F').'</option>';
							$date->modify("-1 month");
						}
					}
					else{
						echo '<option value="201301">'.$todayDate->format('Y F').'</option>';
					}
					?>
				</select> <select name="user_id">
					<?php 
					$users = User::get_users();
					foreach($users as $u){
						/* @var $u User */
						$selected = "";
						if($u->get_id() == $selected_user->get_id()){
							$selected = "selected";
						}
						echo '<option value="'.$u->get_id().'" '.$selected.'>'.$u->get_name().'</option>';
					}
					?>
				</select> <input type="submit" value="OK" class="btn">
			</form>
		</div>
		<div class="personal_note">
			<form method="Post">
				My personal note: <input type="submit" name="save-personal-note"
					class="btn btn-mini" value="Save"
					style="float: right; margin-bottom: 3px;"> <br>
				<textarea name="personal-note" style="width: 250px; height: 60px;"><?php echo $user->get_personal_note();?></textarea>
			</form>
		</div>
		<div style="clear: both;"></div>
	</div>


	<hr>
	<div style="clear: both;"></div>

	<?php
	//Show notifications
	require_once 'include/notifications.php';
	?>
	<table class="table table-bordered">
		<?php if($selected_user->get_id() == $user->get_id()){
			if(isset($_GET['edit_log']) && Log::is_log_exist($_GET['edit_log'])){
				$log = Log::get($_GET['edit_log']);
				if($log->is_editable($user->get_id())){
					include 'include/edit_log_form.php';
				}
				else{
					Notification::warn("You do not have the permission to edit this log!");
				}
			}
			else{
				include 'include/add_log_form.php';
			}
		}
		?>
		<tr>
			<th width="150">Project</th>
			<th width="150">Category</th>
			<th width="150">Date</th>
			<th width="80">From</th>
			<th width="80">To</th>
			<th width="60">Diff</th>
			<th width="270">Log</th>
			<th width="100">Place</th>
			<th width="85"></th>
		</tr>
		<?php 
		
		function hoursToMinutes($hours)
		{
			if (strstr($hours, ':'))
			{
				# Split hours and minutes.
				$separatedData = explode(':', $hours);
		
				$minutesInHours    = $separatedData[0] * 60;
				$minutesInDecimals = $separatedData[1];
		
				$totalMinutes = $minutesInHours + $minutesInDecimals;
			}
			else
			{
				$totalMinutes = $hours * 60;
			}
		
			return $totalMinutes;
		}

		$logs = $selected_user->get_logs($selected_date->format("Y-m-d"));
		foreach($logs as $log){
			/* @var $log Log */
			$project  = Project::get($log->get_project_id());
			$category = AssociatedCategory::get($log->get_category_assoc_id());
			$work_place = WorkPlace::get($log->get_working_place_id());
			$efficiency = Efficiency::get($log->get_efficiency_id());
			
			
			$datetime1 = new DateTime($log->get_from());
			$datetime2 = new DateTime($log->get_to());
			$interval = $datetime1->diff($datetime2);
			
			$diff = $interval->format('%H:%I');
			
						
			if ((hoursToMinutes($diff)<=4) ) { $this_is_too_small = 'style="color: #ff0000"';} else {$this_is_too_small='';}
			
			echo '<tr>';
			echo '<td><a href="project_view.php?project_id='.$project->get_id().'">'.$project->get_name().'</a></td>';
			echo '<td>'.$category->get_name().'</td>';
			echo '<td>'.$log->get_date().'</td>';
			echo '<td>'.date("H:i",strtotime($log->get_from())).'</td>';
			echo '<td>'.date("H:i",strtotime($log->get_to())).'</td>';
			echo '<td '.$this_is_too_small.'>'.$diff.'</td>';
			echo '<td>'.nl2br(Tools::identify_link($log->get_entry())).'</td>';
			echo '<td>'.$work_place->get_name().' <br><span class="hint">'.$efficiency->get_name().'</span></td>';
			if($user->get_id() == $selected_user->get_id() && $log->is_editable($user->get_id())){
				echo '<td><a href="index.php?edit_log='.$log->get_id().'&date='.$selected_date->format('Y-m-d').'"><img src="images/modify.png"></a><span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
					</tr>
					<tr>
						<td><a href="index.php?delete_log='.$log->get_id().'" class="btn" id=""><font color="red">Igen</font></a></td>
						<td><font class="btn">Nem</font></td>
					</tr>
					</table>
					</ul> <br/><span style="font-size:8px;">LogID: '.$log->get_id().'</span> </td>
				</span>';
				
			}
			else{
				echo '<td><span style="font-size:8px;">LogID: '.$log->get_id().'</span></td>';
			}
			echo '</tr>';
		}
		?>
	</table>
	<?php 
	//show charts
	if(count($logs)>0){
		require_once 'include/show_charts.php';		
	}
	//
	
	?>





</div>
