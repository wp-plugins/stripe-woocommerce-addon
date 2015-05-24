==== Stripe Payment Gateway WooCommerce Addon ====
Contributors: nazrulhassanmca
Plugin Name: Stripe WooCommerce Addon
Plugin URI: https://wordpress.org/plugins/stripe-woocommerce-addon/
Tags: woocommerce, stripe, woocommerce addon stripe, stripe for woocommerce,stripe for wordpress,stripe payment method,stripe payment in wordpress,stripe payment gateway for woocommerce,wordpress stripe wocommmerce
Author URI: https://nazrulhassan.wordpress.com/
Author: nazrulhassanmca
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=nazrulhassan@ymail.com&item_name=Donation+Stripe+Woocommerce+Addon
Requires at least: 4.0 
Tested up to: 4.2.2 & WooCommerce 2.3.9
Stable tag: 1.0.1
Version: 1.0.1
License: http://www.gnu.org/licenses/gpl-2.0.html



== Description ==

This plugin acts as an addon for woocommerce to add a payment method for WooCommerce for accepting **Credit Cards Payments** by merchants via **Stripe** directly on checkout page.
This plugin uses Stripe API version  **2015-04-07** to create tokens and charge credit cards. For better visualization of how it looks & works check screenshots tab.

= Features =
1. Very Simple Clean Code plugin to add a Stripe payment method to woocommerce
2. No technical skills needed.
3. Prerequisite visualized on screenshots.
4. Adds Charde Id and Charge time to Order Note.
5. Can be customized easily.
6. Bundles with Official Stripe® API Libraries.
7. Can work with test/sandbox mode of Stripe accounts for testing purpose.
8. Uses Token method to charge Credit Cards rather sending sensitive card details to stripe directly as prescribed by Stripe.
9. It does not needs SSL.
10. Single checkbox to put it in live/test mode.
11. Single checkbox to put it in Authorize or Authorize & Capture.
12. This plugin Support to accept card types.
13. This plugin **does not store Credit Card Details**.
14. This plugin Support refunds in woocommerce interface.

15. This plugin currently supports Multiple Currency accepted one at a time Irrespective of store base currency See Screenshot No.2

Also See

	1. https://support.stripe.com/questions/which-currencies-does-stripe-support
	



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
5. You can check for Testing Card No Here https://stripe.com/docs/testing
6. Integrated Stripe Libraries

== Frequently Asked Questions ==

1. You need to have woocoommerce plugin installed to make this plugin work
2. You need to follow The Screeenshot 1 to obtain API keys from Stripe https://dashboard.stripe.com/
3. This plugin works on test & live api keys.
4. This plugin readily works on local.
5. This plugin does nor requires SSL.
6. This plugin does not store Card Details anywhere.
7. This plugin comes packed with Official Stripe Libraries
8. This plugin requires CURL
9. This plugin does not support Pre Order or Subscriptions 

== Changelog ==

2015.05.25 - version 1.0.1

	1. Added Sending Shipping Address to stripe
	2. Added Sending of Name , Zipcode to Stripe 
	3. Added support of more metadata( WP user_id,Customer IP,Tax,Shipping etc)
	4. Added support to accept card types
	5. Added support for authorize or authorize & capture
	6. Added performance improvement and bugfixes
	7. Added support for refunds from WooCommerce Interface vis Stripe API.
	
2015.02.12

	1. First Release
	
== Upgrade Notice == 
This is first version no known notices yet
