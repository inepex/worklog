<form method="post">
    <tr>
        <input type="hidden" name="log_id"
               value="<?php echo $_GET['edit_log'];?>">
        <td colspan="2"><select style="width: 235px !important;" id="project_select"
                                name="project_id">
                <?php
                $projects = Project::get_projects_which_contain_category($user_id);
                foreach($projects as $project){
                    /* @var $project Project */
                    if($project->get_status()->get_code() == 1){
                        $selected = "";
                        if($log->get_project_id() == $project->get_id()){
                            $selected = "selected";
                        }
                        echo '<option class="project_option" value="'.$project->get_id().'" '.$selected.'>'.$project->get_name().'</option>';
                    }
                }
                ?>
            </select></td>
        <td><input type="hidden" id="selected_date"
                   value="<?php echo $log->get_date();?>"> <select style="width: 115px;"
                                                                   id="date_select"  name="date">
            </select></td>
        <td><input type="text" style="width: 40px;" id="time_from" name="from"
                   value="<?php echo date("H:i",strtotime($log->get_from()));?>"></td>
        <td><input type="text" style="width: 40px;" id="time_to" name="to"
                   value="<?php echo date("H:i",strtotime($log->get_to()));?>"></td>
        <td><img src="images/emotes/<?php echo date('h');?>.png" title="Jó munkát! :)" style="margin:5px;"></td>
        <td rowspan="2" class="editline"><textarea
                style="width: 210px; height: 60px;" name="log_entry"><?php echo $log->get_entry();?></textarea></td>
        <td><?php
            $workplaces = WorkPlace::get_places();
            ?> <select style="width: 80px;" name="work_place_id">
                <?php
                foreach($workplaces as $workplace){
                    /* @var $workplace Workplace */
                    $selected = "";
                    if($workplace->get_id() ==  $log->get_working_place_id()){
                        $selected = "selected";
                    }
                    echo '<option value="'.$workplace->get_id().'" '.$selected.'>'.$workplace->get_name().'</option>';
                }
                ?>
            </select></td>
        <td><input type="submit" value="SAVE" name="edit_log"
                   class="btn btn-primary"></td>
    </tr>

    <tr class="editline">
        <td colspan="3">
            <input type="hidden" id="selected_category_id"
                   value="<?php echo $log->get_category_assoc_id();?>"> <select
                style="width: 370px !important;" id="category_select"
                name="category_assoc_id">
            </select>
        </td>
        <td><a href="" id="time_from_link">Now</a></td>
        <td><a href="" id="time_to_link">Now</a></td>
        <td></td>
        <td><?php
            $efficiencies = Efficiency::get_efficiencies();
            ?> <select style="width: 80px;" name="efficiency_id">
                <?php
                foreach($efficiencies as $efficiency){
                    /* @var $workplace Workplace */
                    $selected = "";
                    if($efficiency->get_id() ==  $log->get_efficiency_id()){
                        $selected = "selected";
                    }
                    echo '<option value="'.$efficiency->get_id().'" '.$selected.'>'.$efficiency->get_name().'</option>';
                }
                ?>
            </select></td>
        <td></td>
    </tr></form>
