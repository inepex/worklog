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

if(isset($_GET['date']) && $_GET['date'] == ""){
	$selected_date = "";
}
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

						//

						for(;$date->format('m')>= $first_log_date->format('m'); $date->modify("first day of previous month")){
						$selected = "";
						if($date->format('Y-m-d') == $selected_date){
							$selected = "selected";
						}

						echo '<option value="'.$date->format('Y-m-d').'" '.$selected.'>'.$date->format('Y. F').'</option>';
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

	// Heat map section

	if(isset($_GET['date']) && $_GET['date'] != ""){
	$date_array = date_parse($_GET['date']);
		}
		else{
	$date_for_heatmap    = new DateTime();
	$date_for_heatmap->modify("first day of this month");
	$date_array = date_parse($date_for_heatmap->format('Y-m-d'));
}

$daysinmonth = cal_days_in_month(CAL_GREGORIAN, $date_array['month'] , $date_array['year']);
$current = $date_array['year']."-".$date_array['month']."-1";
$total = Log::get_sum_time_of_logs_in_a_selected_month($selected_user_id,$current,$selected_company);
$total_hour = substr($total[0]['sum_time'],0,strpos($total[0]['sum_time'],':'));
$total_parts = explode(':', $total[0]['sum_time']);
$total_minutes = $total_parts[0]*60+$total_parts[1];
echo'<div class="subheader">
			<div class="titlebar">
			<h4>Daily sum in selected month (Total: '.$total_parts[0].':'.$total_parts[1].')</h4>
		</div>
		</div>
		<hr>';
echo"<table><tr>";
for ($i=1;$i<=$daysinmonth;$i++) {
			echo "<td align=\"center\" width=\"30\">$i</td>";
		}
		echo"</tr><tr>";

		for ($i=1;$i<=$daysinmonth;$i++) {

			$current = $date_array['year']."-".str_pad($date_array['month'], 2, "0", STR_PAD_LEFT)."-".str_pad($i, 2, "0", STR_PAD_LEFT);
			$summary = Log::get_sum_time_of_logs_on_a_selected_day($selected_user_id,$current,$selected_company);

		 if ($summary[0]['sum_time']) {
			$summary_parts = explode(":", $summary[0]['sum_time']);
			$summary_minutes = $summary_parts[0]*60+$summary_parts[1];
			$thisday = $summary_minutes / $total_minutes;
			echo '<td><div style="border:1px solid #d0d0d0; margin:3px; padding:0px;"><div title="'.$summary[0]['sum_time'].'" style="width:22px; height:20px; background:#005826; opacity:'.($thisday).';"></div></div></td>';
		 }else{
		 	echo '<td><div style="border:1px solid #d0d0d0; margin:3px; padding:0px;"><div title="00:00:00" style="width:22px; height:20px; background:#005826; opacity:0;"></div></div></td>';
		 }
		}
		echo"<tr></table>";
		?>
	<div class="subheader">
		<div class="titlebar">
			<h4>Export</h4>

		</div>

	</div>
	<hr>

	<form method="GET" action="export.php">

		<select name="user_id">
			<option value="0">All</option>
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
		</select> <input type="text" style="width: 80px;" value="datefrom"
			class="datepicker" name="date_from"> <input type="text"
			style="width: 80px;" value="dateto" class="datepicker" name="date_to">
		<input type="submit" class="btn btn-primary" value="Export">
	</form>
</div>
</div>
