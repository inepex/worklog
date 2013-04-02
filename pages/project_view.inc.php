<?php 
if(isset($_GET['project_id']) && $_GET['project_id'] !="" && Project::is_project_exist($_GET['project_id'])){
	$project = new Project($_GET['project_id']);
}
else{
	Notification::warn("Project does not exist!");
	echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
	exit();
}

?>
<script
	type="text/javascript" src="js/projectView.js"></script>
<div class="worklog-container">
	<div class="subheader">

		<div class="titlebar">
			<div style="float: left;">
				<h4>
					<?php
					 echo $project->get_name().' ('.$project->get_status()->get_name().')'?>
				</h4>
				<i><?php echo $project->get_company()->get_name()?> project</i>
				<?php 
				$owner = $project->get_user();
				?>
				- Owner: <a href="index.php?user_id=<?php echo $owner->get_id()?>">  <?php echo $owner->get_name();?> </a>
		
			</div>
			<span style="float: right;">
				<div class="dropdown">
					<a href="#" class="btn dropdown-toggle" data-toggle="dropdown">Duplicate
						project</a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td>Név:</td>
						<td><input type="text" name="duplicate" id="duplicate_name"/></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><a href="#" class="btn" id="duplicate">Duplikálás</a></td>
					</tr>
					</table>
					</ul>
				</span>
				<a href="project_view.php?project_id=<?php echo $project->get_id();?>" class="btn btn-inverse">Project Page</a>
				
				<?php 
				if($project->get_status()->get_code() == 2 || $user->is_admin()){
					echo '<a href="project_edit.php?project_id='.$project->get_id().'" class="btn ">Edit Project</a>';
				}
				?>
				
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<hr>
	<h4><img src="images/description.png"> Description:</h4>
	<?php echo $project->get_description();?>
	<hr>
	<div style="width: 50%; float:left;">
	<h4><img src="images/beginning.png"> Beginning:</h4>
	<?php echo $project->get_beginning();?>
	</div>
	<div style="width: 50%; float:right;">
	<h4><img src="images/destination.png"> Destination:</h4>
	<?php echo $project->get_destination();?>
	</div>
	<div style="clear:both;"></div>
	<hr>
	<h4><img src="images/start.png"> Start/Deadline:</h4> <?php echo $project->get_start_date().' - '.$project->get_start_date();?>
	<hr>
	
	<h4><img src="images/categories.png"> Categories:</h4>
	<?php
	$categories = $project->get_categories();
	?>
	<table class="table table-bordered" style="width: 0;">
		<?php 
		foreach($categories as $category){
			/* @var $category AssociatedCategory */
			echo '<tr>
			<th>'.$category->get_name().'</th>
			<td>'.$category->get_description().'</td>
			</tr>';
		}
		?>
	</table>

	<hr>
	<div style="clear: both;"></div>
	<h4><img src="images/plan.png"> Project Plan</h4>
 
	<table class="table table-bordered" style="width: 0;">
		<tr>
			<th></th>
			<?php
			$workmates = $project->get_workmates();
			foreach($workmates as $workmate){
				/* @var $u AssociatedUser */
				echo '<th style="text-align:center!important;"><a href="index.php?user_id='.$workmate->get_id().'"><img src="photos/'.$workmate->get_picture().'" width="30" alt="'.$workmate->get_name().'" title="'.$workmate->get_name().'"></a><br><span style="font-size:10px;">'.$workmate->get_name().'</span></th>';
			}
			?>
			<th>SUM</th>
		</tr>
		<?php 
		foreach ($categories as $associated_category){
			echo '<tr class="project-plan">';
			echo '<th>'.$associated_category->get_name().'</th>';
			foreach ($workmates as $workmate){
				//$entry['user_id'] = $workmate->get_id();
				//$entry['category_assoc_id'];         =
				echo '<td>'.$project->get_project_plan()->get_sum_for_category_and_user($workmate->get_id(), $associated_category->get_assoc_id()).'</td>';
			}
			echo '<td>'.$project->get_project_plan()->get_sum_for_category($associated_category->get_assoc_id()).'</td>';
			echo '</tr>';
		}
		echo '<tr class="project-plan">';
		echo '<th>SUM</th>';
		foreach ($workmates as $workmate){
			echo '<td>'.$project->get_project_plan()->get_sum_for_user($workmate->get_id()).'</td>';
		}
		echo '<td>'.$project->get_project_plan()->get_sum_of_entries().'</td>';
		echo '</tr>';
		?>
	</table>

	<hr>
	<h4 style="float: left;"><img src="images/statistics.png"> Statistics</h4>

	<table class="table table-bordered">

		<?php 
		$categories   = $project->get_categories();
		$project_plan = $project->get_project_plan();
		foreach($categories as $category){
			$percent = $category->get_category_status_in_percent();
			echo '<tr>
			<td width="180">'.$category->get_name().' <img src="images/information.png"
			title="'.$category->get_description().'">
			</td>
			<td width="670">';
			$status_bar = new StatusBar($percent);
			$status_bar->show_progress_bar();
			$users_with_planned_hours = $category->get_users_with_planned_hours();
			foreach ($users_with_planned_hours as $user_with_planned_hours){
				/* @var $user_with_planned_hours AssociatedUser */
				$worked_hours  = $user_with_planned_hours->get_worked_hours_in_associated_category($category);
				$planned_hours = $user_with_planned_hours->get_planned_hours_in_associated_category($category);
				echo '<div class="statistic_member"><font'.(((substr($worked_hours,0,2)*60+ substr($worked_hours,3,2))>(substr($planned_hours,0,2)*60+ substr($planned_hours,3,2)))?' color="red"':'').'><a href="index.php?user_id='.$workmate->get_id().'"><img src="photos/'.$user_with_planned_hours->get_picture().'" width="20" alt="'.$user_with_planned_hours->get_name().'" title="'.$user_with_planned_hours->get_name().'"></a>&nbsp;'.$worked_hours.'/'.$planned_hours.'</font></div>';
			}
			echo '
			</td>
			<td width="100"><font '.(($percent>100)?'color="red"':'').'>'.$category->get_sum_of_worked_hours().' / '.$project_plan->get_sum_for_category($category->get_assoc_id()).':00  <br><span style="font-size:16px;font-weight:bold;">'.$percent.'%</span><font></td>
			</tr>';
		} 
		$progress_bar = new StatusBar($project->get_worked_per_planned_hour_in_percent(), 'success');
		echo '<tr><td width="180">SUM</td><td width="670">';
		$progress_bar->show_progress_bar();
		echo '</td><td width="100"><font '.(($project->get_worked_per_planned_hour_in_percent()>100)?'color="red"':'').'>'.$project->get_sum_of_worked_hours().' / '.$project->get_project_plan()->get_sum_of_entries().':00 <br><span style="font-size:16px;font-weight:bold;">'.$project->get_worked_per_planned_hour_in_percent().'%</span></font></td></tr>';
		
		
		?>
	</table>

	<hr>


	<h4 style="float: left;"><img src="images/logs.png"> Latest logs</h4>

	<form method="GET" action="project_view.php">
		<div style="float: right;">
			<input type="hidden" name="project_id" id="project_id"
				value="<?php echo $project->get_id();?>"> Filters: <select
				style="width: 120px !important;" name="user_id">
				<?php foreach($workmates as $workmate){
					$selected = "";
					if(isset($_GET['user_id']) &&$_GET['user_id'] == $workmate->get_id()){
						$selected = 'selected = "selected"';
					}
					echo '<option value="'.$workmate->get_id().'" '.$selected.' >'.$workmate->get_name().'</option>';
			}?>

			</select> <select style="width: 120px !important;"
				name="associated_category_id">
				<?php 
				foreach($categories as $category){
					$selected = "";
					if(isset($_GET['associated_category_id']) && $_GET['associated_category_id'] == $category->get_assoc_id()){
						$selected = 'selected = "selected"';
					}
					echo '<option value="'.$category->get_assoc_id().'" '.$selected.' title="'.$category->get_description().'">'.$category->get_name().'</option>';
				}
				?>
			</select> <input type="text" style="width: 80px;" value="datefrom"
				class="datepicker" name="date_from"> <input type="text"
				style="width: 80px;" value="dateto" class="datepicker"
				name="date_to"> <input type="submit" class="btn" value="OK"
				style="width: 60px;">
		</div>
	</form>
	<div style="clear: both;"></div>

	<table class="table table-bordered">
		<tr>
			<th>Workmate</th>
			<th>Category</th>
			<th>Date</th>
			<th>From</th>
			<th>To</th>
			<th>Log</th>
			<th>Place</th>
		</tr>
		<?php
		$user_id                = "";
		$logs_from              = 0;
		$associated_category_id = "";
		$date_from                   = "";
		$date_to                     = "";
		if(isset($_GET['user_id']) && $_GET['user_id']!="" && User::is_exist($_GET['user_id'])){
			$user_id = $_GET['user_id'];
		}
		if(isset($_GET['logs_from']) && $_GET['logs_from']!="" && is_int((int)$_GET['logs_from'])){
			$logs_from = (int)$_GET['logs_from'];
		}
		if(isset($_GET['associated_category_id']) && $_GET['associated_category_id']!="" && strtotime($_GET['associated_category_id'])){
			$associated_category_id = $_GET['associated_category_id'];
		}
		if(isset($_GET['date_from']) && $_GET['date_from']!="" && date_parse($_GET['date_from'])){
			$date_from = $_GET['date_from'];
		}
		if(isset($_GET['date_to']) && $_GET['date_to']!="" && date_parse($_GET['date_to'])){
			$date_to = $_GET['date_to'];
		}
		$logs = $project->get_logs_of_project($user_id, $associated_category_id,$date_from, $date_to, $logs_from);
		$number_of_logs = count($project->get_logs_of_project($user_id, $associated_category_id,$date_from, $date_to));
		foreach($logs as $log){
			/* @var $log Log */
			$user = new User($log->get_user_id());
			$associated_category = new AssociatedCategory($log->get_category_assoc_id());
			$working_place = new WorkPlace($log->get_working_place_id());
			echo '<tr>
			<td>'.$user->get_name().'</td>
			<td>'.$associated_category->get_name().'</td>
			<td>'.$log->get_date().'</td>
			<td>'.$log->get_from().'</td>
			<td>'.$log->get_to().'</td>
			<td>'.$log->get_entry().'</td>
			<td>'.$working_place->get_name().'</td>
			</tr>';
		}
		?>
	</table>

	<div class="pagination pagination-centered">
		<ul>
			<li><a
				href="<?php echo 'project_view.php?project_id='.$project->get_id().'&associated_category_id='.$associated_category_id.'&user_id='.$user_id.'&logs_from='.(($logs_from-Log::$LISTING_LIMIT)<0?0:($logs_from-Log::$LISTING_LIMIT));?>">Prev</a>
			</li>
			<?php
			for ($i = 0; $i<ceil($number_of_logs/Log::$LISTING_LIMIT); $i++){
				if($i*Log::$LISTING_LIMIT == $logs_from){
					echo '<li><a href="project_view.php?project_id='.$project->get_id().'&associated_category_id='.$associated_category_id.'&user_id='.$user_id.'&logs_from='.($i*Log::$LISTING_LIMIT).'" style="color: red;">'.($i+1).'</a></li>';
				}
				else{
					echo '<li><a href="project_view.php?project_id='.$project->get_id().'&associated_category_id='.$associated_category_id.'&user_id='.$user_id.'&logs_from='.($i*Log::$LISTING_LIMIT).'">'.($i+1).'</a></li>';
				}
			}
			?>
			<li><a
				href="<?php echo 'project_view.php?project_id='.$project->get_id().'&associated_category_id='.$associated_category_id.'&user_id='.$user_id.'&logs_from='.($logs_from+Log::$LISTING_LIMIT);?>">Next</a>
			</li>
		</ul>
	</div>

</div>
</div>
