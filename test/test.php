<?php
echo "hello";
require_once '../classes/User.php';
require_once '../classes/WorkPlace.php';
require_once '../classes/Category.php';
require_once '../classes/Efficiency.php';
require_once '../classes/Company.php';
require_once '../classes/Project.php';
require_once '../classes/ProjectPlanEntry.php';
require_once '../classes/ProjectPlan.php';
require_once '../classes/Notification.php';
require_once '../classes/AssociatedUser.php';
require_once '../classes/AssociatedCategory.php';
require_once '../classes/ProjectStatus.php';
require_once '../../worklog-config.php';
//$_SESSION['enterid'] = 75;
var_dump(Project::get(485));
//var_dump(Project::get_projects());
//var_dump(Efficiency::get_efficiencies());
?>