jQuery( document ).ready(function() {

	/*New Product Ordering, fired when Save Order is pressed */
	jQuery( "#save_order" ).click(function() {
		var lastChar,
			cardValue,
			items,
			counter,
			dark,
			parent,
			category_name = 'None',
			subcategory_name = 'None',
			cats,
			subcats;
			
		var OrderArray = [];
		var ProductArray = [];
		
		var currentPage = jQuery('span.current').text();
		
		var array = jQuery("input[name^='order']");
		var n = array.length;
		var idArray = jQuery("input[id^='productid_']");
		
		for(i=0;i<n;i++)
		{
			cardValue =  array.eq(i).val();
			product_id =  idArray.eq(i).attr('id');
			theNumber = product_id.split('_')[1];
			OrderArray.push(cardValue);
			ProductArray.push(theNumber);
		}
		
		jQuery.ajax({
			type: "POST",
			url: myAjax.ajaxurl,
			data: {action: "UpdatePosition", orderArray : OrderArray, productArray : ProductArray, currentPage : currentPage},
			dataType: 'json',
			success: function(data) {
				jQuery("#pc8-product-table").find("tr:gt(0)").remove();
			 
				items = data[0];
				cats = data[1];
				subcats = data[2];
				counter = 0;
					  
				jQuery.each(items, function(k, item) { 
						if (counter % 2 == 0) {
							dark = 'alternate';
						} else {
							dark = '';
						}
						
					jQuery.each(cats, function(i, cat) {
						if(cat.id == item.product_category) {
							category_name = cat.category_name;
						}
						if(item.product_category == 0) {
							category_name = '';
							
						}
					});
					
					jQuery.each(subcats, function(c, subcat) {
						if(subcat.id == item.product_subcategory) {
							subcategory_name = subcat.subcategory_name;
							console.log(subcategory_name);
						}
						if(item.product_subcategory == 0) {
							subcategory_name = '';
							console.log(subcategory_name);
						}
					});
						
				jQuery('#pc8-product-table tr:last').after('<tr class="'+dark+'"><td class="check-column" scope="row"></td><td class="column-product-name">'+item.product_name+'<p><span><a class="opener-product" data-id="'+item.id+'" href="#">Delete</a> | </span><span><a href="admin.php?page=pc8-product-management&edit='+item.id+'">Edit</a></span></p></td><td class="column-product-category">'+category_name+'</td><td class="column-product-subcategory">'+subcategory_name+'</td><td class="column-ordering"><input id="productid_'+item.id+'" type="text" class="pc8-small-input pc8-order" name="order[]" value="'+item.position+'"></td></tr>');
				counter = counter + 1;
				});
			
				jQuery(".pupdate").remove();
				jQuery(".pc8-notice").toggleClass('pc8-notice updated', 500).text('Product order has been updated.');
				jQuery(".updated")
					.effect('highlight');

			}
		});
	});

/* New category order */

	jQuery( "#save_category_positions" ).click(function() {
		var lastChar,
			cardValue,
			items,
			counter,
			dark;
		var OrderArray = [];
		var CategoryArray = [];
		
		var currentPage = jQuery('span.current').text();
		
		var array = jQuery("input[name^='order']");
		var n = array.length;
		var idArray = jQuery("input[id^='categoryid_']");
		
		for(i=0;i<n;i++)
		{
			cardValue =  array.eq(i).val();
			category_id =  idArray.eq(i).attr('id');
			theNumber = category_id.split('_')[1];
			OrderArray.push(cardValue);
			CategoryArray.push(theNumber);
		
		}
		
		jQuery.ajax({
			type: "POST",
			url: myAjax.ajaxurl,
			data: {action: "UpdatePositionCategory", orderArray : OrderArray, categoryArray : CategoryArray, currentPage : currentPage},
			success: function(data) {
				jQuery("#pc8-category-table").find("tr:gt(0)").remove();
			 
				items = JSON.parse(data);	
				counter = 0;
				
				jQuery.each(items , function(k, item) { 
					if (counter % 2 == 0) {
						dark = 'alternate';
					} else {
						dark = '';
					}
					jQuery('#pc8-category-table tr:last').after('<tr class="'+dark+'"><td class="check-column" scope="row"></td><td class="column-categ-name">'+item.category_name+'<p><span><a class="opener-category" data-id="'+item.id+'" href="#">Delete</a> | <a class="opener-edit-category" data-id="'+item.id+'" href="#">Edit</a></span></p></td> <td class="column-categ-description">'+item.category_description+'</td><td class="column-ordering"><input id="categoryid_'+item.id+'" type="text" class="pc8-small-input pc8-order" name="order[]" value="'+item.position+'"></td></tr>');
					counter = counter + 1;
				});
				
				jQuery(".pupdate").remove();
				jQuery(".pc8-notice").toggleClass('pc8-notice updated', 500).text('Category order has been updated.');
				jQuery(".updated")
				.effect('highlight');
			}
		});
	});

	/*New Subcat Order*/
	jQuery( "#save_subcat_positions" ).click(function() {
		var lastChar,
			cardValue,
			items,
			counter,
			dark,
			cats,
			category_name = 'None';
			
		var OrderArray = [];
		var SubcategoryArray = [];
		
		var currentPage = jQuery('span.current').text();
		
		var array = jQuery("input[name^='order']");
		var n = array.length;
		var idArray = jQuery("input[id^='subcategoryid_']");
		
		for(i=0;i<n;i++)
		{
			cardValue =  array.eq(i).val();
			subcategory_id =  idArray.eq(i).attr('id');
			theNumber = subcategory_id.split('_')[1];
			OrderArray.push(cardValue);
			SubcategoryArray.push(theNumber);
		}
		
		jQuery.ajax({
			type: "POST",
			url: myAjax.ajaxurl,
			data: {action: "UpdatePositionSubCategory", orderArray : OrderArray, subcategoryArray : SubcategoryArray, currentPage : currentPage},
			dataType: 'json',
			success: function(data) {
				jQuery("#pc8-subcategory-table").find("tr:gt(0)").remove();
		 
				items = data[0];	
				cats = data[1];	
				counter = 0;
						  
				jQuery.each(items , function(k, item) { 
					if (counter % 2 == 0) {
						dark = 'alternate';
					} else {
						dark = '';
					}
					
					jQuery.each(cats, function(i, cat) {
						if(cat.id == item.subcategory_category) {
							category_name = cat.category_name;
						}
					});

					if(item.subcategory_category == "0") {
						parent = "None";
					} else {
						parent = item.subcategory_category;
					}
						
					jQuery('#pc8-subcategory-table tr:last').after('<tr class="'+dark+'"><td class="check-column" scope="row"></td><td class="column-subcat-name">'+item.subcategory_name+'<p><span><a class="opener-subcategory" data-id="'+item.id+'" href="#">Delete</a> | <a class="opener-edit-subcategory" data-id="'+item.id+'" data-parent="1" href="#">Edit</a></span></p></td><td class="column-subcat-parent">'+category_name+'</td><td class="column-ordering"><input id="subcategoryid_'+item.id+'" type="text" class="pc8-small-input pc8-order" name="order[]" value="'+item.position+'"></td></tr>');
					counter = counter + 1;
				});
			
				jQuery(".pupdate").remove();
				jQuery(".pc8-notice").toggleClass('pc8-notice updated', 500).text('Sub-Category order has been updated.');
				jQuery(".updated")
				.effect('highlight');
			}	
		});
	});


/*Get the subcategories when the category is selected.*/

	jQuery( "#product_category_select" ).change(function() {
		var selectedCategory = jQuery('#product_category_select').find(":selected").val();
		
		jQuery.ajax({
			type: "POST",
			url: myAjax.ajaxurl,
			data: {action: "UpdateCategoryList", selectedCategory : selectedCategory},
			success: function(data, textStatus, XMLHttpRequest) {
			
				var subcats = JSON.parse(data);	
					 
				jQuery("#subcat_list").find('option').remove();
				jQuery("#subcat_list").prop('disabled',false);
					 
				jQuery('#subcat_list')
					.append(jQuery("<option value='0'>Select Sub-Category</option><option value='0'>No Sub-Category</option>"));
					 
				jQuery.each(subcats , function(k, item) { 
					jQuery('#subcat_list')
					.append(jQuery("<option></option>")
					.attr("value", item.id)
					.text(item.subcategory_name));
				});
			}
		});
	});


/*Dialog Boxes*/

/*Catalog Page*/

	jQuery(function() {
		jQuery( "#pc8-dialog-catalog" ).dialog({
			autoOpen: false,
			modal: true
		});
		
		jQuery(document).on('click', '.opener-catalog', function() {
			jQuery( "#pc8-dialog-catalog" ).data('id', this).dialog( "open" );
			var id = jQuery(this).data('id');
			jQuery('#pc8-delete-link').attr('href','admin.php?page=pc8-catalog-management&action=delete&id='+id);
		});
		
		jQuery( "#pc8-close" ).click(function() {
			jQuery('#pc8-dialog-catalog').dialog('close');
		});
	});


/*Products Page */

	jQuery(function() {
		jQuery( "#pc8-dialog" ).dialog({
			autoOpen: false,
			modal: true
		});
		
		jQuery(document).on('click', '.opener-product', function() {
			jQuery( "#pc8-dialog" ).data('id', this).dialog( "open" );
			var id = jQuery(this).data('id');
			jQuery('#pc8-delete-link').attr('href','admin.php?page=pc8-product-management&action=delete&id='+id);
		});
		
		jQuery( "#pc8-close" ).click(function() {
			jQuery('#pc8-dialog').dialog('close');
		});
	});

/*Category Page*/

	jQuery(function() {
		jQuery( "#pc8-dialog-category" ).dialog({
			autoOpen: false,
			modal: true
		});
		
		jQuery(document).on('click', '.opener-category', function() {
			jQuery( "#pc8-dialog-category" ).data('id', this).dialog( "open" );
			var id = jQuery(this).data('id');
			jQuery('#pc8-delete-link').attr('href','admin.php?page=pc8-category-management&action=delete&id='+id);
		});
		
		jQuery( "#pc8-close" ).click(function() {
			jQuery('#pc8-dialog-category').dialog('close');
		});
	});

/*SubCategory Page */

	jQuery(function() {
		jQuery( "#pc8-dialog-subcategory" ).dialog({
			autoOpen: false,
			modal: true
		});
		
		jQuery(document).on('click', '.opener-subcategory', function() {
			jQuery( "#pc8-dialog-subcategory" ).data('id', this).dialog( "open" );
			var id = jQuery(this).data('id');
			jQuery('#pc8-delete-link').attr('href','admin.php?page=pc8-subcategory-management&action=delete&id='+id);
		});

		jQuery( "#pc8-close" ).click(function() {
			jQuery('#pc8-dialog-subcategory').dialog('close');
		});
	});


/*Edit dialog boxes */

/* catalog page */
	jQuery(function() {
		jQuery( "#pc8-catalog-edit-dialog" ).dialog({
			autoOpen: false,
			modal: true
		});
		
		jQuery(document).on('click', '.opener-edit-catalog', function() {
			jQuery( "#pc8-catalog-edit-dialog" ).data('id', this).dialog( "open" );
			var id = jQuery(this).data('id');
			jQuery('#the_cat_id').attr('value',id);
			console.log(id);
			jQuery.ajax({
				type: "POST",
				url: myAjax.ajaxurl,
				data: {action: "getCatalog", CatalogID : id},
				success: function(data) {
					cat = JSON.parse(data);
					jQuery('#catalog_name_ups').val(cat.catalog_name);
					jQuery('#catalog_description_ups').val(cat.catalog_description);
				}
			});
		});
	
		jQuery( "#pc8-close-cat" ).click(function() {
			jQuery('#pc8-category-edit-dialog').dialog('close');
		});
	});



/*category Page*/
	jQuery(function() {
		jQuery( "#pc8-category-edit-dialog" ).dialog({
			autoOpen: false,
			modal: true
		});
		
		jQuery(document).on('click', '.opener-edit-category', function() {
			jQuery( "#pc8-category-edit-dialog" ).data('id', this).dialog( "open" );
			var id = jQuery(this).data('id');
			jQuery('#the_cat_id').attr('value',id);
			
			
			
			jQuery.ajax({
				type: "POST",
				url: myAjax.ajaxurl,
				data: {action: "GetCatRow", CatID : id},
				success: function(data) {
					cat = JSON.parse(data);
					jQuery('#category_name_ups').val(cat.category_name);
					jQuery('#category_description_ups').val(cat.category_description);
				}
			});
		});
	
		jQuery( "#pc8-close-catalog" ).click(function() {
			jQuery('#pc8-catalog-edit-dialog').dialog('close');
		});
	});


	/*Subcategory page */
	jQuery(function() {
		jQuery( "#pc8-subcategory-edit-dialog" ).dialog({
			autoOpen: false,
			modal: true
		});
		
		jQuery(document).on('click', '.opener-edit-subcategory', function() {
			jQuery( "#pc8-subcategory-edit-dialog" ).data('id', this).dialog( "open" );
			var id = jQuery(this).data('id');
			var parent = jQuery(this).data('parent');
			jQuery('#the_subcat_id').attr('value',id);
			
			jQuery.ajax({
				type: "POST",
				url: myAjax.ajaxurl,
				data: {action: "GetCategories", SubCatID : id},
				success: function(data) {
					var cats = JSON.parse(data);
					
					jQuery("#pc8-category_select option").remove();
					jQuery('#pc8-category_select')
							.append(jQuery("<option value='0'>Select Parent Category</option><option value='0'>None</option>"));
					
					var x = 2;
					var index;
					jQuery.each(cats, function(k, item) { 
						jQuery('#pc8-category_select')
						.append(jQuery("<option></option>")
						.attr("value", item.id)
						.text(item.category_name));
						
						if(item.id == parent) {
							index = x;
						}
						x = x + 1;
					});
					
					jQuery('#pc8-category_select option:eq('+index+')').prop('selected', true)
					jQuery('#pc8-category_select option [value='+parent+']').prop('selected', true);
				}
			});
			
			jQuery.ajax({
				type: "POST",
				url: myAjax.ajaxurl,
				data: {action: "GetRow", SubCatID : id},
				success: function(data) {
					subcat = JSON.parse(data);
					jQuery('#subcategory_name_ups').val(subcat.subcategory_name);
					jQuery('#subcategory_description_ups').val(subcat.subcategory_description);
				}
			});
		});
		
		jQuery( "#pc8-close-subcat" ).click(function() {
			jQuery('#pc8-subcategory-edit-dialog').dialog('close');
		});
	});
});

/*When the catalog is changed */
	jQuery(document).on('change', '#pc8_active_catalog', function() {
		var selected_catalog = jQuery("#pc8_active_catalog option").filter(":selected").val();
		jQuery.ajax({
			type: "POST",
			url: myAjax.ajaxurl,
			data: {action: "changeCatalog", catalog_id : selected_catalog},
			success: function(data) {
				console.log(selected_catalog);
				window.location = window.location.href;
			}
		});
		
	});
