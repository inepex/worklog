<?php 
if(!isset($_GET['month'])){
	$selected_date = new DateTime("now");
	$selected_date->modify("first day of this month");
	
}
else {
	$selected_date = new DateTime($_GET['month']);
}
?><div class="worklog-container">

	<div class="subheader">
		<div class="titlebar">
		<div style="float: left;"><form method="GET">
			<h4>List Scrum by
			
			<select name="month">
				<?php 
				$selected_user = $user;
				$todayDate = new DateTime("now");
				$todayDate->modify("first day of this month");
				$earliest_log_date =  new DateTime($selected_user->get_earlies_log_date());
				$earliest_log_date->modify("first day of this month");
				if($earliest_log_date){
					$date = $todayDate;
					for($i=1; $earliest_log_date->format("Y-m-d") <= $date->format("Y-m-d"); $i++){
						$selected = "";
						if($selected_date->format("Y F") == $date->format("Y F")){
							$selected = "selected";
						}
						echo '<option value="'.$date->format('Y-m-d').'" '.$selected.'>'.$date->format('Y F').'</option>';
						$date->modify("-1 month");
					}
				}
				else{
					echo '<option value="201301">'.$todayDate->format('Y F').'</option>';
				}
				?>
			</select> <input type="submit" value="OK" class="btn">
			</form>
			</h4>
		</div>
		 <div style="float: right;">
			<a href="scrum_add.php" class="btn btn-inverse">Add Scrum</a>
		</div>
		<div style="clear: both;"></div>
		</div>
	</div>
	<hr>
	 
	<div style="clear: both;"></div>
 	<form method="post">
	<table class="table table-bordered">
		<tr>
			<th width="120">User:</th>
			<th width="350">Charts</th>
			<th width="250">What happened:</th>
			<th width="250">What is the plan:</th>
			<th width="16"></th>
		</tr>
		<?php 
		$scrum_list = Scrum::get_scrum_by_month($selected_date->format('Y-m-d'));
				
		foreach($scrum_list as $scrum){
			$date=new DateTime($scrum->get_month());
			$scrumuser=User::get($scrum->get_user_id());
				
			echo '<tr><td>'.$scrumuser->get_name().'</td><td>';
			
			echo Tools::get_chart(User::get($scrum->get_user_id()),$date);
			
			echo '</td><td>'.nl2br(Tools::identify_link($scrum->get_past())).'</td><td>'.nl2br(Tools::identify_link($scrum->get_future())).'</td><td>';
		
			if ($scrum->get_user_id()==$user->get_id()) {
				echo '<a href="scrum_edit.php?scrum_id='.$scrum->get_id().'"><img src="images/modify.png"></a>';
			}
			
			echo '</td></tr>';

		}


		?>
		 		
	</table>
	</form>
	 
</div>
 
