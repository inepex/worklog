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
	$category = new Category($user_worked_hours_in_category['category_id']);
	echo '["'.$category->get_name().' ('.gmdate("H:i:s", $user_worked_hours_in_category['worked_hours']).')", '.$user_worked_hours_in_category['worked_hours'].'],';
}

echo ']
);

var options = {
title: "Categories","width":500,
"height":300,sliceVisibilityThreshold:0
};

var chart = new google.visualization.PieChart(document.getElementById("category_chart"));
chart.draw(data, options);
}


function drawChart2() {
	
var data2 = google.visualization.arrayToDataTable([
	
["Type", "Count"],';
foreach ($user_worked_hours_in_projects as $user_worked_hours_in_project){
	$project = new Project($user_worked_hours_in_project['project_id']);
	echo '["'.$project->get_name().' ('.gmdate("H:i:s", $user_worked_hours_in_project['worked_hours']).')", '.$user_worked_hours_in_project['worked_hours'].'],';
}

echo ']
);

var options2 = {
title: "Projects","width":500,
"height":300,sliceVisibilityThreshold:0
};

var chart2 = new google.visualization.PieChart(document.getElementById("project_chart"));
chart2.draw(data2, options2);
}
</script>
';
?>
