<?php 
$selected_user_id    = $user->get_id();
if(isset($_GET['user_id']) && $_GET['user_id'] == ""){
	$selected_user_id = "";
}
else if(isset($_GET['user_id']) && User::is_exist($_GET['user_id'])){
	$selected_user_id = $_GET['user_id'];
}
$selected_date    = new DateTime();
$selected_date->modify("first day of this month");
$selected_date = $selected_date->format('Y-m-d');

$selected_date = "";
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
				<th width="50px">Workmate</th>
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
							if($selected_user_id == $u->get_id()){
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
						if($date->format('Y-m-d') == $first_log_date->format('Y-m-d')){
							if($selected_date == $date->format('Y-m-d')){
								echo '<option value="'.$date->format('Y-m-d').'" selected>'.$date->format('Y. F').'</option>';
							}
							else{
								echo '<option value="'.$date->format('Y-m-d').'" >'.$date->format('Y. F').'</option>';
							}
						}
						else{
							while($date->format('Y-m-d') != $first_log_date->format('Y-m-d')){
								$selected = "";
								if($selected_date == $date->format('Y-m-d')){
									$selected = 'selected = "selected"';
								}
								echo '<option value="'.$date->format('Y-m-d').'" '.$selected.'>'.$date->format('Y. F').'</option>';
								$date->modify("first day of previous month");
							}
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
			$summary = Log::get_sum_time_of_logs($selected_user_id, $selected_date,$selected_company);
			if($summary){
				foreach($summary as $row){
					$u = new User($row['worklog_user_id']);
					$c = new Company($row['worklog_company_id']);
					$monthName = date("F", mktime(0, 0, 0, $row['log_month'], 10));
					echo '<tr>
					<th><a href="index.php?user_id='.$u->get_id().'">'.$u->get_name().'</a></th>
					<td>'.$row['log_year'].'. '.$monthName.'</td>
					<td>'.$c->get_name().'</td>
					<td>'.$row['sum_time'].'</td>			
					</tr>';
				}

			}
			?>
		</table>
	</form>
	
	<?php 
	if(isset($_GET['date']) && $_GET['date'] != ""){
		
		
		
		
		$date_array = date_parse($_GET['date']);
		$daysinmonth = cal_days_in_month(CAL_GREGORIAN, $date_array['month'] , $date_array['year']);
		
		$current = $date_array['year']."-".$date_array['month']."-1";
		$total = Log::get_sum_time_of_logs_in_a_selected_month($current);
		
		$total = substr($total[0]['sum_time'],0,strpos($total[0]['sum_time'],':'));
		
		echo'<div class="subheader">
		<div class="titlebar">
		<h4>Daily sum in selected month (Total: '.$total.' hours)</h4>
		
		</div>
		</div>
		<hr>';
		
		 
		echo"<table><tr>";
		for ($i=1;$i<=$daysinmonth;$i++) {
			echo "<td align=\"center\" width=\"30\">$i</td>";
		}
		
		echo"</tr><tr>";
		
		for ($i=1;$i<=$daysinmonth;$i++) {
			
			$current = $date_array['year']."-".$date_array['month']."-04";
			$summary = Log::get_sum_time_of_logs_on_a_selected_day($current);

		 if ($summary[0]['sum_time']) {
		 	
		 	$thisday = (substr($summary[0]['sum_time'],0,strpos($summary[0]['sum_time'],':'))) / $total;
		 	
			echo '<td><div style="border:1px solid #d0d0d0; margin:3px; padding:0px;"><div title="'.$summary[0]['sum_time'].'" style="width:20px; height:20px; background:#005826; opacity:'.($thisday).';"></div></div></td>';
			
		 } else {
		 	echo '<td><div style="border:1px solid #d0d0d0; margin:3px; padding:0px;"><div title="00:00:00" style="width:20px; height:20px; background:#005826; opacity:0;"></div></div></td>';
		 }
			
		}
		
		
		
		
		 
		
		echo"<tr></table>";
		
	}
	
	
	
	
	?>

</div>
</div>
