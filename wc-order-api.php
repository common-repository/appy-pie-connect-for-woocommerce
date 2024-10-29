<?php
/*
 * Class name see@Wc_Order_PI
 * Used Wc_Order_PI class for WC Order related Api
*/
if (!defined('ABSPATH')) exit;

class Wc_Order_PI
{
    function __construct()
    {
        // its used to register product route
        add_action('rest_api_init', array(&$this,
            'wc_order_routes'
        ));

        // its used to register user_order route
        add_action('rest_api_init', array(&$this,
            'register_user_order_route'
        ));

        // its used to register for view order detail route
        add_action('rest_api_init', array(&$this,
            'register_view_order'
        ));

        // its used to register for update order route
        add_action('rest_api_init', array(&$this,
            'register_update_order'
        ));

        // its used to register for delete order route
        add_action('rest_api_init', array(&$this,
            'register_delete_order'
        ));

        // its used to register for create order route
        add_action('rest_api_init', array(&$this,
            'register_create_order'
        ));

        // its used to register for order received route
        add_action('rest_api_init', array(&$this,
            'register_order_received'
        ));

    }

    function wc_order_routes()
    { // its used to handle user route
        register_rest_route('wc/v3', 'order/list', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_order_list'
            ) ,
            'permission_callback' => array(
                $this,
                'get_order_api_permissions_check'
            )

        ));
    }

    function register_view_order()
    { // its used to handle view order route
        register_rest_route('wc/v3', 'order/list/(?P<order_id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'view_order_detail'
            ) ,
            'permission_callback' => array(
                $this,
                'check_single_order_api_permissions'
            )

        ));
    }

    function register_delete_order()
    { // its used to handle view order route
        register_rest_route('wc/v3', 'order/delete/(?P<order_id>[\d]+)', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'delete_order'
            )

        ));
    }

    function register_user_order_route()
    { // its used to handle user route
        register_rest_route('wc/v3', 'user/order/list/(?P<customer_id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_user_order_list'
            ) ,
            'permission_callback' => array(
                $this,
                'get_user_order_permissions_check'
            )

        ));
    }

    function register_update_order()
    { // its used to handle user route
        register_rest_route('wc/v3', 'order/update', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'update_order'
            ) ,
            'permission_callback' => array(
                $this,
                'update_order_api_permissions_check'
            )

        ));
    }

    function register_create_order()
    { // its used to handle create order route
        register_rest_route('wc/v3', 'order/create', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'create_order',
            ) ,
            'permission_callback' => array(
                $this,
                'check_create_order_permissions'
            )

        ));
    }

    function register_order_received()
    { // its used to handle view order route
        register_rest_route('wc/v3', 'order/received/(?P<order_id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'order_received'
            )

        ));
    }

    //it is used to check permission for get user by id
    function get_order_api_permissions_check(WP_REST_Request $request)
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
    function get_user_order_permissions_check(WP_REST_Request $request)
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
        if (!empty($username) && !empty($password))
        {
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
    }

    // its check single order id permission
    function check_single_order_api_permissions(WP_REST_Request $request)
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
        if (!empty($username) && !empty($password))
        {
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
    }

    function check_create_order_permissions(WP_REST_Request $request)
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

        if (!empty($username) && !empty($password))
        {
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
    }

    //This API helps you to view all the orders.
    function get_order_list(WP_REST_Request $request)
    {
        $per_page = $request['per_page'] ? $request['per_page'] : 12;
        $paged = $request['paged'] ? $request['paged'] : 1;

        $args = array(
            'orderby' => 'date',
            'order' => 'DESC',
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
        if ($request['order_id'])
        {
            $args['post__in'] = array(
                $request['order_id']
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
                global $wpdb;
                $order_data = $order->get_data();
                $order_id = $order_data['id'];
                $order_parent_id = $order_data['parent_id'];
                $order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');
                $order_date_modified = $order_data['date_modified']->date('Y-m-d H:i:s');

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
                $line_items = array();
                $taxes = array();
                $meta_data = array();
                $fee_lines = array();
                $coupon_lines = array();

                if($order->get_coupons()) {
                   foreach($order->get_coupons() as $key => $coupon){
                        $data = $coupon->get_data();
                        $c = new WC_Coupon($data['code']);
                        $coupon_lines[] = array(
                            'order_id'=>$data['order_id'],
                            'code'=>$data['code'],
                            'discount'=>$data['discount'],
                            'discount_type' => $c->discount_type,
                            'product_ids' => $c->product_ids,
                            'minimum_amount' => $c->minimum_amount,
                            'maximum_amount' => $c->maximum_amount,
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
                    $Pname = $wc_product->get_title();
                    ## Access Order Items data properties (in an array of values) ##
                    $item_data = $item_values->get_data();

                    $product_name = $item_data['name'];
                    $product_id = $item_data['product_id'];
                    $variation_id = $item_data['variation_id'];
                    $quantity = $item_data['quantity'];
                    $tax_class = $item_data['tax_class'];
                    $line_subtotal = $item_data['subtotal'];
                    $line_subtotal_tax = $item_data['subtotal_tax'];
                    $line_total = $item_data['total'];
                    $line_total_tax = $item_data['total_tax'];
                    $line_items[] = array(
                        "id" => $item_id,
                        "name" => $product_name,
                        "product_id" => $product_id,
                        "product_name" => $Pname,
                        "variation_id" => $variation_id,
                        "quantity" => $quantity,
                        "tax_class" => $tax_class,
                        "subtotal" => $line_subtotal,
                        "subtotal_tax" => $line_subtotal_tax,
                        "total" => $line_total,
                        "total_tax" => $line_total_tax,
                        "taxes" => $taxes,
                        "meta_data" => $meta_data,
                        "sku" => $sku,
                        "price" => $price

                    );
                }

                //Get access to the database
                $wpprefix = $wpdb->base_prefix;
                $tablename =  $wpprefix.'comments';
                    
                //Get the comment containing the Shippit info
                $shippit_string = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT comment_content FROM $tablename WHERE comment_post_ID = %d AND comment_content LIKE %s",
                        $order_id,
                        '%Shippit.%'
                    )
                );
                
                $trimmed_shippit_string = str_replace('Order Synced with Shippit. Tracking number: ', '', $shippit_string);
                //print_r($trimmed_shippit_string);
                $ordersArr[] = array(
                    "id" => $order_id,
                    "parent_id" => $order_parent_id,
                    "number" => $order_data['number'],
                    "order_key" => $order_data['order_key'],
                    "created_via" => $order_data['created_via'],
                    "version" => $order_data['version'],
                    "status" => $order_data['status'],
                    "currency" => $order_data['currency'],
                    "date_created" => $order_date_created,
                    "date_modified"=>$order_date_modified,
                    "date_created_gmt" => $date_created_gmt,
                    "discount_total" => $order_data['discount_total'],
                    "discount_tax" => $order_data['discount_tax'],
                    "shipping_total" => $order_data['shipping_total'],
                    "shipping_tax" => $order_data['shipping_tax'],
                    "cart_tax" => $order_data['cart_tax'],
                    "total" => $order_data['total'],
                    "total_tax" => $order_data['total_tax'],
                    "prices_include_tax" => false,
                    "customer_id" => $order_data['customer_id'],
                    "customer_ip_address" => $order_data['customer_ip_address'],
                    "customer_user_agent" => $order_data['customer_user_agent'],
                    "customer_note" => $order_data['customer_note'],
                    "billing" => $billining_info,
                    "shipping" => $shipping_info,
                    "payment_method" => $order_data['payment_method'],
                    "payment_method_title" => $order_data['payment_method_title'],
                    "transaction_id" => $order_data['transaction_id'],
                    "date_paid" => $order_data['date_paid'],
                    "date_paid_gmt" => $order_data['date_paid_gmt'],
                    "date_completed" => $order_data['date_completed'],
                    "date_completed_gmt" => $order_data['date_completed_gmt'],
                    "cart_hash" => $order_data['cart_hash'],
                    "line_items" => $line_items,
                    "fee_lines" => $fee_lines,
                    "coupon_lines" => $coupon_lines,
                    "total_count" => $total_count,
                    "get_post_metadata" => get_post_meta($order_id),
                    'Trackingnumber' => $trimmed_shippit_string
                );

            }
            $order_count['total_count'] = array(
                'total_count' => $total_count
            );
            $order_list['orders'] = $ordersArr;

            $finalporders = array_merge($order_list);
            return rest_ensure_response($finalporders);
        }
        else
        {
            return new WP_Error('empty_order', 'Sorry no order available!', array(
                'status' => 404
            ));
        }

    }

    // its used to list order by user
    function get_user_order_list($request)
    {

        $query = new WC_Order_Query(array(
            'orderby' => 'date',
            'order' => 'DESC',
            'customer_id' => $request['customer_id'],
        ));
        $ordersArr = array();
        $orders = $query->get_orders();

        if ($orders)
        {

            foreach ($orders as $key => $order)
            {
                $order_data = $order->get_data();
                $order_id = $order_data['id'];
                $order_parent_id = $order_data['parent_id'];
                $order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');
                $order_date_modified = $order_data['date_modified']->date('Y-m-d H:i:s');

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
                $line_items = array();
                $fee_lines = array();
                $coupon_lines = array();
                $taxes = array();
                $meta_data = array();
                if($order->get_coupons()){
                   foreach($order->get_coupons() as $key => $coupon){
                        $data = $coupon->get_data();
                        $c = new WC_Coupon($data['code']);
                        $coupon_lines[] = array(
                            'order_id'=>$data['order_id'],
                            'code'=>$data['code'],
                            'discount'=>$data['discount'],
                            'discount_type' => $c->discount_type,
                            'product_ids' => $c->product_ids,
                            'minimum_amount' => $c->minimum_amount,
                            'maximum_amount' => $c->maximum_amount,
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
                    $variation_id = $item_data['variation_id'];
                    $quantity = $item_data['quantity'];
                    $tax_class = $item_data['tax_class'];
                    $line_subtotal = $item_data['subtotal'];
                    $line_subtotal_tax = $item_data['subtotal_tax'];
                    $line_total = $item_data['total'];
                    $line_total_tax = $item_data['total_tax'];
                    $line_items[] = array(
                        "id" => $item_id,
                        "name" => $product_name,
                        "product_id" => $product_id,
                        "variation_id" => $variation_id,
                        "quantity" => $quantity,
                        "tax_class" => $tax_class,
                        "subtotal" => $line_subtotal,
                        "subtotal_tax" => $line_subtotal_tax,
                        "total" => $line_total,
                        "total_tax" => $line_total_tax,
                        "taxes" => $taxes,
                        "meta_data" => $meta_data,
                        "sku" => $sku,
                        "price" => $price

                    );
                }

                $ordersArr[] = array(
                    "id" => $order_id,
                    "parent_id" => $order_parent_id,
                    "number" => $order_data['number'],
                    "order_key" => $order_data['order_key'],
                    "created_via" => $order_data['created_via'],
                    "version" => $order_data['version'],
                    "status" => $order_data['status'],
                    "currency" => $order_data['currency'],
                    "date_created" => $order_date_created,
                    "date_created_gmt" => $date_created_gmt,
                    "discount_total" => $order_data['discount_total'],
                    "discount_tax" => $order_data['discount_tax'],
                    "shipping_total" => $order_data['shipping_total'],
                    "shipping_tax" => $order_data['shipping_tax'],
                    "cart_tax" => $order_data['cart_tax'],
                    "total" => $order_data['total'],
                    "total_tax" => $order_data['total_tax'],
                    "prices_include_tax" => false,
                    "customer_id" => $order_data['customer_id'],
                    "customer_ip_address" => $order_data['customer_ip_address'],
                    "customer_user_agent" => $order_data['customer_user_agent'],
                    "customer_note" => $order_data['customer_note'],
                    "billing" => $billining_info,
                    "shipping" => $shipping_info,
                    "payment_method" => $order_data['payment_method'],
                    "payment_method_title" => $order_data['payment_method_title'],
                    "transaction_id" => $order_data['transaction_id'],
                    "date_paid" => $order_data['date_paid'],
                    "date_paid_gmt" => $order_data['date_paid_gmt'],
                    "date_completed" => $order_data['date_completed'],
                    "date_completed_gmt" => $order_data['date_completed_gmt'],
                    "cart_hash" => $order_data['cart_hash'],
                    "line_items" => $line_items,
                    "fee_lines" => $fee_lines,
                    "coupon_lines" => $coupon_lines

                );

            }

            return rest_ensure_response($ordersArr);
        }
        else
        {
            return new WP_Error('empty_order', 'Sorry no order available!', array(
                'status' => 404
            ));
        }

    }

    // its used to view order detail
    function view_order_detail($request)
    {

        $order = wc_get_order($request['order_id']);

        if ($order)
        {
            $order_data = $order->get_data();
            $order_id = $order_data['id'];
            $order_parent_id = $order_data['parent_id'];
            $order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');
            $order_date_modified = $order_data['date_modified']->date('Y-m-d H:i:s');

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
            $line_items = array();
            $fee_lines = array();
            $coupon_lines = array();
            $taxes = array();
            $meta_data = array();

            if($order->get_coupons()){
               foreach($order->get_coupons() as $key => $coupon){
                    $data = $coupon->get_data();
                    $c = new WC_Coupon($data['code']);
                    $coupon_lines[] = array(
                        'order_id'=>$data['order_id'],
                        'code'=>$data['code'],
                        'discount'=>$data['discount'],
                        'discount_type' => $c->discount_type,
                        'product_ids' => $c->product_ids,
                        'minimum_amount' => $c->minimum_amount,
                        'maximum_amount' => $c->maximum_amount,
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
                $variation_id = $item_data['variation_id'];
                $quantity = $item_data['quantity'];
                $tax_class = $item_data['tax_class'];
                $line_subtotal = $item_data['subtotal'];
                $line_subtotal_tax = $item_data['subtotal_tax'];
                $line_total = $item_data['total'];
                $line_total_tax = $item_data['total_tax'];
                $line_items[] = array(
                    "id" => $item_id,
                    "name" => $product_name,
                    "product_id" => $product_id,
                    "variation_id" => $variation_id,
                    "quantity" => $quantity,
                    "tax_class" => $tax_class,
                    "subtotal" => $line_subtotal,
                    "subtotal_tax" => $line_subtotal_tax,
                    "total" => $line_total,
                    "total_tax" => $line_total_tax,
                    "taxes" => $taxes,
                    "meta_data" => $meta_data,
                    "sku" => $sku,
                    "price" => $price

                );
            }

            $ordersArr[] = array(
                "id" => $order_id,
                "parent_id" => $order_parent_id,
                "number" => $order_data['number'],
                "order_key" => $order_data['order_key'],
                "created_via" => $order_data['created_via'],
                "version" => $order_data['version'],
                "status" => $order_data['status'],
                "currency" => $order_data['currency'],
                "date_created" => $order_date_created,
                "date_created_gmt" => $date_created_gmt,
                "discount_total" => $order_data['discount_total'],
                "discount_tax" => $order_data['discount_tax'],
                "shipping_total" => $order_data['shipping_total'],
                "shipping_tax" => $order_data['shipping_tax'],
                "cart_tax" => $order_data['cart_tax'],
                "total" => $order_data['total'],
                "total_tax" => $order_data['total_tax'],
                "prices_include_tax" => false,
                "customer_id" => $order_data['customer_id'],
                "customer_ip_address" => $order_data['customer_ip_address'],
                "customer_user_agent" => $order_data['customer_user_agent'],
                "customer_note" => $order_data['customer_note'],
                "billing" => $billining_info,
                "shipping" => $shipping_info,
                "payment_method" => $order_data['payment_method'],
                "payment_method_title" => $order_data['payment_method_title'],
                "transaction_id" => $order_data['transaction_id'],
                "date_paid" => $order_data['date_paid'],
                "date_paid_gmt" => $order_data['date_paid_gmt'],
                "date_completed" => $order_data['date_completed'],
                "date_completed_gmt" => $order_data['date_completed_gmt'],
                "cart_hash" => $order_data['cart_hash'],
                "line_items" => $line_items,
                "fee_lines" => $fee_lines,
                "coupon_lines" => $coupon_lines

            );

            return rest_ensure_response($ordersArr);
        }
        else
        {
            return new WP_Error('empty_order', 'Sorry invalid order id!', array(
                'status' => 404
            ));
        }

    }

    // its check order api permission
    function update_order_api_permissions_check(WP_REST_Request $request)
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
        if (!empty($username) && !empty($password))
        {
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
    }

    // update order function
    function update_order($request)
    {
        global $wpdb, $woocommerce;
        $parameters = $request->get_params();
        $error = new WP_Error();
        if (empty($parameters['order_id']))
        {
            $error->add(400, __("order_id' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
        }

        $order = wc_get_order($parameters['order_id']);
  
      
        
		
		// billing address update
		$billing_first_name = $parameters['billing']['first_name'] ? $parameters['billing']['first_name'] : $order->get_billing_first_name();
		$billing_last_name = $parameters['billing']['last_name'] ? $parameters['billing']['last_name'] : $order->get_billing_last_name();
		$billing_company = $parameters['billing']['company'] ? $parameters['billing']['company'] : $order->get_billing_company();
		$billing_address_1 = $parameters['billing']['address_1'] ? $parameters['billing']['address_1'] : $order->get_billing_address_1();
		$billing_address_2 = $parameters['billing']['address_2'] ? $parameters['billing']['address_2'] : $order->get_billing_address_2();
		$billing_city = $parameters['billing']['city'] ? $parameters['billing']['city'] : $order->get_billing_city();
		$billing_state = $parameters['billing']['state'] ? $parameters['billing']['state'] : $order->get_billing_state();
		$billing_postcode = $parameters['billing']['postcode'] ? $parameters['billing']['postcode'] : $order->get_billing_postcode();
		$billing_country = $parameters['billing']['country'] ? $parameters['billing']['country'] : $order->get_billing_country();
		$billing_email = $parameters['billing']['email'] ? $parameters['billing']['email'] : $order->get_billing_email();
		$billing_phone = $parameters['billing']['phone'] ? $parameters['billing']['phone'] : $order->get_billing_phone();
		
		// shipping address update
		$shipping_first_name = $parameters['shipping']['first_name'] ? $parameters['shipping']['first_name'] : $order->get_shipping_first_name();
		$shipping_last_name = $parameters['shipping']['last_name'] ? $parameters['shipping']['last_name'] : $order->get_shipping_last_name();
		$shipping_company = $parameters['shipping']['company'] ? $parameters['shipping']['company'] : $order->get_shipping_company();
		$shipping_address_1 = $parameters['shipping']['address_1'] ? $parameters['shipping']['address_1'] : $order->get_shipping_address_1();
		$shipping_address_2 = $parameters['shipping']['address_2'] ? $parameters['shipping']['address_2'] : $order->get_shipping_address_2();
		$shipping_city = $parameters['shipping']['city'] ? $parameters['shipping']['city'] : $order->get_shipping_city();
		$shipping_state = $parameters['shipping']['state'] ? $parameters['shipping']['state'] : $order->get_shipping_state();
		$shipping_postcode = $parameters['shipping']['postcode'] ? $parameters['shipping']['postcode'] : $order->get_shipping_postcode();
		$shipping_country = $parameters['shipping']['country'] ? $parameters['shipping']['country'] :$order->get_shipping_country();
		//$shipping_email = $parameters['shipping']['email'] ? $parameters['shipping']['email'] : $order->get_shipping_email();
		//$shipping_phone = $parameters['shipping']['phone'] ? $parameters['shipping']['phone'] : $order->get_shipping_phone();
		
        $billing = array(
         "_billing_first_name"=>$billing_first_name,
         "_billing_last_name"=>$billing_last_name,
         "_billing_company"=>$billing_company,
         "_billing_address_1"=>$billing_address_1,
         "_billing_address_2"=>$billing_address_2,
         "_billing_city"=>$billing_city,
         "_billing_state"=>$billing_state,
         "_billing_postcode"=>$billing_postcode,
         "_billing_country"=>$billing_country,
         "_billing_email"=>$billing_email,
         "_billing_phone"=>$billing_phone
        );

        $shipping = array(
         "_shipping_first_name"=>$shipping_first_name,
         "_shipping_last_name"=>$shipping_last_name,
         "_shipping_company"=>$shipping_company,
         "_shipping_address_1"=>$shipping_address_1,
         "_shipping_address_2"=>$shipping_address_2,
         "_shipping_city"=>$shipping_city,
         "_shipping_state"=>$shipping_state,
         "_shipping_postcode"=>$shipping_postcode,
         "_shipping_country"=>$shipping_country
        );
        
        if ($order)
        {
            $status = $parameters['status'] ? $parameters['status'] : $order->status;
            
            if($billing){
                foreach($billing as $key=>$address){
                   update_post_meta($order->ID, $key, $address); 
                }
            }
            if($shipping){
                foreach($shipping as $key=>$ship){
                   update_post_meta($order->ID, $key, $ship); 
                }
            }
            $response = $order->update_status($status);
			

			$order_data = array(
				'order_id' => $parameters['order_id'],
				'customer_note' => $parameters['order_comments']
			);
			// update the customer_note on the order
			wc_update_order( $order_data );

            // Line items
            if (!empty($parameters['line_items']))
            {
				if($order->status=="processing"){
					
					return new WP_Error('message', 'Billing and shipping address updated but not order items because your order is in processing!.', array(
                    'status' => 200
                    ));
				}
				if($order->status=="cancelled"){
					
					return new WP_Error('message', 'Billing and shipping address updated but not order items because your order is cancelled.!', array(
                    'status' => 200
                    ));
					
				}
				if($order->status=="completed"){
					
					return new WP_Error('message', 'Billing and shipping address updated but not order items because your order is completed.!', array(
                    'status' => 200
                    ));
				}
				
                foreach ($parameters['line_items'] as $line_item)
                {
                    wc_update_order_item_meta($line_item['id'], '_qty', $line_item['quantity']);
                    wc_update_order_item_meta($line_item['id'], '_line_total', $line_item['subtotal']);
                    wc_update_order_item_meta($line_item['id'], '_line_subtotal', $line_item['total']);
                    $order->save();
                }
				$order->calculate_totals();
				
            }


            if ($response)
            {
                return new WP_Error('message', 'Order updated.', array(
                    'status' => 200
                ));
            }
        }
        else
        {
            return new WP_Error('error', 'Sorry no order id exits!', array(
                'status' => 404
            ));
        }
    }

    // Delete order function
    function delete_order($request)
    {
        // wc_get_order($order_data['id']);
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
        $user_info = get_userdata($user->ID);
		if(!empty($user_info)){
          $userRole = implode(', ', $user_info->roles);
		}
        if ($userRole == 'administrator' && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {

            $order = wc_get_order($request['order_id']);
            if (!empty($order->id))
            {
                $result = wp_delete_post($order->id);
                if ($result)
                {
                    return new WP_Error('message', 'Order is deleted.', array(
                        'status' => 200
                    ));
                }

            }
            else
            {
                return new WP_Error('error', 'Invalid Order ID.', array(
                    'status' => 404
                ));
            }
        }
        else
        {
            return new WP_Error('error', 'Sorry, you are not allowed to do.', array(
                'status' => 404
            ));
        }

    }

    // its used to create order
    function create_order($request)
    {
        global $woocommerce;
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
        $user_info = get_userdata($user->ID);
		if(!empty($user_info)){
          $userRole = implode(', ', $user_info->roles);
		}
        $data = $request->get_params();
        $first_name = $data['billing']['first_name'];
        $last_name = $data['billing']['last_name'];
        $address_1 = $data['billing']['address_1'];
        $city = $data['billing']['city'];
        $state = $data['billing']['state'];
        $postcode = $data['billing']['postcode'];
        $country = $data['billing']['country'];
        $email = $data['billing']['email'];
        $phone = $data['billing']['phone'];
        $customer_id = $data['customer_id'];

        $address = array(
            'first_name' => $data['billing']['first_name'],
            'last_name' => $data['billing']['last_name'],
            'address_1' => $data['billing']['address_1'],
            'address_2' => $data['billing']['address_2'],
            'company' => $data['billing']['company'],
            'city' => $data['billing']['city'],
            'state' => $data['billing']['state'],
            'postcode' => $data['billing']['postcode'],
            'country' => $data['billing']['country'],
            'email' => $data['billing']['email'],
            'phone' => $data['billing']['phone']
          );
		  
		 $shipping = array(
            'first_name' => $data['shipping']['first_name'],
            'last_name' => $data['shipping']['last_name'],
            'address_1' => $data['shipping']['address_1'],
            'address_2' => $data['shipping']['address_2'],
            'company' => $data['shipping']['company'],
            'city' => $data['shipping']['city'],
            'state' => $data['shipping']['state'],
            'postcode' => $data['shipping']['postcode'],
            'country' => $data['shipping']['country'],
            'email' => $data['shipping']['email'],
            'phone' => $data['shipping']['phone']
          );
         
         $error = new WP_Error();
         if (empty($first_name))
         {
            $error->add(400, __("First name field 'first name' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }
         if (empty($last_name))
         {
            $error->add(400, __("Last name field 'last name' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }
         if (empty($address_1))
         {
            $error->add(400, __("Address field 'address' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }
         if (empty($city))
         {
            $error->add(400, __("City field 'city' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }
         if (empty($state))
         {
            $error->add(400, __("State field 'state' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }
         if (empty($postcode))
         {
            $error->add(400, __("State field 'state' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }
         if (empty($country))
         {
            $error->add(400, __("Country field 'country' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }

         if (empty($email))
         {
            $error->add(400, __("Email field 'email' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }
         if (!is_email($email))
         {
            $error->add(400, __("Invalid E-mail-id.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }

         if (empty($phone))
         {
            $error->add(400, __("Phone field 'phone' is required.", 'wp-rest-user') , array(
                'status' => 400
            ));
            return $error;
         }


       //return $customer_id;
        // Now we create the order
        $order = wc_create_order(array(
            'customer_id' => $customer_id
        ));
        if (empty($order))
        {
            return new WP_Error('error', 'Sorry something went wrong try again!.', array(
                'status' => 404
            ));
        }




        // Set addresses
        $order->set_address($address, 'billing');
        $order->set_address($shipping, 'shipping');

        // Line items
        if (!empty($data['line_items']))
        {
            foreach ($data['line_items'] as $line_item)
            {
                $product = wc_get_product(isset($line_item['variation_id']) && $line_item['variation_id'] > 0 ? $line_item['variation_id'] : $line_item['product_id']);
                $order->add_product(get_product($product) , $line_item['quantity']);
            }
        }

        // Coupon items
        if (isset($data['coupon_items']))
        {
            foreach ($data['coupon_items'] as $coupon_item)
            {
                $order->apply_coupon(sanitize_title($coupon_item['code']));
            }
        }

        // Set other details
        $order->set_created_via('checkout');
        $order->set_customer_id($customer_id);
        $order->set_currency(get_woocommerce_currency());
        $order->set_prices_include_tax('yes' === get_option('woocommerce_prices_include_tax'));
        $order->set_customer_note(isset($data['order_comments']) ? $data['order_comments'] : '');

        // Set payment gateway
        $payment_gateways = WC()
            ->payment_gateways
            ->payment_gateways();
        $order->set_payment_method($payment_gateways[$data['payment_method']]);
        // Calculate taxes
        $order->calculate_taxes();
        // Calculate shipping
        $order->calculate_shipping();
        // Calculate totals
        $order->calculate_totals();
        $order->update_status($data['status']);
        //$order->payment_complete();
        $order_id = $order->save();

        return new WP_Error('message', 'Order created successfully! ', array(
            'status' => 200,
            'order_id' => $order_id
        ));
    }

    function order_received($request)
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
        $user_info = get_userdata($user->ID);
		if(!empty($user_info)){
          $userRole = implode(', ', $user_info->roles);
		}
        if ($userRole == 'administrator' && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {
            $order = wc_get_order($request['order_id']);
            if ($order)
            {
                $order_data = $order->get_data();
                $order_id = $order_data['id'];
                $order_parent_id = $order_data['parent_id'];
                $order_date_created = $order_data['date_created']->date('Y-m-d H:i:s');
                $order_date_modified = $order_data['date_modified']->date('Y-m-d H:i:s');

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
                $line_items = array();
                $fee_lines = array();
                $coupon_lines = array();
                $taxes = array();
                $meta_data = array();
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
                    $variation_id = $item_data['variation_id'];
                    $quantity = $item_data['quantity'];
                    $tax_class = $item_data['tax_class'];
                    $line_subtotal = $item_data['subtotal'];
                    $line_subtotal_tax = $item_data['subtotal_tax'];
                    $line_total = $item_data['total'];
                    $line_total_tax = $item_data['total_tax'];
                    $line_items[] = array(
                        "id" => $item_id,
                        "name" => $product_name,
                        "product_id" => $product_id,
                        "variation_id" => $variation_id,
                        "quantity" => $quantity,
                        "tax_class" => $tax_class,
                        "subtotal" => $line_subtotal,
                        "subtotal_tax" => $line_subtotal_tax,
                        "total" => $line_total,
                        "total_tax" => $line_total_tax,
                        "taxes" => $taxes,
                        "meta_data" => $meta_data,
                        "sku" => $sku,
                        "price" => $price

                    );
                }

                $ordersArr[] = array(
                    "id" => $order_id,
                    "parent_id" => $order_parent_id,
                    "number" => $order_data['number'],
                    "order_key" => $order_data['order_key'],
                    "created_via" => $order_data['created_via'],
                    "version" => $order_data['version'],
                    "status" => $order_data['status'],
                    "currency" => $order_data['currency'],
                    "date_created" => $order_date_created,
                    "date_created_gmt" => $date_created_gmt,
                    "discount_total" => $order_data['discount_total'],
                    "discount_tax" => $order_data['discount_tax'],
                    "shipping_total" => $order_data['shipping_total'],
                    "shipping_tax" => $order_data['shipping_tax'],
                    "cart_tax" => $order_data['cart_tax'],
                    "total" => $order_data['total'],
                    "total_tax" => $order_data['total_tax'],
                    "prices_include_tax" => false,
                    "customer_id" => $order_data['customer_id'],
                    "customer_ip_address" => $order_data['customer_ip_address'],
                    "customer_user_agent" => $order_data['customer_user_agent'],
                    "customer_note" => $order_data['customer_note'],
                    "billing" => $billining_info,
                    "shipping" => $shipping_info,
                    "payment_method" => $order_data['payment_method'],
                    "payment_method_title" => $order_data['payment_method_title'],
                    "transaction_id" => $order_data['transaction_id'],
                    "date_paid" => $order_data['date_paid'],
                    "date_paid_gmt" => $order_data['date_paid_gmt'],
                    "date_completed" => $order_data['date_completed'],
                    "date_completed_gmt" => $order_data['date_completed_gmt'],
                    "cart_hash" => $order_data['cart_hash'],
                    "line_items" => $line_items,
                    "fee_lines" => $fee_lines,
                    "coupon_lines" => $coupon_lines

                );

                return rest_ensure_response($ordersArr);
            }
            else
            {
                return new WP_Error('empty_order', 'Sorry invalid order id!', array(
                    'status' => 404
                ));
            }
        }
        else
        {
            return new WP_Error('error', 'Sorry, you are not allowed to do.', array(
                'status' => 404
            ));
        }

    }
}
new Wc_Order_PI();
?>
