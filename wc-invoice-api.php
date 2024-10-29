<?php
/*
 * Class name see@Wc_Order_PI
 * Used Wc_Order_PI class for WC Order related Api
*/
if (!defined('ABSPATH')) exit;

class Wc_Invoice_API
{
    function __construct()
    {
        // its used to register product route
        add_action('rest_api_init', array(&$this,
            'wc_invoice_routes'
        ));
		// its used to register product route
        add_action('rest_api_init', array(&$this,
            'wc_invoice_generate_routes'
        ));
      }

    function wc_invoice_routes()
    { // its used to handle user route
        register_rest_route('wc/v3', 'invoice/list', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_invoice_list'
            ) ,
            'permission_callback' => array(
                $this,
                'get_invoice_api_permissions_check'
            )

        ));
    }
	
	function wc_invoice_generate_routes()
    { // its used to handle user route
        register_rest_route('wc/v3', 'invoice/list', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'generate_invoice_list'
            ) ,
            'permission_callback' => array(
                $this,
                'generate_api_permissions_check'
            )

        ));
    }


    //it is used to check permission for get user by id
    function get_invoice_api_permissions_check(WP_REST_Request $request)
    {
        $headers = array();
		foreach($_SERVER as $name => $value) {
			if($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
				$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
				$headers[$name] = $value;
			}
		}
		$username = $headers['Username'];
		$password = $headers['Password'];
		
		
        $user = get_user_by('login', $username);
        if ($user && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	
	 //it is used to check permission for get user by id
    function generate_api_permissions_check(WP_REST_Request $request)
    {
        $headers = array();
		foreach($_SERVER as $name => $value) {
			if($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
				$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
				$headers[$name] = $value;
			}
		}
		$username = $headers['Username'];
		$password = $headers['Password'];
		
		
        $user = get_user_by('login', $username);
        if ($user && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {
            return true;
        }
        else
        {
            return false;
        }
    }



    //This API helps you to view all the orders.
    function get_invoice_list(WP_REST_Request $request)
    {
        $per_page = $request['per_page'] ? $request['per_page'] : 12;
        $paged = $request['paged'] ? $request['paged'] : 1;

        $args = array(
            'orderby' => 'date',
            'order' => 'DESC',
			'status'=>'wc-completed',
            'limit' => $per_page,
            'page' => $paged
        );

        if (!empty($request['month'] && $request['year']))
        {
            $year = $request['year'];
            $month = $request['month'];
            $args['date_query'] = array(
                'year' => $year,
                'month' => $month
            );
        }

        if (!empty($request['month'] && $request['year'] && $request['day']))
        {
            $year = $request['year'];
            $month = $request['month'];
            $day = $request['day'];
            $args['date_query'] = array(
                'year' => $year,
                'month' => $month,
                'day' => $day
            );
        }

        if ($request['start_date'] && $request['end_date'])
        {
            $st_date = explode('-', $request['start_date']);
            $day = $st_date[0];
            $month = $st_date[1];
            $year = $st_date[2];
            $start_date = $day . '-' . $month . '-' . $year;

            $ed_date = explode('-', $request['end_date']);
            $en_day = $ed_date[0];
            $en_month = $ed_date[1];
            $en_year = $ed_date[2];
            $end_date = $en_day . '-' . $en_month . '-' . $en_year;

            $args['date_query'] = array(
                'column' => 'post_date',
                'after' => $start_date,
                'before' => $end_date
            );
        }
        if (!empty($request['order_status']))
        {
            $args['status'] = $request['order_status'];
        }

        if (!empty($request['date']))
        { 
	        $datetime = new DateTime($request['date']);
			$la_time = new DateTimeZone(get_option('timezone_string'));
			$datetime->setTimezone($la_time);
			$from_date = $datetime->format('Y-m-d H:i:s');
			
            
            $args['date_query'] = array(
               'column' => 'post_modified',
               'after' => $from_date
            );
        }
        if (!empty($request['modified_after']))
        {
    		$datetime = new DateTime($request['modified_after']);
			$la_time = new DateTimeZone(get_option('timezone_string'));
			$datetime->setTimezone($la_time);
			$from_date= $datetime->format('Y-m-d H:i:s');

            $args['date_query'] = array(
               'column' => 'post_modified',
               'after' => $from_date
            );
        }
        if ($request['search'])
        {
            $arg['s'] = $request['search'];
            $ids = wc_order_search($arg['s']);
            $args['post__in'] = $ids;
        }
        if ($request['order_number'])
        {
            $args['post__in'] = array(
                $request['order_number']
            );
        }

        $query = new WC_Order_Query($args);
        $ordersArr = array();
        $orders = $query->get_orders();
        if (empty($request))
        {
            $order_total_count = count(wc_get_orders(array(
                'return' => 'ids',
                'limit' => - 1,
            )));
        }
        else
        {
            $order_total_count = count($orders);
        }
        $total_count = $order_total_count;

        if ($orders)
        {

            foreach ($orders as $key => $order)
            {
                $order_data = $order->get_data();
				
                $order_id = $order_data['id'];
                $order_parent_id = $order_data['parent_id'];
                $order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');
                $order_date_modified = $order_data['date_modified']->date('Y-m-d H:i:s');
				//$order_date_completed = $order_data['date_completed']->date('Y-m-d H:i:s');

                $billining_info = array(
                    "first_name" => $order_data['billing']['first_name'],
                    "last_name" => $order_data['billing']['last_name'],
                    "company" => $order_data['billing']['company'],
                    "address_1" => $order_data['billing']['address_1'],
                    "address_2" => $order_data['billing']['address_2'],
                    "city" => $order_data['billing']['city'],
                    "state" => $order_data['billing']['state'],
                    "postcode" => $order_data['billing']['postcode'],
                    "country" => $order_data['billing']['country'],
                    "email" => $order_data['billing']['email'],
                    "phone" => $order_data['billing']['phone']
                );
                $shipping_info = array(
                    "first_name" => $order_data['shipping']['first_name'],
                    "last_name" => $order_data['shipping']['last_name'],
                    "company" => $order_data['shipping']['company'],
                    "address_1" => $order_data['shipping']['address_1'],
                    "address_2" => $order_data['shipping']['address_2'],
                    "city" => $order_data['shipping']['city'],
                    "state" => $order_data['shipping']['state'],
                    "postcode" => $order_data['shipping']['postcode'],
                    "country" => $order_data['shipping']['country'],
                    "email" => $order_data['shipping']['email'],
                    "phone" => $order_data['shipping']['phone']
                );
                $order = wc_get_order($order_data['id']);
				$subtotal = $order->get_subtotal();
                $line_items = array();
                $taxes = array();
                $meta_data = array();
                $fee_lines = array();
                $coupon_lines = array();
                 if($order->get_coupons()){
                   foreach($order->get_coupons() as $key => $coupon){
                        $data = $coupon->get_data();
                        $c = new WC_Coupon($data['code']);
                        $coupon_lines[] = array(
                            'code'=>$data['code'],
                            'discount'=>$data['discount']
                        );
                   }
   
                }
                foreach ($order->get_items() as $item_key => $item_values)
                {

                    ## Using WC_Order_Item methods ##
                    // Item ID is directly accessible from the $item_key in the foreach loop or
                    $item_id = $item_values->get_id();

                    ## Using WC_Order_Item_Product methods ##
                    $item_name = $item_values->get_name(); // Name of the product
                    $item_type = $item_values->get_type(); // Type of the order item ("line_item")
                    $product_id = $item_values->get_product_id(); // the Product id
                    $wc_product = $item_values->get_product(); // the WC_Product object
                    $wc_product_detail = $wc_product->get_data();
                    $price = $wc_product_detail['price'];
                    $sku = $wc_product_detail['sku'];

                    ## Access Order Items data properties (in an array of values) ##
                    $item_data = $item_values->get_data();

                    $product_name = $item_data['name'];
                    $product_id = $item_data['product_id'];
					$featureImage = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full' );
                    $variation_id = $item_data['variation_id'];
                    $quantity = $item_data['quantity'];
                    $tax_class = $item_data['tax_class'];
                    $line_subtotal = $item_data['subtotal'];
                    $line_subtotal_tax = $item_data['subtotal_tax'];
                    $line_total = $item_data['total'];
                    $line_total_tax = $item_data['total_tax'];
                    $line_items[] = array(
                        "product_name" => $product_name,
                        "quantity" => $quantity,
                        "sku" => $sku,
                        "price" => $price
                    );
                }

                $ordersArr[] = array(
				    "invoice_date"=>$order_date_modified,
					"order_date"=> $order_date_created,
					"order_number" => $order_data['number'],
					"currency" => $order_data['currency'],
					'subtotal' => $subtotal,
                    "total" => $order_data['total'],
					"discount_total" => $order_data['discount_total'],
                    "customer_note" => $order_data['customer_note'],
                    "billing" => $billining_info,
                    "shipping" => $shipping_info,
                    "payment_method" => $order_data['payment_method'],
                    "payment_method_title" => $order_data['payment_method_title'],
                    "line_items" => $line_items,
					"fee_lines" => $fee_lines,
                    "coupon_lines" => $coupon_lines
               );

            }
            $order_count['total_count'] = array(
                'total_count' => $total_count
            );
            $order_list['invoice'] = $ordersArr;

            $finalporders = array_merge($order_count, $order_list);
            return rest_ensure_response($finalporders);
        }
        else
        {
            return new WP_Error('empty_order', 'Sorry no invoice available!', array(
                'status' => 0
            ));
        }

    }
	
	 //This API helps you to view all the orders.
    function generate_invoice_list(WP_REST_Request $request)
    {

        $args = array(
            'orderby' => 'date',
            'order' => 'DESC',
			'status'=>'wc-completed'
        );
		if ($request['order_number'])
        {
            $args['post__in'] = array(
                $request['order_number']
            );
        }


        $query = new WC_Order_Query($args);
        $ordersArr = array();
        $orders = $query->get_orders();
        if (empty($request))
        {
            $order_total_count = count(wc_get_orders(array(
                'return' => 'ids',
                'limit' => - 1,
            )));
        }
        else
        {
            $order_total_count = count($orders);
        }
        $total_count = $order_total_count;

        if ($orders)
        {

            foreach ($orders as $key => $order)
            {
                $order_data = $order->get_data();
				
                $order_id = $order_data['id'];
                $order_parent_id = $order_data['parent_id'];
                $order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');
                $order_date_modified = $order_data['date_modified']->date('Y-m-d H:i:s');
				//$order_date_completed = $order_data['date_completed']->date('Y-m-d H:i:s');

                $billining_info = array(
                    "first_name" => $order_data['billing']['first_name'],
                    "last_name" => $order_data['billing']['last_name'],
                    "company" => $order_data['billing']['company'],
                    "address_1" => $order_data['billing']['address_1'],
                    "address_2" => $order_data['billing']['address_2'],
                    "city" => $order_data['billing']['city'],
                    "state" => $order_data['billing']['state'],
                    "postcode" => $order_data['billing']['postcode'],
                    "country" => $order_data['billing']['country'],
                    "email" => $order_data['billing']['email'],
                    "phone" => $order_data['billing']['phone']
                );
                $shipping_info = array(
                    "first_name" => $order_data['shipping']['first_name'],
                    "last_name" => $order_data['shipping']['last_name'],
                    "company" => $order_data['shipping']['company'],
                    "address_1" => $order_data['shipping']['address_1'],
                    "address_2" => $order_data['shipping']['address_2'],
                    "city" => $order_data['shipping']['city'],
                    "state" => $order_data['shipping']['state'],
                    "postcode" => $order_data['shipping']['postcode'],
                    "country" => $order_data['shipping']['country'],
                    "email" => $order_data['shipping']['email'],
                    "phone" => $order_data['shipping']['phone']
                );
                $order = wc_get_order($order_data['id']);
				$subtotal = $order->get_subtotal();
                $line_items = array();
                $taxes = array();
                $meta_data = array();
                $fee_lines = array();
                $coupon_lines = array();
                 if($order->get_coupons()){
                   foreach($order->get_coupons() as $key => $coupon){
                        $data = $coupon->get_data();
                        $c = new WC_Coupon($data['code']);
                        $coupon_lines[] = array(
                            'code'=>$data['code'],
                            'discount'=>$data['discount']
                        );
                   }
   
                }
                foreach ($order->get_items() as $item_key => $item_values)
                {

                    ## Using WC_Order_Item methods ##
                    // Item ID is directly accessible from the $item_key in the foreach loop or
                    $item_id = $item_values->get_id();

                    ## Using WC_Order_Item_Product methods ##
                    $item_name = $item_values->get_name(); // Name of the product
                    $item_type = $item_values->get_type(); // Type of the order item ("line_item")
                    $product_id = $item_values->get_product_id(); // the Product id
                    $wc_product = $item_values->get_product(); // the WC_Product object
                    $wc_product_detail = $wc_product->get_data();
                    $price = $wc_product_detail['price'];
                    $sku = $wc_product_detail['sku'];

                    ## Access Order Items data properties (in an array of values) ##
                    $item_data = $item_values->get_data();

                    $product_name = $item_data['name'];
                    $product_id = $item_data['product_id'];
					$featureImage = wp_get_attachment_image_src( get_post_thumbnail_id($product_id), 'full' );
                    $variation_id = $item_data['variation_id'];
                    $quantity = $item_data['quantity'];
                    $tax_class = $item_data['tax_class'];
                    $line_subtotal = $item_data['subtotal'];
                    $line_subtotal_tax = $item_data['subtotal_tax'];
                    $line_total = $item_data['total'];
                    $line_total_tax = $item_data['total_tax'];
                    $line_items[] = array(
                        "product_name" => $product_name,
                        "quantity" => $quantity,
                        "sku" => $sku,
                        "price" => $price
                    );
                }
				

                $ordersArr[] = array(
				    "invoice_date"=>$order_date_modified,
					"order_date"=> $order_date_created,
					"order_number" => $order_data['number'],
					"currency" => $order_data['currency'],
					'subtotal' => $subtotal,
                    "total" => $order_data['total'],
					"discount_total" => $order_data['discount_total'],
                    "customer_note" => $order_data['customer_note'],
                    "billing" => $billining_info,
                    "shipping" => $shipping_info,
                    "payment_method" => $order_data['payment_method'],
                    "payment_method_title" => $order_data['payment_method_title'],
                    "line_items" => $line_items,
					"fee_lines" => $fee_lines,
                    "coupon_lines" => $coupon_lines
               );

            }
            $order_list['invoice'] = $ordersArr;
            $finalporders =  $order_list;
            return rest_ensure_response($finalporders);
        }
        else
        {
            return new WP_Error('empty_order', 'Sorry no invoice available!', array(
                'status' => 0
            ));
        }

    }
}
new Wc_Invoice_API();
?>
