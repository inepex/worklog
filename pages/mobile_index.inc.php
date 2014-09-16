<?php
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);
//add new log
$selected_user = $user;
if(isset($_POST['add_log'])){
    $error = false;
    if(!Project::is_project_exist($_POST['project_id'])){
        Notification::warn("Project doesnt exist!");
        $error = true;
    }
    else{
        $project = Project::get($_POST['project_id']);
        if($project->get_status()->get_code() != 1){
            Notification::warn("The project is not active!");
            $error = true;
        }
        else if(!$project->is_associated_category_in_project($_POST['category_assoc_id'])){
            Notification::warn("The category is not in the project!");
            $error = true;
        }
    }
    $date = new DateTime($_POST['date']);
    $now = new DateTime();
    $interval = $now->diff($date);
    $difference = $interval->format('%R%a');
    if($difference<-4){
        Notification::warn("The date is too early!");
        $error = true;
    }
    else if($difference>0){
        Notification::warn("The date cannot be in the future");
        $error = true;
    }
    else{
        if(!isset($_POST['from']) || $_POST['from'] == ''){
            Notification::warn("FROM cannot be empty!");
            $error = true;
        }
        if(!isset($_POST['to']) || $_POST['to'] == ''){
            Notification::warn("TO cannot be empty!");
            $error = true;
        }
        $parsed_from = date_parse_from_format("H:i",$_POST['from']);
        if($parsed_from['warning_count']>0){
            Notification::warn("FROM value is not valid!");
            $error = true;
        }
        $parsed_to = date_parse_from_format("H:i",$_POST['to']);
        if($parsed_to['warning_count']>0){
            Notification::warn("To value is not valid!");
            $error = true;
        }
        if($parsed_from['warning_count'] == 0 && $parsed_to['warning_count'] == 0){
            if(isset($_POST['to']) && $_POST['to'] != '' && isset($_POST['from']) && $_POST['from'] != ''){
                $seconds_diff = strtotime($_POST['date']." ".date($_POST['to'])) - strtotime($_POST['date']." ".date($_POST['from']));
                if($seconds_diff == 0){
                    Notification::warn("TO cannot be the same as FROM!");
                    $error = true;
                }
                else if($seconds_diff < 0){

                    Notification::warn("TO is smaller then FROM!");
                    $error = true;
                }
                else{
                    if(Log::is_overlap($user->get_id(), $_POST['date'], $_POST['from'], $_POST['to'])){
                        Notification::warn("Time overlap!");
                        $error = true;
                    }
                }

            }
        }
    }
    if(!isset($_POST['log_entry']) || $_POST['log_entry'] == ""){
        Notification::warn("Log entry cannot be empty!");
        $error = true;
    }
    if(!WorkPlace::is_workplace_exist($_POST['work_place_id'])){
        Notification::warn("Workplace doesnt exist!");
        $error = true;
    }
    if(!Efficiency::is_efficiency_exist($_POST['efficiency_id'])){
        Notification::warn("Efficiency doesnt exist!");
        $error = true;
    }
    if(!$error){
        Log::add_log($_POST['project_id'], $_POST['category_assoc_id'], $user->get_id(), $_POST['date'], date("H:i",strtotime($_POST['from'])),date("H:i",strtotime($_POST['to'])), $_POST['log_entry'], $_POST['work_place_id'], $_POST['efficiency_id']);
        echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";
        exit();
    }
}

?>
<script type="text/javascript" src="js/index.js"></script>
<div class="worklog-container"  style="width: auto !important;">
    <div class="subheader">
        <div class="profile_photo" style="margin-top: 10px;">
            <a href="user_edit.php"><img
                    src="photos/<?php echo $selected_user->get_picture();?>"
                    title="Click to edit profile"> </a>
        </div>
        <div class="titlebar" style="float: left;">
            <h4>
                <?php echo $selected_user->get_name();?>
                's Worklog - Logs
            </h4>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="clear: both;"></div>

    <?php
    //Show notifications
    require_once 'include/notifications.php';
    ?>
    <table  class="table table-bordered">
        <?php if($selected_user->get_id() == $user->get_id()){
            if(isset($_GET['edit_log']) && Log::is_log_exist($_GET['edit_log'])){
                $log = Log::get($_GET['edit_log']);
                if($log->is_editable($user->get_id())){
                    include 'include/edit_log_form.php';
                }
                else{
                    Notification::warn("You do not have the permission to edit this log!");
                }
            }
            else{
                include 'include/add_log_form_mobile.php';
            }
        }
        ?>
        </table>
<!--   új táblázat     -->

<!--    új táblázat vége    -->
        <?php

        function hoursToMinutes($hours)
        {
            if (strstr($hours, ':'))
            {
                # Split hours and minutes.
                $separatedData = explode(':', $hours);

                $minutesInHours    = $separatedData[0] * 60;
                $minutesInDecimals = $separatedData[1];

                $totalMinutes = $minutesInHours + $minutesInDecimals;
            }
            else
            {
                $totalMinutes = $hours * 60;
            }

            return $totalMinutes;
        }
    ?>





</div>
