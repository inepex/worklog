<?php
$error=0;
if(isset($_POST['action']) && $_POST['action'] =='add') {
	if((isset($_POST['username']) && $_POST['username'] == '') || (isset($_POST['name']) && $_POST['name'] == '')) {
		$error=1;
		Notification::warn("Please fill each row!");
	} else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$error=1;
		Notification::warn("Invalid email format!");
	} else if(strlen($_POST['password'])<6) {
		$error=1;
		Notification::warn("The password is too short (min. 6 characters)!");
	} else {
		User::new_user($_POST['username'], $_POST['password'], $_POST['email'], $_POST['name'], $_POST['default-place'], $_POST['default-efficiency']);
	}
}

if(isset($_POST['action']) && $_POST['action'] =='edit'){
	if(isset($_POST['user_id']) && $_POST['user_id'] !='' && isset($_POST['user_status']) && $_POST['user_status'] !='') {
		$user = User::get($_POST['user_id']);
		$user->edit_status($_POST['user_status']);
	}
}

?>

<div class="worklog-container">

	<div class="subheader">
		<div class="titlebar">
			<h4>Edit Users</h4>
		</div>
	</div>
	<hr>
	<div>
		<h5>Add User</h5>
		
		<?php
		//Show notifications
		require_once 'include/notifications.php';
		?>
		
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<table class="table table-bordered">
			<?php
				$workPlaces = WorkPlace::get_places();
				$efficiencies = Efficiency::get_efficiencies();
				echo '
					<tr>
						<td>
							<label for="username">Username</label><input type="text" style="width: 250px;"  name="username">
							<label for="password">Password</label><input type="text" style="width: 250px;"  name="password">
							<label for="name">Full name</label><input type="text" style="width: 450px;"  name="name">
							<label for="email">E-mail</label><input type="text" style="width: 450px;"  name="email">
							<label for="company">Place</label>
							<select style="width: 150px;" name="default-place">';
							foreach($workPlaces as $workPlace){
								echo'<option value="'.$workPlace->get_id().'">'.$workPlace->get_name().'</option>';
							} echo '
							</select>
							<label for="efficiency">Efficiency</label>
							<select style="width: 150px;" name="default-efficiency">';
							foreach($efficiencies as $efficiency){
								echo '<option value="'.$efficiency->get_id().'">'.$efficiency->get_name().'</option>';
							} echo '
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" value="Add User" class="btn">
						</td>

					</tr>
					<input type="hidden" name="action" value="add" />';
			?>
			</table>
		</form>
	</div>
	<div>
		<h5>Edit User Status</h5>
		<a name="users"></a>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
			<table class="table table-bordered">
				<?php
					if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['user_id']) && $_GET['user_id'] !='') {
						$user = User::get($_GET['user_id']);
					echo '
						<tr>
							<td style="width:400px;">
								<h5>'.$user->get_name().'</h5>
								<select name="user_status">
									<option value="1"'.$user->get_selected_if_state_equals(1).'>Active</option>
									<option value="0"'.$user->get_selected_if_state_equals(0).'>Inactive</option>
									<option value="2"'.$user->get_selected_if_state_equals(2).'>Admin</option>
								</select>
							</td>
							<td>
								<input type="submit" value="Save" class="btn"/>		<a href="add_user.php#users">Mégse</a>
							</td>
						</tr>
						<input type="hidden" name="action" value="edit"/>
						<input type="hidden" name="user_id" value="'.$user->get_id().'"/>
					';
					}
					$users = User::get_users();
					/* @var $user User */
					foreach($users as $user){
						echo '
						<tr>
							<td width="400">
								'.$user->get_name().'
							</td>
							<td>
								<a href="?action=edit&user_id='.$user->get_id().'#users"><img src="images/modify.png"></a>&nbsp;<span class="dropdown"></span>
							</td>
						</tr>';
					}
				?>
			</table>
		</form>
	</div>
</div>