<?php 
$selected_user = $user;
$selected_date = new DateTime("now");


if ((isset($_POST['past'])) && (isset($_POST['future']))){
	$error=false;
	 
 
	if($scrum = Scrum::new_scrum($user->get_id(), $_POST['month'], $_POST['past'], $_POST['future'])){
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=scrum_list.php?user_id=".$user->get_id()."\">";
		exit();
	}
	else{
		Notification::warn("Something wrong with the new scrum  :/ !");
	}
	 
}


?><div class="worklog-container">

	<div class="subheader">
		<div class="titlebar">
			<h4>Add Scrum</h4>

		</div>
	</div>
	<hr>
	<?php require_once 'include/notifications.php';?>
	<div style="clear: both;"></div>
 	<form method="post">
	<table class="table table-bordered">
		<tr>
			<td width="120">Month:</td>
			<td><select name="month">
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
			</select>
			 </td>
		</tr>
		<tr>
			<td>What I did this month:</td>
			<td><textarea style="width: 700px; height: 100px;" name="past"><?php echo ((isset($_POST['past']))?$_POST['past']:'')?></textarea></td>
		</tr>
		<tr>
			<td>What I want to do next month:</td>
			<td><textarea style="width: 700px; height: 100px;" name="future"><?php echo ((isset($_POST['future']))?$_POST['future']:'')?></textarea></td>
		</tr>							
		<tr>
					<td></td>
					<td><input type="submit" class="btn btn-primary" value="Save" name="scrum_add"></td>
				</tr>		
	</table>
	</form>
	 
</div>
 
