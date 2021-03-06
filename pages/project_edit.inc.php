<?php
error_reporting(E_ALL ^ E_DEPRECATED);
if(isset($_GET['project_id']) && $_GET['project_id'] != "" && Project::is_project_exist($_GET['project_id'])){
	$project = Project::get($_GET['project_id']);
	if($project->get_status()->get_code() != 2 && !$user->is_admin()){
		Notification::warn("Only admin can edit an active project!");
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
		exit();
	}
	//update project informations
	if(isset($_POST['update_project'])){
		$error=false;
		if(!isset($_POST['project_name']) || $_POST['project_name'] ==""){
			$error = true;
			Notification::warn("Missing project name!");
		}
		if(!isset($_POST['company_id']) || $_POST['company_id'] ==""){
			$error = true;
			Notification::warn("Missing company!");
		}
		if(isset($_POST['owner_id']) && !User::is_exist($_POST['owner_id'])){
			$error = true;
			Notification::warn("User does not exist!");
		}
		if(!isset($_POST['owner_id']) || $_POST['owner_id'] ==""){
			$error = true;
			Notification::warn("Missing owner!");
		}
		if(!isset($_POST['project_description']) || $_POST['project_description'] ==""){
			$error = true;
			Notification::warn("Missing project description!");
		}
		if(!isset($_POST['start']) || $_POST['start'] ==""){
			$error = true;
			Notification::warn("Missing start date!");
		}
		if(!isset($_POST['deadline']) || $_POST['deadline'] ==""){
			$error = true;
			Notification::warn("Missing deadline date!");
		}
		if(!isset($_POST['project_status']) || $_POST['project_status'] ==""){
			$error = true;
			Notification::warn("Missing status!");
		}
		else if($_POST['project_status'] != '2' && !$user->is_admin()){
			$error = true;
			Notification::warn('Only admin can modify the project status');
		}
		if(isset($_POST['start']) && isset($_POST['deadline']) && $_POST['start']>$_POST['deadline']){
			$error = true;
			Notification::warn("Deadline is bigger then start date!");
		}
		if(!$error){
			if($project->update($_POST['project_name'], $_POST['company_id'],$_POST['owner_id'], $_POST['project_description'], $_POST['beginning'], $_POST['destination'], $_POST['start'], $_POST['deadline'], $_POST['project_status'], $user->get_id())){
				Notification::notice("Updated successfully!");
			}
			
		}

        //update project plan
        if(isset($_POST['plan_entry_value'])){
            for($i=0; $i<count($_POST['plan_entry_value']); $i++){
                if(!preg_match('/[0-9]/', $_POST['plan_entry_value'][$i])){
                    Notification::warn($_POST['plan_entry_value'][$i]." is not a number!");
                }
                else{
                    $project->get_project_plan()->add_entry($_POST['plan_entry_user_id'][$i], $_POST['plan_entry_category_assoc_id'][$i], $_POST['plan_entry_value'][$i]);
                }
            }
            Notification::notice("Project plan updated successfully!");
        }
        //
	}
	//

	//add workmate
	if(isset($_POST['add_workmate']) && isset($_POST['workmate_id'])){
		if(User::is_exist($_POST['workmate_id'])){
			$project->add_workmate($_POST['workmate_id']);
			Notification::notice("Workmate added successfully!");
		}
		else{
			Notification::warn("User does not exist!");
		}
	}
	//

	//add category
	if(isset($_POST['add_category']) && isset($_POST['category_id'])){
		if(Category::is_exist($_POST['category_id'])){
			if(isset($_POST['category_description']) && $_POST['category_description'] !=""){
				$project->add_category($_POST['category_id'], $_POST['category_description']);
				Notification::notice("Category added successfully!");
			}
			else{
				Notification::warn("Category description can not be empty!");
			}
		}
		else{
			Notification::warn("Category does not exist!");
		}
	}
	//
	//edit associated category description
	if(isset($_POST['associated_category_new_description']) && isset($_POST['associated_category_id']) && AssociatedCategory::is_associated_category_exist($_POST['associated_category_id'])){
		$associated_category_to_edit = AssociatedCategory::get($_POST['associated_category_id']);
		$project->update_category_description($_POST['associated_category_id'], $_POST['associated_category_new_description']);
		unset($associated_category_to_edit);
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=project_edit.php?project_id=".$project->get_id()."#categories\">";		
	}
	//
	//delete workmate
	if(isset($_GET['delete_workmate'])){
		$workmate = AssociatedUser::get($_GET['delete_workmate']);
		if(!$workmate->is_have_log_in_project()){
			$project->delete_workmate($_GET['delete_workmate']);
			Notification::notice("Workmated removed!");
			echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=project_edit.php?project_id=".$project->get_id()."\">";
			exit();
		}
		else{
			Notification::warn("User already have log in this project");
			echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=project_edit.php?project_id=".$project->get_id()."\">";
			exit();
		}
		
	}
	//
	
	//delete category
	if(isset($_GET['delete_category'])){
		$project->delete_category($_GET['delete_category']);
		Notification::notice("Category removed!");
		echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=project_edit.php?project_id=".$project->get_id()."\">";
		exit();
	}
	//
}
else{
	Notification::warn("The requested project does not exist!");
	echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
	exit();
}

?>
<div class="worklog-container">
<input type="hidden" name="project_id" id="project_id" value="<?php echo $project->get_id();?>">
	<div class="subheader">

		<div class="titlebar">
            <td><a name="top"></a>
			<div style="float: left;">
				<h4>Create New Project / Edit Project</h4>
			</div>
			<div style="float: right;">
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
				<a href="project_view.php?project_id=<?php echo $project->get_id();?>" class="btn ">Project Page</a> <a
					href="project_edit.php" class="btn btn-inverse">Edit Project</a>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<hr>
	<?php require_once 'include/notifications.php';?>
	<div style="clear: both;"></div>
	<form method="post" action="project_edit.php?project_id=<?php echo $project->get_id();?>#top">
		<table class="table table-bordered">
			<tr>
				<td width="120">Project name:</td>
				<td><input type="text" style="width:450px;" value="<?php echo htmlspecialchars($project->get_name());?>"
					name="project_name"></td>
			</tr>
			<tr>
				<td width="120">Company:</td>
				<td><select name="company_id">
						<?php
						$companies = Company::get_companies();
						foreach($companies as $company){
							/* @var $company Company */
							$selected = '';
							if($project->get_company()->get_id()==$company->get_id()){
								$selected = 'selected';
							}
							echo '<option value="'.$company->get_id().'" '.$selected.'>'.$company->get_name().'</option>';
						}
						?>
				</select>
				</td>
			</tr>
			<tr>
				<td>
					Owner:
				</td>
				<td>
					<select name="owner_id">
					<?php 
						$users = User::get_active_users();
						foreach($users as $u){
							$selected="";
							if($u->get_id() == $project->get_user()->get_id()){
								$selected = 'selected';
							}
							echo '<option value="'.$u->get_id().'" '.$selected.'>'.$u->get_name().'</option>';
						}
						
						
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Description:</td>
				<td>
				<textarea style="width: 700px; height: 100px;"
						name="project_description"><?php echo $project->get_description();?></textarea>
						</td>
			</tr>
			<tr>
				<td>Beginning:</td>
				<td>
				<textarea style="width: 700px; height: 100px;"
						name="beginning"><?php echo $project->get_beginning();?></textarea>
						</td>
			</tr>
			<tr>
				<td>Destination:</td>
				<td>
				<textarea style="width: 700px; height: 100px;"
						name="destination"><?php echo $project->get_destination();?></textarea>
						</td>
			</tr>
			<tr>
				<td>Start:</td>
				<td><input type="text" class="datepicker"
					value="<?php echo $project->get_start_date();?>" name="start"></td>
			</tr>
			<tr>
				<td>Deadline:</td>
				<td><input type="text" class="datepicker"
					value="<?php echo $project->get_end_date();?>" name="deadline"></td>
			</tr>
			<tr>
				<td>Status:</td>
				<td><input type="radio"
				<?php echo(($project->get_status()->get_code() == 1)?'checked':''); ?>
					name="project_status" value="1" <?php echo (!$user->is_admin()?'disabled':'')?>> Active <input type="radio"
					<?php echo(($project->get_status()->get_code()  == 0)?'checked':''); ?>
					name="project_status" value="0" <?php echo (!$user->is_admin()?'disabled':'')?>> Closed <input type="radio"
					<?php echo(($project->get_status()->get_code()  == 2)?'checked':''); ?>
					name="project_status" value="2"> Requested</td>
			</tr>
            <?php
            $workmates = $project->get_workmates();
            $categories = Category::get_categories();
            $associated_categories = $project->get_categories();
            ?>
            <tr>
                <td>Project Plan:</td>

                <td><a name="project_plan"></a>
                    <table class="table table-bordered" style="width: 0;">
                        <tr>
                            <th></th>
                            <?php

                            foreach($workmates as $workmate){
                                /* @var $u AssociatedUser */


                                echo '<th style="text-align:center!important;"><a href="index.php?user_id='.$workmate->get_id().'"><img src="photos/'.$workmate->get_picture().'" width="30" alt="'.$workmate->get_name().'" title="'.$workmate->get_name().'"></a><br><span style="font-size:10px;">'.$workmate->get_name().'</span></th>';
                            }
                            ?>
                            <th>SUM</th>
                        </tr>
                        <?php
                        foreach ($associated_categories as $associated_category){
                            echo '<tr class="project-plan">';
                            echo '<th width="150">'.$associated_category->get_name().'<br><span class="hint">
                     '.$associated_category->get_description().'</span></th>';
                            foreach ($workmates as $workmate){
                                echo '<td><input type="text" class="project-plan-input" name="plan_entry_value[]" value="'.$project->get_project_plan()->get_sum_for_category_and_user($workmate->get_id(), $associated_category->get_assoc_id()).'"/></td>';
                                echo '<input type="hidden" name="plan_entry_user_id[]" value="'.$workmate->get_id().'">';
                                echo '<input type="hidden" name="plan_entry_category_assoc_id[]" value="'.$associated_category->get_assoc_id().'">';
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
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" class="btn btn-primary" value="Save"
                           name="update_project"></td>
            </tr>
        </table>
    </form>


	<hr>

	<div style="width: 30%; float: left;">
	<a name="workmates"></a>
		<h4>Workmates</h4>
		<form method="post" action="project_edit.php?project_id=<?php echo $project->get_id();?>#workmates">
			<table class="table table-bordered">
				<tr>
					<td><select name="workmate_id">
							<?php 
							$users = User::get_active_users();
							foreach ($users as $u){
								/* @var $u User */
								if(!$project->is_user_workmate($u->get_id())){
									echo '<option value="'.$u->get_id().'">'.$u->get_name().'</option>';
								}
							}
							?>
					</select>
					</td>
					<td><input type="submit" value="Add" class="btn"
						name="add_workmate">
					</td>
				</tr>
				<?php
				foreach ($workmates as $workmate){
					/* @var $workmate AssociatedUser */
					echo '<tr>
					<td width="120"><img src="photos/'.$workmate->get_picture().'" width="20" height="20" alt="'.$workmate->get_name().'"  title="'.$workmate->get_name().'">
					'.$workmate->get_name().'</td>
					<td>'.(!$workmate->is_have_log_in_project()?'<span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
					</tr>
					<tr>
						<td><a href="project_edit.php?project_id='.$project->get_id().'&delete_workmate='.$workmate->get_assoc_id().'" class="btn" id=""><font color="red">Igen</font></a></td>
						<td><font class="btn">Nem</font></td>
					</tr>
					</table>
					</ul></td>
				</span>':'').'</td>
					</tr>';
				}
				?>
			</table>
		</form>
	</div>

	<div style="width: 68%; float: right;">
	<a name="categories"></a>
		<h4>Categories</h4>
		<form method="post" action = "project_edit.php?project_id=<?php echo $project->get_id();?>#categories">
			<table class="table table-bordered">
				<?php 
				if(isset($_GET['edit_category']) && AssociatedCategory::is_associated_category_exist($_GET['edit_category'])){
					$associated_category_to_edit =  AssociatedCategory::get($_GET['edit_category']);
					echo '
						<tr>
							<form method="post" action="project_edit.php?project_id='.$project->get_id().'">
							<input type="hidden" name="associated_category_id" value="'.$associated_category_to_edit->get_assoc_id().'">
							<td  style="width: 120px;" >'.$associated_category_to_edit->get_name().'</td><td  ><input type="text" style="width: 450px;" name="associated_category_new_description" value="'.$associated_category_to_edit->get_description().'"></td><td><input type="submit" value="Save" class="btn"></td>
							</form>
						</tr>
					';
				}
				else{
					echo '
						<tr>
						<td><select style="width: 120px;" name="category_id">';
							foreach($categories as $category){
								/* @var $category Category */
								if ($category->get_category_status()=='1') {
									echo '<option value="'.$category->get_id().'">'.$category->get_name().'</option>';
								}
							}
				
					echo '</select>
					</td>
					<td><input type="text" style="width: 450px; '.((isset($_POST['add_category'])  && (!isset($_POST['category_description']) || $_POST['category_description'] ==""))?'background-color:rgb(255, 232, 232);!important;':'').'"
						name="category_description">
					</td>
					<td><input type="submit" value="Add" class="btn"
						name="add_category">
					</td>
				</tr>
					';
				}
				?>
				
				<?php
				foreach($associated_categories as $associated_category){
					/* @var $associated_category AssociatedCategory */
					echo   '<tr><td width="120">'.$associated_category->get_name().'</td>
					<td width="120">'.$associated_category->get_description().'</td>
					<td><a href="project_edit.php?project_id='.$project->get_id().'&edit_category='.$associated_category->get_assoc_id().'#categories" ><img src="images/modify.png" title="Edit" alt="Edit"></a>'.((!$associated_category->is_associated_category_in_use())?'<span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
					</tr>
					<tr>
						<td><a href="project_edit.php?project_id='.$project->get_id().'&delete_category='.$associated_category->get_assoc_id().'" class="btn" id=""><font color="red">Igen</font></a></td>
						<td><font class="btn">Nem</font></td>
					</tr>
					</table>
					</ul></td>
				</span>':'').'</td></tr>';
				}
				?>
			</table>
		</form>
	</div>
    <div style="clear: both;"></div>
</div>
</div>
