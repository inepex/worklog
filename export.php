<?php
	session_set_cookie_params(86400);
	ini_set('session.gc_maxlifetime', 86400);
	session_start();
	error_reporting(E_ALL);
	header("Content-type: text/html; charset=utf-8");
	require_once '../worklog-config.php';
	require_once 'classes/User.php';
	require_once 'classes/WorkPlace.php';
	require_once 'classes/Notification.php';
	require_once 'classes/Company.php';
	require_once 'classes/Efficiency.php';
	require_once 'classes/Category.php';
	require_once 'classes/AssociatedUser.php';
	require_once 'classes/AssociatedCategory.php';
	require_once 'classes/ProjectPlan.php';
	require_once 'classes/ProjectStatus.php';
	require_once 'classes/ProjectPlanEntry.php';
	require_once 'classes/Project.php';
	require_once 'classes/StatusBar.php';
	require_once 'classes/Tools.php';
	require_once 'classes/Log.php';
	
	include('include/login_functions.php');
	PhpConsole::start();
	error_reporting(E_ALL);
	if(isset($_SESSION['enterid'])){
		$user_id = $_SESSION['enterid'];
		$user = new User($user_id);
		$user_name = $user->get_user_name();
		$user_picture = $user->get_picture();

	//error handler
	require_once 'include/mail/sendErrorMail.php';
	global $site_version;
	if ($site_version!='local') {
		set_error_handler('send_error_mail');
	}
	}
	
	
	$data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel"> <meta http-equiv="Content-type" content="application/vnd.ms-excel; charset=utf-8" />
	<head>
	    <!--[if gte mso 9]>
	    <xml>
	        <x:ExcelWorkbook>
	            <x:ExcelWorksheets>
	                <x:ExcelWorksheet>
	                    <x:Name>Sheet 1</x:Name>
	                    <x:WorksheetOptions>
	                        <x:Print>
	                            <x:ValidPrinterInfo/>
	                        </x:Print>
	                    </x:WorksheetOptions>
	                </x:ExcelWorksheet>
	            </x:ExcelWorksheets>
	        </x:ExcelWorkbook>
	    </xml>
	    <![endif]-->
	</head>';


	$data.='<body><table>
	<tr>
	<th width="150">User</th>
	<th width="150">Project</th>
	<th width="150">Category</th>
	<th width="150">CategoryDescription</th>
	<th width="150">Date</th>
	<th width="80">From</th>
	<th width="80">To</th>
	<th width="60">Diff</th>
	<th width="270">Log</th>
	<th width="100">Place</th>
	<th width="100">Efficiency</th>
	
	</tr>';
	
	
	
	$logs = Log::export_logs($_GET['user_id'],$_GET['date_from'],$_GET['date_to']);
 
	foreach($logs as $log){
		/* @var $log Log */
		$project  = Project::get($log->get_project_id());
		$category = AssociatedCategory::get($log->get_category_assoc_id());
		$work_place = WorkPlace::get($log->get_working_place_id());
		$efficiency = Efficiency::get($log->get_efficiency_id());
		$user = new User($log->get_user_id());
		
		$datetime1 = new DateTime($log->get_from());
		$datetime2 = new DateTime($log->get_to());
		$interval = $datetime1->diff($datetime2);
		$diff = $interval->format('%H:%I');
		
		$data.= '<tr>';
		$data.= '<td>'.$user->get_name().'</td>';
		$data.= '<td>'.$project->get_name().'</td>';
		$data.= '<td>'.$category->get_name().'</td>';
		$data.= '<td>'.$category->get_description().'</td>';
		$data.= '<td>'.$log->get_date().'</td>';
		$data.= '<td>'.date("H:i",strtotime($log->get_from())).'</td>';
		$data.= '<td>'.date("H:i",strtotime($log->get_to())).'</td>';
		$data.= '<td>'.$diff.'</td>';
		$data.= '<td>'.$log->get_entry().'</td>';
		$data.= '<td>'.$work_place->get_name().' </td>';
		$data.='<td>'.$efficiency->get_name().'</td>';
		$data.= '</tr>';
		
	}
	$data.='</table></body>';
	
	// filename for download
	$filename = "worklog_export_" . date('Y-m-d H-i') . ".xls";
	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel; charset=utf-8");
	
	
	echo $data;
	 
	 
	exit;
	?>
		
		