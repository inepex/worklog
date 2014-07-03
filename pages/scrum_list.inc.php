<?php 
if(!isset($_GET['user_id'])){
	$selected_user = $user;
}
else if(isset($_GET['user_id']) && !User::is_exist($_GET['user_id'])){
	Notification::warn("User does not exist!");
	$selected_user = $user;
}
else{
	$selected_user = new User($_GET['user_id']);
}
?><div class="worklog-container">

	<div class="subheader">
		<div class="titlebar">
		<div style="float: left;"><form method="GET">
			<h4>List Scrum for
			
			<select name="user_id">
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
			</h4>
		</div>
		<div style="float: right;">
			<a href="scrum_add.php" class="btn btn-inverse">Add Scrum</a>
		</div>
		<div style="clear: both;"></div>
		</div>
	</div>
	<hr>
	 
	<div style="clear: both;"></div>
 	<form method="post">
	<table class="table table-bordered">
		<tr>
			<th width="120">Month:</th>
			<th width="350">Charts</th>
			<th width="250">What happened:</th>
			<th width="250">What is the plan:</th>
			<th width="16"></th>
		</tr>
		<?php 
		$scrum_list = Scrum::get_scrum_by_user($selected_user->get_id());
				
		foreach($scrum_list as $scrum){
			$date=new DateTime($scrum->get_month());
			echo '<tr><td>'.$date->format("Y F").'</td><td>';
			
			echo Tools::get_chart(User::get($scrum->get_user_id()),$date);
			
			echo '</td><td>'.nl2br(Tools::identify_link($scrum->get_past())).'</td><td>'.nl2br(Tools::identify_link($scrum->get_future())).'</td><td>';
		
			if ($scrum->get_user_id()==$user->get_id()) {
				echo '<a href="scrum_edit.php?scrum_id='.$scrum->get_id().'"><img src="images/modify.png"></a>';
			}
			
			echo '</td></tr>';
		}


		?>
		 		
	</table>
	</form>
	 
</div>
 
