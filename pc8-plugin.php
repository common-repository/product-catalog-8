<?php
/*
Plugin Name: Product Catalog 8 
Description: This plugin was created to a provide an easy to use product catalog with many flexible options.
Author: Evan Williams
Author URI: http://www.evwill.com
Version: 1.2.0
*/

/* Set table global variables */

global $wpdb, $catalogs_table, $products_table, $categories_table, $subcategories_table, $pc8_current_version;
$catalogs_table = $wpdb->prefix . "pc8_catalogs";
$products_table = $wpdb->prefix . "pc8_products";
$categories_table = $wpdb->prefix . "pc8_categories";
$subcategories_table = $wpdb->prefix . "pc8_subcategories";
$pc8_current_version = "1.2";

/* Access Check */

function pc8_AccessCheck() {
	defined('ABSPATH') OR exit;
}

/* Check for upadte */
add_action( 'init', 'pc8_UpdateTables' );

function pc8_UpdateTables() {
	global $pc8_current_version;

	$version = get_option('pc8_version');
	
	if($pc8_current_version !== $version) {
		pc8_InstallTables();
		add_option( 'pc8_current_catalog');
		update_option( 'pc8_current_catalog', 1);
		update_option( 'pc8_version', $pc8_current_version);
	}
}


/*Install the Database when the plugin is activated*/
register_activation_hook(__FILE__, 'pc8_ActivatePlugin');

function pc8_ActivatePlugin() {
	pc8_InstallTables();
	pc8_AddOptions();
}

/*Uninstall Code, removes options and drops database tables*/

register_uninstall_hook(__FILE__, 'pc8_UninstallPlugin');

function pc8_UninstallPlugin() {

	global $wpdb, $products_table, $categories_table, $subcategories_table, $catalogs_table;
		
	delete_option( 'pc8_currency_symbol' );
	delete_option( 'pc8_category_display' );
	delete_option( 'pc8_subcategory_display' );
	delete_option( 'pc8_title_display' );
	delete_option( 'pc8_price_display' );
	delete_option( 'pc8_image_display' );
	delete_option( 'pc8_description_display' );
	delete_option( 'pc8_cat_description' );
	delete_option( 'pc8_subcat_description' );
	delete_option( 'pc8_link_title' );	
	delete_option( 'pc8_image_width' );	
	delete_option( 'pc8_text_width' );	
	delete_option( 'pc8_version' );	
	delete_option( 'pc8_current_catalog' );
	

	$wpdb->query("DROP TABLE IF EXISTS $products_table");

	$wpdb->query("DROP TABLE IF EXISTS $categories_table");

	$wpdb->query("DROP TABLE IF EXISTS $subcategories_table");

	$wpdb->query("DROP TABLE IF EXISTS $catalogs_table");
}

/* admin_menu runs after the basic admin panel menu structure is in place,
adds menu options to the menu. */
add_action('admin_menu', 'pc8_AddMenu');

/* add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ); */
/* Need to add the icon URL */
function pc8_AddMenu() {
	add_menu_page('Product Catalog 8 Plugin', 'Product Catalog 8', 'administrator', 'pc8-administration', 'pc8_AdminPage', plugins_url( 'includes/img/icon.png', __FILE__ ), '25.5');
	add_submenu_page( 'pc8-administration', 'Catalog Management - Product Catalog 8', 'Catalogs', 'administrator', 'pc8-catalog-management', 'pc8_CatalogPage' );
	add_submenu_page( 'pc8-administration', 'Product Management - Product Catalog 8', 'Products', 'administrator', 'pc8-product-management', 'pc8_ProductPage' );
	add_submenu_page( 'pc8-administration', 'Category Management - Product Catalog 8', 'Categories', 'administrator', 'pc8-category-management', 'pc8_CategoryPage' );
	add_submenu_page( 'pc8-administration', 'SubCategory Management - Product Catalog 8', 'Sub-Categories', 'administrator', 'pc8-subcategory-management', 'pc8_SubcategoryPage' );
	add_submenu_page( 'pc8-administration', 'Settings - Product Catalog 8', 'Settings', 'administrator', 'pc8-settings', 'pc8_SettingsPage' );
}

/* add the scripts and css */
function pc8_LoadScripts() {

	/*Jquery-UI*/
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-effects-highlight');

	/*Add Stylesheets*/
	wp_register_style( 'pc8-style', plugins_url('includes/css/pc8-style.css', __FILE__) );
    wp_enqueue_style( 'pc8-style' );
	
	wp_register_style( 'jquery-ui', plugins_url('includes/css/jquery-ui.css', __FILE__) );
    wp_enqueue_style( 'jquery-ui' );
	
	
	/*Image Upload Script*/
	wp_enqueue_script('pc8-image-upload', plugins_url('includes/js/pc8-image-upload.js', __FILE__ ));
	
	/*Table ordering script*/
	wp_register_script( "pc8-ordering", plugins_url('includes/js/pc8-ordering.js', __FILE__ ));
	wp_localize_script( 'pc8-ordering', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	wp_enqueue_script('pc8-ordering');
	
	/*Allows upload of media*/
	wp_enqueue_media();
	
}
	
add_action('admin_enqueue_scripts', 'pc8_LoadScripts');


function pc8_FrontendStyle() {
	wp_enqueue_style( 'pc8-frontend', plugins_url('product-catalog-8/includes/css/pc8-frontend.css'), false ); 
}

add_action( 'wp_enqueue_scripts', 'pc8_FrontendStyle' );

/*Add catalog settings to the wp_options table*/
function pc8_AddOptions() {
	add_option( 'pc8_currency_symbol', '$');
	add_option( 'pc8_category_display', 'Show');
	add_option( 'pc8_subcategory_display', 'Show');
	add_option( 'pc8_image_display', 'Show');
	add_option( 'pc8_title_display', 'Show');
	add_option( 'pc8_price_display', 'Show');
	add_option( 'pc8_description_display', 'Show');
	add_option( 'pc8_cat_description', 'Show');
	add_option( 'pc8_subcat_description', 'Show');
	add_option( 'pc8_image_width', '25');
	add_option( 'pc8_text_width', '70');
	add_option( 'pc8_link_title', 'Active');
	add_option( 'pc8_version', '1.2');
	add_option( 'pc8_current_catalog');
	update_option( 'pc8_current_catalog', 1 );
}

/* Include the files that are used */
include "includes/tables.php";
include "includes/main-functions.php";
include "includes/ajax-functions.php";
include "includes/shortcode.php";
?>