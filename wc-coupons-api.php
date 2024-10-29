<?php
/*
 * Class name see@Wc_Coupons_PI
 * Used Wc_Coupons_PI class for WC Coupons related Api
*/
if (!defined('ABSPATH')) exit;

class Wc_Coupons_PI
{
    function __construct()
    {
        // its used to register @wc_coupons  route
        add_action('rest_api_init', array(&$this,
            'wc_coupons_route'
        ));

        // its used to register @view_coupons_route
        add_action('rest_api_init', array(&$this,
            'view_coupons_route'
        ));
        // its used to register @create_coupons_route
        add_action('rest_api_init', array(&$this,
            'create_coupons_route'
        ));

        // its used to register @delete_coupons_route
        add_action('rest_api_init', array(&$this,
            'delete_coupons_route'
        ));

        // its used to register @update_coupons_route
        add_action('rest_api_init', array(&$this,
            'update_coupons_route'
        ));

    }

    function wc_coupons_route()
    { // its used to handle coupon route
        register_rest_route('wc/v3', 'coupons/list', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_coupons_list'
            ) ,
            'permission_callback' => array(
                $this,
                'check_coupon_api_permissions'
            )

        ));
    }

    function view_coupons_route()
    { // its used to handle coupon view route
        register_rest_route('wc/v3', 'coupons/list/(?P<id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'view_coupons_by_id'
            )
        ));
    }

    function create_coupons_route()
    { // its used to handle coupon view route
        register_rest_route('wc/v3', 'coupons/create', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'create_coupon'
            )
        ));
    }

    function delete_coupons_route()
    { // its used to handle delete coupons route
        register_rest_route('wc/v3', 'coupons/delete/(?P<id>[\d]+)', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'delete_coupons'
            )
        ));
    }

    function update_coupons_route()
    { // its used to handle update coupons route
        register_rest_route('wc/v3', 'coupons/update', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'update_coupons'
            )
        ));
    }

    function check_coupon_api_permissions()
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
        $userRole = implode(', ', $user_info->roles);
        if ($userRole == 'administrator' && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    // view all the coupons list.
    function get_coupons_list($request)
    {

        global $woocommerce;
        $per_page = $request['per_page'] ? $request['per_page'] : 10;
        $paged = $request['paged'] ? $request['paged'] : 1;

        $query = array(
            'post_type' => 'shop_coupon',
            'posts_per_page' => $per_page,
            'paged' => $paged,
            'orderby' => 'date',
            'post_status' => 'publish'
        );
        if (!empty($request['code']))
        {
            $query['s'] = $request['code'];
        }
        if ($request['coupon_id'])
        {
            $query['post__in'] = array(
                $request['coupon_id']
            );
        }
        
        if (!empty($request['date']))
        {
            $datetime = new DateTime($request['date']);
			$la_time = new DateTimeZone(get_option('timezone_string'));
			$datetime->setTimezone($la_time);
			$from_date = $datetime->format('Y-m-d H:i:s');
            $query['date_query'] = array(
                'after' => $from_date
            );
        }
		if (!empty($request['modified_after']))
        {
    		$datetime = new DateTime($request['modified_after']);
			$la_time = new DateTimeZone(get_option('timezone_string'));
			$datetime->setTimezone($la_time);
			$from_date= $datetime->format('Y-m-d H:i:s');

            $query['date_query'] = array(
               'column' => 'post_modified',
               'after' => $from_date
            );
        }
		
        $coupons = new WP_Query($query);
        $data = array();
        if (empty($coupons->posts))
        {
            return new WP_Error('empty_coupons', 'No coupons is found', array(
                'status' => 404
            ));
        }
        if ($coupons->posts)
        {
            foreach ($coupons->posts as $coupon_code)
            {
                $c = new WC_Coupon($coupon_code->ID);
                $data[] = array(
                    'id' => $c->get_id() ,
                    'code' => $c->code,
                    'discount_type' => $c->discount_type,
                    'created_at' => $coupon_code->post_date,
                    'coupon_amount' => $c->amount, // value
                    'individual_use' => $c->individual_use,
                    'product_ids' => $c->product_ids,
                    'exclude_product_ids' => $c->exclude_product_ids,
                    'usage_limit' => $c->usage_limit,
                    'usage_limit_per_user' => $c->usage_limit_per_user,
                    'limit_usage_to_x_items' => $c->limit_usage_to_x_items,
                    'usage_count' => $c->usage_count,
                    'expiry_date' => $c->expiry_date, // YYYY-MM-DD
                    'free_shipping' => $c->free_shipping,
                    'product_categories' => $c->product_categories,
                    'exclude_product_categories' => $c->exclude_product_categories,
                    'exclude_sale_items' => $c->exclude_sale_items,
                    'minimum_amount' => $c->minimum_amount,
                    'maximum_amount' => $c->maximum_amount,
                    'customer_email' => $c->customer_email,
                    'description' => $coupon_code->post_excerpt
                );
            }
            $coupons_count['total_count'] = array(
                'coupons_count' => $coupons->found_posts
            );
            $coupons_list['coupons'] = $data;

            $finalcoupons = array_merge($coupons_count, $coupons_list);
            return rest_ensure_response($finalcoupons);
        }

    }

    function view_coupons_by_id($request)
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
        $userRole = implode(', ', $user_info->roles);
        if ($userRole == 'administrator' && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {
            if ($request['id'])
            {
                $coupons = get_post($request['id']);
                $c = new WC_Coupon($request['id']);
                $data = array(
                    'id' => $c->get_id() ,
                    'code' => $c->code,
                    'discount_type' => $c->discount_type,
                    'created_at' => $coupons->post_date,
                    'coupon_amount' => $c->amount, // value
                    'individual_use' => $c->individual_use,
                    'product_ids' => $c->product_ids,
                    'exclude_product_ids' => $c->exclude_product_ids,
                    'usage_limit' => $c->usage_limit,
                    'usage_limit_per_user' => $c->usage_limit_per_user,
                    'limit_usage_to_x_items' => $c->limit_usage_to_x_items,
                    'usage_count' => $c->usage_count,
                    'expiry_date' => $c->expiry_date, // YYYY-MM-DD
                    'free_shipping' => $c->free_shipping,
                    'product_categories' => $c->product_categories,
                    'exclude_product_categories' => $c->exclude_product_categories,
                    'exclude_sale_items' => $c->exclude_sale_items,
                    'minimum_amount' => $c->minimum_amount,
                    'maximum_amount' => $c->maximum_amount,
                    'customer_email' => $c->customer_email,
                    'description' => $coupons->post_excerpt
                );
                return rest_ensure_response($data);
            }

        }
        else
        {
            return new WP_Error('error', 'Sorry, you are not allowed to do.', array(
                'status' => 404
            ));
        }

    }
    function create_coupon($request)
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
        $userRole = implode(', ', $user_info->roles);
        $param = $request->get_params();

        if ($userRole == 'administrator' && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {

            $param = $request->get_params();
            $code = $param['code'];
            $error = new WP_Error();
            if (empty($code))
            {
                $error->add(400, __("Coupon code is required.", 'wp-rest-user') , array(
                    'status' => 400
                ));
                return $error;
            }

            //Check if the coupon has already been created in the database
            global $wpdb;
            $coupon_id = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1;",
                    $code
                )
            );

            if (empty($coupon_id))
            {
                // Create a coupon with the properties you need
                $data = array(
                    'discount_type' => $param['type'],
                    'coupon_amount' => $param['amount'], // value
                    'individual_use' => $param['individual_use'],
                    'product_ids' => $param['product_ids'], //it should be array
                    'exclude_product_ids' => $param['exclude_product_ids'], //it should be array
                    'usage_limit' => $param['usage_limit'],
                    'usage_limit_per_user' => $param['usage_limit_per_user'],
                    'limit_usage_to_x_items' => $param['limit_usage_to_x_items'],
                    'usage_count' => $param['usage_count'],
                    'expiry_date' => $param['expiry_date'], // YYYY-MM-DD
                    'free_shipping' => $param['free_shipping'],
                    'product_categories' => $param['product_categories'],
                    'exclude_product_categories' => $param['exclude_product_categories'],
                    'exclude_sale_items' => $param['exclude_sale_items'],
                    'minimum_amount' => $param['minimum_amount'],
                    'maximum_amount' => $param['maximum_amount'],
                    'customer_email' => $param['maximum_amount'] //it should be array
                    
                );

                //Save the coupon in the database
                $coupon = array(
                    'post_title' => $code,
                    'post_status' => 'publish',
                    'post_excerpt' => $param['description'],
                    'post_author' => $user->ID,
                    'post_type' => 'shop_coupon'
                );

                $new_coupon_id = wp_insert_post($coupon);
                // Write the $data values into postmeta table
                foreach ($data as $key => $value)
                {
                    update_post_meta($new_coupon_id, $key, $value);
                }
                if ($new_coupon_id)
                {
                    return new WP_Error('message', 'Coupon code is created.', array(
                        'status' => 200
                    ));
                }

            }
            else
            {
                return new WP_Error('error', 'Sorry, coupon code is alreay exits.', array(
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
 
    // its used to delete coupon  
    function delete_coupons($request)
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
        $userRole = implode(', ', $user_info->roles);
        global $wpdb;
        if ($userRole == 'administrator' && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {

            $id = $request['id'];
            $code = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT post_title FROM $wpdb->posts WHERE ID = %d AND post_type = 'shop_coupon' AND post_status = 'publish'",
                    $id
                )
            );
            
            $coupon_data = new WC_Coupon($code);
            if (!empty($coupon_data->id))
            {
                $result = wp_delete_post($coupon_data->id);
                if ($result)
                {
                    return new WP_Error('message', 'Coupon code is deleted.', array(
                        'status' => 200
                    ));
                }
            }
            else
            {
                return new WP_Error('error', 'Invalid coupon ID.', array(
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

    // its used for coupons update
    function update_coupons($request){

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
        $userRole = implode(', ', $user_info->roles);

        $param = $request->get_params();
        
        if ($userRole == 'administrator' && wp_check_password($password, $user
            ->data->user_pass, $user->ID))
        {   
            $coupon_id = $param['coupon_id'];
            global $wpdb;
            $coupon_detail = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $wpdb->posts WHERE ID = %d AND post_type = 'shop_coupon' AND post_status = 'publish'",
                    $coupon_id
                )
            );
            $code = $coupon_detail->post_title;
            
            $coupon_data = new WC_Coupon($code);
            
            if (!empty($coupon_data->id))
            {
            $coupon_code =  $param['code'] ? $param['code'] :$coupon_detail->post_title;
            $type = $param['type'] ? $param['type'] : $coupon_data->type;
            $amount = $param['amount'] ? $param['amount'] : $coupon_data->coupon_amount;
            $individual_use = $param['individual_use'] ? $param['individual_use'] : $coupon_data->individual_use;
            $product_ids = $param['product_ids'] ? $param['product_ids'] : $coupon_data->product_ids;
            $exclude_product_ids = $param['exclude_product_ids'] ? $param['exclude_product_ids'] : $coupon_data->exclude_product_ids;
            $usage_limit = $param['usage_limit'] ? $param['usage_limit'] : $coupon_data->usage_limit;
            $usage_limit = $param['usage_limit_per_user'] ? $param['usage_limit_per_user'] : $coupon_data->usage_limit_per_user;
            $usage_limit = $param['limit_usage_to_x_items'] ? $param['limit_usage_to_x_items'] : $coupon_data->limit_usage_to_x_items;
            $usage_limit = $param['usage_count'] ? $param['usage_count'] : $coupon_data->usage_count;
            $expiry_date = $param['expiry_date'] ? $param['expiry_date'] : $coupon_data->expiry_date;
            $free_shipping = $param['free_shipping'] ? $param['free_shipping'] : $coupon_data->free_shipping;
            $product_categories = $param['product_categories'] ? $param['product_categories'] : $coupon_data->product_categories;
            $exclude_product_categories = $param['exclude_product_categories'] ? $param['exclude_product_categories'] : $coupon_data->exclude_product_categories;
            $exclude_sale_items = $param['exclude_sale_items'] ? $param['exclude_sale_items'] : $coupon_data->exclude_sale_items;
            $minimum_amount = $param['minimum_amount'] ? $param['minimum_amount'] : $coupon_data->minimum_amount;
            $maximum_amount = $param['maximum_amount'] ? $param['maximum_amount'] : $coupon_data->maximum_amount;
            $customer_email = $param['customer_email'] ? $param['customer_email'] : $coupon_data->customer_email;
            $description = $param['description'] ? $param['description'] : $coupon_detail->post_excerpt;
               
            $update_coupon = array(
            'ID' => $coupon_data->id,
            'post_title' => $coupon_code,
            'post_status' => 'publish',
            'post_excerpt' => $description,
            'post_author' => $user->ID,
            'post_type' => 'shop_coupon'
            );
            $new_coupon_id =  wp_update_post($update_coupon);
            if(!empty($product_ids)){
              update_post_meta($new_coupon_id, 'product_ids', $product_ids);
            }
            if(!empty($exclude_product_ids)){
                update_post_meta($new_coupon_id, 'exclude_product_ids', $exclude_product_ids);
            }
			
            $data = array(
            'discount_type' => $type,
            'coupon_amount' => $amount, // value
            'individual_use' => $individual_use,
            'usage_limit' => $usage_limit,
            'usage_count' => $usage_count,
            'date_expires' => $expiry_date, // YYYY-MM-DD
            'free_shipping' => $free_shipping,
            'minimum_amount' => $minimum_amount,
			'exclude_sale_items'=>$exclude_sale_items,
            'maximum_amount' => $maximum_amount,
            'customer_email' => $customer_email //it should be array
            );

            foreach ($data as $key => $value)
            {
                update_post_meta($new_coupon_id, $key, $value);
            }

            return new WP_Error('message', 'updated successfully.', array(
                        'status' => 200
                    ));

           }else{
             return new WP_Error('error', 'Invalid coupon ID.', array(
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
new Wc_Coupons_PI();
?>
