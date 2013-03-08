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
	 $("#duplicate_name").click(function (e){
	    	e.stopPropagation();
	    });
	 $("#duplicate").click(function (e){
	    	
	    	if($("#duplicate_name").val() != ""){
	    		$("#duplicate_name").removeClass("highlight");
	    		duplicateProject($("#project_id").val(),$("#duplicate_name").val());
	    	}else{
	    		e.stopPropagation();
	    		$("#duplicate_name").addClass("highlight");
	    	}
	    	
	    	
	    });
});
function duplicateProject(projectId,duplicateName){
	$.ajax({
		url : "ajax/duplicate_project.php",
		type : "POST",
		data : {
			project_id : projectId,
			duplicate_name : duplicateName
		},
	}).done(function(data) {
		if(parseInt(data) != NaN || parseInt(data) != 0){
			window.location = "project_edit.php?project_id="+parseInt(data);
		}
		else{
			alert("Error: "+data);
		}
	});
}
//