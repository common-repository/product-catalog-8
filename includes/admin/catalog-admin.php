<?php

/* 
	Catalog form processing to add a new form
*/

/* These functions need to be called before the header on this page */

/* Updates the catalog, needs to be called before the header is included */
if(isset($_POST['pc8-update-link'])) {
	pc8_updateCatalog();
}

/* This function deletes the catalog */
if(isset($_GET['action'])) {
	if($_GET['action'] = 'delete') {
		DeleteCatalog();
	}
}

/* Add Catalog */
if((isset($_POST['submit_new_catalog'])) && check_admin_referer('update_catalog','update_catalog')) {
	pc8_addCatalog();
}


pc8_IncludeHeader();


/* This function adds the submitted form data to the database */
function pc8_addCatalog() {
	global $wpdb, $catalogs_table;
	
	$catalog_name = $_POST['catalog_name'];
	$catalog_description = $_POST['catalog_description'];
	
	$wpdb->insert(
		$catalogs_table,
			array(
				'catalog_name' => $catalog_name, 
				'catalog_description' => $catalog_description
				)
	);
	
	echo "<div class='updated'>Catalog has been added.</div>";
}

/* Update Catalog */
function pc8_updateCatalog() {
	global $wpdb, $catalogs_table;
	
	$catalog_id = sanitize_text_field($_POST['catalog_id_ups']);
	$catalog_name = sanitize_text_field($_POST['catalog_name_ups']);
	$catalog_description = sanitize_text_field($_POST['catalog_description_ups']);
	
	$wpdb->update( 
	$catalogs_table, 
		array( 
			'catalog_name' => $catalog_name, 
			'catalog_description' => $catalog_description
		),
		array( 'catalog_id' => $catalog_id )
	);

	echo "<div class='updated pupdate'>Catalog has been updated.</div>";
}

/* Deletes a Catalog */
function DeleteCatalog() {
	global $wpdb, $catalogs_table;
	$catalog_id = $_GET['id'];
	$wpdb->delete( $catalogs_table, array( 'catalog_id' => $catalog_id ) );
	echo "<div class='updated'>Catalog has been deleted.</div>";
}

?>

<div class="pc8-form-contain">
	<h2>Add Catalog</h2>
	<form action="admin.php?page=pc8-catalog-management" method="POST">
		<label for="catalog_name">Catalog Name:</label>
		<p><input name="catalog_name" id="catalog_name" type="text"></p>

		<label for="catalog_description">Catalog Description:</label>
		<p><textarea cols="19" rows="6" name="catalog_description" id="catalog_description" type="text"></textarea></p>
		<?php wp_nonce_field('update_catalog','update_catalog'); ?>
		<p><input type="submit" class="button-primary" name="submit_new_catalog" value="Add Catalog"></p>
	</form>
</div>


<?php

/* Gets the page number and limits the number of catalogs to 5 */
global $wpdb, $catalogs_table, $categories_table;

$table = $catalogs_table;
$get_items = pc8_SelectNoOrder($table);

?>
<div class="pc8-table">
<h2 class="pc8-bottom-20">Catalog</h2>
<table class="widefat fixed" cellspacing="0">
    <thead>
		<tr>
			<th id="cb" class="column-cb check-column" scope="col"></th> 
			<th id="catalogname" class="column-catalogname bold-text" scope="col">Catalog Name</th>
			<th id="catalog-description" class="column-catalog-description bold-text" scope="col">Catalog Description</th>
			<th id="catalog-shortcode" class="column-catalog-shortcode bold-text" scope="col">Shortcode</th>
		</tr>
    </thead>
   <tbody>
   <?php 
	$number = 0;
   
	foreach($get_items as $catalog_row) { 
		
		$catalog_name = $catalog_row->catalog_name;
		$catalog_description = $catalog_row->catalog_description;
		$catalog_id = $catalog_row->catalog_id;
   ?>
        <tr <?php if( $number % 2 == 0 ) { echo "class='alternate'"; } ?> >
            <th class="check-column" scope="row"></th>
            <td class="column-catalogname"><?php echo $catalog_name; ?>
					<p>
						<span><a class="opener-catalog" data-id="<?php echo $catalog_id; ?>" href="#">Delete</a> |</span>						
						<span><a class="opener-edit-catalog" data-id="<?php echo $catalog_id; ?>" href="#">Edit</a></span>
					</p>

            </td>
            <td class="column-catalog-description"><?php echo $catalog_description; ?></td>
            <td class="column-catalog-shortcode"><?php echo "[catalog-8 id='" . $catalog_id . "']"; ?></td>
	<?php 
		$number = $number + 1;
	} 
	?>	
        </tr>
    </tbody>
</table>
</div>
<div id="pc8-dialog-catalog" style="display:none;" title="Delete Catalog">
	<p>Are you sure you want to delete this catalog?</p>
	<br>
	<p>
		<a class="button-primary" id="pc8-delete-link" href="#">Delete</a>
		<a class="button-primary" id="pc8-close" href="#">Cancel</a>
	</p>
</div>
<div id="pc8-catalog-edit-dialog" style="display:none;" title="Edit Catalog">
	<p>Make your changes then press Update to save.</p>
	<form action="admin.php?page=pc8-catalog-management" method="POST">
		<label for="catalog_name_ups">Catalog Name</label>
		<p><input class="pc8-90" type="text" name="catalog_name_ups" id="catalog_name_ups"></p>
		
		<label for="catalog_description_ups">Catalog Description</label>
		<p><textarea class="pc8-90" name="catalog_description_ups" id="catalog_description_ups"></textarea></p>
		<br>
		<input type="hidden" id="the_cat_id" value="" name="catalog_id_ups">
		<p>
			<input type="submit" name="pc8-update-link" class="button-primary" value="Update Catalog">
			<a id="pc8-close-catalog" class="button-primary" href="#">Cancel</a>
		</p>
		<?php wp_nonce_field('update_catalog','update_catalog'); ?>
	</form>
</div>