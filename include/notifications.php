<div id="notifications">
	<?php 
	$notifications = Notification::get_notifications();
	foreach($notifications as $notification){
		/* @var $notification Notification */
		echo $notification->get_notification();
	}
	Notification::clear_notifications();
	?>
</div>
