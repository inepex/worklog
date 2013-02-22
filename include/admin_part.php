<div>
	<a name="companies"></a>
	<h4>Admin</h4>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="table table-bordered">
			<?php
			if(isset($_GET['action']) && $_GET['action'] =='edit' && isset($_GET['company_id']) && $_GET['company_id'] !=''){
				$company = new Company($_GET['company_id']);
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="company_name" value="'.$company->get_name().'">
				</td>
				<td><input type="submit" value="Save" class="btn">
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
				<td><a href="?action=edit&company_id='.$company->get_id().'#companies"><img src="images/modify.png"></a>&nbsp;<a href="?action=delete&company_id='.$company->get_id().'">'.(($company->is_in_use())?'':'<img src="images/delete.png">').'</a></td>
				</tr>';
			}
			?>
		</table>
	</form>
</div>



<hr>

<div>
	<a name="places"></a>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="table table-bordered">
			<?php 
			if(isset($_GET['action']) && $_GET['action'] =='edit' && isset($_GET['place_id']) && $_GET['place_id'] !=''){
				$place = new WorkPlace($_GET['place_id']);
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="place_name" value="'.$place->get_name().'">
				</td>
				<td><input type="submit" value="Save" class="btn">
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
				<td><a href="?action=edit&place_id='.$place->get_id().'#places"><img src="images/modify.png"></a>&nbsp;<a href="?action=delete&place_id='.$place->get_id().'">'.(($place->is_in_use())?'':'<img src="images/delete.png">').'</a></td>
				</tr>';
			}
			?>
		</table>
	</form>
</div>


<hr>

<div>
	<a name="categories"></a>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<table class="table table-bordered">
			<?php 
			if(isset($_GET['action']) && $_GET['action'] =='edit' && isset($_GET['category_id']) && $_GET['category_id'] !=''){
				$category = new Category($_GET['category_id']);
				echo '<tr>
				<td><input type="text" style="width: 450px;"  name="category_name" value="'.$category->get_name().'">
				</td>
				<td><input type="submit" value="Save" class="btn">
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
				<td><a href="?action=edit&category_id='.$category->get_id().'#categories"><img src="images/modify.png"></a>&nbsp;<a href="?action=delete&category_id='.$category->get_id().'">'.(($category->is_in_use())?'':'<img src="images/delete.png">').'</a></td>
				</tr>';
			}
			?>
		</table>
	</form>
</div>
