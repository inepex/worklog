//notification close button handler
$('.close').live("click", function() {
	$(this).parent().remove();
});
//
//NOW button handler
$(function() {
	$('#time_from_link').click(function(event) {
		event.preventDefault();
		var time = new Date();
		$('#time_from').val(time.getHours() + ":" + time.getMinutes());
	});

});

$(function() {
	$('#time_to_link').click(function(event) {
		event.preventDefault();
		var time = new Date();
		$('#time_to').val(time.getHours() + ":" + time.getMinutes());
	});

});
//
//date picker
$.datepicker.setDefaults({ dateFormat: 'yy-mm-dd' });
$(function() {
    $( ".datepicker" ).datepicker();
  });
//