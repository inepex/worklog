<?php 
class Tools{
	public static function identify_link($text){
		$text = ' ' . $text;
		$text = preg_replace('`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i','$1<a href="$2">$2</a>',$text);
		$text = substr($text, 1);
		return $text;
		;
	}
	
	public static function get_chart($selected_user,$selected_date) {

		$user_worked_hours_in_categories = $selected_user->get_worked_hours_in_categories($selected_date->format('Y-m-d'));
		$user_worked_hours_in_projects = $selected_user->get_worked_hours_in_projects($selected_date->format('Y-m-d'));
		
		$txt= '';
		
		$txt.=' 
		<div id="category_chart-'.$selected_user->get_id().'-'.$selected_date->format('Y-m-d').'" style=""></div>
		<div id="project_chart-'.$selected_user->get_id().'-'.$selected_date->format('Y-m-d').'" style=""></div>
		 
		 
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
					$txt.= '["'.$category->get_name().' ('.str_pad($hours, 2, "0", STR_PAD_LEFT).':'.str_pad($minutes, 2, "0", STR_PAD_LEFT).')", '.$user_worked_hours_in_category['worked_hours'].'],';
				}
				
				$txt.= ']
		);
				
		var options = {
			title: "Categories","width":350,
			"height":150,sliceVisibilityThreshold:0,
			tooltip: {isHtml: true},
			chartArea: {"width": "100%", "height": "80%"},
			tooltipText: "percentage",
							
		};
				
		var chart = new google.visualization.PieChart(document.getElementById("category_chart-'.$selected_user->get_id().'-'.$selected_date->format('Y-m-d').'"));
		chart.draw(data, options);
		}
				
				
		function drawChart2() {
				
		var data2 = google.visualization.arrayToDataTable([
				
		["Type", "Count"],';
				foreach ($user_worked_hours_in_projects as $user_worked_hours_in_project){
					$project = Project::get($user_worked_hours_in_project['project_id']);
					$hours = floor($user_worked_hours_in_project['worked_hours']/3600);
					$minutes = floor(($user_worked_hours_in_project['worked_hours']/60)%60);
					$txt.= '["'.$project->get_name().' ('.str_pad($hours, 2, "0", STR_PAD_LEFT).':'.str_pad($minutes, 2, "0", STR_PAD_LEFT).')", '.$user_worked_hours_in_project['worked_hours'].'],';
				}
				
				$txt.= ']
		);
				
		var options2 = {
		title: "Projects","width":350,
		"height":150,sliceVisibilityThreshold:0,
		tooltip: {isHtml: true},
		chartArea: {"width": "100%", "height": "80%"},
		tooltipText: "percentage"
		};
				
		var chart2 = new google.visualization.PieChart(document.getElementById("project_chart-'.$selected_user->get_id().'-'.$selected_date->format('Y-m-d').'"));
		chart2.draw(data2, options2);
		}
		</script>
		';
				
		return $txt;
		
	}
}
?>