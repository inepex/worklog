<?php
 
$user_worked_hours_in_categories = $selected_user->get_worked_hours_in_categories($selected_date->format('Y-m-d'));
$user_worked_hours_in_projects = $selected_user->get_worked_hours_in_projects($selected_date->format('Y-m-d'));
echo '
<div>
<div id="category_chart" style="float:left;"></div>
<div  id="project_chart" style="float:right;"></div>
</div>
<div style="clear:both;"></div>
<script type="text/javascript">
google.load("visualization", "1", {
packages:["corechart"]});
google.setOnLoadCallback(drawChart);
google.setOnLoadCallback(drawChart2);

function drawChart() {

var data = google.visualization.arrayToDataTable([

["Type", "Count"],';
foreach ($user_worked_hours_in_categories as $user_worked_hours_in_category){
	$category = Category::get($user_worked_hours_in_category['category_id']);
	$hours = floor($user_worked_hours_in_category['worked_hours']/3600);
	$minutes = floor(($user_worked_hours_in_category['worked_hours']/60)%60);
	echo '["'.$category->get_name().' ('.str_pad($hours, 2, "0", STR_PAD_LEFT).':'.str_pad($minutes, 2, "0", STR_PAD_LEFT).')", '.$user_worked_hours_in_category['worked_hours'].'],';
}

echo ']
);

var options = {
	title: "Categories","width":500,
	"height":700,sliceVisibilityThreshold:0,
	tooltip: {isHtml: true},
	tooltipText: "percentage",
};

var chart = new google.visualization.PieChart(document.getElementById("category_chart"));
chart.draw(data, options);
}


function drawChart2() {
	
var data2 = google.visualization.arrayToDataTable([
	
["Type", "Count"],';
foreach ($user_worked_hours_in_projects as $user_worked_hours_in_project){
	$project = Project::get($user_worked_hours_in_project['project_id']);
	$hours = floor($user_worked_hours_in_project['worked_hours']/3600);
	$minutes = floor(($user_worked_hours_in_project['worked_hours']/60)%60);
	echo '["'.$project->get_name().' ('.str_pad($hours, 2, "0", STR_PAD_LEFT).':'.str_pad($minutes, 2, "0", STR_PAD_LEFT).')", '.$user_worked_hours_in_project['worked_hours'].'],';
}

echo ']
);

var options2 = {
title: "Projects","width":500,
"height":700,sliceVisibilityThreshold:0,
tooltip: {isHtml: true},
tooltipText: "percentage"
};

var chart2 = new google.visualization.PieChart(document.getElementById("project_chart"));
chart2.draw(data2, options2);
}
</script>
';
?>
