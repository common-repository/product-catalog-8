<?php
/*This function deals with the shortcode, gets the attributes, and outputs the catalog on
the actual wordpress page.*/

function Output_Catalog($attributes) {
	
	global $wpdb, $categories_table, $subcategories_table, $products_table, $catalogs_table;
	
	/*GET OPTIONS*/
	$symbol_option = get_option( 'pc8_currency_symbol', '$');
	$cat_display_option = get_option( 'pc8_category_display', 'Show');
	$subcat_display_option = get_option( 'pc8_subcategory_display', 'Show');
	$subcat_description_option = get_option( 'pc8_subcat_description', 'Show');
	$cat_description_option = get_option( 'pc8_cat_description', 'Show');
	$image_display = get_option( 'pc8_image_display', 'Show');
	$title_display = get_option( 'pc8_title_display', 'Show');
	$price_display = get_option( 'pc8_price_display', 'Show');
	$description_display = get_option( 'pc8_description_display', 'Show');
	$image_width = get_option('pc8_image_width', '25');
	$text_width = get_option('pc8_text_width', '70');
	$link_display = get_option('pc8_link_title', 'Active');
	$pc8_output = "";
	


	extract(shortcode_atts(array(
						"id" => "1",
						"cat" => "all",
						"sub" => "all"
						), $attributes));

	/* Get all the data to create the catalog */
	$products = pc8_SelectAllQueryOutput($products_table, $id);
	$categories = pc8_SelectAllQueryOutput($categories_table, $id);
	$subcategories = pc8_SelectAllQueryOutput($subcategories_table, $id);
						

	/* If category attribute is set, get only the products from that category, and only output that category title */
	/* ELSE run the regular code that displays everything */

	if($cat !== "all") { 
		/* queries to get products from one category */
		$get_cat_info = pc8_getCatInfo($categories_table, $cat);

		$category_id = $get_cat_info->id;
		$category_name = $get_cat_info->category_name;
		$category_description = $get_cat_info->category_description;
		$products = pc8_getAllFromCategory($products_table, $category_id);
	
		/*Don't Output the Category title if Subcategory Att is set */
		if($sub == 'all') {
			/* Output the category name and description if needed */
			if($cat_display_option == 'Show') {
				$pc8_output .= "<div class='pc8-category'><h2 class='pc8-catmarg'>$category_name</h2></div>";
			}
			if($cat_description_option == 'Show') {
				$pc8_output .= "<div class='pc8-category-description'>$category_description</div>";
			}
		}

		if($sub == "all") {
		/* This outputs the products that have no subcategory */
		foreach($products as $product) {
		
			/*Define product variables for output*/
			$product_name = $product->product_name;
			$product_description = stripslashes_deep($product->product_description);
			$product_image = $product->product_image;
			$product_price = $product->product_price;
			$product_link = $product->product_link;
			$product_category_id = $product->product_category;
			$product_subcategory_id = $product->product_subcategory;
	
			/*The check for no categories*/
			if(($product_subcategory_id == 0)&&($product_category_id == $category_id)) {
				$pc8_output .= "<div class='pc8-product'>";
				
				if($image_display == 'Show') {
					$pc8_output .= "<div class='pc8-product-image' style='width:$image_width%'><img class='pc8-resize' src='" . esc_url($product_image) . "'/></div>";
				}
				
				$pc8_output .= "<div class='pc8-product-text' style='width:$text_width%'>";
				
				if($title_display == 'Show') {
					if($link_display == 'Active') {
						$pc8_output .= "<a href='$product_link' target='_blank'><h3 class='pc8-nospace pc8-product-title'>$product_name</h3></a>";
					} else {
						$pc8_output .= "<h3 class='pc8-nospace pc8-product-title'>$product_name</h3>";
					}
				}
				
				if($price_display == 'Show') {
					$pc8_output .= "<p class='pc8-nospace'>$symbol_option$product_price</p>";
				}
				
				if($description_display == 'Show') {
					$pc8_output .= "<p class='pc8-margtop'>" . $product_description . "</p>";
				}
				
				$pc8_output .= "</div></div>";
			}
		}
			
		/*Loop through subcategories*/
		foreach($subcategories as $subcategory) {
			
			$subcategory_name = $subcategory->subcategory_name;
			$subcategory_parent = $subcategory->subcategory_category;
			$subcategory_id = $subcategory->id;
			$subcategory_description = $subcategory->subcategory_description; 

			/*If the subcategory belongs under this category*/
			if($subcategory_parent == $category_id) {
			
				if($subcat_display_option == 'Show') {
					$pc8_output .= "<div class='pc8-subcategory'><h3 class='pc8-indent pc8-catmarg'>$subcategory_name</h3></div>";
				}
				if($subcat_description_option == 'Show') {
					$pc8_output .= "<div class='pc8-subcategory-description pc8-indent'>$subcategory_description</div>";
				}
				
				/*Loop through products */
				foreach($products as $product) {
					
					/*Define product variables for output*/
					$product_name = $product->product_name;
					$product_description = stripslashes_deep($product->product_description);
					$product_image = $product->product_image;
					$product_price = $product->product_price;
					$product_link = $product->product_link;
					$product_category_id = $product->product_category;
					$product_subcategory_id = $product->product_subcategory;
				
					
					/*As we loop through, if the products category is same as current category, output*/
					if($product_subcategory_id == $subcategory_id) {
						$pc8_output .= "<div class='pc8-product'>";
						
						if($image_display == 'Show') {
							$pc8_output .= "<div class='pc8-product-image' style='width:$image_width%'><img class='pc8-resize' src='" . esc_url($product_image) . "'/></div>";
						}
						
						$pc8_output .= "<div class='pc8-product-text' style='width:$text_width%'>";
						
						if($title_display == 'Show') {
							if($link_display == 'Active') {
								$pc8_output .= "<a href='$product_link' target='_blank'><h3 class='pc8-nospace pc8-product-title'>$product_name</h3></a>";
							} else {
								$pc8_output .= "<h3 class='pc8-nospace pc8-product-title'>$product_name</h3>";
							}
						}
						
						if($price_display == 'Show') {
							$pc8_output .= "<p class='pc8-nospace'>$symbol_option$product_price</p>";
						}
						
						if($description_display == 'Show') {
							$pc8_output .= "<p class='pc8-margtop'>" . $product_description . "</p>";
						}
						
						$pc8_output .= "</div></div>";
					}
					
				}
			}
	

	
		}

		}
		/*If the subcategory attribute is set.*/
		if($sub !== "all") {
	
					/*Loop through subcategories*/
		foreach($subcategories as $subcategory) {
				
			$subcategory_name = $subcategory->subcategory_name;
			$subcategory_parent = $subcategory->subcategory_category;
			$subcategory_id = $subcategory->id;
			$subcategory_description = $subcategory->subcategory_description; 

			/*If the subcategory is the same as the one the user entered.*/
			if($sub == $subcategory_name) {
				
				/*If the subcategory belongs under this category*/
				if($subcategory_parent == $category_id) {
				
					if($subcat_display_option == 'Show') {
						$pc8_output .= "<div class='pc8-subcategory'><h2 class='pc8-catmarg'>$subcategory_name</h3></div>";
					}
					if($subcat_description_option == 'Show') {
						$pc8_output .= "<div class='pc8-subcategory-description'>$subcategory_description</div>";
					}
					
					/*Loop through products */
					foreach($products as $product) {
						
						/*Define product variables for output*/
						$product_name = $product->product_name;
						$product_description = stripslashes_deep($product->product_description);
						$product_image = $product->product_image;
						$product_price = $product->product_price;
						$product_link = $product->product_link;
						$product_category_id = $product->product_category;
						$product_subcategory_id = $product->product_subcategory;
					
						
						/*As we loop through, if the products category is same as current category, output*/
						if($product_subcategory_id == $subcategory_id) {
							$pc8_output .= "<div class='pc8-product'>";
							
							if($image_display == 'Show') {
								$pc8_output .= "<div class='pc8-product-image' style='width:$image_width%'><img class='pc8-resize' src='" . esc_url($product_image) . "'/></div>";
							}
							
							$pc8_output .= "<div class='pc8-product-text' style='width:$text_width%'>";
							
							if($title_display == 'Show') {
								if($link_display == 'Active') {
									$pc8_output .= "<a href='$product_link' target='_blank'><h3 class='pc8-nospace pc8-product-title'>$product_name</h3></a>";
								} else {
									$pc8_output .= "<h3 class='pc8-nospace pc8-product-title'>$product_name</h3>";
								}
							}
							
							if($price_display == 'Show') {
								$pc8_output .= "<p class='pc8-nospace'>$symbol_option$product_price</p>";
							}
							
							if($description_display == 'Show') {
								$pc8_output .= "<p class='pc8-margtop'>" . $product_description . "</p>";
							}
							
							$pc8_output .= "</div></div>";
						}
						
					}
				}
			}
		}
	}	
	}
		else { 
			$pc8_output = "<div class='pc8-catalog-container'>";
	
	/*If our product has no category or subcategory, output it up here at the top */
	
			/*Loop through products */
			foreach($products as $product) {
				
				/*Define product variables for output*/
				$product_name = $product->product_name;
				$product_description = stripslashes_deep($product->product_description);
				$product_image = $product->product_image;
				$product_price = $product->product_price;
				$product_link = $product->product_link;
				$product_category_id = $product->product_category;
				$product_subcategory_id = $product->product_subcategory;
			
				/*The check for no categories*/
				if(($product_subcategory_id == 0)&&($product_category_id == 0)) {
					$pc8_output .= "<div class='pc8-product'>";
					
					if($image_display == 'Show') {
						$pc8_output .= "<div class='pc8-product-image' style='width:$image_width%'><img class='pc8-resize' src='" . esc_url($product_image) . "'/></div>";
					}
					
					$pc8_output .= "<div class='pc8-product-text' style='width:$text_width%'>";
					
					if($title_display == 'Show') {
						if($link_display == 'Active') {
							$pc8_output .= "<a href='$product_link' target='_blank'><h3 class='pc8-nospace pc8-product-title'>$product_name</h3></a>";
						} else {
							$pc8_output .= "<h3 class='pc8-nospace pc8-product-title'>$product_name</h3>";
						}
					}
					
					if($price_display == 'Show') {
						$pc8_output .= "<p class='pc8-nospace'>$symbol_option$product_price</p>";
					}
					
					if($description_display == 'Show') {
						$pc8_output .= "<p class='pc8-margtop'>" . $product_description . "</p>";
					}
					
					$pc8_output .= "</div></div>";
				}
				
			}

	
	/*Loop through the categories*/
	foreach($categories as $category) {
	
		$category_id = $category->id;
		$category_name = $category->category_name;
		$category_description = $category->category_description;
		
		if($cat_display_option == 'Show') {
			$pc8_output .= "<div class='pc8-category'><h2 class='pc8-catmarg'>$category_name</h2></div>";
		}
		if($cat_description_option == 'Show') {
			$pc8_output .= "<div class='pc8-category-description'>$category_description</div>";
		}
		
		/*If our product has no subcat output it up here at the top */
	
			/*Loop through products */
			foreach($products as $product) {
				
				/*Define product variables for output*/
				$product_name = $product->product_name;
				$product_description = stripslashes_deep($product->product_description);
				$product_image = $product->product_image;
				$product_price = $product->product_price;
				$product_link = $product->product_link;
				$product_category_id = $product->product_category;
				$product_subcategory_id = $product->product_subcategory;
			
				/*The check for no categories*/
				if(($product_subcategory_id == 0)&&($product_category_id == $category_id)) {
					$pc8_output .= "<div class='pc8-product'>";
					
					if($image_display == 'Show') {
						$pc8_output .= "<div class='pc8-product-image' style='width:$image_width%'><img class='pc8-resize' src='" . esc_url($product_image) . "'/></div>";
					}
					
					$pc8_output .= "<div class='pc8-product-text' style='width:$text_width%'>";
					
					if($title_display == 'Show') {
						if($link_display == 'Active') {
							$pc8_output .= "<a href='$product_link' target='_blank'><h3 class='pc8-nospace pc8-product-title'>$product_name</h3></a>";
						} else {
							$pc8_output .= "<h3 class='pc8-nospace pc8-product-title'>$product_name</h3>";
						}
					}
					
					if($price_display == 'Show') {
						$pc8_output .= "<p class='pc8-nospace'>$symbol_option$product_price</p>";
					}
					
					if($description_display == 'Show') {
						$pc8_output .= "<p class='pc8-margtop'>" . $product_description . "</p>";
					}
					
					$pc8_output .= "</div></div>";
				}
				
			}
		
		/*Loop through subcategories*/
		foreach($subcategories as $subcategory) {
		
		$subcategory_name = $subcategory->subcategory_name;
		$subcategory_parent = $subcategory->subcategory_category;
		$subcategory_id = $subcategory->id;
		$subcategory_description = $subcategory->subcategory_description; 
		
		/*If the subcategory belongs under this category*/
		if($subcategory_parent == $category_id) {
		
			if($subcat_display_option == 'Show') {
				$pc8_output .= "<div class='pc8-subcategory'><h3 class='pc8-indent pc8-catmarg'>$subcategory_name</h3></div>";
			}
			if($subcat_description_option == 'Show') {
				$pc8_output .= "<div class='pc8-subcategory-description pc8-indent'>$subcategory_description</div>";
			}
			
			/*Loop through products */
			foreach($products as $product) {
				
				/*Define product variables for output*/
				$product_name = $product->product_name;
				$product_description = stripslashes_deep($product->product_description);
				$product_image = $product->product_image;
				$product_price = $product->product_price;
				$product_link = $product->product_link;
				$product_category_id = $product->product_category;
				$product_subcategory_id = $product->product_subcategory;
			
				
				/*As we loop through, if the products category is same as current category, output*/
				if($product_subcategory_id == $subcategory_id) {
					$pc8_output .= "<div class='pc8-product'>";
					
					if($image_display == 'Show') {
						$pc8_output .= "<div class='pc8-product-image' style='width:$image_width%'><img class='pc8-resize' src='" . esc_url($product_image) . "'/></div>";
					}
					
					$pc8_output .= "<div class='pc8-product-text' style='width:$text_width%'>";
					
					if($title_display == 'Show') {
						if($link_display == 'Active') {
							$pc8_output .= "<a href='$product_link' target='_blank'><h3 class='pc8-nospace pc8-product-title'>$product_name</h3></a>";
						} else {
							$pc8_output .= "<h3 class='pc8-nospace pc8-product-title'>$product_name</h3>";
						}
					}
					
					if($price_display == 'Show') {
						$pc8_output .= "<p class='pc8-nospace'>$symbol_option$product_price</p>";
					}
					
					if($description_display == 'Show') {
						$pc8_output .= "<p class='pc8-margtop'>" . $product_description . "</p>";
					}
					
					$pc8_output .= "</div></div>";
				}
				
			}
		}
	

	
		}
	
	}
	}


	
	$pc8_output .= "</div>";


	
	return $pc8_output;
	
}


//Do your query and stuff here

add_shortcode("catalog-8", "Output_Catalog");

?>