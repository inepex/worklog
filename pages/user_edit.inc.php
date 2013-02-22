<?php
//SAVE PROFILE CHANGES
$error=0;
if(isset($_POST['save-profile-button'])){
	if(isset($_POST['user-name']) && $_POST['user-name'] == ""){
		$error=1;
		Notification::warn("The user name cant be empty!");
	}
	if(isset($_POST['user-name']) && $_POST['user-name'] != ""){
		$user->edit_user_name($_POST['user-name']);
	}
	if(isset($_POST['default-workplace']) && $_POST['default-workplace'] != ""){
		$user->edit_default_workplace($_POST['default-workplace']);
	}
	if((isset($_POST['password']) || isset($_POST['re-password'])) && $_POST['password'] != $_POST['re-password']){
		$error=1;
		Notification::warn("The passwords are not the same!");
	}
	else if(isset($_POST['password']) && isset($_POST['re-password']) && (strlen($_POST['password'])<6 || strlen($_POST['re-password'])<6) && (strlen($_POST['password'])!="" || strlen($_POST['re-password'])!="")){
		$error=1;
		Notification::warn("The password is too short (min. 6 character)!");
	}
	else if(isset($_POST['password']) && isset($_POST['re-password']) && $_POST['password'] != "" && $_POST['re-password'] != "" && $_POST['re-password'] == $_POST['password']){
		$user->edit_password($_POST['password']);
	}
	if(isset($_FILES['profile-photo']['name']) && $_FILES['profile-photo']['name'] != ""){
		$user->edit_profile_picture($_FILES['profile-photo']);
	}
	if($error==0){
		Notification::notice("Saved successfully");
	}
}
//
//SAVE ADMIN CHANGES
if($user->get_status() == "2"){
	if(isset($_GET['action']) && $_GET['action'] =='delete'){
		if(isset($_GET['company_id']) && $_GET['company_id'] !=''){
			Company::delete_company($_GET['company_id']);
			header('Location:user_edit.php');
			exit();
		}
		if(isset($_GET['place_id']) && $_GET['place_id'] !=''){
			WorkPlace::delete_work_place($_GET['place_id']);
			header('Location:user_edit.php');
			exit();
		}
		if(isset($_GET['category_id']) && $_GET['category_id'] !=''){
			Category::delete_category($_GET['category_id']);
			header('Location:user_edit.php');
			exit();
		}
	}
	if(isset($_POST['action']) && $_POST['action'] =='add'){
		if(isset($_POST['company_name']) && $_POST['company_name'] !=''){
			Company::new_company($_POST['company_name']);
		}
		if(isset($_POST['place_name']) && $_POST['place_name'] !=''){
			WorkPlace::new_place($_POST['place_name']);
		}
		if(isset($_POST['category_name']) && $_POST['category_name'] !=''){
			Category::new_category($_POST['category_name']);
		}
	}
	if(isset($_POST['action']) && $_POST['action'] =='edit'){
		if(isset($_POST['company_id']) && $_POST['company_id'] !='' && isset($_POST['company_name']) && $_POST['company_name'] !=''){
			$company = new Company($_POST['company_id']);
			$company->edit_name($_POST['company_name']);
		}
		if(isset($_POST['place_id']) && $_POST['place_id'] !='' && isset($_POST['place_name']) && $_POST['place_name'] !=''){
			$place = new WorkPlace($_POST['place_id']);
			$place->edit_name($_POST['place_name']);
		}
		if(isset($_POST['category_id']) && $_POST['category_id'] !='' && isset($_POST['category_name']) && $_POST['category_name'] !=''){
			$category = new Category($_POST['category_id']);
			$category->edit_name($_POST['category_name']);
		}
	}
}//
?>
<div class="worklog-container">

	<div class="subheader">
		<div class="profile_photo">
			<img src="photos/<?php echo $user->get_picture();?>">
		</div>
		<div class="titlebar">
			<h4>
				<?php echo $user->get_name();?>
				's Settings
			</h4>
			Last login:
			<?php echo $user->get_enter_date();?>
		</div>
	</div>

	<div style="clear: both;"></div>
	<hr>
	
	<?php
	//Show notifications 
	require_once 'include/notifications.php';
	?>

	<form method="post" enctype="multipart/form-data">
		<table class="table table-bordered" style="width: 0;">
			<tr>
				<td>Full name</td>
				<td><?php echo $user->get_name();?></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><?php echo $user->get_email();?></td>
			</tr>
			<tr>
				<td>Username</td>
				<td><input type="text" name="user-name"
					value="<?php echo $user->get_user_name();?>"></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="password" name="password"></td>
			</tr>
			<tr>
				<td>Password again</td>
				<td><input type="password" name="re-password"></td>
			</tr>
			<tr>
				<td>Profile photo</td>
				<td class="profile_photo"><input type="file" name="profile-photo"><br />
					<img src="photos/<?php echo $user->get_picture();?>">
				</td>
			</tr>
			<tr>
				<td>Default place</td>
				<td><select style="width: 80px;" name="default-workplace">
						<?php 
						$workPlaces = WorkPlace::get_places();
						foreach($workPlaces as $workPlace){
							/* @var $workPlace WorkPlace */
							$selected = "";
							if($user->get_default_place()->get_id() == $workPlace->get_id()){
								$selected = "selected";
							}
							echo '<option value="'.$workPlace->get_id().'" '.$selected.' >'.$workPlace->get_name().'</option>';
						}
						?>
				</select>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="submit" value="Save" name="save-profile-button"
					class="btn btn-primary"></td>
			</tr>
		</table>
	</form>
	<?php 
	if($user->get_status()=='2'){
		require_once 'include/admin_part.php';
	}
	?>
	<hr>
</div>
