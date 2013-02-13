<div class="worklog-container">

	<div class="subheader">

		<div class="titlebar">
			<div style="float: left;">
				<h4>Create New Project / Edit Project</h4>
			</div>
			<div style="float: right;">
				<a href="#" class="btn">Duplicate project</a> <a href="project_view.php"
					class="btn ">Project Page</a> <a href="project_edit.php" class="btn btn-inverse">Edit
					Project</a>
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>

	<hr>

	<div style="clear: both;"></div>
 	<form method="post">
			<table class="table table-bordered">
				<tr>
					<td width="120">Project name:</td>
					<td><input type="text"></td>
				</tr>
				<tr>
					<td>Description:</td>
					<td><textarea style="width: 700px; height: 100px;"></textarea></td>
				</tr>
				<tr>
					<td>Start:</td>
					<td>dátumválasztó</td>
				</tr>
				<tr>
					<td>Deadline:</td>
					<td>dátumválasztó</td>
				</tr>
				<tr>
					<td>Status:</td>
					<td><input type="radio"> Active <input type="radio"> Closed <input
						type="radio"> Archived</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" class="btn btn-primary" value="Save"></td>
				</tr>
			</table>
		</form>

		<hr>
		
		<div style="width: 30%; float: left;">
			<h4>Workmates</h4>
			<form method="post">
			<table class="table table-bordered">
				<tr>
					<td><select>
							<option value="1">Hidi Tibor</option>
							<option value="2">Madi Gabor</option>
					</select>
					</td>
					<td><input type="submit" value="Add" class="btn">
					</td>
				</tr>
				<tr>
					<td width="120"><img src="photos/tibi.jpg" width="20" height="20"> Hidi Tibor</td>
					<td><img src="images/delete.png"></td>
				</tr>
			</table>
			</form>
		</div>

		<div style="width: 68%; float: right;">
			<h4>Categories</h4>
			<form method="post">
			<table class="table table-bordered">
				<tr>
					<td><select style="width: 120px;">
							<option value="1">megbeszélés</option>
							<option value="2">design</option>
					</select>
					</td>
					<td><input type="text" style="width: 450px;">
					</td>
					<td><input type="submit" value="Add" class="btn">
					</td>
				</tr>
				<tr>
					<td width="120">megbeszélés</td>
					<td width="120">ez ezt jelenti most</td>
					<td><img src="images/delete.png"></td>
				</tr>
			</table>
			</form>
		</div>

		<div style="clear: both;"></div>
		<hr>
		
		<h4>Project Plan</h4>
		<form method="post">
			<table class="table table-bordered" style="width: 0;">
				 <tr>
				 	<th></th>
				 	<th>Hidi Tibor</th>
				 	<th>Madi Gabor</th>
				 	<th>SUM</th>
				 </tr>
				<tr>
				 	<th>design</th>
				 	<td><input type="text" style="width: 30px;"></td>
				 	<td><input type="text" style="width: 30px;"></td>
				 	<td>20</td>
				 </tr>
				  <tr>
				 	<th>megbeszélés</th>
				 	<td><input type="text" style="width: 30px;"></td>
				 	<td><input type="text" style="width: 30px;"></td>
				 	<td>20</td>
				 </tr>
				  <tr>
				 	<th>SUM</th>
				 	<td>10</td>
				 	<td>10</td>
				 	<td>20</td>
				 </tr>
			</table>
			<input type="submit" class="btn btn-primary" value="Save">
		</form>	
			
	</div>
</div>
