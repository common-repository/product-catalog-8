<?php
	pc8_IncludeHeader();
	
	global $categories_table, $subcategories_table;

	$catalog_id = get_option('pc8_current_catalog');
	
	$table = $categories_table;
	$get_categories = pc8_SelectCategoriesAlph($table, $catalog_id);	
	
	if((isset($_POST['submit_new_subcategory'])) && check_admin_referer('create_subcat','create_subcat')) {
		ProcessSubCategoryForm($catalog_id);
	}
	
	if(isset($_GET['action'])) {
		if($_GET['action'] = 'delete') {
			pc8_Delete($subcategories_table);
		}
	}
	
	if((isset($_POST['submit_edit_subcategory'])) && check_admin_referer('update_subcat','update_subcat')) {
		UpdateSubcategory();
	}
	
	function ProcessSubCategoryForm($catalog_id) {
		global $wpdb, $subcategories_table;
		
		$subcategory_name = $_POST['subcategory_name'];
		$subcategory_description = $_POST['subcategory_description'];
		$subcategory_category = $_POST['subcategory_category'];
		
		$wpdb->insert($subcategories_table,
			array(
				'subcategory_name' => $subcategory_name,
				'subcategory_description' => $subcategory_description,
				'subcategory_category' => $subcategory_category,
				'catalog' => $catalog_id
				)
			);
			
		echo "<div class='updated pupdate'>Sub-Category has been created.</div>";
	}
	
	function UpdateSubcategory() {
		global $wpdb, $subcategories_table;
		
		$subcategory_id = $_POST['subcategory_id_ups'];
		$subcategory_name = $_POST['subcategory_name_ups'];
		$subcategory_description = $_POST['subcategory_description_ups'];
		$subcategory_category = $_POST['subcategory_category_ups'];
		
		$wpdb->update( 
		$subcategories_table, 
			array( 
				'subcategory_name' => $subcategory_name, 
				'subcategory_description' => $subcategory_description,
				'subcategory_category' => $subcategory_category
			),
			array( 'id' => $subcategory_id )
		);
	
		echo "<div class='updated pupdate'>Sub-Category has been updated.</div>";
	}
	
?>

<div class="pc8-form-contain">
	<h2>Add a Sub-Category</h2>
	<form action="admin.php?page=pc8-subcategory-management" method="POST">
		<label for="subcategory_name">Sub-Category Name: </label>
		<p><input type="text" name="subcategory_name" id="subcategory_name"></p>
		
		<label for="subcategory_description">Sub-Category Description</label>
		<p><textarea cols="19" rows="6" name="subcategory_description" id="subcategory_description" type="text"></textarea></p>
		
		<label for="sub_category_select">Parent Category</label>
		<p><select id="sub_category_select" class="pc8-default-input" name="subcategory_category">
			<option value="NoCat">Select Category</option>
		<?php
			foreach($get_categories as $category_row) {
		?>
			<option value="<?php echo "$category_row->id" ?>"><?php echo $category_row->category_name ?></option>
		<?php
			}
		?>
		</select></p>		
		<?php wp_nonce_field('create_subcat','create_subcat'); ?>
			<p><input type="submit" class="button-primary" name="submit_new_subcategory" value="Add Sub-Category"></p>	
	</form>
</div>

<?php
	//get the data
	global $wpdb, $subcategories_table;

	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

	$table = $subcategories_table;
	$limit = 10;
	$offset = ( $pagenum - 1 ) * $limit;
	$total = pc8_CountRows($table);
	$get_items = pc8_SelectLimitQuery($table, $offset, $limit, $catalog_id);
	$num_of_pages = ceil( $total / $limit );
?>

<div class="pc8-table">
<h2 class="pc8-bottom-20">Your Sub-Categories</h2>
<table id="pc8-subcategory-table" class="widefat fixed" cellspacing="0">
    <thead>
		<tr>
			<th id="cb" class="column-cb check-column" scope="col"></th> 
			<th id="subcat-name" class="column-columnname bold-text" scope="col">Sub-Category Name</th>
			<th id="subcat-parent" class="column-columnname bold-text" scope="col">Parent</th>
			<th id="subcat-order" class="column-ordering bold-text" scope="col">Order</th>		
		</tr>
    </thead>
   <tbody>
   <?php 
	$number = 0;
   
	foreach($get_items as $subcategory_row) { 
		
		$subcategory_name = $subcategory_row->subcategory_name;
		$subcategory_description = $subcategory_row->subcategory_description;
		$subcategory_id = $subcategory_row->id;
		$subcategory_position = $subcategory_row->position;
		$subcategory_parent = $subcategory_row->subcategory_category;
		$parent_name = "None";
		

		$parent_name = pc8_GetParent($get_categories, $subcategory_parent);
   ?>
        <tr <?php if( $number % 2 == 0 ) { echo "class='alternate'"; } ?> >
            <td class="check-column" scope="row"></td>
            <td class="column-subcat-name"><?php echo $subcategory_name; ?>
					<p>
						<span><a class="opener-subcategory" data-id="<?php echo $subcategory_id; ?>" href="#">Delete</a> |</span>						
						<span><a class="opener-edit-subcategory" data-id="<?php echo $subcategory_id; ?>" data-parent="<?php echo $subcategory_parent; ?>" href="#">Edit</a></span>
					</p>
				</div>
			</td>
            <td class="column-subcat-parent"><?php echo $parent_name; ?></td>
			<td class="column-ordering"><input id="subcategoryid_<?php echo $subcategory_id; ?>" type="text" class="pc8-small-input pc8-order" name="order[]" value="<?php echo $subcategory_position; ?>"></td>
	<?php 	
		$number = $number + 1;
	} 
	?>	
        </tr>
    </tbody>
</table>

<?php 
if(!empty($get_items)) { ?>
	<div id="save_subcat_positions" class="button-primary pc8-save-order">Save Order</div>
<?php } elseif (empty($get_items)) { ?>
	<div class="pc8-instructions">You currently have no subcategories, use the form to the left to create one.</div>
<?php } ?>
<div id="pc8-dialog-subcategory" style="display:none;" title="Delete Sub-Category">
	<p>Are you sure you want to delete this sub-category? It will automatically be removed from all products and categories.</p>
	<p>
		<a class="button-primary" id="pc8-delete-link" href="#">Delete</a>
		<a class="button-primary" id="pc8-close" href="#">Cancel</a>
	</p>
</div>
<?php
	pc8_Pagination($num_of_pages, $pagenum);
?>
</div>
<!---Subcategory Edit Dialog-->
<div id="pc8-subcategory-edit-dialog" style="display:none;" title="Edit Sub-Category">
	<p>Make your changes then press Update to save.</p>
	<form action="admin.php?page=pc8-subcategory-management" method="POST">
		<label for="subcategory_name_ups">Sub-Category Name: </label>
		<p><input class="pc8-90" type="text" name="subcategory_name_ups" id="subcategory_name_ups"></p>
		
		<label for="subcategory_description_ups">Sub-Category Description</label>
		<p><textarea class="pc8-90" name="subcategory_description_ups" id="subcategory_description_ups" type="text"></textarea></p>
		
		<label for="sub_category_select_ups">Parent Category</label>
		<p><select id="pc8-category_select" class="pc8-default-input" name="subcategory_category_ups">
			<option value="0">Select Parent Category</option>
			<option value="0">None</option>

		</select>
		</p>
			<input type="hidden" name="subcategory_id_ups" id="the_subcat_id" value="">
			<?php wp_nonce_field('update_subcat','update_subcat'); ?>
			<p><input type="submit" class="button-primary" name="submit_edit_subcategory" value="Update Sub-Category">
			<a id="pc8-close-subcat" class="button-primary" href="#">Cancel</a></p>	
	</form>
</div>