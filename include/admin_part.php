<div>
	<a name="companies"></a>
	<h4>Admin</h4>
	<h5>Companies</h5>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="table table-bordered">
			<?php
			if(isset($_GET['action']) && $_GET['action'] =='edit' && isset($_GET['company_id']) && $_GET['company_id'] !=''){
				$company = new Company($_GET['company_id']);
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="company_name" value="'.$company->get_name().'">
				</td>
				<td><input type="submit" value="Save" class="btn">   <a href="user_edit.php#companies">Mégse</a>
				</td>
				</tr>
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="company_id" value="'.$company->get_id().'" />';
			}
			else{
				echo '<tr>
					
				<td><input type="text" style="width: 450px;"  name="company_name">
				</td>
				<td><input type="submit" value="Add" class="btn">
				</td>
				</tr>
				<input type="hidden" name="action" value="add" />';
			}
			$companies = Company::get_companies();
			foreach($companies as $company){
				/* @var $company Company */
				echo '<tr>
				<td width="120">'.$company->get_name().'</td>
				<td><a href="?action=edit&company_id='.$company->get_id().'#companies"><img src="images/modify.png"></a>&nbsp;'.(($company->is_in_use())?'':'<span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
					</tr>
					<tr>
						<td><a href="?action=delete&company_id='.$company->get_id().'" class="btn" id=""><font color="red">Igen</font></a></td>
						<td><font class="btn">Nem</font></td>
					</tr>
					</table>
					</ul></td>
					</span>').'</td>
				</tr>';
			}
			?>
		</table>
	</form>
</div>



<hr>

<div>
	<h5>Workplaces</h5>
	<a name="places"></a>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="table table-bordered">
			<?php 
			if(isset($_GET['action']) && $_GET['action'] =='edit' && isset($_GET['place_id']) && $_GET['place_id'] !=''){
				$place = new WorkPlace($_GET['place_id']);
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="place_name" value="'.$place->get_name().'">
				</td>
				<td><input type="submit" value="Save" class="btn">   <a href="user_edit.php#places">Mégse</a>
				</td>
				</tr>
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="place_id" value="'.$place->get_id().'" />';
			}
			else{
				echo '<tr>

				<td><input type="text" style="width: 450px;"  name="place_name">
				</td>
				<td><input type="submit" value="Add" class="btn">
				</td>
				</tr>
				<input type="hidden" name="action" value="add" />';
			}
			$places = WorkPlace::get_places();
			/* @var $place WorkPlace */
			foreach($places as $place){
				echo '<tr>
				<td width="120">'.$place->get_name().'</td>
				<td><a href="?action=edit&place_id='.$place->get_id().'#places"><img src="images/modify.png"></a>&nbsp;'.(($place->is_in_use())?'':'<span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
					</tr>
					<tr>
						<td><a href="?action=delete&place_id='.$place->get_id().'" class="btn" id=""><font color="red">Igen</font></a></td>
						<td><font class="btn">Nem</font></td>
					</tr>
					</table>
					</ul></td>
					</span>').'</td>
				</tr>';
			}
			?>
		</table>
	</form>
</div>


<hr>

<div>
	<h5>Categories</h5>
	<a name="categories"></a>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="table table-bordered">
			<?php 
			if(isset($_GET['action']) && $_GET['action'] =='edit' && isset($_GET['category_id']) && $_GET['category_id'] !=''){
				$category = new Category($_GET['category_id']);
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="category_name" value="'.$category->get_name().'">
				</td>
				<td><input type="submit" value="Save" class="btn">   <a href="user_edit.php#categories">Mégse</a>
				</td>
				</tr>
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="category_id" value="'.$category->get_id().'" />';
			}
			else{
				echo '<tr>
			
				<td><input type="text" style="width: 450px;"  name="category_name">
				</td>
				<td><input type="submit" value="Add" class="btn">
				</td>
				</tr>
				<input type="hidden" name="action" value="add" />';
			}
			$categories = Category::get_categories();
			foreach($categories as $category){
				/* @var $category Category */
				echo '<tr>
				<td width="120">'.$category->get_name().'</td>
				<td><a href="?action=edit&category_id='.$category->get_id().'#categories"><img src="images/modify.png"></a>&nbsp;'.(($category->is_in_use())?'':'<span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
					</tr>
					<tr>
						<td><a href="?action=delete&category_id='.$category->get_id().'" class="btn" id=""><font color="red">Igen</font></a></td>
						<td><font class="btn">Nem</font></td>
					</tr>
					</table>
					</ul></td>
					</span>').'
				</tr>';
			}
			?>
		</table>
	</form>
</div>


<div>
	<a name="efficiency"></a>
	 
	<h5>Efficiency</h5>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="table table-bordered">
			<?php
			if(isset($_GET['action']) && $_GET['action'] =='edit' && isset($_GET['efficiency_id']) && $_GET['efficiency_id'] !=''){
				$efficiency = new Efficiency($_GET['efficiency_id']);
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="efficiency_name" value="'.$efficiency->get_name().'">
				</td>
				<td><input type="submit" value="Save" class="btn">   <a href="user_edit.php#efficiency">Mégse</a>
				</td>
				</tr>
				<input type="hidden" name="action" value="edit" />
				<input type="hidden" name="efficiency_id" value="'.$efficiency->get_id().'" />';
			}
			else{
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="efficiency_name">
				</td>
				<td><input type="submit" value="Add" class="btn">
				</td>
				</tr>
				<input type="hidden" name="action" value="add" />';
			} 
			$efficiencies = Efficiency::get_efficiencies();
			foreach($efficiencies as $efficiency){
				/* @var $efficiency Efficienxy */
				echo '<tr>
				<td width="120">'.$efficiency->get_name().'</td>
				<td><a href="?action=edit&efficiency_id='.$efficiency->get_id().'#efficiency"><img src="images/modify.png"></a>&nbsp;'.(($efficiency->is_in_use())?'':'<span class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="images/delete.png"></a>
					<ul class="dropdown-menu">
					<table>
					<tr>
						<td colspan="2"><img src="images/warning.png"> Biztosan törölni szeretnéd?</td>
					</tr>
					<tr>
						<td><a href="?action=delete&efficiency_id='.$efficiency->get_id().'" class="btn" id=""><font color="red">Igen</font></a></td>
						<td><font class="btn">Nem</font></td>
					</tr>
					</table>
					</ul></td>
					</span>').'</td>
				</tr>';
			}
			?>
		</table>
	</form>
</div>
