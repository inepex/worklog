//notification close button handler
$('.close').live("click", function() {
	$(this).parent().remove();
});
//

// date picker
$.datepicker.setDefaults({
	dateFormat : 'yy-mm-dd'
});
$(function() {
	$(".datepicker").datepicker();
});
//