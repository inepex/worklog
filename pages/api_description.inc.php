<div class="worklog-container">

	<div class="subheader">
		<div class="titlebar">
			<h4>Worklog API</h4>

		</div>
	</div>
	<hr>
	
 
	<p>You can find your API key at User Settings.</p>
	
	<hr>
	<h6>Login</h6>
	<p>api_login.php?username={YOUR_USER_NAME}&md5password={YOUR_PASSWORD_IN_MD5_HASH}</p>

	<p><i>Returns the user's API key, user_id and user_name. If login is not possible, all return values will be null.</i>
	
	<pre>
{
	"success": true,
	"message": "OK",
	"response": {
		"api_key": "apikeycomeshere",
		"user_id": "1",
		"user_name": "firstname.familyname"
	}
}
</pre>
	
	</p>
	
	<hr>
	<h6>Get logs</h6>
	<p>api_get_logs.php?api_key={YOUR_API_KEY}&date={FIRST_DAY_OF_A_SELECTED_MONTH}</p>
	<p><i>Returns the list of logs of selected user in the selected month groupped by log_id. Date parameter should be the following format: YYYY-MM-01</i>
	<pre>
{
	"success": true,
	"message": "OK",
	"response": [
		{
		"log_id": "1",
		"log": {
			"project": "Project_name",
			"category": "Name of category",
			"date": "2014-01-08",
			"from": "10:00",
			"to": "11:00",
			"diff": "01:00",
			"entry": "Log comes here",
			"work_place": "Iroda2",
			"efficiency": "Normál (90%)"
		},
		{
		"log_id": "2",
		"log": {
			"project": "Project_name",
			"category": "Name of category",
			"date": "2014-01-08",
			"from": "11:00",
			"to": "12:00",
			"diff": "01:00",
			"entry": "Log comes here",
			"work_place": "Iroda1",
			"efficiency": "Normál (90%)"
		}
	]
}
</pre>
	
	
	</p>
 
</div>
