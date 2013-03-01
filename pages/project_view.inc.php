<?php 
if(Project::is_project_exist($_GET['project_id'])){
	$project = new Project($_GET['project_id']);
}
else{
	Notification::warn("Project does not exist!");
	header('Location:index.php');
	exit();
}

?>
<div class="worklog-container">

	<div class="subheader">

		<div class="titlebar">
			<div style="float: left;">
				<h4><?php echo $project->get_name();?></h4>
				<i><?php echo $project->get_company()->get_name()?> project</i>
			</div>
			<div style="float: right;">
				<a href="#" class="btn">Duplicate project</a> <a
					href="project_view.php" class="btn btn-inverse">Project Page</a> <a
					href="project_edit.php" class="btn ">Edit Project</a>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<hr>
		<?php echo $project->get_description();?>
	<hr>
	<?php 
	$owner = $project->get_user();
	?>
	<h4>Workmates:</h4>

	<a href="#"> <img src="photos/<?php echo $owner->get_picture();?>" width="60" height="60" style="border: 4px solid #12ad2b;"></a> |
	<?php 
	$workmates = $project->get_workmates();
	foreach($workmates as $workmate){
		/* @var $workmate AssociatedUser */
		echo '<a href="#"><img src="photos/'.$workmate->get_picture().'" width="60" height="60"> </a>';
	}
	?>
	<hr>
	<h4>Categories:</h4>
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
	<h4>Project Plan</h4>
	
	
	<table class="table table-bordered" style="width: 0;">
			<tr>
				<th></th>
				<?php

				foreach($workmates as $workmate){
					/* @var $u AssociatedUser */
					echo '<th>'.$workmate->get_name().'</th>';
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
	<h4 style="float: left;">Statistics</h4>

	<table class="table table-bordered">

		<tr>
			<td>megbeszélés <img src="images/information.png"
				title="Ez a kategória itt ezt és azt jelenti">
			</td>
			<td><div
					style="height: 20px; width: 600px; border: 1px solid #d0d0d0;">
					<div style="height: 20px; width: 150px; background: #005826;"></div>
				</div>
			</td>
			<td>1:00 / 3:00 (30%)</td>
		</tr>
		<tr>
			<td>design <img src="images/information.png"
				title="Ez a kategória itt ezt és azt jelenti">
			</td>
			<td><div
					style="height: 20px; width: 600px; border: 1px solid #d0d0d0;">
					<div style="height: 20px; width: 150px; background: #005826;"></div>
				</div>
			</td>
			<td>1:00 / 3:00 (30%)</td>
		</tr>
		<tr>
			<td>megbeszélés <img src="images/information.png"
				title="Ez a kategória itt ezt és azt jelenti">
			</td>
			<td><div
					style="height: 20px; width: 600px; border: 1px solid #d0d0d0;">
					<div style="height: 20px; width: 600px; background: #ed1c24;"></div>
				</div>
			</td>
			<td>4:00 / 3:00 (125%)</td>
		</tr>

		<tr>
			<th>overall SUM</th>
			<td><div
					style="height: 20px; width: 600px; border: 1px solid #d0d0d0;">
					<div style="height: 20px; width: 400px; background: #0054a6;"></div>
				</div>
			</td>
			<td>2:00 / 3:00 (75%)</td>
		</tr>


	</table>

	<hr>


	<h4 style="float: left;">Latest logs</h4>


	<div style="float: right;">
		Filters: <select style="width: 120px !important;">
			<option value="1">Hidi Tibor</option>
			<option value="2">Osvath Judit</option>
		</select> <select style="width: 120px !important;">
			<option value="1">design</option>
			<option value="2">megbeszélés</option>
		</select> <input type="text" style="width: 60px;" value="datefrom"> <input
			type="text" style="width: 60px;" value="dateto"> <input type="submit"
			class="btn" value="OK" style="width: 20px;">
	</div>

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
			<th></th>
		</tr>

		<tr>
			<td><a href="project_view.php">Hidi Tibor</a>
			</td>
			<td><a href="#">Design</a>
			</td>
			<td>2013-02-01</td>
			<td>09:30</td>
			<td>10:30</td>
			<td>Ezt csináltam, azt csináltam</td>
			<td>iroda1</td>
			<td><img src="images/modify.png"> <img src="images/delete.png">
			</td>
		</tr>
		<tr>
			<td><a href="project_view.php">Gabor Madi</a>
			</td>
			<td><a href="#">Design</a>
			</td>
			<td>2013-02-01</td>
			<td>09:30</td>
			<td>10:30</td>
			<td>Ezt csináltam, azt csináltam</td>
			<td>iroda1</td>
			<td></td>
		</tr>
		<tr>
			<td><a href="project_view.php">Judit Osvath</a>
			</td>
			<td><a href="#">Design</a>
			</td>
			<td>2013-02-01</td>
			<td>09:30</td>
			<td>10:30</td>
			<td>Ezt csináltam, azt csináltam</td>
			<td>iroda1</td>
			<td></td>
		</tr>

	</table>

	<div class="pagination pagination-centered">
		<ul>
			<li><a href="#">Prev</a></li>
			<li><a href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">Next</a></li>
		</ul>
	</div>

</div>
</div>
