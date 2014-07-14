<script type="text/javascript" src="js/projectView.js"></script>
<div class="worklog-container">
	<div class="subheader">

		<div class="subheader">
		<div class="titlebar">
			<h4>Plan</h4>

		</div>
	</div>
	<form method="post">
	Please choose one or more from the following active projects: <br/><br/>
		<?php 
		$projects = Project::get_projects("", "ASC", "project_name",1,"","","");
		$number_of_projects = count(Project::get_projects("", "", "",1,"","",""));
		$i=0;
		echo "<table cellpadding='2'><tr>";
		foreach($projects as $project){
			/* @var $project Project */
			//$status = new ProjectStatus($project->get_status());
			
			echo '<td><input type="checkbox" name="project_ids" value="'.$project->get_id().'"> '.$project->get_name().' </td>';
			$i++;
			if ($i % 4 == 0) { echo "</tr><tr>"; }
			
			
		}
		echo "</tr></table>";
		?>
			<br/><input type="submit" class="btn btn-primary" value="Get information">
		</form>		
	<hr>
	 
	<h4 style="float: left;"><img src="images/statistics.png">Statistics</h4>

	<div style="clear:both;"></div>

		<?php 
		
		echo '<div class="workmates" style="float:left;">';
		 	echo '<div style="border:1px solid;">&nbsp;</div>';
		 	echo '<div style="border:1px solid;">Workmates</div>';
			$users = User::get_users();
			foreach ($users as $u){
				/* @var $u User */
				
					echo '<div style="border:1px solid;">'.$u->get_name().'</div>';
				
			}
		 
		echo '</div>';
		
		
		$project = new Project(444);
		
		$categories   = $project->get_categories();
		
		$project_plan = $project->get_project_plan();
		sizeof($categories);
	 
		
		echo '<div class="project" style="float:left;">';
				
				echo '<div style="border:1px solid;">'.$project->get_name().'</div>';
		
				echo '<div class="project_category">';	
							
				foreach($categories as $category){
					echo '<div style="float:left; border:1px solid; width:80px;">'.$category->get_name().' </div>';
				}
				
				echo '</div>';
				
				
				$users = User::get_users();
				foreach ($users as $u){
					/* @var $u User */
					
					
					foreach($categories as $category){
						if(!$project->is_user_workmate($u->get_id())){
							echo '<div style="float:left; border:1px solid; width:80px; text-align:center;"> --- </div>';
						} else {
							echo '<div style="float:left; border:1px solid; width:80px; text-align:center;">'.$project->get_logged_hours($u->get_id(),$category->get_assoc_id()).' / '.$project->get_planned_hours($u->get_id(),$category->get_assoc_id()).'</div>';
						}
					}
						
						echo '<div style="clear:both;"></div>';
					
				}
				
				
						
		
		
		echo '</div>';
		
		
		
	 
		
		?>
	
	<div style="clear:both;"></div>

	 </div>
</div>

