<?php
/**
 * Plugin Name: Appy Pie Connect for WooCommerce
 * Plugin URI: https://www.appypie.com/
 * Description: Adding custom WooCommerce endpoints on WP REST API v3
 * Version: 1.1.2
 * Requires at least: 4.8
 * Requires PHP: 7.2
 * Text Domain: appy-pie-connect-for-woocommerce
 * Author: Appy Pie Team
 * Author URI: https://appypie.com/
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */


if (!class_exists('Appy_Pie_Connect_Woocommece_custom_api'))
{

    class Appy_Pie_Connect_Woocommece_custom_api
    {

        function __construct()
        { // its used to register user route
            add_action('rest_api_init', array(&$this,
                'get_register_user_route'
            ));

            //Register a new user
            add_action('rest_api_init', array(&$this,
                'customer_register'
            ));

            //User delete route
            add_action('rest_api_init', array(&$this,
                'delete_user'
            ));

            //Update user detail route
            add_action('rest_api_init', array(&$this,
                'update_user_detail'
            ));

            //get specific user detail route
            add_action('rest_api_init', array(&$this,
                'get_user_by_id'
            ));

            //its used for login route
            add_action('rest_api_init', array(&$this,
                'get_user_login'
            ));

            //its used to check email id exits or not
            add_action('rest_api_init', array(&$this,
                'forget_password'
            ));

            //its used to check email id exits or not
            add_action('rest_api_init', array(&$this,
                'reset_password'
            ));

            //Wc product related api is added in this file.
            require plugin_dir_path(__FILE__) . '/product-api.php';
            //Wc order related api is added in this file.
            require plugin_dir_path(__FILE__) . '/wc-order-api.php';
            //Wc Invoice related api is added in this file.
            require plugin_dir_path(__FILE__) . '/wc-invoice-api.php';
            //Wc coupons related api is added in this file.
            require plugin_dir_path(__FILE__) . '/wc-coupons-api.php';
            //Wc category related api is added in this file.
            //require plugin_dir_path(__FILE__) . '/wc-category-api.php';
            //Wc wc-custom-webhook related created custom webhook added in this file.
            require plugin_dir_path(__FILE__) . '/wc-custom-webhook.php';
            //Wc wc-webhook-api related api is added in this file.
            require plugin_dir_path(__FILE__) . '/wc-webhook-api.php';
        }

        function get_register_user_route()
        { // its used to handle user route
            register_rest_route('wc/v3', 'customer/list', array(
                'methods' => 'GET',
                'callback' => array(
                    $this,
                    'get_user_list'
                ),
                'permission_callback' => '__return_true'
            ));
        }

        function customer_register()
        { // its used to handle user route
            register_rest_route('wc/v3', 'customer/create', array(
                'methods' => 'POST',
                'callback' => array(
                    $this,
                    'add_new_user'
                ),
                'permission_callback' => '__return_true'
            ));
        }

        function get_user_login()
        { // its used to handle user route
            register_rest_route('wc/v3', 'customer/login', array(
                'methods' => 'POST',
                'callback' => array(
                    $this,
                    'user_login_route'
                ),
                'permission_callback' => '__return_true'
            ));
        }

        function delete_user()
        { // its used to handle delete request route
            register_rest_route('wc/v3', 'customer/delete/(?P<user_id>[\d]+)', array(
                'methods' => 'DELETE',
                'callback' => array(
                    $this,
                    'delete_wc_user'
                ),
                'permission_callback' => '__return_true'
            ));
        }

        function update_user_detail()
        { // its used to handle user info update route
            register_rest_route('wc/v3', 'customer/update', array(
                'methods' => 'POST',
                'callback' => array(
                    $this,
                    'update_user_info'
                ),
                'permission_callback' => '__return_true'
            ));
        }

        function get_user_by_id()
        { // its used to handle user info update route
            register_rest_route('wc/v3', 'customer/detail/(?P<user_id>[\d]+)', array(
                'methods' => 'GET',
                'callback' => array(
                    $this,
                    'get_user_info_by_userid',
                ) ,
                'permission_callback' => array(
                    $this,
                    'get_items_permissions_check'
                )
            ));
        }

        function forget_password()
        { // its used to handle user route
            register_rest_route('wc/v3', 'customer/forget_password', array(
                'methods' => 'POST',
                'callback' => array(
                    $this,
                    'check_user_email'
                ),
                'permission_callback' => '__return_true'

            ));
        }

        function reset_password()
        { // its used to handle user reset_password route
            register_rest_route('wc/v3', 'customer/reset_password', array(
                'methods' => 'POST',
                'callback' => array(
                    $this,
                    'reset_user_password'
                ),
                'permission_callback' => '__return_true'
            ));
        }

        /**
         * Its used to display all registered users
         *
         * @name get_user_list
         * @param WP_REST_Request $request
         * @return WP_REST_Response|WP_Error
         */
        function get_user_list(WP_REST_Request $request)
        {

            $per_page = $request['per_page'] ? $request['per_page'] : 10;
            $paged = $request['paged'] ? $request['paged'] : 1;

			$headers = array();
			foreach($_SERVER as $name => $value) {
				if($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
					$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
					$headers[$name] = $value;
				}
			}
            $username = isset($headers['Username']) ? sanitize_text_field($headers['Username']) : '';
            $password = isset($headers['Password']) ? sanitize_text_field($headers['Password']) : '';

            if (empty($username) || empty($password)) {
                return new WP_Error('error', __('Username and password are required.', 'wp-rest-user'), array('status' => 400));
            }
        
            $user = get_user_by('login', $username);
            if (!$user || !wp_check_password($password, $user->data->user_pass, $user->ID)) {
                return new WP_Error('error', __('Incorrect credentials. Try again.', 'wp-rest-user'), array('status' => 401));
            }
        
            $user_info = get_userdata($user->ID);
            $userRole = implode(', ', $user_info->roles);
        
            if ($userRole !== 'administrator') {
                return new WP_Error('error', __('Sorry, you are not allowed to access this.', 'wp-rest-user'), array('status' => 403));
            }

            $query = array(
                'role' => 'customer',
                'orderby' => 'registered',
                'order' => 'DESC',
                'number' => $per_page, // How many per page
                'paged' => $paged,
            );
            $query = array(
                'role' => 'customer',
                'orderby' => 'registered',
                'order' => 'DESC',
                'number' => $per_page, // How many per page
                'paged' => $paged,
            );
            $query = array(
                'role' => 'customer',
                'orderby' => 'registered',
                'order' => 'DESC',
                'number' => $per_page, // How many per page
                'paged' => $paged,
            );
            $query = array(
                'role' => 'customer',
                'orderby' => 'registered',
                'order' => 'DESC',
                'number' => $per_page, // How many per page
                'paged' => $paged,
            );
            $query = array(
                'role' => 'customer',
                'orderby' => 'registered',
                'order' => 'DESC',
                'number' => $per_page, // How many per page
                'paged' => $paged,
            );
            $query = array(
                'role' => 'customer',
                'orderby' => 'registered',
                'order' => 'DESC',
                'number' => $per_page, // How many per page
                'paged' => $paged,
            );
            if ($request['search']) {
                $query['search'] = '*'.esc_attr( $request['search'] ).'*';
                $query['search_columns'] =  array(
                'user_login',
                'user_nicename',
                'user_email'
                ); 
            }
            if (!empty($request['month'] && $request['year'])) {
                $year = $request['year'];
                $month = $request['month'];
                $query['date_query'] = array(
                'year' => $year,
                'month' =>$month
                //'day' => $day
                );
            }

            if(!empty($request['date'])){
				 /*$datetime = new DateTime($request['date']);
				 $la_time = new DateTimeZone(get_option('timezone_string'));
				 $datetime->setTimezone($la_time);
				 $from_date = $datetime->format('Y-m-d H:i:s');
				 */
                 $query['date_query'] = array(
                    'after' => sanitize_text_field($request['date'])
                );
            }
		   
		    if(!empty($request['modified_after'])){
			    /*
				 $datetime = new DateTime($request['modified_after']);
				 $la_time = new DateTimeZone(get_option('timezone_string'));
				 $datetime->setTimezone($la_time);
				 $from_date = $datetime->format('Y-m-d H:i:s');
				 */
                 $query['date_query'] = array(
                    'after' => sanitize_text_field($request['modified_after'])
                );
            }

            $userArr = array();
            $users = new WP_User_Query($query);
            $total_count = $users->get_total();
            $userData = $users->get_results();

            if (empty($userData)) {
                return new WP_Error('error', __('No record found.', 'wp-rest-user'), array('status' => 400));
            }

            foreach ($userData as $user)
            {
                $user_info = get_userdata($user->ID);
                $userRole = implode(', ', $user_info->roles);
                $first_name = $user_info->first_name;
                $last_name = $user_info->last_name;

                $billing = array(
                    'billing_first_name' => get_user_meta($user->ID, 'billing_first_name', true),
                    'billing_last_name' => get_user_meta($user->ID, 'billing_last_name', true),
                    'billing_company' => get_user_meta($user->ID, 'billing_company', true),
                    'billing_address_1' => get_user_meta($user->ID, 'billing_address_1', true),
                    'billing_address_2' => get_user_meta($user->ID, 'billing_address_2', true),
                    'billing_city' => get_user_meta($user->ID, 'billing_city', true),
                    'billing_postcode' => get_user_meta($user->ID, 'billing_postcode', true),
                    'billing_country' => get_user_meta($user->ID, 'billing_country', true),
                    'billing_state' => get_user_meta($user->ID, 'billing_state', true),
                    'billing_phone' => get_user_meta($user->ID, 'billing_phone', true),
                    'billing_email' => get_user_meta($user->ID, 'billing_email', true),
                );

                $shipping = array(
                    'shipping_first_name' => get_user_meta($user->ID, 'shipping_first_name', true),
                    'shipping_last_name' => get_user_meta($user->ID, 'shipping_last_name', true),
                    'shipping_company' => get_user_meta($user->ID, 'shipping_company', true),
                    'shipping_address_1' => get_user_meta($user->ID, 'shipping_address_1', true),
                    'shipping_address_2' => get_user_meta($user->ID, 'shipping_address_2', true),
                    'shipping_city' => get_user_meta($user->ID, 'shipping_city', true),
                    'shipping_postcode' => get_user_meta($user->ID, 'shipping_postcode', true),
                    'shipping_country' => get_user_meta($user->ID, 'shipping_country', true),
                    'shipping_state' => get_user_meta($user->ID, 'shipping_state', true),
                    'shipping_phone' => get_user_meta($user->ID, 'shipping_phone', true),
                    'shipping_email' => get_user_meta($user->ID, 'shipping_email', true),
                );
        
                $is_activated = get_user_meta($user->ID, 'is_activated', true);
                $active_status = $is_activated === 'no' ? 'no' : 'yes';

				if($is_activated=='no'){
					$active_status  = 'no';
				}
				elseif($is_activated=='yes'){
					$active_status  = 'yes';
				}else{
					$active_status  = 'yes';
				}

                $userArr[] = array(
                    'ID' => $user->ID,
                    'user_id' => $user->ID,
                    'email' => $user->user_email,
                    'user_registered' => $user->user_registered,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'is_activated' => $active_status,
                    'role' => $userRole,
                    'billing' => $billing,
                    'shipping' => $shipping,
                );
            }
            $user_count = array('user_count' => $total_count);
            $users_list = array('users' => $userArr);
            $finaluserData = array_merge($user_count, $users_list);

            return rest_ensure_response($finaluserData);
        }

        /**
         * Adds a new user via REST API.
         *
         * @param WP_REST_Request $request
         * @return WP_Error|WP_REST_Response
         */
        function add_new_user(WP_REST_Request $request)
        {
            $parameters = $request->get_params();
            $username   = wp_trim_words($parameters['username']);
            $email      = sanitize_text_field($parameters['email']);
            $password   = wp_trim_words($parameters['password']);
            $first_name = sanitize_text_field($parameters['first_name']);
            $last_name  = sanitize_text_field($parameters['last_name']);

            // Validate email format
            if (!is_email($email)) {
                return new WP_Error('invalid_email', __("Invalid email format.", 'wp-rest-user'), array('status' => 400));
            }

            //billing address
            $billing = array( 
            "billing_first_name" => sanitize_text_field($parameters['billing']['0']['first_name']),
            "billing_last_name" => sanitize_text_field($parameters['billing']['0']['last_name']),
            "billing_company" => sanitize_text_field($parameters['billing']['0']['company']),
            "billing_address_1" => sanitize_text_field($parameters['billing']['0']['address_1']),
            "billing_address_2" => sanitize_text_field($parameters['billing']['0']['address_2']),
            "billing_city" => sanitize_text_field($parameters['billing']['0']['city']),
            "billing_postcode" => sanitize_text_field($parameters['billing']['0']['postcode']),
            "billing_country" => sanitize_text_field($parameters['billing']['0']['country']),
            "billing_state" => sanitize_text_field($parameters['billing']['0']['state']),
            "billing_phone" => sanitize_text_field($parameters['billing']['0']['phone']),
            "billing_email" => sanitize_text_field($parameters['billing']['0']['email']),
            );
            //shipping address
            $shipping = array( 
            "shipping_first_name" => sanitize_text_field($parameters['shipping']['0']['first_name']),
            "shipping_last_name" => sanitize_text_field($parameters['shipping']['0']['last_name']),
            "shipping_company" => sanitize_text_field($parameters['shipping']['0']['company']),
            "shipping_address_1" => sanitize_text_field($parameters['shipping']['0']['address_1']),
            "shipping_address_2" => sanitize_text_field($parameters['shipping']['0']['address_2']),
            "shipping_city" => sanitize_text_field($parameters['shipping']['0']['city']),
            "shipping_postcode" => sanitize_text_field($parameters['shipping']['0']['postcode']),
            "shipping_country" => sanitize_text_field($parameters['shipping']['0']['country']),
            "shipping_state" => sanitize_text_field($parameters['shipping']['0']['state']),
            "shipping_phone" => sanitize_text_field($parameters['shipping']['0']['phone']),
            "shipping_email" => sanitize_text_field($parameters['shipping']['0']['email']),
            );

            $response = array();
            $error = new WP_Error();
            if (empty($username))
            {
                $error->add(400, __("Username field 'username' is required.", 'wp-rest-user') , array(
                    'status' => 400
                ));
                return $error;
            }

            if (empty($email))
            {
                $error->add(401, __("Email field 'email' is required.", 'wp-rest-user') , array(
                    'status' => 400
                ));
                return $error;
            }
            if (empty($password))
            {
                $error->add(404, __("Password field 'password' is required.", 'wp-rest-user') , array(
                    'status' => 400
                ));
                return $error;
            }


            $user_id = username_exists($username);
            if (!$user_id && email_exists($email) == false)
            {
                $user_id = wp_create_user($username, $password, $email);
                wp_new_user_notification($user_id);
                update_user_meta($user_id, 'is_activated', 'no');
                $user_data = wp_update_user(array( 'ID' => $user_id, 'first_name' => $first_name,'last_name'=>$last_name ) );

                if($billing){
                    foreach($billing as $key =>$address){
                        update_user_meta($user_id, $key, $address);
                    }
                }

                if($shipping){
                    foreach($shipping as $key =>$address){
                        update_user_meta($user_id, $key, $address);
                    }
                }
                
                if (!is_wp_error($user_id))
                {
                    // Ger User Meta Data (Sensitive, Password included. DO NOT pass to front end.)
                    $user = get_user_by('id', $user_id);
                    // WooCommerce specific code
                    if (class_exists('WooCommerce'))
                    {
                        $user->set_role('customer');
                    }
                    // Ger User Data (Non-Sensitive, Pass to front end.)
                    $response['code'] = 200;
                    // Translators: %s is the username.
                    $translated_text = __("User '%s' Thanks for registration, check your inbox!", "wp-rest-user");
                    $response['message'] = sprintf($translated_text, $username);
                }
                else
                {
                    return $user_id;
                }
            }
            else
            {
                $error->add(406, __("Email already exists, please try 'Reset Password'", 'wp-rest-user') , array(
                    'status' => 400
                ));
                return $error;
            }
            return new WP_REST_Response($response);

        }

        /**
         * Handles the deletion of a WooCommerce user via REST API.
         *
         * @param WP_REST_Request $request
         * @return WP_Error|array
         */
        function delete_wc_user(WP_REST_Request $request)
        {
            global $wpdb;
			$headers = array();
			foreach($_SERVER as $name => $value) {
				if($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
					$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
					$headers[$name] = $value;
				}
			}
            $username = isset($headers['Username']) ? sanitize_text_field($headers['Username']) : '';
            $password = isset($headers['Password']) ? sanitize_text_field($headers['Password']) : '';

            if (empty($username) || empty($password)) {
                return new WP_Error('missing_credentials', 'You must specify a valid username and password.', array('status' => 400));
            }
        
            $user = get_user_by('login', $username);

            // Verify the user exists
            if (!$user) {
                return new WP_Error('invalid_username', 'Invalid username.', array('status' => 400));
            }

            // Check the password
            if (!wp_check_password($password, $user->data->user_pass, $user->ID)) {
                return new WP_Error('invalid_password', 'Invalid password.', array('status' => 400));
            }

            $user_info = get_userdata($user->ID);
            $userRole = implode(', ', $user_info->roles);

            // Verify the user has the administrator role
            if ($userRole !== 'administrator') {
                return new WP_Error('unauthorized', 'You do not have permission to delete users.', array('status' => 403));
            }

            // Get the user to delete
            $deleteUser = get_user_by('id', $request['user_id']);
            if (!$deleteUser) {
                return new WP_Error('invalid_user_id', 'Invalid user ID.', array('status' => 400));
            }

            // Delete the user
            $table_name = $wpdb->prefix . 'users';
            $user_id = $request['user_id'];
            $res = $wpdb->query($wpdb->prepare("DELETE FROM {$table_name} WHERE ID = %d", $user_id));

            // Verify the user was deleted
            if ($res === false) {
                return new WP_Error('deletion_failed', 'Failed to delete the user.', array('status' => 500));
            }

            return array(
                'code' => 200,
                'message' => __("User was deleted successfully", "wp-rest-user")
            );
        }
 
        /**
         * Updates the user information via REST API.
         *
         * @param WP_REST_Request $request
         * @return WP_Error|array
         */
        function update_user_info(WP_REST_Request $request)
        {
            $headers = array();
			foreach($_SERVER as $name => $value) {
				if($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
					$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
					$headers[$name] = $value;
				}
			}
            $username = isset($headers['Username']) ? sanitize_text_field($headers['Username']) : '';
            $password = isset($headers['Password']) ? sanitize_text_field($headers['Password']) : '';

            // Verify the username and password are provided
            if (empty($username) || empty($password)) {
                return new WP_Error('missing_credentials', 'You must specify a valid username and password.', array('status' => 400));
            }

            $user = get_user_by('login', $username);

            // Verify the user exists
            if (!$user) {
                return new WP_Error('invalid_username', 'Invalid username.', array('status' => 400));
            }

            // Check the password
            if (!wp_check_password($password, $user->data->user_pass, $user->ID)) {
                return new WP_Error('invalid_password', 'Invalid password.', array('status' => 400));
            }

            $user_info = get_userdata($user->ID);
            $userRole = implode(', ', $user_info->roles);
            
            // Verify the user has the administrator role
            if ($userRole !== 'administrator') {
                return new WP_Error('unauthorized', 'You do not have permission to update user information.', array('status' => 403));
            }

            $parameters = $request->get_params();
            $user_id = isset($parameters['user_id']) ? intval($parameters['user_id']) : 0;

            // Verify the user ID is valid
            if ($user_id <= 0) {
                return new WP_Error('invalid_user_id', 'Invalid user ID.', array('status' => 400));
            }
			
			// Get user data to update
            $user_data = get_userdata($user_id);
            if (!$user_data) {
                return new WP_Error('user_not_found', 'User not found.', array('status' => 404));
            }

            // Prepare the data for updating
            $update_data = array('ID' => $user_id);
            $update_data['user_email'] = isset($parameters['email']) ? sanitize_email($parameters['email']) : $user_data->user_email;
            $update_data['first_name'] = isset($parameters['first_name']) ? sanitize_text_field($parameters['first_name']) : $user_data->first_name;
            $update_data['last_name'] = isset($parameters['last_name']) ? sanitize_text_field($parameters['last_name']) : $user_data->last_name;

            // Ensure the email is not already in use
            if (email_exists($update_data['user_email']) && $update_data['user_email'] !== $user_data->user_email) {
                return new WP_Error('email_exists', 'Email already in use.', array('status' => 400));
            }

            // Update the user data
            $user_id = wp_update_user($update_data);
            if (is_wp_error($user_id)) {
                return new WP_Error('update_failed', $user_id->get_error_message(), array('status' => 500));
            }

            // Update billing and shipping data
            $billing_fields = array(
                'billing_first_name', 'billing_last_name', 'billing_company',
                'billing_address_1', 'billing_address_2', 'billing_city',
                'billing_postcode', 'billing_country', 'billing_state',
                'billing_phone', 'billing_email'
            );
            $shipping_fields = array(
                'shipping_first_name', 'shipping_last_name', 'shipping_company',
                'shipping_address_1', 'shipping_address_2', 'shipping_city',
                'shipping_postcode', 'shipping_country', 'shipping_state',
                'shipping_phone', 'shipping_email'
            );

            foreach ($billing_fields as $field) {
                if (isset($parameters['billing'][0][$field])) {
                    update_user_meta($user_id, $field, sanitize_text_field($parameters['billing'][0][$field]));
                }
            }

            foreach ($shipping_fields as $field) {
                if (isset($parameters['shipping'][0][$field])) {
                    update_user_meta($user_id, $field, sanitize_text_field($parameters['shipping'][0][$field]));
                }
            }

            return array(
                'code' => 200,
                'message' => 'User profile successfully updated.'
            );
        }

        function check_user_email($request)
        {
            $parameters = $request->get_params();
            if (empty($parameters['email']) || $parameters['email'] === '')
            {
                return new WP_Error('no_email', 'Enter email address.', array(
                    'status' => 400
                ));
            }
            $exists = email_exists($parameters['email']);
            if (!$exists)
            {
                return new WP_Error('bad_email', 'No user found with this email address.', array(
                    'status' => 500
                ));
            }
            if ($exists)
            {
                $res = array(
                    'data' => array(
                        'status' => 200,
                    ) ,
                    'user_id' => $exists,
                    'message' => 'User exits.',
                );
                return new WP_REST_Response($res);

            }
        }

        function reset_user_password($request)
        {
            $parameters = $request->get_params();

            if (empty($parameters['user_id']))
            {
                return new WP_Error('error', 'user_id can not be blank.', array(
                    'status' => 400
                ));
            }
            else if (empty($parameters['password']))
            {
                return new WP_Error('error', 'New password can not be blank.', array(
                    'status' => 400
                ));
            }
            $user = get_user_by('id', $parameters['user_id']);
            if (!$user)
            {
                return new WP_Error('error', 'Sorry wrong user_id', array(
                    'status' => 400
                ));
            }
            else
            {

                $user_id = $parameters['user_id'];
                $password = $parameters['password'];
                $response = wp_set_password(wp_slash($password) , $user_id);
                $res = array(
                    'data' => array(
                        'status' => 200,
                    ) ,
                    'message' => 'Password reset successfully.',
                );
                return new WP_REST_Response($res);
            }
        }

        // it is used to check permission for get user by id
        function get_items_permissions_check(WP_REST_Request $request)
        {
            $headers = array();
			foreach($_SERVER as $name => $value) {
				if($name != 'HTTP_MOD_REWRITE' && (substr($name, 0, 5) == 'HTTP_' || $name == 'CONTENT_LENGTH' || $name == 'CONTENT_TYPE')) {
					$name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', str_replace('HTTP_', '', $name)))));
					$headers[$name] = $value;
				}
			}
            
            // Retrieve and sanitize the username and password from headers
            $username = isset($headers['Username']) ? sanitize_text_field($headers['Username']) : '';
            $password = isset($headers['Password']) ? sanitize_text_field($headers['Password']) : '';

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

        /**
         * Get user info by user ID
         *
         * @param WP_REST_Request $request
         * @return WP_Error|WP_REST_Response
         */
        function get_user_info_by_userid(WP_REST_Request $request)
        {
            // Sanitize the user ID input
            $user_id = isset($request['user_id']) ? intval($request['user_id']) : 0;

            // Check if the user ID is valid and the user exists
            if ($user_id <= 0 || !($user = get_user_by('ID', $user_id))) {
                return new WP_Error('invalid_user_id', __('Sorry, invalid User ID'), array('status' => 400));
            }

            $user_info = get_userdata($user_id);

           // Prepare billing and shipping address data
            $billing_address = array(
                "billing_first_name" => get_user_meta($user_id, 'billing_first_name', true),
                "billing_last_name" => get_user_meta($user_id, 'billing_last_name', true),
                "billing_company" => get_user_meta($user_id, 'billing_company', true),
                "billing_address_1" => get_user_meta($user_id, 'billing_address_1', true),
                "billing_address_2" => get_user_meta($user_id, 'billing_address_2', true),
                "billing_city" => get_user_meta($user_id, 'billing_city', true),
                "billing_postcode" => get_user_meta($user_id, 'billing_postcode', true),
                "billing_country" => get_user_meta($user_id, 'billing_country', true),
                "billing_state" => get_user_meta($user_id, 'billing_state', true),
                "billing_phone" => get_user_meta($user_id, 'billing_phone', true),
                "billing_email" => get_user_meta($user_id, 'billing_email', true)
            );

            $shipping_address = array(
                "shipping_first_name" => get_user_meta($user_id, 'shipping_first_name', true),
                "shipping_last_name" => get_user_meta($user_id, 'shipping_last_name', true),
                "shipping_company" => get_user_meta($user_id, 'shipping_company', true),
                "shipping_address_1" => get_user_meta($user_id, 'shipping_address_1', true),
                "shipping_address_2" => get_user_meta($user_id, 'shipping_address_2', true),
                "shipping_city" => get_user_meta($user_id, 'shipping_city', true),
                "shipping_postcode" => get_user_meta($user_id, 'shipping_postcode', true),
                "shipping_country" => get_user_meta($user_id, 'shipping_country', true),
                "shipping_state" => get_user_meta($user_id, 'shipping_state', true),
                "shipping_phone" => get_user_meta($user_id, 'shipping_phone', true),
                "shipping_email" => get_user_meta($user_id, 'shipping_email', true)
            );

            $userRole = implode(', ', $user_info->roles);
            $first_name = $user_info->first_name;
            $last_name = $user_info->last_name;

            $userArr = array(
                'ID' => $user->ID,
                'user_id' => $user->ID,
                'email' => $user->user_email,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'user_registered' => $user->user_registered,
                'role' => $userRole,
                'billing_address' => $billing_address,
                'shipping_address' => $shipping_address
            );

            return new WP_REST_Response($userArr);
        }

        /**
         * Handles user login via REST API.
         *
         * @param WP_REST_Request $request
         * @return WP_Error|WP_REST_Response
         */        
        function user_login_route(WP_REST_Request $request)
        {

            $parameters = $request->get_params();
            $username = isset($parameters['username']) ? sanitize_text_field($parameters['username']) : '';
            $password = isset($parameters['password']) ? sanitize_text_field($parameters['password']) : '';

            // Check if username and password are provided
            if (empty($username)) {
                return new WP_Error('missing_username', __("Username field 'username' is required.", 'wp-rest-user'), array('status' => 400));
            }

            if (empty($password)) {
                return new WP_Error('missing_password', __("Password field 'password' is required.", 'wp-rest-user'), array('status' => 400));
            }

            // Retrieve user by email or login
            if (email_exists($username)) {
                $user = get_user_by('email', $username);
            } else {
                $user = get_user_by('login', $username);
            }

            // Check if user exists and password is correct
            if ($user && wp_check_password($password, $user->data->user_pass, $user->ID)) {
                $user_info = get_userdata($user->ID);
                $userRole = implode(', ', $user_info->roles);
                $first_name = $user_info->first_name;
                $last_name = $user_info->last_name;

                $response = array(
                    "user_email" => $user->user_email,
                    "user_nicename" => $user->user_nicename,
                    "user_display_name" => $user->user_display_name,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "user_id" => $user->ID,
                    "role" => $userRole
                );

                return new WP_REST_Response($response);
            }

            // If user doesn't exist or password is incorrect, return an error
            return new WP_Error('invalid_credentials', __('Sorry, invalid username or password!', 'wp-rest-user'), array('status' => 401));
        }

    }
    new Appy_Pie_Connect_Woocommece_custom_api();

}
?>