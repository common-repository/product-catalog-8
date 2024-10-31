<?php

/*
	Settings Page
*/

pc8_IncludeHeader();

/*Update Options if user clicked Save */
if((isset($_POST['pc8_options_update'])) && check_admin_referer('update_settings','update_settings')) {

		$new_symbol = sanitize_text_field($_POST['pc8_currency']);
		update_option( 'pc8_currency_symbol', $new_symbol );

		$new_option = $_POST['pc8_category_display'];
		update_option( 'pc8_category_display', $new_option );

		$new_option = $_POST['pc8_subcategory_display'];
		update_option( 'pc8_subcategory_display', $new_option );

		$new_option = $_POST['pc8_image_display'];
		update_option( 'pc8_image_display', $new_option );

		$new_option = $_POST['pc8_title_display'];
		update_option( 'pc8_title_display', $new_option );

		$new_option = $_POST['pc8_price_display'];
		update_option( 'pc8_price_display', $new_option );

		$new_option = $_POST['pc8_description_display'];
		update_option( 'pc8_description_display', $new_option );

		$new_option = $_POST['pc8_cat_description'];
		update_option( 'pc8_cat_description', $new_option );
		
		$new_option = $_POST['pc8_subcat_description'];
		update_option( 'pc8_subcat_description', $new_option );
		
		$new_option = $_POST['pc8_link_title'];
		update_option( 'pc8_link_title', $new_option );

		$new_option = sanitize_text_field($_POST['pc8_image_width']);
		update_option( 'pc8_image_width', $new_option );

		$new_option = sanitize_text_field($_POST['pc8_text_width']);
		update_option( 'pc8_text_width', $new_option );

		echo "<div class='updated pupdate'>Settings have been saved.</div>";
}

/*Return to default settings*/
if(isset($_POST['pc8_options_default'])) {
	update_option( 'pc8_currency_symbol', '$' );
	update_option( 'pc8_category_display', 'Show' );
	update_option( 'pc8_subcategory_display', 'Show' );
	update_option( 'pc8_image_display', 'Show' );
	update_option( 'pc8_title_display', 'Show' );
	update_option( 'pc8_price_display', 'Show' );
	update_option( 'pc8_description_display', 'Show' );
	update_option( 'pc8_cat_description', 'Show' );
	update_option( 'pc8_subcat_description', 'Show' );
	update_option( 'pc8_link_title', 'Active' );
	update_option( 'pc8_image_width', '25' );
	update_option( 'pc8_text_width', '70' );
	echo "<div class='updated pupdate'>Settings have been returned to default.</div>";
}

/*GET OPTIONS*/
$symbol = get_option( 'pc8_currency_symbol', '$');
$cat_display = get_option( 'pc8_category_display', 'Show');
$subcat_display = get_option( 'pc8_subcategory_display', 'Show');
$image_display = get_option( 'pc8_image_display', 'Show');
$title_display = get_option( 'pc8_title_display', 'Show');
$price_display = get_option( 'pc8_price_display', 'Show');
$description_display = get_option( 'pc8_description_display', 'Show');
$cat_description = get_option( 'pc8_cat_description', 'Show');
$subcat_description = get_option( 'pc8_subcat_description', 'Show');
$link_display = get_option('pc8_link_title', 'Active');
$image_width = get_option( 'pc8_image_width', '25');
$text_width = get_option( 'pc8_text_width', '70');
?>

<div class="pc8-settings-contain">
	<h2>Settings</h2>
	<form action="admin.php?page=pc8-settings" method="POST">
	<p>
		Currency Symbol: <input type='text' name='pc8_currency' class='pc8-small-input' value="<?php echo $symbol ?>"> 
	</p>
	<p>
		Category Titles: 
		<select name='pc8_category_display'>
			<option <?php if($cat_display == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($cat_display == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Sub-Category Titles: 
		<select name='pc8_subcategory_display'>
			<option <?php if($subcat_display == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($subcat_display == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Category Description: 
		<select name='pc8_cat_description'>
			<option <?php if($cat_description == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($cat_description == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Sub-Category Description: 
		<select name='pc8_subcat_description'>
			<option <?php if($subcat_description == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($subcat_description == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Product Images: 
		<select name='pc8_image_display'>
			<option <?php if($image_display == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($image_display == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Product Title: 
		<select name='pc8_title_display'>
			<option <?php if($title_display == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($title_display == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Product Price: 
		<select name='pc8_price_display'>
			<option <?php if($price_display == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($price_display == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Product Description: 
		<select name='pc8_description_display'>
			<option <?php if($description_display == "Show") { echo "selected"; } ?> value='Show'>Show</option>
			<option <?php if($description_display == "Hide") { echo "selected"; } ?> value='Hide'>Hide</option>
		</select>
	</p>
	<p>
		Product Title Link: 
		<select name='pc8_link_title'>
			<option <?php if($link_display == "Active") { echo "selected"; } ?> value='Active'>Active</option>
			<option <?php if($link_display == "Inactive") { echo "selected"; } ?> value='Inactive'>Inactive</option>
		</select>
	</p>
	<p>
		Width of Image (%): <input type='text' name='pc8_image_width' class='pc8-med-input' value="<?php echo $image_width ?>" />
	</p>	
	<p> 
		Width of Description (%): <input type='text' name='pc8_text_width' class='pc8-med-input' value="<?php echo $text_width ?>" />
	</p>
	<?php wp_nonce_field('update_settings','update_settings'); ?>
	<input type='submit' name='pc8_options_update' class='button-primary' value='Save Settings'>
	<form action="admin.php?page=pc8-settings" method="POST">
		<input type='submit' name='pc8_options_default' class='pc8-margleft button-primary' value='Default Settings'>
	</form>
	</form>
</div>
<div class="pc8-settings-right">
	This is the settings page, here you can determine how the catalog is displayed to users on the front end. By selecting 'Show'
	or 'Hide' you can choose which elements are displayed. 
	
	<p><b>Currency Symbol</b> is placed in front of the price, default is '$'.</p>
	<p><b>Category Titles</b> and <b>Subcategory Titles</b> will display above their corresponding products if 'Show' is selected.</p>
	<p><b>Category Description</b> and <b>Sub-Category Description</b> are displayed next to corresponding titles if 'Show' is selected.</p>
	<p><b>Product Title Link</b> makes the product title a clickable link to the specified URL. </p>
	<p><b>Width of Image (%)</b> allows you to determine the size of the image, enter an integer between 1 and 100 (Default: 25), <a target="_blank" href="http://productcatalog8.evwill.com/img/displayguide.jpg">click here</a> for more info.</p>
	<p><b>Width of Description (%)</b> allows you to determine the width of the product text, enter an integer between 1 and 100 (Default: 70), <a target="_blank" href="http://productcatalog8.evwill.com/img/displayguide.jpg">click here</a> for more info.</p>
</div>