<?php
//sorting
$order_by = 'worklog_project_id';
$order= 'asc';
if(isset($_GET['order']) && ($_GET['order'] == 'desc' || $_GET['order'] == 'asc')){
	$order =$_GET['order'];
}
if(isset($_GET['order_by']) && $_GET['order_by'] !=""){
	$order_by =$_GET['order_by'];
}
function order_change($order){
	if($order == "asc"){
		return 'desc';
	}
	else{
		return 'asc';
	}
}
//

//paging
$page=0;
if(isset($_GET['page']) && $_GET['page'] != ""){
	$page = (int)$_GET['page'];
}
//

//Searching
$keyword = "";
if(isset($_GET['search']) && $_GET['search'] != ""){
	$keyword = $_GET['search'];
}
//
$project_status = '';
if(isset($_GET['project_status']) && ProjectStatus::is_status_exist((int)$_GET['project_status'])){
	$project_status = $_GET['project_status'];
}
//delete project
if(isset($_GET['delete_project']) && $_GET['delete_project'] != "" && Project::is_project_exist($_GET['delete_project'])){
	Project::delete_project($_GET['delete_project']);
	//Notification::notice("Deleted successfully!");
	echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=projects_list.php?search=".$keyword."&order_by=".$order_by."&order=".$order."&page=".$page."\">";
	exit();
}
//
?>
<div class="worklog-container">
	<div class="subheader">
		<div class="titlebar">
			<?php echo '<form method="get" action="projects_list.php?search='.$keyword.'&order_by='.$order_by.'&order='.$order.'&page='.$page.'" >'; ?>
			<input type="hidden" name="search" value="<?php echo $keyword;?>" /> 
			<input type="hidden" name="order_by" value="<?php echo $order_by;?>" />
			<input type="hidden" name="order" value="<?php echo $order;?>" />
			<input type="hidden" name="page" value="<?php echo $page?>" />
			<div style="float: left;">
				<h4>
					Projects list <select name="project_status">
						<option value="" >All</option>
						<?php 
						$all_status = ProjectStatus::get_all_status();
						foreach ($all_status as $status){
							echo '<option value="'.$status->get_code().'" '.(($project_status == $status->get_code())?"selected":"").'>'.$status->get_name().'</option>';
						}
						?>
					</select> <input type="submit" value="OK" class="btn">
				</h4>
			</div>
			</form>

			<div style="float: right;">
				<h4>
					<form method="get">
						<input type="text" name="search" value="<?php echo ((isset($_GET['search']) && $_GET['search'] !='')?$_GET['search']:'');?>"> <input type="submit"
							value="Search" class="btn">
					</form>
				</h4>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<hr>
	<?php require_once 'include/notifications.php';?>
	<div style="clear: both;"></div>
	<div id="projects_list">
		<table class="table table-bordered">
			<tr>
				<th>
					<?php 
					echo '<a href="projects_list.php?search='.$keyword.'&order_by=worklog_project_id&order='.order_change($order).'&page='.$page.'&project_status='.$project_status.'">ID</a>';
					?>
					
				</th>
				<th><?php 
					echo '<a href="projects_list.php?search='.$keyword.'&order_by=project_name&order='.order_change($order).'&page='.$page.'&project_status='.$project_status.'">Project name</a>';
					?>
				</th>
				<th>
					<?php 
					echo '<a href="projects_list.php?search='.$keyword.'&order_by=worklog_user_id&order='.order_change($order).'&page='.$page.'&project_status='.$project_status.'">Owner</a>';
					?>
				</th>
				<th>
					<?php 
					echo '<a href="projects_list.php?search='.$keyword.'&order_by=project_status&order='.order_change($order).'&page='.$page.'&project_status='.$project_status.'">Status</a>';
					?>
				</th>
				<th>
					<?php 
					echo '<a href="projects_list.php?search='.$keyword.'&order_by=start_date&order='.order_change($order).'&page='.$page.'&project_status='.$project_status.'">Start date</a>';
					?>
				</th>
				<th>
					<?php 
					echo '<a href="projects_list.php?search='.$keyword.'&order_by=end_date&order='.order_change($order).'&page='.$page.'&project_status='.$project_status.'">End date</a>';
					?>
				</th>
				<th>Edit</th>
				<th>Delete</th>
				<?php 
				$projects = Project::get_projects($keyword, $order, $order_by,$project_status,$page);
				$number_of_projects = count(Project::get_projects($keyword, $order, $order_by,$project_status));
				foreach($projects as $project){
					/* @var $project Project */
					//$status = new ProjectStatus($project->get_status());
					echo '<tr>';
					echo 	'<td>'.$project->get_id().'</td>
					<td><a href="project_view.php?project_id='.$project->get_id().'">'.$project->get_name().'</a></td>
					<td>'.$project->get_user()->get_name().'</td>
					<td>'.$project->get_status()->get_name().'</td>
					<td>'.$project->get_start_date().'</td>
					<td>'.$project->get_end_date().'</td>
					<td><a href="project_edit.php?project_id='.$project->get_id().'"><img src="images/modify.png"</a></td>
					<td> '.(!Project::is_project_used($project->get_id())?'<span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
						<tr>
							<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
						</tr>
						<tr>
							<td><a href="projects_list.php?delete_project='.$project->get_id().'&search='.$keyword.'&order_by='.$order_by.'&order='.$order.'&page='.$page.'" class="btn" id=""><font color="red">Igen</font></a></td>
							<td><font class="btn">Nem</font></td>
						</tr>
					</table>
					</ul></td>
					</span></td>':'');	
					echo '</tr>';
				}
				?>
			</tr>
		</table>
		<div class="pagination pagination-centered">
			<ul>
				<?php 
				//paging buttons
				if($page>0){
					echo '<li><a href="projects_list.php?search='.$keyword.'&order_by='.$order_by.'&order='.$order.'&page='.($page-1).'&project_status='.$project_status.'">Prev</a></li>';
				}
				for($i=0; $i<ceil($number_of_projects/Project::$number_of_projects_per_page); $i++){
					echo '<li><a href="projects_list.php?search='.$keyword.'&order_by='.$order_by.'&order='.$order.'&page='.$i.'&project_status='.$project_status.'" '.(($i==$page)?'style="color: red;"':'').'>'.($i+1).'</a></li>';
				}
				echo '<li><a href="projects_list.php?search='.$keyword.'&order_by='.$order_by.'&order='.$order.'&page='.($page+1).'&project_status='.$project_status.'">Next</a></li>';
				//
				?>
			</ul>
		</div>
	</div>
</div>
