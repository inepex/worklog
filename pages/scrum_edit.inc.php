<?php 
$selected_user = $user;
$selected_date = new DateTime("now");


if (isset($_GET['scrum_id'])) {
	 
	$scrum = new Scrum ($_GET['scrum_id']); 
	$scrumuser = User::get($scrum->get_user_id());
	$scrumdate = new DateTime($scrum->get_month());
		 
}

if ((isset($_POST['past'])) && (isset($_POST['future']))){
	$error=false;
	
	$scrum = new Scrum ($_POST['scrum_id']);
	 
	if ($scrum->get_user_id()!=$user->get_id()) {
		Notification::warn("This is not your scrum, you cannot modify it!");
	} else {
		$scrum->edit_scrum($_POST['past'], $_POST['future']);
		
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=scrum_list.php?user_id=".$user->get_id()."\">";
	}

	 
}



?><div class="worklog-container">

	<div class="subheader">
		<div class="titlebar">
			<h4>Edit Scrum (<?php echo $scrumuser->get_name();?> / <?php echo $scrumdate->format('Y F');?>)</h4>

		</div>
	</div>
	<hr>
	<?php require_once 'include/notifications.php';?>
	<div style="clear: both;"></div>
 	<form method="post">
	<table class="table table-bordered">
		 
		<tr>
			<td>What I did this month:</td>
			<td><textarea style="width: 700px; height: 100px;" name="past"><?php echo $scrum->get_past(); ?></textarea></td>
		</tr>
		<tr>
			<td>What I want to do next month:</td>
			<td><textarea style="width: 700px; height: 100px;" name="future"><?php echo $scrum->get_future(); ?></textarea></td>
		</tr>							
		<tr>
		<td></td>
		<td><input type="hidden" name="scrum_id" value="<?php echo $scrum->get_id(); ?>"><input type="submit" class="btn btn-primary" value="Save" name="scrum_edit"></td>
	</tr>		
	</table>
	</form>
	 
</div>
 
