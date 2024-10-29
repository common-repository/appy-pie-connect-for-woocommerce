<?php 
/*
* Custom WC Webhook Manager
* Add new webhook topic only for WC order completed.
* Developed by Rohit Diwakar
*/ 

if (!defined('ABSPATH')) exit;

if( ! class_exists( 'Appy_Pie_Connect_Custom_WC_Webhook_Manager' ) ) :
    
    class Appy_Pie_Connect_Custom_WC_Webhook_Manager {
        
        public function __construct() {  
            
            add_filter('woocommerce_webhook_topics', array( $this, 'add_custom_webhook_topics' ), 10, 1 );
            add_action('woocommerce_order_status_completed', array( $this, 'on_woocommerce_order_status_completed' ), 10, 2 );
            add_filter('woocommerce_webhook_payload', array( $this, 'add_custom_webhook_payload' ), 10, 4 );

        }

        /*
        Custom topics must start with 'action.woocommerce_' or 'action.wc_'
        */

        public function add_custom_webhook_topics( $topics ){
        	$topics['action.wc_custom_order_completed'] = 'Order Completed';
        	return $topics;
        }

        //Trigger wc_custom_order_completed hook on woocommerce_order_status_completed hook.
        public function on_woocommerce_order_status_completed( $order_id, $order ){
        	
        	do_action('wc_custom_order_completed', $order_id, $order );
        }

        /*
         * Set payload for our custom topic.
        */
        public function add_custom_webhook_payload( $payload, $resource, $resource_id, $webhook_id ) {
        	
        	if( isset( $payload['action'] ) && $payload['action'] == 'wc_custom_order_completed' && !empty( $payload['arg'] ) ) {
        		$webhook = wc_get_webhook( $webhook_id );
                
                $current_user = get_current_user_id();
                wp_set_current_user( $webhook->get_user_id() );
                $version = str_replace( 'wp_api_', '', $webhook->get_api_version() );
                $resource = 'order';
                $payload = wc()->api->get_endpoint_data( "/wc/{$version}/{$resource}s/{$resource_id}" );
                
                // Restore the current user.
                wp_set_current_user( $current_user );
        	}
        	return $payload;
        }
    }
    
endif;

$Appy_Pie_Connect_Custom_WC_Webhook_Manager = new Appy_Pie_Connect_Custom_WC_Webhook_Manager();
