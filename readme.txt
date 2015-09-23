==== Stripe Payment Gateway WooCommerce Addon ====
Contributors: nazrulhassanmca
Plugin Name: Stripe WooCommerce Addon
Plugin URI: https://wordpress.org/plugins/stripe-woocommerce-addon/
Tags: woocommerce plugin stripe,woocommerce addon stripe,stripe for woocommerce,stripe payment in wordpress,stripe payment gateway for woocommerce,woocommerce stripe gateway download,stripe plugin for woocommerce,stripe woocommerce plugin,stripe payment gateway for woocommerce,woocommerce credit cards payment with stripe,saved card for woocommerce,saved card stripe,stripe payment refunds,stripe woocommerce addon,credit cards payment stripe and woocommerce,free stripe woocommerce plugin
Author URI: https://nazrulhassan.wordpress.com/
Author: nazrulhassanmca
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=nazrulhassan@ymail.com&item_name=Donation+Stripe+Woocommerce+Addon
Requires at least: WP 4.0  & WooCommerce 2.2+
Tested up to: 4.3 & WooCommerce 2.4.6
Stable tag: 1.0.5
Version: 1.0.5
License: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

This plugin acts as an addon for woocommerce to add a payment method for WooCommerce for accepting **Credit Cards Payments** by merchants via **Stripe** directly on checkout page.
This plugin uses Stripe API version  **2015-09-03** to create tokens and charge credit cards. For better visualization of how it looks & works check screenshots tab.



= Features =
1. Simple Clean Code to accept Credit cards via Stripe in woocommerce
2. No technical skills needed.
3. Prerequisite visualized on screenshots.
4. Adds Charde Id and Charge time to Order Note.
5. This plugin can be customized easily.
6. This plugin bundles with <a href="https://github.com/stripe/stripe-php">Official Stripe® API Libraries</a> Version 1.18.0 to support PHP 5.2 Compatibility.
7. Single checkbox to set plugin in Sandbox/Live mode.
8. This plugin does not store **Credit Card Details**
9. Uses Token method to charge Credit Cards rather sending sensitive card details to stripe directly as prescribed by Stripe.
10. This plugin requires SSL on merchant site as described <a href="https://stripe.com/help/ssl">here</a>.
11. This plugin suppports Authorize / Authorize and Capture.
12. This plugin suppports provides to accept the type of card you like.
13. This plugin Support refunds **(Only in Cents)** in woocommerce interface
14. This plugin currently supports Multiple Currency accepted one at a time Irrespective of store base currency See Screenshot No.2
15. This plugin supports provides to enable allowed shipping method.
16. This plugin supports dynamic cards logo based on allowed cards in Plugin settings
17. Pro Version supports saved card for future checkout.
18. This plug-in supports the **<a href="http://www.cartspan.com/">CartSpan</a> QuickBooks accounting integration** by providing detailed payment methods for account reconciliation. This functionality is not available with any other payment gateway or WooCommerce accounting integration.

Also See

	1. https://support.stripe.com/questions/which-currencies-does-stripe-support
	

=Pro Version Video=

https://www.youtube.com/watch?v=Q1LXMQsVuaA

== Screenshots ==

01. Screenshot 1 - Api Key Location 
02. Screenshot 2 - Admin Settings of Addon
03. Screenshot 3 - Checkout Page Form
04. Screeensho 4 - This plugin passes Order Id in stripe meta and billing email in description.
05. Screenshot 5 - This shows a partial refunded amount of order with charge & refund ID with charge id passed to order.
06. Screenshot 6 - This shows how refund appears on stripe dashboard

== Installation ==

1. Upload 'stripe-woocommerce-addon' folder to the '/wp-content/plugins/' directory
2. Activate 'Stripe Woocommerce Addon' from wp plugin lists in admin area
3. Plugin will appear in settings of woocommerce
4. You can set the addon settings from 
   wocommmerce -> settings -> Checkout -> Stripe Cards Settings
5. You can check for Testing Card No <a href="https://stripe.com/docs/testing" target="_blank" >Here</a> 
6. **Make sure you have 2 Decimal Places in Pricing Options (WooCommerce > Settings > General > Currency Options) with Trailing Zeros Checked else the charge created by card will be incorrect**

== Frequently Asked Questions ==

1. You need to have woocoommerce plugin installed to make this plugin work
2. You need to follow The Screeenshot 1 to obtain API keys from Stripe <a href="https://dashboard.stripe.com/dashboard">Dashboard</a>
3. This plugin works on test & live api keys.
4. This plugin readily works on local.
5. This plugin requires SSL as per <a href="https://stripe.com/help/ssl">Here</a> but can work even without SSL.
6. This plugin does not store Card Details anywhere **except last4 and brand when enabled for <a href="http://www.cartspan.com/">CartSpan</a>**
7. This plugin comes packed with Official Stripe Libraries
8. This plugin requires CURL
9. This plugin does not support Pre Order or Subscriptions 
10. Stripe & PCI compliance requires to use SSL always
11. This plugin Support refunds **(Only in Cents)** in woocommerce interface. On full refund order state needs to be manually changed to refunded.
12. Upon refunds the items are not restocked automatically you need to use <a href="https://wordpress.org/plugins/woocommerce-auto-restore-stock/">this plugin</a> to restock automatically. 
13. You can check for Testing Card No <a href="https://stripe.com/docs/testing" target="_blank" >Here</a> 
14. To pass public descriptor you can comment out 'statement_descriptor'  in charge creation.
15. To prevent stripe receipt on billing email comment out 'receipt_email' from charge creation.


== Changelog ==

2015.08.26 - Version 1.0.5

	1. API upgrade to 2015-08-19 for Stripe.
	2. Minor Bugfixes and performance improvement.

2015.08.20 - Version 1.0.4

	1. Added support to limit payment method based on shipping method allowed.
	2. Added support to show Cards logo dynamically as  selected in admin.
	3. Added default credit card form introduced by WooCommerce and built on base from Stripe® Official  to remove the explicit translation needed by plugin in fact if woocommerce is translated accurately the frontend form would display Translated texts.
	4. Added support for CartSpan http://www.cartspan.com QuickBooks accounting integration for Accounting Recouncilation.

2015.08.16 - Version 1.0.3

	1. Added check for CURL to install the plugin.
	2. Fixed "SyntaxError: JSON.parse: unexpected non-whitespace character after JSON data at line 1 column 52 of the JSON data" in woocommerce 2.4.
	3. Added receipt_email in charge creation for fraud tracking.
	4. Fixed hard coded styles in year and month dropdown.
	5. Fixed charging with Zero decimal currencies details https://support.stripe.com/questions/which-zero-decimal-currencies-does-stripe-support.

2015.05.29 - Version 1.0.2

	1. Added sending billing email in meta for fraud tracking
	2. Fixed Warnings & Notices when plugin run in WP debug mode set to true.
	3. Added sending Woocommerce refund reason text in refund meta.
	4. Performance improvement and other minor bug fixes.

2015.05.25 - Version 1.0.1

	1. Added Sending Shipping Address to stripe
	2. Added Sending of Name , Zipcode to Stripe 
	3. Added support of more metadata( WP user_id,Customer IP,Tax,Shipping etc)
	4. Added support to accept card types
	5. Added support for authorize or authorize & capture
	6. Added performance improvement and bugfixes
	7. Added support for refunds from WooCommerce Interface via Stripe API.
	
2015.02.12 - Version 1.0.0

	1. First Release
	
== Upgrade Notice == 
2015.08.20 - Version 1.0.4

	1. This version introduces use of default credit card forms so a dropdown to select month and year will no longer be visible.
	2. Due to addition of dynamic card logos the function to extend stripe icon/image appearing at checkout has been modified as  
	function isa_extended_stripe_icon() {
		$icon = '';
		$icon .= '<img src="http://localhost/waynemcdonell/wp-content/themes/twentyfourteen/images/authorizenet.png" alt="Stripe Payment Gateway" />';
		return $icon;
	}
	add_filter( 'woocommerce_stripe_icon', 'isa_extended_stripe_icon' );
	3. Due to incorrect file Permission for woocommerce plugin the default credit card form might cause issues at your end.The permission should be (Folder 755)& (Files 644) recursively.


