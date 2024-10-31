<?php
/*This file has all AJAX related functions */

pc8_AccessCheck();

/*This function is called by AJAX, when the disk icon is clicked on product page*/

function UpdatePosition() {
	global $wpdb, $products_table, $categories_table, $subcategories_table;
	
	$product_position = $_POST['orderArray'];
	$product_ids = $_POST['productArray'];
	
	$table = $products_table;
	$counter = 0;
	
	foreach($product_ids as $productID) {	
		$position = $product_position[$counter];
		$wpdb->update( 
			$table, 
			array( 
				'position' => $position, 
			),
			array( 'id' => $productID )
		);
		$counter = $counter + 1;
		}
	
	if($_POST['currentPage'] !== "") {
		$pagenum = $_POST['currentPage'];
		$limit = 10;
		$offset = ( $pagenum - 1 ) * $limit;
		$total = pc8_CountRows($table);
		$get_items = pc8_SelectLimitQuery($table, $offset, $limit);
		$table = $categories_table;
		$get_cats = pc8_SelectAllQuery($table);
		$table = $subcategories_table;
		$get_subcats = pc8_SelectAllQuery($table);
	}
	else {
		$get_items = pc8_SelectAllQuery($table);
		$table = $categories_table;
		$get_cats = pc8_SelectAllQuery($table);
		$table = $subcategories_table;
		$get_subcats = pc8_SelectAllQuery($table);
	}
	echo json_encode(array($get_items, $get_cats, $get_subcats));
	die();
	
}	
	

add_action("wp_ajax_UpdatePosition", "UpdatePosition" );
add_action("wp_ajax_nopriv_UpdatePosition", "UpdatePosition");

/*This function is called by AJAX, when the disk icon is clicked on category page*/

function UpdatePositionCategory() {
	global $wpdb, $categories_table;
	
	$category_position = $_POST['orderArray'];
	$category_ids = $_POST['categoryArray'];
	
	$table = $categories_table;
	$counter = 0;
	
	foreach($category_ids as $categoryID) {	
		$position = $category_position[$counter];
		$wpdb->update( 
			$table, 
			array( 
				'position' => $position, 
			),
			array( 'id' => $categoryID )
		);
		$counter = $counter + 1;
		}
		
	if($_POST['currentPage'] !== "") {
		$pagenum = $_POST['currentPage'];
		$limit = 5;
		$offset = ( $pagenum - 1 ) * $limit;
		$total = pc8_CountRows($table);
		$get_items = pc8_SelectLimitQuery($table, $offset, $limit);
	}
	else {
		$get_items = pc8_SelectAllQuery($table);
	}
	
	echo json_encode($get_items);
	
	die();
}
	

add_action("wp_ajax_UpdatePositionCategory", "UpdatePositionCategory" );
add_action("wp_ajax_nopriv_UpdatePositionCategory", "UpdatePositionCategory");

/*This function is called by AJAX, when the disk icon is clicked on category page*/

function UpdatePositionSubCategory() {
	global $wpdb, $subcategories_table, $categories_table;
	
	$subcategory_position = $_POST['orderArray'];
	$subcategory_ids = $_POST['subcategoryArray'];
	
	$table = $subcategories_table;
	$counter = 0;
	
	foreach($subcategory_ids as $subcategoryID) {	
		$position = $subcategory_position[$counter];
		$wpdb->update( 
			$table, 
			array( 
				'position' => $position, 
			),
			array( 'id' => $subcategoryID )
		);
		$counter = $counter + 1;
		}
	if($_POST['currentPage'] !== "") {
		$pagenum = $_POST['currentPage'];
		$limit = 5;
		$offset = ( $pagenum - 1 ) * $limit;
		$total = pc8_CountRows($table);
		$get_items = pc8_SelectLimitQuery($table, $offset, $limit);
	}
	else {
		$get_items = pc8_SelectAllQuery($table);
		$table = $categories_table;
		$get_more_items = pc8_SelectAllQuery($table);
	}
	
	echo json_encode(array($get_items, $get_more_items));


		
	die();
}
	

add_action("wp_ajax_UpdatePositionSubCategory", "UpdatePositionSubCategory" );
add_action("wp_ajax_nopriv_UpdatePositionSubCategory", "UpdatePositionSubCategory");

/*This function is called by AJAX, updates subcategory list after category is selected. */

function UpdateCategoryList() {
	global $wpdb, $subcategories_table;

	global $wpdb;
	$table = $subcategories_table;
	$catid = $_POST['selectedCategory'];

	if($catid !== '0') {
		$get_items = $wpdb->get_results( "SELECT * FROM $table WHERE subcategory_category = $catid ORDER BY subcategory_name ASC" );
		echo json_encode($get_items);
	}
	else {
		$get_items = "";
		echo json_encode($get_items);
	}

	die();

}

add_action("wp_ajax_UpdateCategoryList", "UpdateCategoryList" );
add_action("wp_ajax_nopriv_UpdateCategoryList", "UpdateCategoryList");

/*Called to get the categories to populate the parent list in popup*/

function GetCategories() {
	global $wpdb, $categories_table;
	$table = $categories_table;
	$get_items = pc8_SelectAllQuery($table);
	echo(json_encode($get_items));
	die();
}

add_action("wp_ajax_GetCategories", "GetCategories" );
add_action("wp_ajax_nopriv_GetCategories", "GetCategories");

function GetRow() {
	global $wpdb, $subcategories_table;
	$table = $subcategories_table;
	$id = $_POST['SubCatID'];
	$get_items = pc8_SelectSpecific($table, $id);
	echo(json_encode($get_items));
	die();
}

add_action("wp_ajax_GetRow", "GetRow" );
add_action("wp_ajax_nopriv_GetRow", "GetRow");



function GetCatRow() {
	global $wpdb, $categories_table;
	$table = $categories_table;
	$id = $_POST['CatID'];
	$get_items = pc8_SelectSpecific($table, $id);
	echo(json_encode($get_items));
	die();

}

add_action("wp_ajax_GetCatRow", "GetCatRow" );
add_action("wp_ajax_nopriv_GetCatRow", "GetCatRow");

/* Get Catalogs */
function GetCatalogs() {
	global $wpdb, $catalogs_table;
	$table = $catalogs_table;
	$get_items = pc8_SelectAllCatalogs();
	echo(json_encode($get_items));
	die();
}

add_action("wp_ajax_GetCatalogs", "GetCatalogs" );
add_action("wp_ajax_nopriv_GetCatalogs", "GetCatalogs");

/* Update current catalog option */

function changeCatalog() {
	$id = $_POST['catalog_id'];
	update_option( 'pc8_current_catalog', $id );
	die();
} 

add_action("wp_ajax_changeCatalog", "changeCatalog" );
add_action("wp_ajax_nopriv_changeCatalog", "changeCatalog");

function getCatalog() {
	global $wpdb, $catalogs_table;
	$table = $catalogs_table;
	$id = $_POST['CatalogID'];
	$get_items = pc8_SelectSpecificLog($table, $id);
	echo(json_encode($get_items));
	die();

}

add_action("wp_ajax_getCatalog", "getCatalog" );
add_action("wp_ajax_nopriv_getCatalog", "getCatalog");

?>