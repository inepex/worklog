// NOW button handler
$(function() {
	$('#time_from_link').click(function(event) {
		event.preventDefault();
		var time = new Date();
		$('#time_from').val(('0' + (time.getHours())).slice(-2) + ":" + ('0' + (time.getMinutes())).slice(-2));
	});

});

$(function() {
	$('#time_to_link').click(function(event) {
		event.preventDefault();
		var time = new Date();
		$('#time_to').val(('0' + (time.getHours())).slice(-2) + ":" + ('0' + (time.getMinutes())).slice(-2));
	});

});
//
// Last button handler
$(function() {
	$('#last_time_link').click(function(event) {
		event.preventDefault();
		$.ajax({
			url : "ajax/get_last_log_to_time.php",
		}).done(function(data) {
			$('#time_from').val(data);
		});
	});

});
var categories;
//category select handler
$(function() {
	$('#category_select').change(function() {
		var categoryId = $('#category_select :selected').val();
		fillCategoryDescription(categoryId);
	});
});
function fillCategoryDescription(categoryId){
	var categoryDescription = "";
	for (i = 0; i < categories.length; i++) {
		if(categories[i]['assoc_id'] == categoryId){
			categoryDescription = categories[i]['description'];
		}
	}
	$('#category_description').text(" "+categoryDescription);
}
//
//project select handler
$(document).ready(function() {
	if($('#project_select :selected').val() != undefined){
		var projectId = $('#project_select :selected').val();		
	}
	else{
		var projectId = $('#project_select option:first').val();
	}

	if(projectId != undefined){
		getCategories(projectId);	
	}
	fillDateSelect();
});
$('#countrylist').trigger('change');
$(function() {
	$('#project_select').change(function() {
		var projectId = $('#project_select :selected').val();
		getCategories(projectId);
	});
});
function getCategories(projectId) {
	$.ajax({
		url : "ajax/get_categories.php",
		type : "POST",
		async: false,
		data : {
			project_id : projectId
		}
	}).done(function(data) {
		fillCategoriesSelect(processCategoriesJson(data));
		$('#category_select').trigger('change');
	});
}
function processCategoriesJson(categoriesJson) {
	try{
		categories = JSON.parse(categoriesJson);
	}
	catch(error){
		console.log(categoriesJson);
	}
	return categories;
}
function fillCategoriesSelect(categories) {
	$('#category_select').empty();
		for (i = 0; i < categories.length; i++) {
			if($("#selected_category_id").val() != undefined && categories[i]['assoc_id'] == $("#selected_category_id").val() ){
				$('#category_select').append($("<option  selected='selected'></option>").attr("value",categories[i]['assoc_id']).attr("title",categories[i]['description']).text(categories[i]['name']));
			}
			else{
				$('#category_select').append($("<option></option>").attr("value",categories[i]['assoc_id']).attr("title",categories[i]['description']).text(categories[i]['name']));	
			}
		}
}
//
// date select fill
function fillDateSelect(){
	var weekday=new Array(7);
	weekday[0]="Sunday";
	weekday[1]="Monday";
	weekday[2]="Tuesday";
	weekday[3]="Wednesday";
	weekday[4]="Thursday";
	weekday[5]="Friday";
	weekday[6]="Saturday";
	for(i=0; i<5; i++){
		date = new Date();
		date.setDate(date.getDate() - i);
		var year  = date.getFullYear();
		var month = ('0' + (date.getMonth()+1)).slice(-2);
		var day   = ('0' + (date.getDate())).slice(-2);
		if($("#selected_date").val() != undefined && year+"-"+month+"-"+day == $("#selected_date").val() ){
			$('#date_select').append($("<option selected='selected'></option>").attr("value", year+"-"+month+"-"+day).text(year+"-"+month+"-"+day+" ("+weekday[date.getDay()]+")"));
		}else{
			$('#date_select').append($("<option></option>").attr("value", year+"-"+month+"-"+day).text(year+"-"+month+"-"+day+" ("+weekday[date.getDay()]+")"));	
		}
			
	}	
}
//