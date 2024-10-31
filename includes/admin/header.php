<?php 
	pc8_AccessCheck();
	$screen = get_current_screen();
	$catalogs = pc8_SelectAllCatalogs();
	$this_link = $screen->base;
	$current_catalog = get_option('pc8_current_catalog');
?>

<div class="pc8-menubar">
	<a class="pc8-link" href="admin.php?page=pc8-administration">
		<div class="pc8-menuitem <?php if( $this_link == 'toplevel_page_pc8-administration' ) { echo 'pc8-bg'; } ?>">Dashboard</div>
	</a>
	<a class="pc8-link" href="admin.php?page=pc8-catalog-management">
		<div class="pc8-menuitem <?php if( $this_link == 'product-catalog-8_page_pc8-catalog-management' ) { echo 'pc8-bg'; } ?>">Catalogs</div>
	</a>
	<a class="pc8-link" href="admin.php?page=pc8-product-management">
		<div class="pc8-menuitem <?php if( $this_link == 'product-catalog-8_page_pc8-product-management' ) { echo 'pc8-bg'; } ?>">Products</div>
	</a>
	<a class="pc8-link" href="admin.php?page=pc8-category-management">
		<div class="pc8-menuitem <?php if( $this_link == 'product-catalog-8_page_pc8-category-management' ) { echo 'pc8-bg'; } ?>">Categories</div>
	</a>
	<a class="pc8-link" href="admin.php?page=pc8-subcategory-management">
		<div class="pc8-menuitem  <?php if( $this_link == 'product-catalog-8_page_pc8-subcategory-management' ) { echo 'pc8-bg'; } ?>">Sub-Categories</div>
	</a>
	<a class="pc8-link" href="admin.php?page=pc8-settings">
		<div class="pc8-menuitem no-margin <?php if( $this_link == 'product-catalog-8_page_pc8-settings' ) { echo 'pc8-bg'; } ?>">Settings</div>
	</a>
		
		<?php if(( $this_link == 'product-catalog-8_page_pc8-product-management' )||( $this_link == 'toplevel_page_pc8-administration' )||( $this_link == 'product-catalog-8_page_pc8-category-management' )||( $this_link == 'product-catalog-8_page_pc8-subcategory-management' ))
		{ ?>
		<label for="pc8_active_catalog">Catalog: </label>
		<select id="pc8_active_catalog" name="pc8_active_catalog">
			<?php 
				foreach($catalogs as $catalog) {  
			?>
				<option <?php if($current_catalog == $catalog->catalog_id) { echo "selected";} ?> value="<?php echo $catalog->catalog_id ?>"><?php echo $catalog->catalog_name ?></option>
			<?php 
				} 
			?>
		</select>
		<?php } ?>
</div>
<div class="pc8-notice"></div>
