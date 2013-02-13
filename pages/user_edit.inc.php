<?php
error_reporting(E_ALL);
$user_id = $_SESSION['enterid'];
$user = new User($user_id);
?>
<div class="worklog-container">

<div class="subheader">
	<div class="profile_photo">
			<img src="photos/<?php echo $user->get_picture();?>">
		</div>
		<div class="titlebar">
			<h4><?php echo $user->get_name();?>'s Settings</h4>
			Last login: <?php echo $user->get_enter_date();?>
		</div>
	</div>
	
		<div style="clear: both;"></div>
	<hr>
 
 
<form method="post">
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
			<td><input type="text" value="<?php echo $user->get_user_name();?>"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password"></td>
		</tr>
		<tr>
			<td>Password again</td>
			<td><input type="password"></td>
		</tr>		
		<tr>
			<td>Profile photo</td>
			<td><input type="file" value="tibor.hidi"><br/>
			<img src="photos/<?php echo $user->get_picture();?>">
			</td>
		</tr>
		<tr>
			<td>Default place</td>
			<td>
				<select style="width: 80px;">
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
			<td> </td>
			<td><input type="submit" value="Save" class="btn btn-primary"></td>
		</tr> 	 
	</table>
 </form>
	
	
	
	<hr>
	
	<div>
			<h4>Companies - csak adminoknak</h4>
			<form method="post">
			<table class="table table-bordered">
				<tr>
		 
					<td><input type="text" style="width: 450px;">
					</td>
					<td><input type="submit" value="Add" class="btn">
					</td>
				</tr>
				<?php
				$companies = Company::get_companies();
				foreach($companies as $company){
					/* @var $company Company */
					echo '<tr>
					<td width="120">'.$company->get_name().'</td>
					<td><img src="images/modify.png"> <img src="images/delete.png"></td>
					</tr>';
				} 
				?>
			</table>
			</form>
		</div>
		
		
		
	<hr>
	
	<div>
			<h4>Places - csak adminoknak</h4>
			<form method="post">
			<table class="table table-bordered">
				<tr>
		 
					<td><input type="text" style="width: 450px;">
					</td>
					<td><input type="submit" value="Add" class="btn">
					</td>
				</tr>
				<tr>
					<td width="120">Iroda1</td>
					<td><img src="images/modify.png"> <img src="images/delete.png"></td>
				</tr>
				<tr>
					<td width="120">Iroda2</td>
					<td><img src="images/modify.png"> <img src="images/delete.png"></td>
				</tr>
				<tr>
					<td width="120">Tárgyaló1</td>
					<td> </td>
				</tr>
			</table>
			</form>
		</div>
		
	
	<hr>
	
	<div>
			<h4>Categories - csak adminoknak</h4>
			<form method="post">
			<table class="table table-bordered">
				<tr>
		 
					<td><input type="text" style="width: 450px;">
					</td>
					<td><input type="submit" value="Add" class="btn">
					</td>
				</tr>
				<tr>
					<td width="120">megbeszélés</td>
					<td><img src="images/modify.png"> <img src="images/delete.png"></td>
				</tr>
				<tr>
					<td width="120">design</td>
					<td><img src="images/modify.png"> <img src="images/delete.png"></td>
				</tr>
				<tr>
					<td width="120">adatbázis</td>
					<td> </td>
				</tr>
			</table>
			</form>
		</div>
		
		
		
		
</div>
</div>
