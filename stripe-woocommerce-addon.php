<?php
/**
 * Plugin Name: Stripe WooCommerce Addon
 * Plugin URI: https://wordpress.org/plugins/stripe-woocommerce-addon/
 * Description: This plugin adds a payment option in WooCommerce for customers to pay with their Credit Cards Via Stripe.
 * Version: 1.0.2
 * Author: Syed Nazrul Hassan
 * Author URI: https://nazrulhassan.wordpress.com/
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function stripe_init()
{

if(!class_exists('Stripe'))
{
	include(plugin_dir_path( __FILE__ )."lib/Stripe.php");
}
function add_stripe_gateway_class( $methods ) 
{
	$methods[] = 'WC_Stripe_Gateway'; 
	return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'add_stripe_gateway_class' );

if(class_exists('WC_Payment_Gateway'))
{
	class WC_Stripe_Gateway extends WC_Payment_Gateway 
	{
		
		public function __construct()
		{

		$this->id               = 'stripe';
		$this->icon             = apply_filters( 'woocommerce_stripe_icon', plugins_url( 'images/stripe.png' , __FILE__ ) );
		$this->has_fields       = true;
		$this->method_title     = 'Stripe Cards Settings';		
		$this->init_form_fields();
		$this->init_settings();
		
		$this->supports                  = array(  'products',  'refunds');
		
		$this->title               	   = $this->get_option( 'stripe_title' );
		$this->stripe_testsecretkey      = $this->get_option( 'stripe_testsecretkey' );
		$this->stripe_livesecretkey      = $this->get_option( 'stripe_livesecretkey' );
		$this->stripe_storecurrency      = $this->get_option( 'stripe_storecurrency' );
		$this->stripe_sandbox            = $this->get_option( 'stripe_sandbox' ); 
		$this->stripe_authorize_only     = $this->get_option( 'stripe_authorize_only' );
		$this->stripe_cardtypes          = $this->get_option( 'stripe_cardtypes');
		
		if(!defined("STRIPE_SANDBOX"))
		{ define("STRIPE_SANDBOX"           , ($this->stripe_sandbox        =='yes'? true : false)); }
		
		if(!defined("STRIPE_TRANSACTION_MODE"))
		{ define("STRIPE_TRANSACTION_MODE"  , ($this->stripe_authorize_only =='yes'? false : true)); }
		
		if(STRIPE_SANDBOX == 'yes')
		{ Stripe::setApiKey($this->stripe_testsecretkey);  }
		else
		{ Stripe::setApiKey($this->stripe_livesecretkey);  }
		
		if (is_admin()) 
		{
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}
		

		}

		public function admin_options()
		{
		?>
		<h3><?php _e( 'Stripe addon for Woocommerce', 'woocommerce' ); ?></h3>
		<p><?php  _e( 'Stripe is a company that provides a way for individuals and businesses to accept payments over the Internet.', 'woocommerce' ); ?></p>
		<table class="form-table">
		  <?php $this->generate_settings_html(); ?>
		</table>
		<?php
		}

		public function init_form_fields()
		{
		$this->form_fields = array(
		'enabled' => array(
		  'title' => __( 'Enable/Disable', 'woocommerce' ),
		  'type' => 'checkbox',
		  'label' => __( 'Enable Stripe', 'woocommerce' ),
		  'default' => 'yes'
		  ),
		'stripe_title' => array(
		  'title' => __( 'Title', 'woocommerce' ),
		  'type' => 'text',
		  'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
		  'default' => __( 'Stripe', 'woocommerce' ),
		  'desc_tip'      => true,
		  ),
		'stripe_testsecretkey' => array(
		  'title' => __( 'Test Secret Key', 'woocommerce' ),
		  'type' => 'text',
		  'description' => __( 'This is the Secret Key found in API Keys in Account Dashboard.', 'woocommerce' ),
		  'default' => '',
		  'desc_tip'      => true,
		  'placeholder' => 'Stripe Test Secret Key'
		  ),
		
		'stripe_livesecretkey' => array(
		  'title' => __( 'Live Secret Key', 'woocommerce' ),
		  'type' => 'text',
		  'description' => __( 'This is the Secret Key found in API Keys in Account Dashboard.', 'woocommerce' ),
		  'default' => '',
		  'desc_tip'      => true,
		  'placeholder' => 'Stripe Live Secret Key'
		  ),
		
		'stripe_storecurrency'    => array(
                  'title'        => __('Fund Receiving Currency'),
                  'type'     	   => 'select',
			   'class'        => 'select',
			   'css'          => 'width: 350px;',
			   'desc_tip'     => __( 'Select the currency in which you like to receive payment the currency that has (*) is unsupported on  American Express Cards.This is independent of store base currency so please update your cart price accordingly.', 'woocommerce' ),
			   
                  'options'      => array( 'USD'=>' United States Dollar','AED'=>'United Arab Emirates Dirham','AFN'=>' Afghan Afghani*','ALL'=>' Albanian Lek','AMD'=>' Armenian Dram','ANG'=>' Netherlands Antillean Gulden','AOA'=>' Angolan Kwanza*','ARS'=>' Argentine Peso*','AUD'=>' Australian Dollar','AWG'=>' Aruban Florin','AZN'=>' Azerbaijani Manat','BAM'=>' Bosnia & Herzegovina Convertible Mark','BBD'=>' Barbadian Dollar','BDT'=>' Bangladeshi Taka','BGN'=>' Bulgarian Lev','BIF'=>' Burundian Franc','BMD'=>' Bermudian Dollar','BND'=>' Brunei Dollar','BOB'=>' Bolivian Boliviano*','BRL'=>' Brazilian Real*','BSD'=>' Bahamian Dollar','BWP'=>' Botswana Pula','BZD'=>' Belize Dollar','CAD'=>' Canadian Dollar','CDF'=>' Congolese Franc','CHF'=>' Swiss Franc','CLP'=>' Chilean Peso*','CNY'=>' Chinese Renminbi Yuan','COP'=>' Colombian Peso*','CRC'=>' Costa Rican Colón*','CVE'=>' Cape Verdean Escudo*','CZK'=>' Czech Koruna*','DJF'=>' Djiboutian Franc*','DKK'=>' Danish Krone','DOP'=>' Dominican Peso','DZD'=>' Algerian Dinar','EEK'=>' Estonian Kroon*','EGP'=>' Egyptian Pound','ETB'=>' Ethiopian Birr','EUR'=>' Euro','FJD'=>' Fijian Dollar','FKP'=>' Falkland Islands Pound*','GBP'=>' British Pound','GEL'=>' Georgian Lari','GIP'=>' Gibraltar Pound','GMD'=>' Gambian Dalasi','GNF'=>' Guinean Franc*','GTQ'=>' Guatemalan Quetzal*','GYD'=>' Guyanese Dollar','HKD'=>' Hong Kong Dollar','HNL'=>' Honduran Lempira*','HRK'=>' Croatian Kuna','HTG'=>' Haitian Gourde','HUF'=>' Hungarian Forint*','IDR'=>' Indonesian Rupiah','ILS'=>' Israeli New Sheqel','INR'=>' Indian Rupee*','ISK'=>' Icelandic Króna','JMD'=>' Jamaican Dollar','JPY'=>' Japanese Yen','KES'=>' Kenyan Shilling','KGS'=>' Kyrgyzstani Som','KHR'=>' Cambodian Riel','KMF'=>' Comorian Franc','KRW'=>' South Korean Won','KYD'=>' Cayman Islands Dollar','KZT'=>' Kazakhstani Tenge','LAK'=>' Lao Kip*','LBP'=>' Lebanese Pound','LKR'=>' Sri Lankan Rupee','LRD'=>' Liberian Dollar','LSL'=>' Lesotho Loti','LTL'=>' Lithuanian Litas','LVL'=>' Latvian Lats','MAD'=>' Moroccan Dirham','MDL'=>' Moldovan Leu','MGA'=>' Malagasy Ariary','MKD'=>' Macedonian Denar','MNT'=>' Mongolian Tögrög','MOP'=>' Macanese Pataca','MRO'=>' Mauritanian Ouguiya','MUR'=>' Mauritian Rupee*','MVR'=>' Maldivian Rufiyaa','MWK'=>' Malawian Kwacha','MXN'=>' Mexican Peso*','MYR'=>' Malaysian Ringgit','MZN'=>' Mozambican Metical','NAD'=>' Namibian Dollar','NGN'=>' Nigerian Naira','NIO'=>' Nicaraguan Córdoba*','NOK'=>' Norwegian Krone','NPR'=>' Nepalese Rupee','NZD'=>' New Zealand Dollar','PAB'=>' Panamanian Balboa*','PEN'=>' Peruvian Nuevo Sol*','PGK'=>' Papua New Guinean Kina','PHP'=>' Philippine Peso','PKR'=>' Pakistani Rupee','PLN'=>' Polish Złoty','PYG'=>' Paraguayan Guaraní*','QAR'=>' Qatari Riyal','RON'=>' Romanian Leu','RSD'=>' Serbian Dinar','RUB'=>' Russian Ruble','RWF'=>' Rwandan Franc','SAR'=>' Saudi Riyal','SBD'=>' Solomon Islands Dollar','SCR'=>' Seychellois Rupee','SEK'=>' Swedish Krona','SGD'=>' Singapore Dollar','SHP'=>' Saint Helenian Pound*','SLL'=>' Sierra Leonean Leone','SOS'=>' Somali Shilling','SRD'=>' Surinamese Dollar*','STD'=>' São Tomé and Príncipe Dobra','SVC'=>' Salvadoran Colón*','SZL'=>' Swazi Lilangeni','THB'=>' Thai Baht','TJS'=>' Tajikistani Somoni','TOP'=>' Tongan Paʻanga','TRY'=>' Turkish Lira','TTD'=>' Trinidad and Tobago Dollar','TWD'=>' New Taiwan Dollar','TZS'=>' Tanzanian Shilling','UAH'=>' Ukrainian Hryvnia','UGX'=>' Ugandan Shilling','UYU'=>' Uruguayan Peso*','UZS'=>' Uzbekistani Som','VND'=>' Vietnamese Đồng','VUV'=>' Vanuatu Vatu','WST'=>' Samoan Tala','XAF'=>' Central African Cfa Franc','XCD'=>' East Caribbean Dollar','XOF'=>' West African Cfa Franc*','XPF'=>' Cfp Franc*','YER'=>' Yemeni Rial','ZAR'=>' South African Rand','ZMW'=>' Zambian Kwacha'),
            'description'  => "<span style='color:red;'>Select the currency in which you like to receive payment the currency that has (*) is unsupported on  American Express Cards.This is independent of store base currency so please update your cart price accordingly.</span>",
       
       'default' => 'USD',     
        ),
		
		'stripe_sandbox' => array(
		  'title'       => __( 'Stripe Sandbox', 'woocommerce' ),
		  'type'        => 'checkbox',
		  'label'       => __( 'Enable stripe sandbox (Live Mode if Unchecked)', 'woocommerce' ),
		  'description' => __( 'If checked its in sanbox mode and if unchecked its in live mode', 'woocommerce' ),
		  'desc_tip'      => true,
		  'default'     => 'no',
		),
		
		'stripe_authorize_only' => array(
		'title'       => __( 'Authorize Only', 'woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Authorize Only Mode (Authorize & Capture If Unchecked)', 'woocommerce' ),
		'description' => __( 'If checked will only authorize the credit card only upon checkout.', 'woocommerce' ),
		'desc_tip'      => true,
		'default'     => 'no',
		),
		
		'stripe_cardtypes' => array(
			 'title'    => __( 'Accepted Cards', 'woocommerce' ),
			 'type'     => 'multiselect',
			 'class'    => 'chosen_select',
			 'css'      => 'width: 350px;',
			 'desc_tip' => __( 'Select the card types to accept.', 'woocommerce' ),
			 'options'  => array(
				'mastercard'       => 'MasterCard',
				'visa'             => 'Visa',
				'discover'         => 'Discover',
				'amex' 		    => 'American Express',
				'jcb'		    => 'JCB',
				'dinersclub'       => 'Dinners Club',
			 ),
			 'default' => array( 'mastercard', 'visa', 'discover', 'amex' ),
			),
		
	  );
  		}
  		
  		
  		/*Get Card Types*/
		function get_card_type($number)
		{
		    $number=preg_replace('/[^\d]/','',$number);
		    if (preg_match('/^3[47][0-9]{13}$/',$number))
		    {
		        return 'amex';
		    }
		    elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',$number))
		    {
		        return 'dinersclub';
		    }
		    elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/',$number))
		    {
		        return 'discover';
		    }
		    elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/',$number))
		    {
		        return 'jcb';
		    }
		    elseif (preg_match('/^5[1-5][0-9]{14}$/',$number))
		    {
		        return 'mastercard';
		    }
		    elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/',$number))
		    {
		        return 'visa';
		    }
		    else
		    {
		        return 'unknown';
		    }
		}// End of getcard type function
  		
  		
  		//Function to check IP
		function get_client_ip() 
		{
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
				$ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = '0.0.0.0';
			return $ipaddress;
		}
		
		//End of function to check IP
  		

		public function payment_fields()
		{			
	?>
		<table>
		    <tr>
		    	<td><label for="stripe_cardno"><?php echo __( 'Card No.', 'woocommerce') ?></label></td>
			<td><input type="text" name="stripe_cardno" class="input-text" placeholder="Credit Card No" /></td>
		    </tr>
		    <tr>
		    	<td><label class="" for="stripe_expiration_date"><?php echo __( 'Expiration date', 'woocommerce') ?>.</label></td>
			<td>
			   <select name="stripe_expmonth" style="height: 33px;">
			      <option value=""><?php _e( 'Month', 'woocommerce' ) ?></option>
			      <option value='01'>01</option>
			      <option value='02'>02</option>
			      <option value='03'>03</option>
			      <option value='04'>04</option>
			      <option value='05'>05</option>
			      <option value='06'>06</option>
			      <option value='07'>07</option>
			      <option value='08'>08</option>
			      <option value='09'>09</option>
			      <option value='10'>10</option>
			      <option value='11'>11</option>
			      <option value='12'>12</option>  
			    </select>
			    <select name="stripe_expyear" style="height: 33px;">
			      <option value=""><?php _e( 'Year', 'woocommerce' ) ?></option><?php
			      $years = array();
			      for ( $i = date( 'y' ); $i <= date( 'y' ) + 15; $i ++ ) {
				printf( '<option value="20%u">20%u</option>', $i, $i );
			      } ?>
			    </select>
			</td>
		    </tr>
		    <tr>
		    	<td><label for="stripe_cardcvv"><?php echo __( 'Card CVC', 'woocommerce') ?></label></td>
			<td><input type="text" name="stripe_cardcvv" class="input-text" placeholder="CVC" /></td>
		    </tr>
		</table>
	        <?php  
		} // end of public function payment_fields()

		public function process_payment( $order_id )
		{
		global $error;
		global $woocommerce;
		$wc_order 	= new WC_Order( $order_id );
		$grand_total 	= $wc_order->order_total;
		$amount 	     = $grand_total * 100 ;

		$cardtype = $this->get_card_type(sanitize_text_field($_POST['stripe_cardno']));
			
         		if(!in_array($cardtype ,$this->stripe_cardtypes ))
         		{
         			wc_add_notice('Merchant do not support accepting in '.$cardtype,  $notice_type = 'error' );
         			return array (
								'result'   => 'success',
								'redirect' => WC()->cart->get_checkout_url(),
							   );
				die;
         		}

	
		try
		{

		// create token for customer/buyer credit card
		$token_id = Stripe_Token::create(array(
 				"card" => array( 
 						'number' 	     	=> sanitize_text_field($_POST['stripe_cardno']), 
 						'cvc' 			=> sanitize_text_field($_POST['stripe_cardcvv']),
						'exp_month' 		=> sanitize_text_field($_POST['stripe_expmonth']), 
						'exp_year' 		=> sanitize_text_field($_POST['stripe_expyear']), 
						
						
						'name'  			=> $wc_order->billing_first_name.' '.$wc_order->billing_last_name,
						'address_line1'	=> $wc_order->billing_address_1 ,
						'address_line2'	=> $wc_order->billing_address_2,
						'address_city'		=> $wc_order->billing_city,
						'address_state'	=> $wc_order->billing_state,
						'address_zip'		=> $wc_order->billing_postcode,
						'address_country'	=> $wc_order->billing_country
						) 
				            	      )
						);
		
		$charge = Stripe_Charge::create(array( 
				'amount' 	     		=> $amount, 
				'currency' 			=> $this->stripe_storecurrency, 
				'card'				=> $token_id->id, 
				'capture'				=> STRIPE_TRANSACTION_MODE,
				'statement_descriptor'   => 'Order#'.$wc_order->get_order_number(),
				'metadata' 			=> array(
											'Order #' 	  => $order_id,
											'Total Tax'      => $wc_order->get_total_tax(),
											'Total Shipping' => $wc_order->get_total_shipping(),
											'Customer IP'	  => $this->get_client_ip(),
											'WP customer #'  => $wc_order->user_id,
											'Billing Email'  => $wc_order->billing_email,
										   ) ,
				'description'  		=> get_bloginfo('blogname').' Order #'.$wc_order->get_order_number(),
				'shipping' 		     => array(
											'address' => array(
												'line1'		=> $wc_order->shipping_address_1,
												'line2'		=> $wc_order->shipping_address_2,
												'city'		=> $wc_order->shipping_city,
												'state'		=> $wc_order->shipping_state,
												'country'		=> $wc_order->shipping_country,
												'postal_code'	=> $wc_order->shipping_postcode		
												),
											'name' => $wc_order->shipping_first_name.' '.$wc_order->shipping_last_name,
											'phone'=> $wc_order->billing_phone 
										)
				
			     )
			);
											
						
		}
		
		catch (Exception $e) 
		{	
			
			$body         = $e->getJsonBody(); 
			$error        = $body['error']['message'];
 			echo json_encode(array('message' => $error ));
 			wc_add_notice($error, $notice_type = 'error' );
		}

		if($token_id->id !='')
		{
		  if ($charge->paid == true) 
		  {
		  
			$epoch     = $charge->created;
			$dt        = new DateTime("@$epoch"); 
			$timestamp = $dt->format('Y-m-d H:i:s e');
			$chargeid  = $charge->id ; 

			$wc_order->add_order_note(__( 'Stripe payment completed at-'.$timestamp.'-with Charge ID='.$chargeid ,'woocommerce'));			
			$wc_order->payment_complete($chargeid);
			WC()->cart->empty_cart();
			
			return array (
			  'result'   => 'success',
			  'redirect' => $this->get_return_url( $wc_order ),
			);
		  }
		  else
		  {
			$wc_order->add_order_note( __( 'Stripe payment failed.'.$error, 'woocommerce' ) );
			wc_add_notice($error, $notice_type = 'error' );
			
		  }
		  
		}


		} // end of function process_payment()

		
		public function process_refund($order_id, $amount = NULL, $reason = '' ) {
		
		
		if($amount > 0 )
		{
			$CHARGE_ID 	= get_post_meta( $order_id , '_transaction_id', true );
			$charge 		= Stripe_Charge::retrieve($CHARGE_ID);
			$refund 		= $charge->refunds->create(
												array(
												'amount' 		=> $amount*100,
												'metadata'	=> array('Order #' 		=> $order_id,
																    'Refund reason' => $reason 
																   ),
												)
										      );
			if($refund)	
			{

			$repoch      = $refund->created;
			$rdt         = new DateTime("@$repoch"); 
			$rtimestamp  = $rdt->format('Y-m-d H:i:s e');
			$refundid    = $refund->id; 
			$wc_order    = new WC_Order( $order_id );
			$wc_order->add_order_note( __( 'Stripe Refund completed at. '.$rtimestamp.' with Refund ID = '.$refundid , 'woocommerce' ) );				
			return true;
			}
			else
			{
			return false;
			}
		
		
		}
		else
		{
			return false;
		}
			
		
		
		}// end of  process_refund()



	}  // end of class WC_Stripe_Gateway

} // end of if class exist WC_Gateway

}

add_action( 'plugins_loaded', 'stripe_init' );
