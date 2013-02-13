<div class="worklog-container">

	<div class="subheader" ">
	
		<div class="profile_photo" style="margin-top:10px;">
			<img src="photos/tibi.jpg">
		</div>
		<div class="titlebar" style="float:left;">
			<h4>Hidi Tibor's Worklog - LogView</h4>
			<select>
				<option value="201301">2013. January</option>
				<option value="201302">2013. February</option>
			</select>
			<select>
				<option value="201301">Hidi Tibor</option>
				<option value="201302">Csordas Mihaly</option>
				<option value="201302">Madi Gabor</option>
			</select>
			<input type="sumbit" value="OK" class="btn"
					style="width: 20px;">
					
		</div>
		<div class="personal_note">My personal note:  <input type="sumbit" class="btn btn-mini" value="Save" style="width:30px; float:right; margin-bottom: 3px;"> <br><textarea style="width: 250px; height: 60px;">Ide beírhatsz bármit magadnak emlékeztetőül</textarea></div>
		<div style="clear: both;"></div>
	</div>
	
					
	<hr>
	<div style="clear: both;"></div>
	
	<script>
	$(
		    function(){
		     
		        $('#time_from_link').click(function(){
		                  var time = new Date();                
		                  $('#time_from').val(time.getHours() + ":" + time.getMinutes());  
		        });
		        
		    }
		);

	$(
		    function(){
		     
		        $('#time_to_link').click(function(){
		                  var time = new Date();                
		                  $('#time_to').val(time.getHours() + ":" + time.getMinutes());  
		        });
		        
		    }
		);
	</script>
	
	<div class="alert">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Warning!</strong> You have already had a log entry at this time.
</div>


	<form method="post">
		<table class="table table-bordered">
			<tr>
				<td><select style="width: 120px !important;">
						<option value="1">IneTrack új weboldal</option>
						<option value="2">Iktató bugfix</option>
				</select>
				</td>
				<td><select style="width: 120px !important;">
						<option value="1">megbeszélés</option>
						<option value="2">design</option>
				</select>
				</td>
				<td><select style="width: 120px;"> 
						<option value="1">2013-02-12 (Monday)</option>
						<option value="1">2013-02-11 (Sunday)</option>
						<option value="1">2013-02-10 (Saturday)</option>
						<option value="1">2013-02-09 (Friday)</option>
				</select>
				</td>
				<td><input type="text" style="width: 40px;" id="time_from">
				</td>
				<td><input type="text" style="width: 40px;" id="time_to">
				</td>
				<td rowspan="2" class="editline"><textarea style="width: 250px; height: 60px;"></textarea>
				</td>
				<td><select style="width: 80px;">
						<option value="1">Iroda1</option>
						<option value="1">Iroda2</option>
						<option value="1">Tárgyaló1</option>
						<option value="1">Tárgyaló2</option>
						<option value="1">Otthon</option>
				</select>
				</td>
				<td><input type="sumbit" value="OK" class="btn btn-primary"
					style="width: 20px;">
				</td>
			</tr>
			
			
			<tr class="editline">
			<td colspan="3"><img src="images/information.png"> Ez a kategória ebben a projektben ezt jeleni</td>
				<td><a href="#" id="time_from_link">Now</a></td>
				<td><a href="#" id="time_to_link">Now</a></td>
				 
				<td></td>
				<td> </td>
				
			
			
			</tr>
			<tr>
				<th>Project</th>
				<th>Category</th>
				<th>Date</th>
				<th>From</th>
				<th>To</th>
				<th>Log</th>
				<th>Place</th>
				<th></th>
			</tr>

			<tr>
				<td><a href="project_view.php">Inepex új weboldal</a>
				</td>
				<td><a href="#">Design</a>
				</td>
				<td>2013-02-01</td>
				<td>09:30</td>
				<td>10:30</td>
				<td>Ezt csináltam, azt csináltam</td>
				<td>iroda1</td>
				<td><img src="images/modify.png"> <img src="images/delete.png">
				</td>
			</tr>
			<tr>
				<td><a href="project_view.php">Inepex új weboldal</a>
				</td>
				<td><a href="#">Design</a>
				</td>
				<td>2013-02-01</td>
				<td>09:30</td>
				<td>10:30</td>
				<td>Ezt csináltam, azt csináltam</td>
				<td>iroda1</td>
				<td></td>
			</tr>
			<tr>
				<td><a href="project_view.php">Inepex új weboldal</a>
				</td>
				<td><a href="#">Design</a>
				</td>
				<td>2013-02-01</td>
				<td>09:30</td>
				<td>10:30</td>
				<td>Ezt csináltam, azt csináltam</td>
				<td>iroda1</td>
				<td></td>
			</tr>

		</table>
	</form>





</div>
