<?php
	pc8_IncludeHeader();
	
	global $wpdb, $subcategories_table, $categories_table, $products_table, $images_table;

	$catalog_id = get_option('pc8_current_catalog');
	
	$table = $subcategories_table;
	$get_subcategories = pc8_SelectAllQuery($table);
	
	$table = $categories_table;
	$get_categories = pc8_SelectCategoriesAlph($table, $catalog_id);

	if((isset($_POST['submit_new_product'])) && check_admin_referer('create_product','create_product')) {
		ProcessProductForm($catalog_id);
	}
	
	if(isset($_GET['action'])) {
		if($_GET['action'] = 'delete') {
			pc8_Delete($products_table);
		}
	}
	
	if((isset($_POST['submit_edit_product'])) && check_admin_referer('update_product','update_product')) {
		UpdateProduct();
	}
	

	function ProcessProductForm($catalog_id) {
		global $wpdb, $products_table, $product_category_table, $images_table;
		
		$product_name = sanitize_text_field($_POST['product_name']);
		$product_image = sanitize_text_field($_POST['Item_Image']);
		$product_description = $_POST['product_description'];
		$product_price = sanitize_text_field($_POST['product_price']);
		$product_link = sanitize_text_field($_POST['product_link']);
		$product_category = sanitize_text_field($_POST['product_category']);
		
		if(isset($_POST['product_subcategory'])) {
			$product_subcategory = sanitize_text_field($_POST['product_subcategory']);
		}
		else {
			$product_subcategory = 0;
		}
		
		/*Insert basic product information*/
		
		$wpdb->insert($products_table, 
			array(
				'product_name' => $product_name,
				'product_description' => $product_description,
				'product_price' => $product_price,
				'product_link' => $product_link,
				'product_image' => $product_image,
				'product_category' => $product_category,
				'catalog' => $catalog_id,
				'product_subcategory' => $product_subcategory
				)
			);

			echo "<div class='updated'>Product has been added.</div>";
	
	}
	
	/*If update button was pressed, update the DB */
	

	
	function UpdateProduct() {
		global $wpdb, $products_table; 
		
		$product_id = sanitize_text_field($_POST['product_id']);
		$product_name = sanitize_text_field($_POST['product_name']);
		$product_image = sanitize_text_field($_POST['Item_Image']);
		$product_description = $_POST['product_description'];
		$product_price = sanitize_text_field($_POST['product_price']);
		$product_link = sanitize_text_field($_POST['product_link']);
		$product_category = sanitize_text_field($_POST['product_category']);	
		$product_subcategory = sanitize_text_field($_POST['product_subcategory']);	
	
		$wpdb->update( 
		$products_table, 
		array( 
			'product_name' => $product_name, 
			'product_image' => $product_image, 
			'product_description' => $product_description, 
			'product_price' => $product_price,
			'product_link' => $product_link,
			'product_category' => $product_category,
			'product_subcategory' => $product_subcategory
		),
		array( 'id' => $product_id )
	);
	
	echo "<div class='updated pupdate'>Product has been updated.</div>";
	}
	
	/*If edit is not set, show the normal page */
	
	if(!isset($_GET['edit'])) {	

?>

<div class="pc8-product-contain">
<h2>Add a Product</h2>
<form action="admin.php?page=pc8-product-management" method="POST">
		<label for="product_name">Product Name:</label>
		<p><input name="product_name" id="product_name" type="text"></p>
		
		<div class="form-field-product pc8-bottom-20">
			<label for="pc8_image_url">Product Image:</label>
			<p>
				<input id="pc8_image_url" type="text" size="40" name="Item_Image" value="http://" /> 
				<input id="pc8_upload_button" class="button" type="button" value="Choose Image" />
			</p>
		</div>
		
		<label for="product_description">Product Description:</label>
		<p>
		<?php wp_editor( "", 'product_description', $settings = array("textarea_rows" => 10) ); ?> 
		</p>
		<label for="product">Price:</label>
		<p><input name="product_price" id="product_price" type="text"></p>
		<label for="product">Product Link:</label>
		<p><input name="product_link" id="product_link" type="text"></p>
		
		<label for="product">Product Category:</label>
		<p>
			<select id="product_category_select" class="pc8-default-input" name="product_category">
				<option value="0">Select Category</option>
				<option value="0">No Category</option>
			<?php
				foreach($get_categories as $category_row) {
			?>
				<option value="<?php echo "$category_row->id" ?>"><?php echo $category_row->category_name ?></option>
			<?php
				}
			?>
			</select>
		</p>

		<label for="product">Product Sub-Category:</label>
		<p>
			<select id="subcat_list" class="pc8-default-input" name="product_subcategory" disabled>
				<option value="0">Select Sub-Category</option>
				<option value="0">No Sub-Category</option>
			<?php
				foreach($get_subcategories as $subcategory_row) {
			?>
				<option value="<?php echo "$subcategory_row->id" ?>"><?php echo $subcategory_row->subcategory_name ?></option>
			<?php
				}
			?>
			</select>
		</p>
		<?php wp_nonce_field('create_product','create_product'); ?>
		<p><input type="submit" class="button-primary" name="submit_new_product" value="Add Product"></p>
	</form>
</div>
<?php
	//get the data

	global $wpdb, $products_table;

	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

	$table = $products_table;
	$limit = 10;
	$offset = ( $pagenum - 1 ) * $limit;
	$total = pc8_CountRows($table);
	$get_items = pc8_SelectLimitQuery($table, $offset, $limit, $catalog_id);
	$num_of_pages = ceil( $total / $limit );
?>

<div class="pc8-table">
<h2 class="pc8-bottom-20">Your Products</h2>
<table id="pc8-product-table" class="widefat fixed" cellspacing="0">
    <thead>
		<tr>
			<th id="cb" class="column-cb check-column" scope="col"></th> 
			<th id="product-name" class="column-product-name bold-text" scope="col">Product Name</th>
			<th id="product-category" class="column-product-category bold-text" scope="col">Category</th>
			<th id="product-subcategory" class="column-product-subcategory bold-text" scope="col">Sub-Category</th>
			<th id="order-description" class="column-ordering bold-text" scope="col">Order </th>
		</tr>
    </thead>
   <tbody>
   <?php 
	$number = 0;
	foreach($get_items as $product_row) { 
		$product_name = $product_row->product_name;
		$product_price = $product_row->product_price;
		$product_position = $product_row->position;
		$product_category = $product_row->product_category;
		$product_subcategory = $product_row->product_subcategory;
		$product_id = $product_row->id;
		
		
		$get_category = pc8_GetParent($get_categories, $product_category);
		$get_subcategory = pc8_GetSubCat($get_subcategories, $product_subcategory);
   ?>
        <tr <?php if( $number % 2 == 0 ) { echo "class='alternate'"; } ?> >
            <td class="check-column" scope="row"></td>
            <td class="column-product-name"><?php echo $product_name; ?>
				<p>
					<span><a class="opener-product" data-id="<?php echo $product_id; ?>" href="#">Delete</a> |</span>						
					<span><a href="admin.php?page=pc8-product-management&edit=<?php echo $product_id; ?>">Edit</a></span>						
					
				</p>
			</td>
			<td class="column-product-category"><?php echo $get_category ?></td>
			<td class="column-product-subcategory"><?php echo $get_subcategory ?></td>
			<td class="column-ordering"><input id="productid_<?php echo $product_id; ?>" type="text" class="pc8-small-input pc8-order" name="order[]" value="<?php echo $product_position; ?>"></td>
	<?php 
		$number = $number + 1;
	} 
	?>	
        </tr>
    </tbody>
</table>
<?php if(!empty($get_items)) { ?>
	<div id="save_order" class="button-primary pc8-save-order">Save Order</div>
<?php } elseif (empty($get_items)) { ?>
	<div class="pc8-instructions">You currently have no products, use the form to the left to create some.</div>
<?php } ?>

<!---Product Delete Dialog--->
<div id="pc8-dialog" style="display:none;" title="Delete Product">
	<p>Are you sure you want to delete this product?</p>
	<p>
		<a class="button-primary" id="pc8-delete-link" href="#">Delete</a>
		<a class="button-primary" id="pc8-close" href="#">Cancel</a>
	</p>
</div>

<?php

pc8_Pagination($num_of_pages, $pagenum);
?>
</div>

<?php
}
/*IF item edit ID is found in the URL */
if(isset($_GET['edit'])) {

$id = $_GET['edit'];
$table = $products_table;
$get_product = pc8_SelectSpecific($table, $id);


?>
<h2>Edit Product</h2>
<div class="pc8-product-contain">
	<form action="admin.php?page=pc8-product-management" method="POST">
		<label for="product_name">Product Name:</label>
		<p><input name="product_name" id="product_name" type="text" value="<?php echo $get_product->product_name; ?>"></p>
		
		<div class="form-field-product pc8-bottom-20">
			<label for="pc8_image_url">Product Image:</label>
			<p>
				<input id="pc8_image_url" type="text" size="40" name="Item_Image" value="<?php echo esc_url($get_product->product_image); ?>" /> 
				<input id="pc8_upload_button" class="button" type="button" value="Upload Image" />
			</p>
		</div>
		
		<label for="product_description">Product Description:</label>
		<p>
		<?php wp_editor( stripslashes_deep($get_product->product_description), 'product_description', $settings = array("textarea_rows" => 10) ); ?> 
		</p>
		<label for="product">Price:</label>
		<p><input name="product_price" id="product_price" type="text" value="<?php echo $get_product->product_price; ?>"></p>
		<label for="product">Product Link:</label>
		<p><input name="product_link" id="product_link" type="text" value="<?php echo esc_url($get_product->product_link); ?>"></p>
		
		<label for="product">Product Category:</label>
		<p>
			<select id="product_category_select" class="pc8-default-input" name="product_category">
				<option value="0">Select Category</option>
				<option value="0">No Category</option>
			<?php
				foreach($get_categories as $category_row) {
				
				if( $category_row->id == $get_product->product_category ) {
					$selected = "selected";
				}
				else {
					$selected = "";
				}
			?>
				<option <?php echo $selected; ?> value="<?php echo "$category_row->id" ?>"><?php echo $category_row->category_name ?></option>
			<?php
				}
			?>
			</select>
		</p>

		<label for="product">Product Sub-Category:</label>
		<p>
			<select id="subcat_list" class="pc8-default-input" name="product_subcategory">
				<option value="0">Select Sub-Category</option>
				<option value="0">No Sub-Category</option>
			<?php
				$table = $subcategories_table;
				$id = $get_product->product_category;
				$get_subcategories = pc8_SelectSubcats($table, $id);
			
				foreach($get_subcategories as $subcategory_row) {
					
					if( $subcategory_row->id == $get_product->product_subcategory ) {
						$selected = "selected";
					}
					else {
						$selected = "";
					}
			?>
				<option <?php echo $selected; ?> value="<?php echo $subcategory_row->id ?>"><?php echo $subcategory_row->subcategory_name ?></option>
			<?php
				}
			?>
			</select>
		</p>
		<input type="hidden" name="product_id" value="<?php echo $get_product->id; ?>">
		<?php wp_nonce_field('update_product','update_product'); ?>
		<p><input type="submit" class="button-primary" name="submit_edit_product" value="Update Product"></p>
	</form>
</div>

<?php
}
?>