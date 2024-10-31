<?php
/*
	This file contains many of the primary functions required for the plugin 
*/

pc8_AccessCheck();


/* Admin output file include functions */

function pc8_AdminPage() {
	include "admin/mainpage.php";
}

function pc8_CatalogPage() {
	include "admin/catalog-admin.php";
}

function pc8_ProductPage() {
	include "admin/product-admin.php";
}

function pc8_CategoryPage() {
	include "admin/category-admin.php";
}

function pc8_SubCategoryPage() {
	include "admin/subcategory-admin.php";
}

function pc8_SettingsPage() {
	include "admin/settings-admin.php";
}

function pc8_IncludeHeader() {
	include "admin/header.php";
}

/* Query functions */

//select all catalogs
function pc8_SelectAllCatalogs() {
	global $wpdb, $catalogs_table;
	$get_items = $wpdb->get_results( "SELECT * FROM $catalogs_table ORDER BY catalog_name ASC" );
	return $get_items;
}

function pc8_SelectNoOrder($table) {
	global $wpdb;
	$get_items = $wpdb->get_results( "SELECT * FROM $table" );
	return $get_items;
}

function pc8_SelectLimitQuery($table, $offset, $limit, $catalog_id) {
	global $wpdb;
	$get_items = $wpdb->get_results( "SELECT * FROM $table WHERE catalog = '$catalog_id' ORDER BY position ASC LIMIT $offset, $limit" );
	return $get_items;
}

function pc8_SelectAllQuery($table) {
	global $wpdb;
	$catalog_id = get_option('pc8_current_catalog');
	$get_items = $wpdb->get_results( "SELECT * FROM $table WHERE catalog = '$catalog_id' ORDER BY position ASC" );
	return $get_items;
}

function pc8_SelectAllQueryOutput($table, $catalog_id) {
	global $wpdb;
	$get_items = $wpdb->get_results( "SELECT * FROM $table WHERE catalog = '$catalog_id' ORDER BY position ASC" );
	return $get_items;
}



function pc8_SelectCategoriesAlph($table, $catalog_id) {
	global $wpdb;
	$get_items = $wpdb->get_results( "SELECT * FROM $table WHERE catalog = '$catalog_id' ORDER BY category_name ASC" );
	return $get_items;
}

function pc8_CountRows($table) {
	global $wpdb;
	$total = $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
	return $total;
}

function pc8_SelectSpecific($table, $id) {
	global $wpdb;
	$get_item = $wpdb->get_row( "SELECT * FROM $table WHERE id = $id" );
	return $get_item;
}

function pc8_SelectSpecificLog($table, $id) {
	global $wpdb;
	$get_item = $wpdb->get_row( "SELECT * FROM $table WHERE catalog_id = $id" );
	return $get_item;
}

function pc8_SelectImage($table, $product_id) {
	global $wpdb;
	$get_image = $wpdb->get_row( "SELECT * FROM $table WHERE product_id = $product_id" );
	return $get_image;
}

function pc8_SelectSubcats($table, $category_id) {
	global $wpdb;
	$get_subcats = $wpdb->get_results( "SELECT * FROM $table WHERE subcategory_category = $category_id ORDER BY subcategory_name DESC" );
	return $get_subcats;
}

function pc8_getCatInfo($table, $category_name) {
	global $wpdb;
	$get_cat_id = $wpdb->get_row("SELECT * FROM $table WHERE category_name = '$category_name'");
	return $get_cat_id;
}

function pc8_getAllFromCategory($table, $category_id) {
	global $wpdb;
	$get_cat_name = $wpdb->get_results("SELECT * from $table WHERE product_category = $category_id");
	return $get_cat_name;
}

function pc8_Pagination($num_of_pages, $pagenum) {
	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pagenum', '%#%' ),
		'format' => '',
		'prev_text' => __( '&laquo;', 'aag' ),
		'next_text' => __( '&raquo;', 'aag' ),
		'total' => $num_of_pages,
		'current' => $pagenum
	) );

	if ( $page_links ) {
		echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
	}
}

/*Function that is called when delete is clicked in delete popup for any item*/

function pc8_Delete($table) {
	global $wpdb, $categories_table, $subcategories_table, $products_table;
	
	$id = $_GET['id'];
	$wpdb->delete ( $table, array ( 'id' => $id ) );
	
	if($table == $products_table) {
		echo "<div class='updated pupdate'>Product has been deleted.</div>";
	} elseif( $table == $categories_table ) {
		pc8_completeCategoryRemoval($id);
		echo "<div class='updated pupdate'>Category has been deleted.</div>";
	} elseif( $table == $subcategories_table ) {
		echo "<div class='updated pupdate'>SubCategory has been deleted.</div>";
	} else {
		echo "<div class='notice pupdate'>There has been an error.</div>";
	}
}

/*Removes the category from all products and subcategories by updating them, when the category
is deleted*/

function pc8_completeCategoryRemoval($category_id) {
	global $wpdb, $products_table, $subcategories_table;
	
	$table = $products_table;
	$wpdb->update( 
		$table, 
		array( 
			'product_category' => 0, 
		),
		array( 'product_category' => $category_id )
	);
	
	$table = $subcategories_table;
	$wpdb->update( 
		$table, 
		array( 
			'subcategory_category' => 0, 
		),
		array( 'subcategory_category' => $category_id )
	);
}

/*Returns the category name if given the ID */
	function pc8_GetParent($cats, $id) {	
		foreach($cats as $catrow) {
			if($catrow->id == $id) {
				$parent_name = $catrow->category_name;
				return $parent_name;
			}
		}
	}
	
/*Returns the subcategory name if given the ID */	
	function pc8_GetSubCat($cats, $id) {	
		foreach($cats as $catrow) {
			if($catrow->id == $id) {
				$parent_name = $catrow->subcategory_name;
				return $parent_name;
			}
		}
	}


/*SHORTCODE FUNCTIONS*/

/*Add Product*/

function pc8_AddProduct($id) {
	global $wpdb, $products_table;

	$product_info = pc8_SelectSpecific($table, $id);
	
	$product_name = $product_info->product_name;
	$product_description = $product_info->product_description;
	$product_image = $product_info->product_image;
	$product_price = $product_info->product_price;


}


?>