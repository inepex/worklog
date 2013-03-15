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
				<th></th>
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
				<td></td>
			</tr>
			<?php 
			$summary = Log::get_sum_time_of_logs($selected_user_id, $selected_date,$selected_company);
			if($summary){
				$counter = 0;
				foreach($summary as $row){

					$u = new User($row['worklog_user_id']);
					$user_worked_hours_in_categories = $u->get_worked_hours_in_categories($row['log_year'].'-'.$row['log_month'].'-'.'01');
					$user_worked_hours_in_projects = $u->get_worked_hours_in_projects($row['log_year'].'-'.$row['log_month'].'-'.'01');
					$c = new Company($row['worklog_company_id']);
					$monthName = date("F", mktime(0, 0, 0, $row['log_month'], 10));
					echo '<tr>
					<th>'.$u->get_name().'</th>
					<td>'.$row['log_year'].'. '.$monthName.'</td>
					<td>'.$c->get_name().'</td>
					<td>'.$row['sum_time'].'</td>
					<td><div id="chart_div'.$counter.'"></div><div id="chart_div_'.$counter.'"></div></td>
					</tr>';
					//show chart
					echo '<script type="text/javascript">
					google.load("visualization", "1", {packages:["corechart"]});
					google.setOnLoadCallback(drawChart);
					google.setOnLoadCallback(drawChart2);

					function drawChart() {

					var data = google.visualization.arrayToDataTable([

					["Type", "Count"],';
					foreach ($user_worked_hours_in_categories as $user_worked_hours_in_category){
						$category = new Category($user_worked_hours_in_category['category_id']);
						echo '["'.$category->get_name().'", '.$user_worked_hours_in_category['worked_hours'].'],';
					}

					echo ']
					);

					var options = {
					title: "user worked hours in category","width":400,
					"height":300,sliceVisibilityThreshold:0
				};

				var chart = new google.visualization.PieChart(document.getElementById("chart_div'.$counter.'"));
				chart.draw(data, options);
				}
					
					
				function drawChart2() {
					
				var data2 = google.visualization.arrayToDataTable([
					
				["Type", "Count"],';
					foreach ($user_worked_hours_in_projects as $user_worked_hours_in_project){
						$project = new Project($user_worked_hours_in_project['project_id']);
						echo '["'.$project->get_name().'", '.$user_worked_hours_in_project['worked_hours'].'],';
					}

					echo ']
					);

					var options2 = {
					title: "user worked hours in projects","width":400,
					"height":300,sliceVisibilityThreshold:0
				};

				var chart2 = new google.visualization.PieChart(document.getElementById("chart_div_'.$counter.'"));
				chart2.draw(data2, options2);
				}
				</script>';

					//
					$counter++;
				}

			}
			?>
		</table>
	</form>

</div>
</div>
