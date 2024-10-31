=== Plugin Name ===
Contributors: EvWill
Donate link: http://pc8.evwill.com/donate.php
Tags: product, catalog, catalogue, product catalog, product catalogue
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Product Catalog 8 is a simple product catalog. Products, Categories and Subcategories can be created and edited, the catalog is displayed on the front-end using the shortcode [catalog-8].
On the settings page various display options can be set to customize the output on the frontend. This catalog can actually be used as an e-commerce tool because product descriptions can contain
html, so if you create a paypal button, paste it into a product description to begin selling.

* Frontend Demo: http://pc8.evwill.com/demo/
* Plugin Homepage: http://pc8.evwill.com/

Features:

* Products can be added under both categories and subcategories
* Categories or subcategories can be on different pages using shortcode attributes.
* Settings page to customize how products are displayed
* HTML can be added inside product descriptions

What's next for 2.0, which will be released in late 2014, some of these new features will be released in version 1.4-1.9 in updates during Q4 2014:

* Product Pages
* Multiple Images per Product
* Fancybox integration
* More layouts
* More customization
* Product sorting in the backend
* Much better codebase

== Installation ==

1. Upload the directory 'product-catalog-8' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place the shortcode [catalog-8] on any page.

== Frequently Asked Questions ==

= Is this an e-commerce plugin? =

Sort of. You are able to paste paypal buttons into the description area of products, I plan to integrate more options in the future.

= How do I put catalogs on seperate pages? =

On the Catalogs admin page, just copy the given shortcode. Each catalog has an id so the shortcode will be [catalog-8 id='1'] or [catalog-8 id='2']. If no id is given, the shortcode will default to the original catalog.

= How do I put categories on seperate pages? =

Using shortcode attributes. For example, lets say you have a category named "Laptops" you would simple use the shortcode [catalog-8 cat="Laptops"]. If your "Laptops" category has a subcategory named "Lenovo", you could output those products with [catalog-8 cat="Laptops" sub="Lenovo]. More documentation can be found on the PC8 site.

= Why is the plugin named Product Catalog 8? =

Well "Product Catalog" was too generic, so I added a random number to the end!

== Screenshots ==

1. Screenshot of products displayed on the frontend
2. Screenshot of a backend administration page

== Changelog ==

= 1.2 =
* Multiple catalogs are now possible.

= 1.1.1 =
* Broke the shortcode in the last version, oops! This is a patch.

= 1.1 =
* Shortcodes now can accept 'cat' or 'sub' attribute to output only a single category or subcategory on a specific page.

= 1.0 =
* Initial Release


== Upgrade Notice ==

Upgrade to 1.2 to use multiple catalogs.
