<?php 
$selected_user    = $user;
if(isset($_GET['user_id']) && $_GET['user_id'] != "" && User::is_exist($_GET['user_id'])){
	$selected_user = new User($_GET['user_id']);
}
$selected_date    = new DateTime();
$selected_date->modify("first day of this month");
$selected_date = $selected_date->format('Y-m-d');
if(isset($_GET['date']) && $_GET['date'] != ""){
	$date_array = date_parse($_GET['date']);
	if($date_array['year'] && $date_array['month'] && $date_array['day']){
		$selected_date = new DateTime($date_array['year'].'-'.$date_array['month'].'-'.$date_array['day']);
		$selected_date = $selected_date->format('Y-m-d');
	}
}
$selected_company = "";
if(isset($_GET['company_id']) && $_GET['company_id'] != "" && Company::is_company_exist($_GET['company_id'])){
	$selected_company = $_GET['company_id'];
}
?>
<div class="worklog-container">

	<div class="subheader">
		<div class="titlebar">
			<h4>Summary</h4>

		</div>
	</div>
	<hr>


	<form method="GET">
		<table class="table table-bordered" style="width: 0;">

			<tr>
				<th>Workmate</th>
				<th>Month</th>
				<th>Company</th>
				<th>Workhours</th>
			</tr>

			<tr>
				<td><select name="user_id">
						<option value="">All</option>
						<?php 
						$users = User::get_users();
						foreach($users as $u){
							/* @var $u User */
							$selected = "";
							if($selected_user->get_id() == $u->get_id()){
									$selected = 'selected = "selected"';
							}
							echo '<option value="'.$u->get_id().'" '.$selected.'>'.$u->get_name().'</option>';
						}
						?>
				</select>
				</td>
				<td><select name="date">
						<option value="">Always</option>
						<?php 
						$first_log_date = Log::get_first_log_date();
						$first_log_date = new DateTime($first_log_date);
						$first_log_date->modify("first day of this month");
						$date = new DateTime("now");
						$date->modify("first day of this month");
						while($date->format('Y-m-d') != $first_log_date->format('Y-m-d')){
							$selected = "";
							if($selected_date == $date->format('Y-m-d')){
								$selected = 'selected = "selected"';
							}
							echo '<option value="'.$date->format('Y-m-d').'" '.$selected.'>'.$date->format('Y-m-d').'</option>';
							$date->modify("first day of previous month");
						}
						?>
				</select>
				</td>
				<td><select name="company_id">
						<option value="">All</option>
						<?php 
						$companies = Company::get_companies();
						foreach($companies as $company){
							$selected = "";
							if($selected_company == $company->get_id()){
								$selected = 'selected = "selected"';
							}
							/* @var $company Company */
							echo '<option value="'.$company->get_id().'" '.$selected.'>'.$company->get_name().'</option>';
						}
						?>
				</select>
				</td>
				<td><input type="submit" class="btn" value="OK">
				</td>
			</tr>
			<?php 
				//TODO: lekérést megírni:$user->get_worked_hours($date,$company);
			
			?>


			<tr>
				<th>Judit Osvath</th>
				<td>2012. January</td>
				<td>Inepex Ltd</td>
				<td>120:10</td>
			</tr>
			<tr>
				<th>Judit Osvath</th>
				<td>2012. January</td>
				<td>Székhelyszolgálat.net</td>
				<td>40:10</td>
			</tr>
			<tr>
				<th>Tibor Hidi</th>
				<td>2012. January</td>
				<td>Székhelyszolgálat.net</td>
				<td>40:10</td>
			</tr>

		</table>
	</form>

</div>
</div>
