<?php
	pc8_IncludeHeader();
	
	global $categories_table;

	$catalog_id = get_option('pc8_current_catalog');
	
	$table = $categories_table;
	$get_categories = pc8_SelectAllQuery($table);
	
	if((isset($_POST['submit_new_category'])) && check_admin_referer('create_category','create_category')) {
		ProcessCategoryForm($catalog_id);
	}
	
	if(isset($_GET['action'])) {
		if($_GET['action'] == 'delete') {
			pc8_Delete($categories_table);
		}
	}
	
	if((isset($_POST['pc8-update-link'])) && check_admin_referer('update_category','update_category')) {
		UpdateCategory();
	}
	
	function ProcessCategoryForm($catalog_id) {
		global $wpdb, $categories_table;
		
		$category_name = sanitize_text_field($_POST['category_name']);
		$category_description = sanitize_text_field($_POST['category_description']);
		
		$wpdb->insert($categories_table,
			array(
				'category_name' => $category_name,
				'category_description' => $category_description,
				'catalog' => $catalog_id
				)
			);
			
		echo "<div class='updated pupdate'>Category has been created.</div>";
	}
	
	function UpdateCategory() {
		global $wpdb, $categories_table;
		
		$category_id = sanitize_text_field($_POST['category_id_ups']);
		$category_name = sanitize_text_field($_POST['category_name_ups']);
		$category_description = sanitize_text_field($_POST['category_description_ups']);
		
		$wpdb->update( 
		$categories_table, 
			array( 
				'category_name' => $category_name, 
				'category_description' => $category_description
			),
			array( 'id' => $category_id )
		);
	
		echo "<div class='updated pupdate'>Category has been updated.</div>";
	}
		
	
	
?>

<div class="pc8-form-contain">
	<h2>Add a Category</h2>
	<form action="admin.php?page=pc8-category-management" method="POST">
		<label for="category_name">Category Name: </label>
		<p><input type="text" name="category_name" id="category_name"></p>
		
		<label for="category_description">Category Description</label>
		<p><textarea cols="19" rows="6" name="category_description" id="category_description" type="text"></textarea></p>
		<p><input type="submit" class="button-primary" name="submit_new_category" value="Add Category"></p>	
		<?php wp_nonce_field('create_category','create_category'); ?>
	</form>
</div>

<?php
	//get the data
global $wpdb, $categories_table;

$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

$table = $categories_table;
$limit = 10;
$offset = ( $pagenum - 1 ) * $limit;
$total = pc8_CountRows($table);
$get_items = pc8_SelectLimitQuery($table, $offset, $limit, $catalog_id);
$num_of_pages = ceil( $total / $limit );
?>

<div class="pc8-table">
<h2 class="pc8-bottom-20">Your Categories</h2>
<table id="pc8-category-table" class="widefat fixed" cellspacing="0">
    <thead>
		<tr>
			<th id="cb" class="column-cb check-column" scope="col"></th> 
			<th id="categ-name" class="column-categ-name bold-text" scope="col">Category Name</th>
			<th id="categ-description" class="column-categ-description bold-text" scope="col">Category Description</th>
			<th id="categ-order" class="column-ordering bold-text" scope="col">Order</th>
		</tr>
    </thead>
   <tbody>
   <?php 
	$number = 0;
   
	foreach($get_items as $category_row) { 
		$category_name = $category_row->category_name;
		$category_description = $category_row->category_description;
		$category_id = $category_row->id;
		$category_position = $category_row->position;
	
   ?>
        <tr <?php if( $number % 2 == 0 ) { echo "class='alternate'"; } ?> >
            <td class="check-column" scope="row"></td>
            <td class="column-categ-name"><?php echo $category_name; ?>
					<p>
						<span><a class="opener-category" data-id="<?php echo $category_id; ?>" href="#">Delete</a> |</span>						
						<span><a class="opener-edit-category" data-id="<?php echo $category_id; ?>" href="#">Edit</a></span>
					</p>
			</td>
            <td class="column-categ-description"><?php echo $category_description; ?></td>
			<td class="column-ordering"><input id="categoryid_<?php echo $category_id; ?>" type="text" class="pc8-small-input pc8-order" name="order[]" value="<?php echo $category_position; ?>"></td>
	<?php 
		$number = $number + 1;
	} 
	?>	
        </tr>
    </tbody>
</table>
<?php if(!empty($get_items)) { ?>
	<div id="save_category_positions" class="button-primary pc8-save-order ">Save Order</div>
<?php } elseif (empty($get_items)) { ?>
	<div class="pc8-instructions">You currently have no categories, use the form to the left to create one.</div>
<?php } ?>
<div id="pc8-dialog-category" style="display:none;" title="Delete Category">
	<p>Are you sure you want to delete this category? It will be removed from all products and subcategories.</p>
	<br>
	<p>
		<a class="button-primary" id="pc8-delete-link" href="#">Delete</a>
		<a class="button-primary" id="pc8-close" href="#">Cancel</a>
	</p>
</div>
<?php
	pc8_Pagination($num_of_pages, $pagenum);
?>
</div>
<!---Category Edit Dialog -->
<div id="pc8-category-edit-dialog" style="display:none;" title="Edit Category">
	<p>Make your changes then press Update to save.</p>
	<form action="admin.php?page=pc8-category-management" method="POST">
		<label for="category_name_ups">Category Name</label>
		<p><input class="pc8-90" type="text" name="category_name_ups" id="category_name_ups"></p>
		
		<label for="category_description_ups">Category Description</label>
		<p><textarea class="pc8-90" name="category_description_ups" id="category_description_ups"></textarea></p>
		<br>
		<input type="hidden" id="the_cat_id" value="" name="category_id_ups">
		<p>
			<input type="submit" name="pc8-update-link" class="button-primary" value="Update Category">
			<a id="pc8-close-cat" class="button-primary" href="#">Cancel</a>
		</p>
		<?php wp_nonce_field('update_category','update_category'); ?>
	</form>
</div>