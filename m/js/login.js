$(document).ready(function() {
	$("#submit").click(function() {
		var username = $("#username").val();
		var password = $().crypt({
			method : "md5",
			source : $("#password").val()
		});
		$.ajax({
			url : "http://worklog.h1v3.com/API/authenticate.php",
			type : "get",
			data : {
				username : username,
				password : password
			}
		}).done(function(response) {
			alert(response);
		});
	});
});