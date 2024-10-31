<?php
pc8_AccessCheck();
function pc8_InstallTables() {

	global $wpdb;
	global $catalogs_table;
	global $products_table;
	global $categories_table;
	global $subcategories_table;
	global $pc8_current_version;
	$version = get_option('pc8_version');
	

	$sql = "CREATE TABLE $catalogs_table ( 
	catalog_id int(11) NOT NULL AUTO_INCREMENT,
	catalog_name text NOT NULL,
	catalog_description text NOT NULL,
	UNIQUE KEY id (catalog_id) );";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	$wpdb->query( 
			"INSERT IGNORE INTO $catalogs_table 
	(catalog_id, catalog_name, catalog_description) 
	VALUES 
	(1, 'Your Catalog', 'This description is for your use only, it is not displayed on the frontend')"
	        );


	$sql = "CREATE TABLE $products_table (
	id int(11) NOT NULL AUTO_INCREMENT,
	product_name text NOT NULL,
	product_description text NOT NULL,
	product_price text NOT NULL,
	product_link text NOT NULL,
	product_image text NOT NULL,
	catalog int(11) NOT NULL,
	product_category int(11) NOT NULL,
	product_subcategory int(11) NOT NULL,
	position int(11) NOT NULL,
	UNIQUE KEY id (id) );";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	
	$sql = "CREATE TABLE $categories_table (
	id int(11) NOT NULL AUTO_INCREMENT,
	category_name text NOT NULL,
	category_description text NOT NULL,
	catalog int(11) NOT NULL,
	position int(11) NOT NULL,
	UNIQUE KEY id (id) );";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	$sql = "CREATE TABLE $subcategories_table (
	id int(11) NOT NULL AUTO_INCREMENT,
	subcategory_name text NOT NULL,
	subcategory_description text NOT NULL,
	subcategory_category int(11) NOT NULL,
	catalog int(11) NOT NULL,
	position int(11) NOT NULL,
	UNIQUE KEY id (id) );";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	/* If the catalog is less than version 1.2, set all the items to catalog 1 */

	if(($version == null)||($version < 1.2)) {

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql = "UPDATE $products_table SET catalog = 1;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql = "UPDATE $categories_table SET catalog = 1;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$sql = "UPDATE $subcategories_table SET catalog = 1;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

	
}

?>