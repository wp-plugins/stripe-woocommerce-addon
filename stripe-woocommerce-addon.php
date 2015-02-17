<?php
/**
 * Plugin Name: Stripe WooCommerce Addon
 * Plugin URI: https://wordpress.org/plugins/stripe-woocommerce-addon/
 * Description: This plugin adds a payment option in WooCommerce for customers to pay with their Credit Cards Via Stripe.
 * Version: 1.0.0
 * Author: Syed Nazrul Hassan
 * Author URI: https://nazrulhassan.wordpress.com/
 * License: GPL2
 */

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
		$this->title               	 = $this->get_option( 'stripe_title' );
		$this->stripe_testsecretkey      = $this->get_option( 'stripe_testsecretkey' );
		$this->stripe_livesecretkey      = $this->get_option( 'stripe_livesecretkey' );
		$this->stripe_sandbox            = $this->get_option( 'stripe_sandbox' ); 

		define("STRIPE_SANDBOX", ($this->stripe_sandbox=='yes'? true : false));

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

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

		
		'stripe_sandbox' => array(
		  'title'       => __( 'stripe sandbox', 'woocommerce' ),
		  'type'        => 'checkbox',
		  'label'       => __( 'Enable stripe sandbox', 'woocommerce' ),
		  'default'     => 'no',
		  'description' => __( 'If checked its in sanbox mode and if unchecked its in live mode', 'woocommerce' )
		)
		
	  );
  		}

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

		
	
		if(STRIPE_SANDBOX == 'yes')
		{ Stripe::setApiKey($this->stripe_testsecretkey);  }
		else
		{ Stripe::setApiKey($this->stripe_livesecretkey);   }
		
			
		// create token for customer/buyer credit card

		$token_id = Stripe_Token::create(array(
			 				"card" => array( 
			 						"number" 	     => sanitize_text_field($_POST['stripe_cardno']), 
									"exp_month" 	=> sanitize_text_field($_POST['stripe_expmonth']), 
									"exp_year" 	=> sanitize_text_field($_POST['stripe_expyear']), 
									"cvc" 		=> sanitize_text_field($_POST['stripe_cardcvv']) 
									) 
				            	      )
						);

		// charge customer/buyer credit card based on card's unique token 
	
		try
		{

		$charge = Stripe_Charge::create(array( 
							"amount" 	     => $amount, 
							"currency" 	=> "USD", 
							"card"		=> $token_id->id, 
							"metadata" 	=> array("order_id" => $order_id) ,
							"description"  => $wc_order->billing_email
						     )
						);
		}
		
		catch (Exception $e) 
		{	
			
			$body         = $e->getJsonBody(); 
			$error        = $body['error']['message'];
 			echo json_encode(array('message' => $error ));
 			$woocommerce->add_error( __( 'Sorry, Error.'.$error, 'woocommerce' ) );
			
		}

		if($token_id->id !='')
		{
		  if ($charge->paid == true) 
		  {
			$epoch     = $charge->created;
			$dt        = new DateTime("@$epoch"); 
			$timestamp = $dt->format('Y-m-d H:i:s e');
			$chargeid  = $charge->id ; 

		  	$wc_order->add_order_note( __( 'Stripe payment completed at. '.$timestamp.' with Charge ID = '.$chargeid , 'woocommerce' ) );
			$wc_order->payment_complete();
			return array (
			  'result'   => 'success',
			  'redirect' => $this->get_return_url( $wc_order ),
			);
		  }
		  else
		  {
			$wc_order->add_order_note( __( 'Stripe payment failed.'.$error, 'woocommerce' ) );
			$woocommerce->add_error( __( 'Sorry, Error.'.$error, 'woocommerce' ) );
		  }
		  
		}


		} // end of function process_payment()

	}  // end of class WC_Stripe_Gateway

} // end of if class exist WC_Gateway

}

add_action( 'plugins_loaded', 'stripe_init' );
