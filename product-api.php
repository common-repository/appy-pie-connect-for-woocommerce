<?php
/*
 * Class name see@WcProductPI
 * Used WcProductPI class to display product listing
*/
if (!defined('ABSPATH')) exit;

class Wc_Product_PI
{
    function __construct()
    { // its used to register product route
        add_action('rest_api_init', array(&$this,
            'wc_product_routes'
        ));

        // its used to register product route
        add_action('rest_api_init', array(&$this,
            'view_single_product'
        ));
        
        // its used to register update product route
        add_action('rest_api_init', array(&$this,
            'view_single_update_product'
        ));


        // its used to register product author route
        add_action('rest_api_init', array(&$this,
            'get_product_by_author'
        ));

        // its used to register product category route
        add_action('rest_api_init', array(&$this,
            'get_category_list'
        ));

        // its register for product category slug route
        add_action('rest_api_init', array(&$this,
            'get_product_by_category_slug'
        ));

        // its register for create products
        add_action('rest_api_init', array(&$this,
            'create_product_route'
        ));

        // Delete product route
        add_action('rest_api_init', array(&$this,
            'delete_product_route'
        ));

        // Update product route
        add_action('rest_api_init', array(&$this,
            'update_product_route'
        ));

        // Update product list route
        add_action('rest_api_init', array(&$this,
            'wc_get_update_product_routes'
        ));
       
        // Create wc product category
        add_action('rest_api_init', array(&$this,
            'create_woocommerce_category_endpoint'
        ));
        
        //update wc product category
        add_action('rest_api_init', array(&$this,
            'update_woocommerce_category_endpoint'
        ));

        //delete wc product category
        add_action('rest_api_init', array(&$this,
         'delete_woocommerce_category_endpoint'
        ));

        //filter wc product category
        add_action('rest_api_init', array(&$this,
        'filter_wc_category_by_slug'
        ));

        //create variation wuth wc attribute
        add_action('rest_api_init', array(&$this,
        'create_wc_attribute_variation_productid_slug'
        ));

        //update variation wuth wc attribute
        add_action('rest_api_init', array(&$this,
        'update_wc_attribute_variation_productid_slug'
        ));

        //get variation wuth wc product
        add_action('rest_api_init', array(&$this,
        'wc_variation_routes'
        ));

    }

    function wc_product_routes()
    { // its used to handle user route all product list
        register_rest_route('wc/v3', 'product/list', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_product_list'
            ),
            'permission_callback' => array(
                $this,
                'check_product_list_permissions'
            )
        ));
    }
   
    // list and find variation product
    function wc_variation_routes()
    { 
        register_rest_route('wc/v3', 'variation/list', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_variation_list'
            ),
            'permission_callback' => array(
                $this,
                'check_variation_list_permissions'
            )
        ));
    }

    function wc_get_update_product_routes()
    { // its used to handle user route for all update product list
        register_rest_route('wc/v3', 'product/updatelist', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_update_product_list'
            ),
            'permission_callback' => array(
                $this,
                'check_update_product_list_permissions'
            )
        ));
    }

    function view_single_product()
    { // its used to handle user route single product list
        register_rest_route('wc/v3', 'product/list/(?P<id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_single_product_detail'
            ),
            'permission_callback' => array(
                $this,
                'check_single_product_permissions'
            )
        ));
    }

    function view_single_update_product()
    { // its used to handle user route for update single product
        register_rest_route('wc/v3', 'product/updatelist/(?P<id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_single_update_product_detail'
            ),
            'permission_callback' => array(
                $this,
                'check_update_single_product_list_permissions'
            )
        ));
    }
    
    // its create attribute with variation according to product id
    function create_wc_attribute_variation_productid_slug()
    { 
        register_rest_route('wc/v3', 'product/createvariation', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'create_product_variation_with_attribute'
            ),
            'permission_callback' => array(
                $this,
                'create_variation_attribute_permissions'
            )
        ));
    } 

    // its update attribute with variation according to product id
    function update_wc_attribute_variation_productid_slug()
    { 
        register_rest_route('wc/v3', 'product/updatevariation', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'update_product_variation_with_attribute'
            ),
            'permission_callback' => array(
                $this,
                'update_variation_attribute_permissions'
            )
        ));
    } 

    function get_product_by_author()
    { // its used to handle user route
        register_rest_route('wc/v3', 'product/author/(?P<id>[\d]+)', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'listing_product_by_author'
            ),
            'permission_callback' => array(
                $this,
                'get_api_permissions_check'
            )
        ));
    }

    function get_category_list()
    { // its used to handle user route
        register_rest_route('wc/v3', 'product/categories', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'display_wc_category_list'
            ),
            'permission_callback' => array(
                $this,
                'get_category_api_permissions_check'
            )
        ));
    }

    //Create new category
    function create_woocommerce_category_endpoint() {
        register_rest_route('wc/v3', 'product/create-category', array(

            'methods' => 'POST',
            'callback' => array(
                $this,
                'create_woocommerce_category'
            ),
            'permission_callback' => array(
                $this,
                'create_woocommerce_category_permissions'
            )
        ));
    }

    //update new category
    function update_woocommerce_category_endpoint() {
        register_rest_route('wc/v3', 'product/update-category', array(

            'methods' => 'POST',
            'callback' => array(
                $this,
                'update_woocommerce_category'
            ),
            'permission_callback' => array(
                $this,
                'update_woocommerce_category_permissions'
            )
        ));
    }

    //delete category
    function delete_woocommerce_category_endpoint() {
        register_rest_route('wc/v3', 'product/delete-category/(?P<id>\d+)', array(

            'methods' => 'POST',
            'callback' => array(
                $this,
                'delete_woocommerce_category'
            ),
            'permission_callback' => array(
                $this,
                'delete_category_api_permissions_check'
            )
        ));
    }
    
    function get_product_by_category_slug()
    { // its used to handle category route
        register_rest_route('wc/v3', 'product/category', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'get_wc_product_by_category'
            ),
            'permission_callback' => array(
                $this,
                'get_category_slug_api_permissions_check'
            )
        ));
    }

   //Filter wc product category by slug
    function filter_wc_category_by_slug()
    { // its used to handle category route
        register_rest_route('wc/v3', 'product/filtercategory', array(
            'methods' => 'GET',
            'callback' => array(
                $this,
                'filter_wc_product_by_category'
            ),
            'permission_callback' => array(
                $this,
                'filter_category_slug_api_permissions_check'
            )
        ));
    }
    
    function create_product_route()
    { // its used to handle @create_product_route
        register_rest_route('wc/v3', 'product/create', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'create_product'
            )
        ));
    }

    function delete_product_route()
    { // its used to handle @delete_product_routes
        register_rest_route('wc/v3', 'product/delete/(?P<product_id>[\d]+)', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'delete_product'
            )
        ));
    }

    function update_product_route()
    { // its used to handle @update_product_route
        register_rest_route('wc/v3', 'product/update', array(
            'methods' => 'POST',
            'callback' => array(
                $this,
                'update_product'
            )
        ));
    }


    // it is used to check permission for single product api
    function check_single_product_permissions(WP_REST_Request $request)
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
            return true;
        }
        else
        {
            return false;
        }
    }

    // it is used to check permission for variation listing api
    function check_variation_list_permissions(WP_REST_Request $request)
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
                return true;
            }
            else
            {
                return false;
            }
    }


    function get_variation_list(WP_REST_Request $request) {

        $parent_product_id = $_GET['pid'];
        $sku = $_GET['sku'];
       
        $product_id = isset($_GET['pid']) ? $_GET['pid'] : null;

        // Get all variations of a product by its ID
        if (!empty($product_id)) {
            $args = array(
                'post_type'     => 'product_variation',
                'post_status'   => array('private', 'publish'),
                'numberposts'   => -1,
                'orderby'       => 'menu_order',
                'order'         => 'asc',
                'post_parent'   => $product_id
            );

            $variations = get_posts($args);

            $filtered_variations = array();

            // Check if a SKU is provided for filtering
            $sku_to_filter = isset($_GET['sku']) ? $_GET['sku'] : null;

            foreach ($variations as $variation) {
                $variation_ID = $variation->ID;
                $variation_sku = get_post_meta($variation_ID, '_sku', true);

                // If SKU is not provided or matches the specified SKU, include the variation
                if (empty($sku_to_filter) || $variation_sku === $sku_to_filter) {
                $product_variation = new WC_Product_Variation($variation_ID);
                //print_r($product_variation);

                    // Get the custom field value
                    $custom_field_value = get_post_meta($variation_ID, 'custom_field', true);
                    // Get all variation data using WC_Product_Variation methods
                    $variation_data = array(
                        'parent_id' => $product_variation->get_parent_id(),
                        'parent_name' => $product_variation->get_title(),
                        'Variation_id' => $variation_ID,
                        'sku' => $variation_sku,
                        'Attributes' => $product_variation->get_variation_attributes(),
                        'Regular Price' => $product_variation->get_regular_price(),
                        'Sale_Price' => $product_variation->get_sale_price(),
                        'Price' => $product_variation->get_price(),
                        'Description' => $product_variation->get_description(),
                        'Dimensions' => array(
                            'Length' => $product_variation->get_length(),
                            'Width' => $product_variation->get_width(),
                            'Height' => $product_variation->get_height(),
                        ),
                        'Weight' => $product_variation->get_weight(),
                        'Stock_Status' => $product_variation->get_stock_status(),
                        'Manage_Stock' => $product_variation->get_manage_stock(),
                        'slug' => $product_variation->get_slug(),
                        'date_created' => $product_variation->get_date_created(),
                        'name' => $product_variation->get_name(),
                        'date_modified' => $product_variation->get_date_modified(),
                        'status' => $product_variation->get_status(),
                        'featured' => $product_variation->get_featured(),
                        'catalog_visibility' => $product_variation->get_catalog_visibility(),
                        'backorders' => $product_variation->get_backorders(),
                        'low_stock_amount' => $product_variation->get_low_stock_amount(),
                        'custom_field' => $custom_field_value,
                        
                        // Additional attributes can be retrieved using get_post_meta() or other WC_Product_Variation methods
                    );

                    $filtered_variations[] = $variation_data;
                }
            }
            // Output filtered variations data in JSON format
            header('Content-Type: application/json');
            echo wp_json_encode( $filtered_variations );
        } else {
            // Output an empty array if product ID is not provided
            header('Content-Type: application/json');
            echo wp_json_encode(array());
        }

    }

    // it is used to check permission for product listing api
    function check_product_list_permissions(WP_REST_Request $request)
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
				return true;
			}
			else
			{
				return false;
			}
    }

    function get_product_list(WP_REST_Request $request)
    {
        $posts_per_page = $request['per_page'] ? $request['per_page'] : 10;
        $paged = $request['paged'] ? $request['paged'] : 1;
        $custommetavalue = $_GET['meta_value'];
        $customKey = $_GET['meta_key'];

        $product_id = isset($_GET['product_id']) ? sanitize_text_field($_GET['product_id']) : '';
        echo esc_html($product_id);
        $Title = $_GET['title'];
        $query = array(
            'post_type' => 'product',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'title'      => $Title,
            'p' => $product_id,
            'meta_key'      => $customKey,
            'meta_value'    => $custommetavalue,
        );
		if (!empty($request['sku']))
        {
			 $query['post_status'] = array('draft','publish');
		}else{
			 $query['post_status'] = 'publish';
		}

        if (!empty($request['_regular_price']))
        {
            $query['posts_per_page'] = - 1;
            unset($query['paged']);
        }

        if ($request['order'] == 'newest')
        {
            $query['orderby'] = 'date';
            $query['order'] = 'DESC';
        }

        //Product list by price lowest order
        else if ($request['order'] == 'lowest')
        {
            $query['orderby'] = array(
                'meta_value_num' => 'ASC'
            );
            $query['meta_key'] = '_price';
        }

        //Product list by price higher order
        else if ($request['order'] == 'highest')
        {
            $query['orderby'] = array(
                'meta_value_num' => 'DESC'
            );
            $query['meta_key'] = '_price';
        }
        //list of best selling products in woocommerce
        else if ($request['order'] == 'topsale')
        {
            $query['orderby'] = array(
                'meta_value_num' => 'DESC'
            );
            $query['meta_key'] = 'total_sales';
        }
        else if ($request['custom'] == 'meta_value')
        {
            $query->query_vars['meta_value'] = 'DESC';
        }
          else
        {
            //$order = isset($request['order'])? $request['order'] : 'ASC';
            $query['orderby'] = 'date';
            $query['order'] = $order;
        }
        if ($request['onsale'])
        {
            $query['post__in'] = array_merge(wc_get_product_ids_on_sale());
        }
        if ($product) {
            if ($product->is_type('variable')) {
        if ($request['product_id'])
        {
            $query['post__in'] = array(
                $request['product_id']
            );
        } 
         }
        }

        if (!empty($request['search']))
        {
            $query['s'] = $request['search'];
        }

        if ($request['product_cat'])
        {
            $query['product_cat'] = $request['product_cat'];
        }
        if ($request['cat_id'])
        {
            $query['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $request['cat_id'],
                    'operator' => 'IN'
                ) ,

            );
        }

        if ($request['start_date'] && $request['end_date'])
        {
			if(!empty($request['start_date'])){
				$st_date = explode('-', $request['start_date']);
				$day = $st_date[0];
				$month = $st_date[1];
				$year = $st_date[2];
				$start_date = $day . '-' . $month . '-' . $year;
			}
            if(!empty($request['end_date'])){
				$ed_date = explode('-', $request['end_date']);
				$en_day = $ed_date[0];
				$en_month = $ed_date[1];
				$en_year = $ed_date[2];
				$end_date = $en_day . '-' . $en_month . '-' . $en_year;
			}

            $query['date_query'] = array(
                'column' => 'post_date',
                'after' => $start_date,
                'before' => $end_date
            );
        }

        if (!empty($request['date']))
        {
            $datetime = new DateTime($request['date']);
		    $la_time = new DateTimeZone(get_option('timezone_string'));
			$datetime->setTimezone($la_time);
		    $from_date = $datetime->format('Y-m-d H:i:s'); 
            $query['date_query'] = array(
                'after' =>$from_date
            );
           

        }
		if(!empty($request['modified_after'])){
				 $datetime = new DateTime($request['modified_after']);
				 $la_time = new DateTimeZone(get_option('timezone_string'));
				 $datetime->setTimezone($la_time);
				 $from_date = $datetime->format('Y-m-d H:i:s');
                 $query['date_query'] = array(
                    'after' => $from_date
                );
          }
        if (!empty($request['month'] && $request['year']))
        {
            $year = $request['year'];
            $month = $request['month'];
            $query['date_query'] = array(
                'year' => $year,
                'month' => $month
                //'day' => $day
                
            );
        }
        if ($request['stock_status'] == 'instock')
        {
            $query['meta_query'] = array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                ) ,
            );
        }
  
        if ($request['stock_status'] == 'outofstock')
        {
            $query['meta_query'] = array(
                array(
                    'key' => '_stock_status',
                    'value' => 'outofstock'
                ) ,
            );
        }
            if ($product) {
                if ($product->is_type('variable')) {
            if (!empty($request['sku']))
            {
                $query['meta_query'] = array(
                    array(
                        'key' => '_sku',
                        'value' => $request['sku'],
                        'compare' => '='
                    ),
                );
            }
          }
        }

        if (!empty($request['sku']))
         {
            $query['meta_query'] = array(
                array(
                    'key' => '_sku',
                    'value' => $request['sku'],
                    'compare' => '='
                 ),
            );
        }

        $productArr = array();
        $products = new WP_Query($query);

        if (!empty($products->posts))
        {
            foreach ($products->posts as $item)
            {
                $regular_price = get_post_meta($item->ID, '_regular_price', true);
                $date_on_sale_from = get_post_meta($item->ID, 'date_on_sale_from', true);
                $date_on_sale_to = get_post_meta($item->ID, 'date_on_sale_to', true);
                $stock_status = get_post_meta($item->ID, '_stock_status', true);
                $on_sale = get_post_meta($item->ID, 'on_sale', true);
                $sku = get_post_meta($item->ID, '_sku', true);
                $stock = get_post_meta($item->ID, '_stock', true);
                $sale_price = get_post_meta($item->ID, '_sale_price', true);
                $feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'single-post-thumbnail');
                $shop_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'shop_thumbnail');
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'thumbnail');
                $productImage = $feature_image[0] ? $feature_image[0] : '';
                $slug = get_post_field('post_name', $item->ID);
                $feature_size = array(
                    'shop_thumbnail' => $shop_thumbnail,
                    'thumbnail' => $thumbnail
                );
                $product_cats_ids = wc_get_product_term_ids($item->ID, 'product_cat');
                $categories = array();
                foreach ($product_cats_ids as $cat_id)
                {
                    $term = get_term_by('id', $cat_id, 'product_cat');
                    $categories[] = array(
                        'category_name' => $term->name,
                        'category_slug' => $term->slug,
                        'cat_id' => $term->term_id
                    );
                }
                $product = wc_get_product($item->ID);
                //print_r($product);

                $arr = $product->get_attributes();
                $product_attribute = array();
                foreach ($arr as $key => $attr)
                {
                    $attrValue = $attr->get_data();
                   // print_r($attrValue);
                    $product_attribute[] = array(
                        'id' => $attrValue['id'],
                        'name' => $attrValue['name'],
                        'option' => $attrValue['options'],
                        'position' => $attrValue['position'],
                        'visible' => $attrValue['variation'],
                        'variation' => $attrValue['variation']

                    );
                }
                $dimensions = array(
                    "weight" => $product->get_weight() ,
                    "length" => $product->get_length() ,
                    "height" => $product->get_height() ,

                );

                $type = $product->get_type();
                if($type == 'variable'){
                    $variations = $product->get_available_variations();
                    //print_r($variations);
                  }
                else{
                 $variations = array();
                }

                $productArr[] = array(
                    "product_id" => $item->ID,
                    "product_id" => $item->ID,
                    "date_created" => $item->post_date,
                    "title" => get_the_title($item->ID) ,
                    "description" => $item->post_content,
                    "slug" => $slug,
                    "type" => $product->get_type() ,
                    "status" => $product->get_status() ,
                    "url" => get_permalink($item->ID) ,
                    "short_description" => $item->post_excerpt,
                    "_stock_status" => $stock_status,
                    "stock_quantity" => $stock,
                    "feacture_image" => $productImage,
                    "media_details" => $feature_size,
                    "categories" => $categories,
                    "_sku" => $sku,
                    "price" => $regular_price,
                    "_regular_price" => $regular_price,
                    "_sale_price" => $sale_price,
                    "date_on_sale_from" => $product->get_date_on_sale_from() ,
                    "date_on_sale_to" => $product->get_date_on_sale_to() ,
                    "on_sale" => $product->is_on_sale() ,
                    "weight" => $product->get_weight() ,
                    "dimensions" => $dimensions,
                    "tags" => "",
                    "attributes" => $product_attribute,
                    "rating_count" => $product->get_average_rating(),
                    "default_attributes"=>$product->default_attributes,
                    "variations" => $variations,
                    "get_post_metadata" => get_post_meta($item->ID)
                );
            }

            $product_count['total_count'] = array(
                'product_count' => $products->found_posts
            );
            $product_list['products'] = $productArr;

            $finalproduct = array_merge($product_count, $product_list);
            return rest_ensure_response($finalproduct);
        }
        else
        {
            $response_data = array(); // Empty array
            $response = wp_json_encode(array('products' => $response_data));
            echo esc_html($response);
        }

    }
    
    // it is used to check permission for update product listing api
    function check_update_product_list_permissions(WP_REST_Request $request)
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
                return true;
            }
            else
            {
                return false;
            }
    }

    function get_update_product_list(WP_REST_Request $request)
    {
        $posts_per_page = $request['per_page'] ? $request['per_page'] : 10;
        $paged = $request['paged'] ? $request['paged'] : 1;
        $custommetavalue = $_GET['meta_value'];
        $customKey = $_GET['meta_key'];
        
        $query = array(
            'post_type' => 'product',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'orderby'  => 'modified',
            'meta_key'      => $customKey,
            'meta_value'    => $custommetavalue,
        );

		if (!empty($request['sku']))
        {
			 $query['post_status'] = array('draft','publish');
		}else{
			 $query['post_status'] = 'publish';
		}

        if (!empty($request['_regular_price']))
        {
            $query['posts_per_page'] = - 1;
            unset($query['paged']);
        }

        if ($request['order'] == 'newest')
        {
           $query['orderby'] = 'modified';
           $query['order'] = 'ASC';
        }

        //Product list by price lowest order
        else if ($request['order'] == 'lowest')
        {
            $query['orderby'] = array(
                'meta_value_num' => 'ASC'
            );
            $query['meta_key'] = '_price';
        }

        //Product list by price higher order
        else if ($request['order'] == 'highest')
        {
            $query['orderby'] = array(
                'meta_value_num' => 'ASC'
            );
            $query['meta_key'] = '_price';
        }
        //list of best selling products in woocommerce
        else if ($request['order'] == 'topsale')
        {
            $query['orderby'] = array(
                'meta_value_num' => 'DESC'
            );
            $query['meta_key'] = 'total_sales';
        }
        else
        {
            //$order = isset($request['order'])? $request['order'] : 'ASC';
            $query['orderby'] = 'modified';
            $query['order'] = $order;
        }
        if ($request['onsale'])
        {
            $query['post__in'] = array_merge(wc_get_product_ids_on_sale());
        }
        if ($request['product_id'])
        {
            $query['post__in'] = array(
                $request['product_id']
            );
        }

        if (!empty($request['search']))
        {
            $query['s'] = $request['search'];
        }

        if ($request['product_cat'])
        {
            $query['product_cat'] = $request['product_cat'];
        }
        if ($request['cat_id'])
        {
            $query['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $request['cat_id'],
                    'operator' => 'IN'
                ) ,

            );
        }

        if ($request['start_date'] && $request['end_date'])
        {
			if(!empty($request['start_date'])){
				$st_date = explode('-', $request['start_date']);
				$day = $st_date[0];
				$month = $st_date[1];
				$year = $st_date[2];
				$start_date = $day . '-' . $month . '-' . $year;
			}
            if(!empty($request['end_date'])){
				$ed_date = explode('-', $request['end_date']);
				$en_day = $ed_date[0];
				$en_month = $ed_date[1];
				$en_year = $ed_date[2];
				$end_date = $en_day . '-' . $en_month . '-' . $en_year;
			}

            $query['date_query'] = array(
                'column' => 'post_date',
                'after' => $start_date,
                'before' => $end_date
            );
        }

        if (!empty($request['date']))
        {
            $datetime = new DateTime($request['date']);
		    $la_time = new DateTimeZone(get_option('timezone_string'));
			$datetime->setTimezone($la_time);
		    $from_date = $datetime->format('Y-m-d H:i:s'); 
            $query['date_query'] = array(
                'after' =>$from_date
            );
           

        }
		if(!empty($request['modified_after'])){
				 $datetime = new DateTime($request['modified_after']);
				 $la_time = new DateTimeZone(get_option('timezone_string'));
				 $datetime->setTimezone($la_time);
				 $from_date = $datetime->format('Y-m-d H:i:s');
                 $query['date_query'] = array(
                    'after' => $from_date
                );
          }
        if (!empty($request['month'] && $request['year']))
        {
            $year = $request['year'];
            $month = $request['month'];
            $query['date_query'] = array(
                'year' => $year,
                'month' => $month
                //'day' => $day
                
            );
        }
        if ($request['stock_status'] == 'instock')
        {
            $query['meta_query'] = array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                ) ,
            );
        }

        if ($request['stock_status'] == 'outofstock')
        {
            $query['meta_query'] = array(
                array(
                    'key' => '_stock_status',
                    'value' => 'outofstock'
                ) ,
            );
        }
		
		if (!empty($request['sku']))
         {
            $query['meta_query'] = array(
                array(
                    'key' => '_sku',
                    'value' => $request['sku'],
                    'compare' => '='
                 ),
            );
        }
		
        $productArr = array();
        $products = new WP_Query($query);
       // print_r($products);

        if (!empty($products->posts))
        {
            foreach ($products->posts as $item)
            {
                $regular_price = get_post_meta($item->ID, '_regular_price', true);
                $date_on_sale_from = get_post_meta($item->ID, 'date_on_sale_from', true);
                $date_on_sale_to = get_post_meta($item->ID, 'date_on_sale_to', true);
                $stock_status = get_post_meta($item->ID, '_stock_status', true);
                $on_sale = get_post_meta($item->ID, 'on_sale', true);
                $sku = get_post_meta($item->ID, '_sku', true);
                $stock = get_post_meta($item->ID, '_stock', true);
                $sale_price = get_post_meta($item->ID, '_sale_price', true);
                $feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'single-post-thumbnail');
                $shop_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'shop_thumbnail');
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'thumbnail');
                $productImage = $feature_image[0] ? $feature_image[0] : '';
                $slug = get_post_field('post_name', $item->ID);
                $feature_size = array(
                    'shop_thumbnail' => $shop_thumbnail,
                    'thumbnail' => $thumbnail
                );
                $product_cats_ids = wc_get_product_term_ids($item->ID, 'product_cat');
                $categories = array();
                foreach ($product_cats_ids as $cat_id)
                {
                    $term = get_term_by('id', $cat_id, 'product_cat');
                    $categories[] = array(
                        'category_name' => $term->name,
                        'category_slug' => $term->slug,
                        'cat_id' => $term->term_id
                    );
                }
                $product = wc_get_product($item->ID);

                $arr = $product->get_attributes();
                $product_attribute = array();
                foreach ($arr as $key => $attr)
                {
                    $attrValue = $attr->get_data();
                    $product_attribute[] = array(
                        'id' => $attrValue['id'],
                        'name' => $attrValue['name'],
                        'option' => $attrValue['options'],
                        'position' => $attrValue['position'],
                        'visible' => $attrValue['variation'],
                        'variation' => $attrValue['variation']

                    );
                }
                $dimensions = array(
                    "weight" => $product->get_weight() ,
                    "length" => $product->get_length() ,
                    "height" => $product->get_height() ,

                );

                $type = $product->get_type();
                if($type == 'variable'){
                    $variations = $product->get_available_variations();
                  }
                else{
                 $variations = array();
                }
              
               // $the_query = new WP_Query( $args );
                $productArr[] = array(
                    "product_id" => $item->ID,
                    "date_created" => $item->post_date,
                    "modified_date" => $item->post_modified,
                    "title" => get_the_title($item->ID) ,
                    "description" => $item->post_content,
                    "slug" => $slug,
                    "type" => $product->get_type() ,
                    "status" => $product->get_status() ,
                    "url" => get_permalink($item->ID) ,
                    "short_description" => $item->post_excerpt,
                    "_stock_status" => $stock_status,
                    "stock_quantity" => $stock,
                    "feacture_image" => $productImage,
                    "media_details" => $feature_size,
                    "categories" => $categories,
                    "_sku" => $sku,
                    "price" => $regular_price,
                    "_regular_price" => $regular_price,
                    "_sale_price" => $sale_price,
                    "date_on_sale_from" => $product->get_date_on_sale_from() ,
                    "date_on_sale_to" => $product->get_date_on_sale_to() ,
                    "on_sale" => $product->is_on_sale() ,
                    "weight" => $product->get_weight() ,
                    "dimensions" => $dimensions,
                    "tags" => "",
                    "attributes" => $product_attribute,
                    "rating_count" => $product->get_average_rating(),
                    "default_attributes"=>$product->default_attributes,
                    "variations" => $variations,
                    "get_post_metadata" => get_post_meta($item->ID)
                );
            }

            $product_count['total_count'] = array(
                'product_count' => $products->found_posts
            );
            $product_list['products'] = $productArr;

            $finalproduct = array_merge($product_count, $product_list);
            return rest_ensure_response($finalproduct);
        }
        else
        {
            return new WP_Error('error', 'no product found!.', array());
        }

    }
    
    function get_single_product_detail($request)
    {   global $woocommerce;
        $product_id = $request['id'];
        $product = wc_get_product($product_id);

        if (!empty($product))
        {

            $attachment_ids = $product->get_gallery_image_ids();
            $get_gallery_image = array();
            foreach ($attachment_ids as $attachment_id)
            {
                // Display the image URL
                $Original_image_url = wp_get_attachment_url($attachment_id);
                $get_gallery_image[] = array(
                    'gallery_image' => $Original_image_url
                );
            }
            $product_cats_ids = wc_get_product_term_ids($product_id, 'product_cat');
            $categories = array();
            foreach ($product_cats_ids as $cat_id)
            {
                $term = get_term_by('id', $cat_id, 'product_cat');
                $categories[] = array(
                    'category_name' => $term->name,
                    'category_slug' => $term->slug,
                    'cat_id' => $term->term_id
                );
            }

            $arr = $product->get_attributes();
            $product_attribute = array();

            foreach ($arr as $key => $attr)
            {
                $attrValue = $attr->get_data();
                $product_attribute[] = array(
                    'id' => $attrValue['id'],
                    'name' => $attrValue['name'],
                    'option' => $attrValue['options'],
                    'position' => $attrValue['position'],
                    'visible' => $attrValue['variation'],
                    'variation' => $attrValue['variation']
                );
            }
            $dimensions = array(
                "weight" => $product->get_weight() ,
                "length" => $product->get_length() ,
                "height" => $product->get_height() ,

            );

            $type = $product->get_type();
            if($type == 'variable'){
              $variations = $product->get_available_variations();
            }
            else{
              $variations = array();
            }

            $response = array(
                'id' => $product->id,
                'name' => $product->get_name() ,
                'slug' => $product->get_slug() ,
                'description' => $product->get_description() ,
                "status" => $product->get_status() ,
                "type" => $product->get_type() ,
                'short_description' => $product->get_short_description() ,
                'sku' => $product->get_sku() ,
                'price' => $product->get_price() ,
                'regular_price' => $product->get_regular_price() ,
                'sale_price' => $product->get_sale_price() ,
                'attributes' => $product_attribute,
                'dimensions' => $dimensions,
                'stock_quantity' => $product->get_stock_quantity() ,
                'image' => wp_get_attachment_url($product->get_image_id()) ,
                'get_gallery_image' => $get_gallery_image,
                "rating_count" => $product->get_average_rating() ,
                "date_on_sale_from" => $product->get_date_on_sale_from() ,
                "date_on_sale_to" => $product->get_date_on_sale_to() ,
                "on_sale" => $product->is_on_sale() ,
                'categories' => $categories,
                "variations" => $variations,
                "default_attributes"=>$product->default_attributes
            );
            return rest_ensure_response($response);
        }
        else
        {
            return new WP_Error('error', 'Invalid Product ID !.', array());
        }

    }

    // it is used to check permission for single product update api
    function check_update_single_product_list_permissions(WP_REST_Request $request)
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
                return true;
            }
            else
            {
                return false;
            }
    }

    function get_single_update_product_detail($request)
    {   global $woocommerce;
        $product_id = $request['id'];
        $product = wc_get_product($product_id);

        if (!empty($product))
        {

            $attachment_ids = $product->get_gallery_image_ids();
            $get_gallery_image = array();
            foreach ($attachment_ids as $attachment_id)
            {
                // Display the image URL
                $Original_image_url = wp_get_attachment_url($attachment_id);
                $get_gallery_image[] = array(
                    'gallery_image' => $Original_image_url
                );
            }
            $product_cats_ids = wc_get_product_term_ids($product_id, 'product_cat');
            $categories = array();
            foreach ($product_cats_ids as $cat_id)
            {
                $term = get_term_by('id', $cat_id, 'product_cat');
                $categories[] = array(
                    'category_name' => $term->name,
                    'category_slug' => $term->slug,
                    'cat_id' => $term->term_id
                );
            }

            $arr = $product->get_attributes();
            $product_attribute = array();

            foreach ($arr as $key => $attr)
            {
                $attrValue = $attr->get_data();
                $product_attribute[] = array(
                    'id' => $attrValue['id'],
                    'name' => $attrValue['name'],
                    'option' => $attrValue['options'],
                    'position' => $attrValue['position'],
                    'visible' => $attrValue['variation'],
                    'variation' => $attrValue['variation']
                );
            }
            $dimensions = array(
                "weight" => $product->get_weight() ,
                "length" => $product->get_length() ,
                "height" => $product->get_height() ,

            );

            $type = $product->get_type();
            if($type == 'variable'){
              $variations = $product->get_available_variations();
            }
            else{
              $variations = array();
            }

            $response = array(
                'id' => $product->id,
                'name' => $product->get_name() ,
                'slug' => $product->get_slug() ,
                'description' => $product->get_description() ,
                "status" => $product->get_status() ,
                "type" => $product->get_type() ,
                'short_description' => $product->get_short_description() ,
                'sku' => $product->get_sku() ,
                'price' => $product->get_price() ,
                'regular_price' => $product->get_regular_price() ,
                'sale_price' => $product->get_sale_price() ,
                'attributes' => $product_attribute,
                'dimensions' => $dimensions,
                'stock_quantity' => $product->get_stock_quantity() ,
                'image' => wp_get_attachment_url($product->get_image_id()) ,
                'get_gallery_image' => $get_gallery_image,
                "rating_count" => $product->get_average_rating() ,
                "date_on_sale_from" => $product->get_date_on_sale_from() ,
                "date_on_sale_to" => $product->get_date_on_sale_to() ,
                "on_sale" => $product->is_on_sale() ,
                'categories' => $categories,
                "variations" => $variations,
                "default_attributes"=>$product->default_attributes
            );
            return rest_ensure_response($response);
        }
        else
        {
            return new WP_Error('error', 'Invalid Product ID !.', array());
        }

    }

    // it is used to check permission for get user by id
    function get_api_permissions_check(WP_REST_Request $request)
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
            return true;
        }
        else
        {
            return false;
        }
    }

    function listing_product_by_author($request)
    {
        $authorID = $request['id'];
        $posts_per_page = $request['per_page'] ? $request['per_page'] : 10;
        $paged = $request['paged'] ? $request['paged'] : 1;
        $query = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'orderby' => 'date',
            'author' => $authorID
        );
        $productArr = array();
        $products = new WP_Query($query);
        $user_exits = get_userdata($authorID);
        if ($user_exits)
        {
            if (!empty($products->posts) && count($products->posts) > 0)
            {
                foreach ($products->posts as $item)
                {
                    $regular_price = get_post_meta($item->ID, '_regular_price', true);
                    $date_on_sale_from = get_post_meta($item->ID, 'date_on_sale_from', true);
                    $date_on_sale_to = get_post_meta($item->ID, 'date_on_sale_to', true);
                    $stock_status = get_post_meta($item->ID, '_stock_status', true);
                    $on_sale = get_post_meta($item->ID, 'on_sale', true);
                    $sku = get_post_meta($item->ID, '_sku', true);
                    $stock = get_post_meta($item->ID, '_stock', true);
                    $sale_price = get_post_meta($item->ID, '_sale_price', true);
                    $feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'single-post-thumbnail');
                    $shop_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'shop_thumbnail');
                    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'thumbnail');
                    $productImage = $feature_image[0] ? $feature_image[0] : '';
                    $slug = get_post_field('post_name', $item->ID);
                    $feature_size = array(
                        'shop_thumbnail' => $shop_thumbnail,
                        'thumbnail' => $thumbnail
                    );
                    $product_cats_ids = wc_get_product_term_ids($item->ID, 'product_cat');
                    $categories = array();
                    foreach ($product_cats_ids as $cat_id)
                    {
                        $term = get_term_by('id', $cat_id, 'product_cat');
                        $categories[] = array(
                            'category_name' => $term->name,
                            'category_slug' => $term->slug,
                            'cat_id' => $term->term_id
                        );
                    }
                    $product = wc_get_product($item->ID);
                    $arr = $product->get_attributes();
                    $product_attribute = array();
                    foreach ($arr as $key => $attr)
                    {
                        $attrValue = $attr->get_data();
                        $product_attribute[] = array(
                            'id' => $attrValue['id'],
                            'name' => $attrValue['name'],
                            'option' => $attrValue['options'],
                            'position' => $attrValue['position'],
                            'visible' => $attrValue['variation'],
                            'variation' => $attrValue['variation']

                        );
                    }
                    $dimensions = array(
                        "weight" => $product->get_weight() ,
                        "length" => $product->get_length() ,
                        "height" => $product->get_height() ,

                    );

                    $productArr[] = array(
                        "product_id" => $item->ID,
                        "date_created" => $item->post_date,
                        "title" => get_the_title($item->ID) ,
                        "description" => $item->post_content,
                        "slug" => $slug,
                        "type" => $product->get_type() ,
                        "status" => $product->get_status() ,
                        "url" => get_permalink($item->ID) ,
                        "short_description" => $item->post_excerpt,
                        "_stock_status" => $stock_status,
                        "stock_quantity" => $stock,
                        "feacture_image" => $productImage,
                        "media_details" => $feature_size,
                        "categories" => $categories,
                        "_sku" => $sku,
                        "price" => $regular_price,
                        "_regular_price" => $regular_price,
                        "_sale_price" => $sale_price,
                        "date_on_sale_from" => $product->get_date_on_sale_from() ,
                        "date_on_sale_to" => $product->get_date_on_sale_to() ,
                        "on_sale" => $product->is_on_sale() ,
                        "weight" => $product->get_weight() ,
                        "dimensions" => $dimensions,
                        "tags" => "",
                        "attributes" => $product_attribute,
                        "rating_count" => $product->get_average_rating() ,
                        "variations" => "",
                    );
                }

                $product_count['total_count'] = array(
                    'product_count' => $products->found_posts
                );
                $product_list['products'] = $productArr;
                $finalproduct = array_merge($product_count, $product_list);
                return rest_ensure_response($finalproduct);
            }
            else
            {
                return new WP_Error('error', 'Sorry no posts available.', array());
            }
        }
        else
        {
            return new WP_Error('error', 'Invalid User Id.', array());
        }
    }

    // it is used to check permission for get user by id
    function get_category_api_permissions_check(WP_REST_Request $request)
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
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //Create  WC Prouduct Category
    function create_woocommerce_category($request) {
        // Get the username and password from the request
            // Category data to delete
            $category_data = array(
                'name' => $request->get_param('name'),
                'description' => $request->get_param('description'),
                'parent' => $request->get_param('parent'),
            );
    
            // Create the category using WooCommerce functions
            $category_id = wp_insert_term($category_data['name'], 'product_cat', $category_data);
    
            if (!is_wp_error($category_id)) {
                return array('message' => 'Category created successfully', 'category_id' => $category_id['term_id']);
            } else {
                return array('error' => 'Failed to create category.', 'details' => $category_id->get_error_message());
            }
       // } else {
            return array('error' => 'Authentication failed.');
       // }
    }
   
       // it is used to check permission for get delete by category
       function delete_category_api_permissions_check(WP_REST_Request $request)
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
               return true;
           }
           else
           {
               return false;
           }
       }

     //delete WC Prouduct Category
     function delete_woocommerce_category($request) {
        
        $category_id = $request->get_param('id');
        $result = wp_delete_term($category_id, 'product_cat');
    
        if (is_wp_error($result)) {
            error_log("Error deleting category: " . $result->get_error_message());
            return new WP_Error('category_not_deleted', 'Category could not be deleted.', array('status' => 500));
        } else {
            return 'Category deleted successfully.';
            //return array('message' => 'Category deleted successfully', 'category_id' => $category_id);
        }
    }

    // it is used to check permission for update category
    function update_woocommerce_category_permissions(WP_REST_Request $request)
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
            return true;
        }
        else
        {
            return false;
        }
    }

    //Update wc product ctegory
    function update_woocommerce_category($request) {
        
        $category_id = $request->get_param('id');
        $category_name = sanitize_text_field($request->get_param('name')); // Get the new category name

        //$category = get_term($category_id, 'product_cat');
        $category = get_term($category_id, 'product_cat');
       // print_r($category);

        if (is_wp_error($category) || empty($category)) {
            return new WP_Error('category_not_found', 'Category not found.', array('status' => 404));
        }

        $args = array(
            'name' => $category_name,
            'term_id' => $category_id,
            'taxonomy' => 'product_cat'
        );

        $result = wp_update_term($category_id, 'product_cat', $args);

        if (is_wp_error($result)) {
            return new WP_Error('category_not_updated', 'Category could not be updated.', array('status' => 500));
        } else {
            return 'updated successfully For Category Id:'.$category_id;
        }
    }

     // it is used to check permission for get category listing
     function create_woocommerce_category_permissions(WP_REST_Request $request)
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
             return true;
         }
         else
         {
             return false;
         }
     }

    function display_wc_category_list()
    {
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false;
        $cat_args = array(
            'taxonomy'   => 'product_cat',
            'orderby' => $orderby,
            'order' => $order,
            'hide_empty' => $hide_empty
        );
        $categories = array();
        $product_categories = get_terms($cat_args);
        if (!empty($product_categories))
        {
            foreach ($product_categories as $key => $category)
            {
                $thumbnail_id = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id);
                $categories[] = array(
                    'id' => $category->term_id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'parent' => $category->parent,
                    'description' => $category->description,
                    'count' => $category->count,
                    'url' => get_term_link($category) ,
                    'image' => $image

                );
            }
            return rest_ensure_response($categories);
        }
        else
        {
            return new WP_Error('error', 'Sorry no category available.', array());
        }

    }
    
    // it is used to check permission for get category by id and slug
    function get_category_slug_api_permissions_check(WP_REST_Request $request)
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
            return true;
        }
        else
        {
            return false;
        }
    }
   
    function get_wc_product_by_category(WP_REST_Request $request)
    {
        if ($request['slug'] || $request['category_id'])
        {

            if ($request['slug'])
            {
                $args = array(
                    'post_type' => 'product',
                    'numberposts' => - 1,
                    'post_status' => 'publish',
                    'product_cat' => $request['slug'],
                    'orderby' => 'date',
                    'order' => 'ASC'
                );

            }
            if ($request['category_id'])
            {
                $args = array(
                    'post_type' => 'product',
                    'numberposts' => - 1,
                    'post_status' => 'publish',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => $request['category_id'],
                            'operator' => 'IN'
                        ) ,
                    )
                );
            }
            $productArr = array();
            $product = get_posts($args);

           // if (!empty($product) && count($product) > 0)
           // {
                foreach ($product as $item)
                {
                    $regular_price = get_post_meta($item->ID, '_regular_price', true);
                    $stock_status = get_post_meta($item->ID, '_stock_status', true);
                    $sku = get_post_meta($item->ID, '_sku', true);
                    $stock = get_post_meta($item->ID, '_stock', true);
                    $sale_price = get_post_meta($item->ID, '_sale_price', true);
                    $feature_image = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'single-post-thumbnail');
                    $shop_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'shop_thumbnail');
                    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID) , 'thumbnail');
                    $productImage = $feature_image[0] ? $feature_image[0] : '';
                    $slug = get_post_field('post_name', $item->ID);
                    $feature_size = array(
                        'shop_thumbnail' => $shop_thumbnail,
                        'thumbnail' => $thumbnail
                    );
                    $product_cats_ids = wc_get_product_term_ids($item->ID, 'product_cat');
                    $categories = array();
                    foreach ($product_cats_ids as $cat_id)
                    {
                        $term = get_term_by('id', $cat_id, 'product_cat');
                        $categories[] = array(
                            'category_name' => $term->name,
                            'category_slug' => $term->slug,
                            'cat_id' => $term->term_id
                        );
                    }

                    $productArr[] = array(
                        "product_id" => $item->ID,
                        "date" => $item->post_date,
                        "title" => get_the_title($item->ID) ,
                        'description' => $item->post_content,
                        'slug' => $slug,
                        'url' => get_permalink($item->ID) ,
                        'short_description' => $item->post_excerpt,
                        '_regular_price' => $regular_price,
                        '_stock_status' => $stock_status,
                        'stock_quantity' => $stock,
                        '_sku' => $sku,
                        'feacture_image' => $productImage,
                        '_sale_price' => $sale_price,
                        'media_details' => $feature_size,
                        'categories' => $categories
                    );
                }

                return rest_ensure_response($product);
            /*}
            else
            {
                return new WP_Error('empty_category', 'there is no product in this category', array(
                    'status' => 404
                ));
            }*/
        }
        else
        {
            return new WP_Error('error', 'Invalid Request!', array(
                'status' => 404
            ));
        }
    }
    
    // it is used to check permission for get category by slug
    function filter_category_slug_api_permissions_check(WP_REST_Request $request)
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
            return true;
        }
        else
        {
            return false;
        }
    }

   //filter wc product by category by slug
    function filter_wc_product_by_category($request)
    {
        $category = array();
        $name = $request['slug'];

       
        $category = get_term_by( 'slug', $name, 'product_cat' );
        if (!empty( $category ) ) {
	     return $category;
        }

    }

    //This API is used to create a new product.
    function create_product($request)
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
            $param = $request->get_params();
            //print_r($param); 
            $post_title = $_POST['name'];

            $post_content = $param['description'];
            $short_description = $param['short_description'];
            //meta key
            $metakey = isset($_POST['meta_key']) ? sanitize_key($_POST['meta_key']) : '';
            $metakey1 = isset($_POST['meta_key1']) ? sanitize_key($_POST['meta_key1']) : '';
            $metakey2 = isset($_POST['meta_key2']) ? sanitize_key($_POST['meta_key2']) : '';
            $metakey3 = isset($_POST['meta_key3']) ? sanitize_key($_POST['meta_key3']) : '';
            $metakey4 = isset($_POST['meta_key4']) ? sanitize_key($_POST['meta_key4']) : '';
            $metakey5 = isset($_POST['meta_key5']) ? sanitize_key($_POST['meta_key5']) : '';
            $metakey6 = isset($_POST['meta_key6']) ? sanitize_key($_POST['meta_key6']) : '';
            $metakey7 = isset($_POST['meta_key7']) ? sanitize_key($_POST['meta_key7']) : '';
            $metakey8 = isset($_POST['meta_key8']) ? sanitize_key($_POST['meta_key8']) : '';
            $metakey9 = isset($_POST['meta_key9']) ? sanitize_key($_POST['meta_key9']) : '';
            $metakey10 = isset($_POST['meta_key10']) ? sanitize_key($_POST['meta_key10']) : '';
            $metakey11 = isset($_POST['meta_key11']) ? sanitize_key($_POST['meta_key11']) : '';
            $metakey12 = isset($_POST['meta_key12']) ? sanitize_key($_POST['meta_key12']) : '';
            $metakey13 = isset($_POST['meta_key13']) ? sanitize_key($_POST['meta_key13']) : '';
            $metakey14 = isset($_POST['meta_key14']) ? sanitize_key($_POST['meta_key14']) : '';
            $metakey15 = isset($_POST['meta_key15']) ? sanitize_key($_POST['meta_key15']) : '';
            $metakey16 = isset($_POST['meta_key16']) ? sanitize_key($_POST['meta_key16']) : '';
            $metakey17 = isset($_POST['meta_key17']) ? sanitize_key($_POST['meta_key17']) : '';
            $metakey18 = isset($_POST['meta_key18']) ? sanitize_key($_POST['meta_key18']) : '';
            $metakey19 = isset($_POST['meta_key19']) ? sanitize_key($_POST['meta_key19']) : '';

            //meta value
            $metavalue = isset($_POST['meta_value']) ? sanitize_text_field($_POST['meta_value']) : '';
            $metavalue1 = isset($_POST['meta_value1']) ? sanitize_text_field($_POST['meta_value1']) : '';
            $metavalue2 = isset($_POST['meta_value2']) ? sanitize_text_field($_POST['meta_value2']) : '';
            $metavalue3 = isset($_POST['meta_value3']) ? sanitize_text_field($_POST['meta_value3']) : '';
            $metavalue4 = isset($_POST['meta_value4']) ? sanitize_text_field($_POST['meta_value4']) : '';
            $metavalue5 = isset($_POST['meta_value5']) ? sanitize_text_field($_POST['meta_value5']) : '';
            $metavalue6 = isset($_POST['meta_value6']) ? sanitize_text_field($_POST['meta_value6']) : '';
            $metavalue7 = isset($_POST['meta_value7']) ? sanitize_text_field($_POST['meta_value7']) : '';
            $metavalue8 = isset($_POST['meta_value8']) ? sanitize_text_field($_POST['meta_value8']) : '';
            $metavalue9 = isset($_POST['meta_value9']) ? sanitize_text_field($_POST['meta_value9']) : '';
            $metavalue10 = isset($_POST['meta_value10']) ? sanitize_text_field($_POST['meta_value10']) : '';
            $metavalue11 = isset($_POST['meta_value11']) ? sanitize_text_field($_POST['meta_value11']) : '';
            $metavalue12 = isset($_POST['meta_value12']) ? sanitize_text_field($_POST['meta_value12']) : '';
            $metavalue13 = isset($_POST['meta_value13']) ? sanitize_text_field($_POST['meta_value13']) : '';
            $metavalue14 = isset($_POST['meta_value14']) ? sanitize_text_field($_POST['meta_value14']) : '';
            $metavalue15 = isset($_POST['meta_value15']) ? sanitize_text_field($_POST['meta_value15']) : '';
            $metavalue16 = isset($_POST['meta_value16']) ? sanitize_text_field($_POST['meta_value16']) : '';
            $metavalue17 = isset($_POST['meta_value17']) ? sanitize_text_field($_POST['meta_value17']) : '';
            $metavalue18 = isset($_POST['meta_value18']) ? sanitize_text_field($_POST['meta_value18']) : '';
            $metavalue19 = isset($_POST['meta_value19']) ? sanitize_text_field($_POST['meta_value19']) : '';

            // attributes key
            $attrikey = isset($_POST['attri_key']) ? sanitize_key($_POST['attri_key']) : '';
            $attrikey1 = isset($_POST['attri_key1']) ? sanitize_key($_POST['attri_key1']) : '';
            $attrikey2 = isset($_POST['attri_key2']) ? sanitize_key($_POST['attri_key2']) : '';
            $attrikey3 = isset($_POST['attri_key3']) ? sanitize_key($_POST['attri_key3']) : '';
            $attrikey4 = isset($_POST['attri_key4']) ? sanitize_key($_POST['attri_key4']) : '';
            $attrikey5 = isset($_POST['attri_key5']) ? sanitize_key($_POST['attri_key5']) : '';
            $attrikey6 = isset($_POST['attri_key6']) ? sanitize_key($_POST['attri_key6']) : '';
            $attrikey7 = isset($_POST['attri_key7']) ? sanitize_key($_POST['attri_key7']) : '';
            $attrikey8 = isset($_POST['attri_key8']) ? sanitize_key($_POST['attri_key8']) : '';
            $attrikey9 = isset($_POST['attri_key9']) ? sanitize_key($_POST['attri_key9']) : '';
            $attrikey10 = isset($_POST['attri_key10']) ? sanitize_key($_POST['attri_key10']) : '';
            $attrikey11 = isset($_POST['attri_key11']) ? sanitize_key($_POST['attri_key11']) : '';
            $attrikey12 = isset($_POST['attri_key12']) ? sanitize_key($_POST['attri_key12']) : '';
            $attrikey13 = isset($_POST['attri_key13']) ? sanitize_key($_POST['attri_key13']) : '';
            $attrikey14 = isset($_POST['attri_key14']) ? sanitize_key($_POST['attri_key14']) : '';

            //attributes value
            $attrivalue = isset($_POST['attri_value']) ? sanitize_text_field($_POST['attri_value']) : '';
            $attrivalue1 = isset($_POST['attri_value1']) ? sanitize_text_field($_POST['attri_value1']) : '';
            $attrivalue2 = isset($_POST['attri_value2']) ? sanitize_text_field($_POST['attri_value2']) : '';
            $attrivalue3 = isset($_POST['attri_value3']) ? sanitize_text_field($_POST['attri_value3']) : '';
            $attrivalue4 = isset($_POST['attri_value4']) ? sanitize_text_field($_POST['attri_value4']) : '';
            $attrivalue5 = isset($_POST['attri_value5']) ? sanitize_text_field($_POST['attri_value5']) : '';
            $attrivalue6 = isset($_POST['attri_value6']) ? sanitize_text_field($_POST['attri_value6']) : '';
            $attrivalue7 = isset($_POST['attri_value7']) ? sanitize_text_field($_POST['attri_value7']) : '';
            $attrivalue8 = isset($_POST['attri_value8']) ? sanitize_text_field($_POST['attri_value8']) : '';
            $attrivalue9 = isset($_POST['attri_value9']) ? sanitize_text_field($_POST['attri_value9']) : '';
            $attrivalue10 = isset($_POST['attri_value10']) ? sanitize_text_field($_POST['attri_value10']) : '';
            $attrivalue11 = isset($_POST['attri_value11']) ? sanitize_text_field($_POST['attri_value11']) : '';
            $attrivalue12 = isset($_POST['attri_value12']) ? sanitize_text_field($_POST['attri_value12']) : '';
            $attrivalue13 = isset($_POST['attri_value13']) ? sanitize_text_field($_POST['attri_value13']) : '';
            $attrivalue14 = isset($_POST['attri_value14']) ? sanitize_text_field($_POST['attri_value14']) : '';

            $error = new WP_Error();
            if (empty($post_title))
            {
                $error->add(400, __("Product name is required.", 'wp-rest-user') , array(
                    'status' => 400
                ));
                return $error;
            }
            $metainput = $_POST['meta_input'];
            //print_r($metainput);
           // exit;
            $status = $_POST['status'];
            
            $new_product = array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_status' => $status,
                'post_excerpt' => $short_description,
                'post_type' => 'product',
                //'categories' => array('id' => 21, 'id'=> 25),
               // 'attributes' => $attributes,
                
            );
         
            $product_id = wp_insert_post($new_product);
           // print_r($new_product);

            $categorie = $param['categories'];
            $categories = explode(',', $categorie);
            $categories = array();
            
             if (is_array($category) && !empty($category)) {

                 foreach ($category as $id)
                {
                    $cat = get_term_by('id', $id, 'product_cat');
                    array_push($categores, $cat->slug);
                 }
             }
             // Define an array of custom fields
            $custom_fields = array(
                $metakey => $metavalue,
                $metakey1 => $metavalue1,
                $metakey2 => $metavalue2,
                $metakey3 => $metavalue3,
                $metakey4 => $metavalue4,
                $metakey5 => $metavalue5,
                $metakey6 => $metavalue6,
                $metakey7 => $metavalue7,
                $metakey8 => $metavalue8,
                $metakey9 => $metavalue9,
                $metakey10 => $metavalue10,
                $metakey11 => $metavalue11,
                $metakey12 => $metavalue12,
                $metakey13 => $metavalue13,
                $metakey14 => $metavalue14,
                $metakey15 => $metavalue15,
                $metakey16 => $metavalue16,
                $metakey17 => $metavalue17,
                $metakey18 => $metavalue18,
                $metakey19 => $metavalue19
        );

        // Add custom fields to the product
        foreach ($custom_fields as $key => $value) {
            update_post_meta($product_id, $key, $value);
        }
             // how to create attributes
            if ($product_id)
            {
              
              update_post_meta($product_id, '_product_attributes',  $attributes);
               
            
                if($param['images']){
                	foreach($param['images'] as $url){
                      $this->attach_product_thumbnail($product_id, $url['src'], 1);
                	}
                }
                $categorie = $param['categories'];
                $type = $param['type'] ? $param['type'] : 'simple';
                wp_set_object_terms($product_id, (int)$categorie, 'product_cat');
                wp_set_object_terms($product_id, $type, 'product_type');
                wp_set_post_terms($product_id, 'instock', 'product_visibility', true);
                update_post_meta($product_id, '_visibility', $param['_visibility']);
                update_post_meta($product_id, '_stock_status', 'instock');
                update_post_meta($product_id, 'total_sales', $param['total_sales']);
                update_post_meta($product_id, '_downloadable', $param['_downloadable']);
                update_post_meta($product_id, '_virtual', $param['_virtual']);
                update_post_meta($product_id, '_regular_price', $param['_regular_price']);
                update_post_meta($product_id, '_sale_price', $param['_sale_price']);
                update_post_meta($product_id, '_purchase_note', $param['_purchase_note']);
                update_post_meta($product_id, '_weight', $param['_weight']);
                update_post_meta($product_id, '_length', $param['_length']);
                update_post_meta($product_id, '_width', $param['_width']);
                update_post_meta($product_id, '_height', $param['_height']);
                update_post_meta($product_id, '_sku', $param['_sku']);
                update_post_meta($product_id, '_sale_price_dates_from', $param['_sale_price_dates_from']);
                update_post_meta($product_id, '_sale_price_dates_to', $param['_sale_price_dates_to']);
                update_post_meta($product_id, '_price', $param['_regular_price']);
                update_post_meta($product_id, '_sold_individually', $param['_sold_individually']);
                update_post_meta($product_id, '_manage_stock', $param['_manage_stock']);
                update_post_meta($product_id, '_backorders', $param['_backorders']);
                update_post_meta($product_id, '_stock', $param['_stock']);
                update_post_meta($product_id, '_downloadable_files', $param['_downloadable_files']);
                update_post_meta($product_id, '_download_limit', $param['_download_limit']);
                update_post_meta($product_id, '_download_expiry', $param['_download_expiry']);
                update_post_meta($product_id, '_download_type', $param['_download_type']); 
                $parent_id = $product_id;
                function setFeaturedImageAndGalleryToProduct($product_id, $featured_image_url, $gallery_image_urls) {
                    // Function to add images to the product gallery
                    function attachImagesToProductGallery($product_id, $image_urls) {
                        foreach ($image_urls as $image_url) {
                            // Check if the image URL is valid
                            if (filter_var($image_url, FILTER_VALIDATE_URL)) {
                                // Use wp_remote_get() to fetch the image
                                $response = wp_remote_get($image_url);
                    
                                if (is_wp_error($response)) {
                                    // Handle error
                                    $error_message = $response->get_error_message();
                                    echo esc_html("Something went wrong: $error_message");
                                    continue;
                                }
                    
                                // Get the response body
                                $image_data = wp_remote_retrieve_body($response);
                    
                                if (!empty($image_data)) {
                                    // Download the image and add it to the media library
                                    $image_name = basename($image_url);
                                    $upload_file = wp_upload_bits($image_name, null, $image_data);
                    
                                    if (!$upload_file['error']) {
                                        $file_path = $upload_file['file'];
                                        $file_name = basename($file_path);
                                        $attachment = array(
                                            'post_mime_type' => wp_check_filetype($file_name)['type'],
                                            'post_title' => sanitize_file_name($file_name),
                                            'post_content' => '',
                                            'post_status' => 'inherit'
                                        );
                    
                                        // Insert the image as an attachment to the media library
                                        $attach_id = wp_insert_attachment($attachment, $file_path, $product_id);
                                        require_once ABSPATH . 'wp-admin/includes/image.php';
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                    
                                        // Add the attachment ID to the product gallery
                                        $attachment_ids = get_post_meta($product_id, '_product_image_gallery', true);
                                        $attachment_ids = explode(',', $attachment_ids);
                                        $attachment_ids[] = $attach_id;
                    
                                        // Update product gallery meta
                                        update_post_meta($product_id, '_product_image_gallery', implode(',', $attachment_ids));
                                    }
                                } else {
                                    // Handle empty image data
                                    echo "Failed to retrieve image data.";
                                }
                            }
                        }
                    }
                
                    // Set the featured image for the product
                    if (filter_var($featured_image_url, FILTER_VALIDATE_URL)) {
                        // Use wp_remote_get() to fetch the image
                        $response = wp_remote_get($featured_image_url);
                    
                        if (is_wp_error($response)) {
                            // Handle error
                            $error_message = $response->get_error_message();
                            echo esc_html("Something went wrong: $error_message");
                        } else {
                            // Get the response body
                            $image_data = wp_remote_retrieve_body($response);
                    
                            if (!empty($image_data)) {
                                // Download the image and add it to the media library
                                $image_name = basename($featured_image_url);
                                $upload_file = wp_upload_bits($image_name, null, $image_data);
                    
                                if (!$upload_file['error']) {
                                    $file_path = $upload_file['file'];
                                    $file_name = basename($file_path);
                                    $attachment = array(
                                        'post_mime_type' => wp_check_filetype($file_name)['type'],
                                        'post_title' => sanitize_file_name($file_name),
                                        'post_content' => '',
                                        'post_status' => 'inherit'
                                    );
                    
                                    // Insert the image as an attachment to the media library
                                    $attach_id = wp_insert_attachment($attachment, $file_path, $product_id);
                                    require_once ABSPATH . 'wp-admin/includes/image.php';
                                    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                                    wp_update_attachment_metadata($attach_id, $attach_data);
                    
                                    // Set the attachment ID as the featured image for the product
                                    set_post_thumbnail($product_id, $attach_id);
                                } else {
                                    // Handle upload error
                                    echo esc_html("Image upload failed: " . $upload_file['error']);
                                }
                            } else {
                                // Handle empty image data
                                echo esc_html("Failed to retrieve image data.");
                            }
                        }
                    }                    
                
                    // Attach images to the product gallery
                    attachImagesToProductGallery($product_id, $gallery_image_urls);
                }
                // Usage: Provide the product ID, the featured image URL, and the image URLs to attach to the product gallery
                $featured_image_url = $_POST['featured']; // Replace with the featured image URL
                // Add more image URLs as needed for the product gallery
                 $product_gallery_image_urls =   explode(',',$_POST['src']);
                
                setFeaturedImageAndGalleryToProduct($product_id, $featured_image_url, $product_gallery_image_urls);
                 
                
                if ($param['variations'])
                {
                    foreach ($param['variations'] as $val)
                    {

                        $variation = array(
                            'post_title' => $post_title . ' (variation)',
                            'post_content' => '',
                            'post_status' => 'publish',
                            'post_parent' => $parent_id,
                            'post_type' => 'product_variation'
                        );

                        $variation_id = wp_insert_post($variation);

                        update_post_meta($variation_id, 'is_in_stock', $val['is_in_stock']);
                        update_post_meta($variation_id, 'max_qty', $val['max_qty']);
                        update_post_meta($variation_id, 'min_qty', $val['min_qty']);
                        update_post_meta($variation_id, '_regular_price', $val['_regular_price']);
                        update_post_meta($variation_id, '_price', $val['_regular_price']);
                        update_post_meta($variation_id, '_stock_qty', $val['_stock_qty']);
                        update_post_meta($variation_id, '_stock_status', 'instock');
                        update_post_meta($variation_id, '_manage_stock', $val['_manage_stock']);
                        update_post_meta($variation_id, '_stock', $val['stock_quantity']);

                        if ($val['image']['url'])
                        {
                           add_variation_image($variation_id, $val['image']['url']);
                        }
                        

                        if (is_array($val['attributes']))
                        {
                            foreach ($val['attributes'] as $key => $v)
                            {
                                
                                $att_slug = sanitize_title($key);
                                update_post_meta($variation_id, 'attribute_' . $att_slug, $v);
                            }
                        }

                    }
                }            
               
                return new WP_Error('message', 'Product created.', array('status' => 200, 'pid' => $product_id));
            }

        }
        else
        {
            return new WP_Error('error', 'Sorry, you are not allowed to do Please check authentication.', array(
                'status' => 404
            ));
        }

    }

    // create variation with attribute by product ID
    function create_variation_attribute_permissions(WP_REST_Request $request)
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
            return true;
        }
        else
        {
            return false;
        }
    }

    //Create product variation with attribute Product ID

    function create_product_variation_with_attribute(){

        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : false;

        if (!$product_id) {
        return;
        }

        // attributes key
        $attrikey = isset($_POST['attri_key']) ? sanitize_key($_POST['attri_key']) : '';
        $attrikey1 = isset($_POST['attri_key1']) ? sanitize_key($_POST['attri_key1']) : '';
        $attrikey2 = isset($_POST['attri_key2']) ? sanitize_key($_POST['attri_key2']) : '';
        $attrikey3 = isset($_POST['attri_key3']) ? sanitize_key($_POST['attri_key3']) : '';
        $attrikey4 = isset($_POST['attri_key4']) ? sanitize_key($_POST['attri_key4']) : '';
        $attrikey5 = isset($_POST['attri_key5']) ? sanitize_key($_POST['attri_key5']) : '';

        //attributes value
        $attrivalue = isset($_POST['attri_value']) ? sanitize_text_field($_POST['attri_value']) : '';
        $attrivalue1 = isset($_POST['attri_value1']) ? sanitize_text_field($_POST['attri_value1']) : '';
        $attrivalue2 = isset($_POST['attri_value2']) ? sanitize_text_field($_POST['attri_value2']) : '';
        $attrivalue3 = isset($_POST['attri_value3']) ? sanitize_text_field($_POST['attri_value3']) : '';
        $attrivalue4 = isset($_POST['attri_value4']) ? sanitize_text_field($_POST['attri_value4']) : '';
        $attrivalue5 = isset($_POST['attri_value5']) ? sanitize_text_field($_POST['attri_value5']) : '';
        
        // Define your attribute data in an associative array
        $attribute_data = array(
            $attrikey => $attrivalue,
            $attrikey1 => $attrivalue1,
            $attrikey2 => $attrivalue2,
            $attrikey3 => $attrivalue3,
            $attrikey4 => $attrivalue4,
            $attrikey5 => $attrivalue5,
        );
        // Initialize an empty array for product attributes
        $attributes = array();
        // Initialize a position counter
        $position = 0;
        // Loop through the attribute data
            $existing_attributes = get_post_meta($product_id, '_product_attributes', true);

        if (empty($existing_attributes)) {
        foreach ($attribute_data as $attrikey => $attrivalue) {
            // Define the attribute structure
            if (!empty($attrivalue)) {
            
            $attribute = array(
                'name' => $attrikey,
                'value' => $attrivalue,
                'position' => $position,
                'is_visible' => 1,
                'is_variation' => 1,
                'is_taxonomy' => 0,
            );

            // Add the attribute to the attributes array
            $attributes[$attrikey] = $attribute;

            // Increment the position for the next attribute
            $position++;
            }
        }
            // Update the product attributes
            update_post_meta($product_id, '_product_attributes', $attributes);

        } else {
            foreach ($attribute_data as $attrikey => $attrivalue) {
                if (!empty($attrivalue)) {
                    // Check if the attribute already exists
                    if (isset($existing_attributes[$attrikey])) {
                        $existing_values = $existing_attributes[$attrikey]['value'];

                        // Explode existing values and add the new value if it's not already present
                        $values_array = explode('|', $existing_values);
                        if (!in_array($attrivalue, $values_array)) {
                            $values_array[] = $attrivalue;
                        }

                        // Implode values array with the pipe character and update the attribute
                        $existing_attributes[$attrikey]['value'] = implode('|', $values_array);
                    } else {
                        // If the attribute doesn't exist, create a new attribute
                        $attribute = array(
                            'name' => $attrikey,
                            'value' => $attrivalue,
                            'position' => 0,
                            'is_visible' => 1,
                            'is_variation' => 1,
                            'is_taxonomy' => 0,
                        );

                        $existing_attributes[$attrikey] = $attribute;
                    }
                }
            }
            // Update the product attributes
            update_post_meta($product_id, '_product_attributes', $existing_attributes);
        }
        // Define variations and add them to the parent product vairiable
            
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
        $regular_price = isset($_POST['regular_price']) ? floatval($_POST['regular_price']) : 0.0;
        $sale_price = isset($_POST['sale_price']) ? floatval($_POST['sale_price']) : 0.0;
        $stock_status = isset($_POST['stock_status']) ? sanitize_text_field($_POST['stock_status']) : '';
        $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0.0;
        $length = isset($_POST['length']) ? floatval($_POST['length']) : 0.0;
        $width = isset($_POST['width']) ? floatval($_POST['width']) : 0.0;
        $height = isset($_POST['height']) ? floatval($_POST['height']) : 0.0;
        $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
        $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';
        $sku = isset($_POST['sku']) ? sanitize_text_field($_POST['sku']) : '';
        $backorders = isset($_POST['backorders']) ? sanitize_text_field($_POST['backorders']) : '';
        $low_stock_amount = isset($_POST['low_stock_amount']) ? intval($_POST['low_stock_amount']) : 0;
        $download_limit = isset($_POST['download_limit']) ? intval($_POST['download_limit']) : 0;
        $download_expiry = isset($_POST['download_expiry']) ? intval($_POST['download_expiry']) : 0;
        $virtual = isset($_POST['virtual']) ? boolval($_POST['virtual']) : false;
        $custom_field_value = isset($_POST['custom_field']) ? sanitize_text_field($_POST['custom_field']) : '';

        $image_urls = $_POST['image_urls'];

        $attribute = explode('|', $_POST['attri_value']);
        $attribute1 = explode('|',$_POST['attri_value1']);
        $attribute2 = explode('|',$_POST['attri_value2']);
        $attribute3 = explode('|',$_POST['attri_value3']);
        $attribute4 = explode('|',$_POST['attri_value4']);
        $attribute5 = explode('|',$_POST['attri_value5']);
        
        // attributes key
        $attrikeyv = isset($_POST['attri_key']) ? strtolower(sanitize_key($_POST['attri_key'])) : '';
        $attrikeyv1 = isset($_POST['attri_key1']) ? strtolower(sanitize_key($_POST['attri_key1'])) : '';
        $attrikeyv2 = isset($_POST['attri_key2']) ? strtolower(sanitize_key($_POST['attri_key2'])) : '';
        $attrikeyv3 = isset($_POST['attri_key3']) ? strtolower(sanitize_key($_POST['attri_key3'])) : '';
        $attrikeyv4 = isset($_POST['attri_key4']) ? strtolower(sanitize_key($_POST['attri_key4'])) : '';
        $attrikeyv5 = isset($_POST['attri_key5']) ? strtolower(sanitize_key($_POST['attri_key5'])) : '';
        //echo $attribute2[2];
        //exit;

        $variations = array();

        if (!empty($price)) {
            $variation = array(
            'price' => $price,
            'regular_price' => $regular_price,
            'sale_price' => $sale_price,
            'stock_status' => $stock_status,
            'weight' => $weight,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'stock' => $stock,
            'description' => $description,
            'sku' => $sku,
            'backorders' => $backorders,
            'low_stock_amount' => $low_stock_amount,
            'download_limit' => $download_limit,
            'download_expiry' => $download_expiry,
            'image_urls' => $image_urls,
            'attribute' => isset($attribute[0]) ? $attribute[0]: '',
            'attribute1' => isset($attribute1[0]) ? $attribute1[0]: '',
            'attribute2' => isset($attribute2[0]) ? $attribute2[0]: '',
            'attribute3' => isset($attribute3[0]) ? $attribute3[0]: '',
            'attribute4' => isset($attribute4[0]) ? $attribute4[0]: '',
            'attribute5' => isset($attribute5[0]) ? $attribute5[0]: '',

            );
            $variations[] = $variation;
        }

        function find_existing_variation($product_id, $variation_data) {
            // Get all variations of the product
            $variations = get_posts(array(
                'post_type'      => 'product_variation',
                'post_parent'    => $product_id,
                'posts_per_page' => -1,
            ));
            
            // Loop through existing variations and check for similarity with provided data
            foreach ($variations as $variation) {
                // Get the variation data from the database
                $existing_variation_data = get_post_meta($variation->ID);
                
                // Compare the existing variation data with the provided variation data
                $is_same = true;
                foreach ($variation_data as $key => $value) {
                    // Check if the variation attribute exists and matches the provided value
                    if (isset($existing_variation_data[$key][0]) && $existing_variation_data[$key][0] !== $value) {
                        $is_same = false;
                        break; // Break the loop if any attribute doesn't match
                    }
                }
                
                // If the variation data matches, update the existing variation attributes
                
                if ($is_same) {
                    foreach ($variation_data as $key => $value) {
                        // Check if the attribute already exists in the variation data
                        if (isset($existing_variation_data[$key][0])) {
                            $existing_values = explode('|', $existing_variation_data[$key][0]);
                            if (!in_array($value, $existing_values)) {
                                // Append the new value to the existing attribute values
                                $existing_values[] = $value;
                                $new_value = implode('|', $existing_values);
                                update_post_meta($variation->ID, $key, $new_value);
                            }
                        } else {
                            // If the attribute doesn't exist, set the new value
                            update_post_meta($variation->ID, $key, $value);
                        }
                    }
                    return $variation->ID; // Return the existing variation ID
                }
            }
            
            // If no matching variation is found, return null
            return null;
        }
        
        foreach ($variations as $variation_data) {

                $existing_variation = find_existing_variation($product_id, $variation_data);
        
            if ($existing_variation === null) {
                
                $variation = array(
                    'post_title'   => 'Variation #' . $product_id,
                    'post_parent'  => $product_id,
                    'post_type'    => 'product_variation',
                    'post_status'  => 'publish',
                );
                // Insert the variation
                $variation_id = wp_insert_post($variation);
        
                // Set variation attributes
                if ($variation_id) {
                    foreach ($variation_data as $key => $value) {
                        update_post_meta($variation_id, $key, $value);
                    }
                    
                    // Add custom field as the featured image
                    if (isset($variation_data['image_urls'])) {
                        $image_url = $variation_data['image_urls'];
                    
                        // Use wp_remote_get() to fetch the image
                        $response = wp_remote_get($image_url);
                    
                        if (is_wp_error($response)) {
                            // Handle error
                            $error_message = $response->get_error_message();
                            echo esc_html("Something went wrong: $error_message");
                        } else {
                            // Get the response body
                            $image_data = wp_remote_retrieve_body($response);
                    
                            if (!empty($image_data)) {
                                $image_name = basename($image_url);
                                $upload_file = wp_upload_bits($image_name, null, $image_data);
                    
                                if (!$upload_file['error']) {
                                    $file_path = $upload_file['file'];
                                    $file_name = basename($file_path);
                                    $attachment = array(
                                        'post_mime_type' => wp_check_filetype($file_name)['type'],
                                        'post_title'     => preg_replace('/\.[^.]+$/', '', $file_name),
                                        'post_content'   => '',
                                        'post_status'    => 'inherit'
                                    );
                    
                                    $attach_id = wp_insert_attachment($attachment, $file_path, $variation_id);
                    
                                    if (!is_wp_error($attach_id)) {
                                        // Set the variation's featured image (thumbnail)
                                        set_post_thumbnail($variation_id, $attach_id);
                                    }
                                } else {
                                    // Handle upload error
                                    echo esc_html("Image upload failed: " . $upload_file['error']);
                                }
                            } else {
                                // Handle empty image data
                                echo esc_html("Failed to retrieve image data.");
                            }
                        }
                    }
                    
                    
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv, $variation_data['attribute']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv1, $variation_data['attribute1']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv2, $variation_data['attribute2']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv3, $variation_data['attribute3']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv4, $variation_data['attribute4']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv5, $variation_data['attribute5']);
            
                    // Set other variation data
                    update_post_meta($variation_id, '_manage_stock', 'yes');
                    update_post_meta($variation_id, '_virtual', $virtual);
                    update_post_meta($variation_id, '_price', $variation_data['price']);
                    update_post_meta($variation_id, '_regular_price', $variation_data['regular_price']);
                    update_post_meta($variation_id, '_sale_price', $variation_data['sale_price']);
                    update_post_meta($variation_id, '_stock_status', $variation_data['stock_status']);
                    update_post_meta($variation_id, '_weight', $variation_data['weight']);
                    update_post_meta($variation_id, '_length', $variation_data['length']);
                    update_post_meta($variation_id, '_width', $variation_data['width']);
                    update_post_meta($variation_id, '_height', $variation_data['height']);
                    update_post_meta($variation_id, '_stock', $variation_data['stock']);
                    update_post_meta($variation_id, '_variation_description', $variation_data['description']);
                    update_post_meta($variation_id, '_sku', $variation_data['sku']);
                    update_post_meta($variation_id, '_backorders', $variation_data['backorders']);
                    update_post_meta($variation_id, '_low_stock_amount', $variation_data['low_stock_amount']);
                    update_post_meta($variation_id, '_wc_variation_file_names'.[$variation_id], $variation_data['file_names']);
                    update_post_meta($variation_id, '_wc_variation_file_urls'.[$variation_id], $variation_data['file_urls']);
                    update_post_meta($variation_id, '_download_limit', $variation_data['download_limit']);
                    update_post_meta($variation_id, '_download_expiry', $variation_data['download_expiry']);
                    // Create Custom Field in variations
                    update_post_meta($variation_id, 'custom_field', $custom_field_value);

                }
            } 
        }

        if ($variation_id) {
            //Clear product transients to force front-end update
            wc_delete_product_transients($product_id);
            // For instance, send a success message or update some other data in the database
            return new WP_Error('message', 'Variations created successfully!', array(
                'status' => 200,
                'vid' => $variation_id
            ));
        } else {
            // Handle errors if variations were not created
            echo 'Failed to create variations!';
        }
            
    }

       // update variation with attribute by product ID
        function update_variation_attribute_permissions(WP_REST_Request $request)
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
                return true;
            }
            else
            {
                return false;
            }
        }

       //update product variation with attribute Product ID

        function update_product_variation_with_attribute(){

            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : false;

            if (!$product_id) {
            return;
            }
            // attributes key
            $attrikey = isset($_POST['attri_key']) ? sanitize_key($_POST['attri_key']) : '';
            $attrikey1 = isset($_POST['attri_key1']) ? sanitize_key($_POST['attri_key1']) : '';
            $attrikey2 = isset($_POST['attri_key2']) ? sanitize_key($_POST['attri_key2']) : '';
            $attrikey3 = isset($_POST['attri_key3']) ? sanitize_key($_POST['attri_key3']) : '';
            $attrikey4 = isset($_POST['attri_key4']) ? sanitize_key($_POST['attri_key4']) : '';
            $attrikey5 = isset($_POST['attri_key5']) ? sanitize_key($_POST['attri_key5']) : '';

            //attributes value
            $attrivalue = isset($_POST['attri_value']) ? sanitize_text_field($_POST['attri_value']) : '';
            $attrivalue1 = isset($_POST['attri_value1']) ? sanitize_text_field($_POST['attri_value1']) : '';
            $attrivalue2 = isset($_POST['attri_value2']) ? sanitize_text_field($_POST['attri_value2']) : '';
            $attrivalue3 = isset($_POST['attri_value3']) ? sanitize_text_field($_POST['attri_value3']) : '';
            $attrivalue4 = isset($_POST['attri_value4']) ? sanitize_text_field($_POST['attri_value4']) : '';
            $attrivalue5 = isset($_POST['attri_value5']) ? sanitize_text_field($_POST['attri_value5']) : '';
            
            // Define your attribute data in an associative array
            $attribute_data = array(
                $attrikey => $attrivalue,
                $attrikey1 => $attrivalue1,
                $attrikey2 => $attrivalue2,
                $attrikey3 => $attrivalue3,
                $attrikey4 => $attrivalue4,
                $attrikey5 => $attrivalue5
            );
           // print_r($attribute_data);
            $attributes = array();
            // Initialize a position counter
            $position = 0;  
            // Replace $variation_id with the specific variation ID you want to update
            $variation_id = isset($_POST['product_id']) ? $_POST['product_id'] : false; // Replace with your variation ID

            $existing_attributes = get_post_meta($variation_id, '_product_attributes', true);

            foreach ($attribute_data as $attrikey => $attrivalue) {
                if (!empty($attrivalue)) {
                    // Check if the attribute already exists for this variation
                    if (isset($existing_attributes[$attrikey])) {
                        $existing_values = $existing_attributes[$attrikey]['value'];

                        // Explode existing values and add the new value if it's not already present
                        $values_array = explode('|', $existing_values);

                        // Convert existing values to lowercase for case-insensitive comparison
                        $lowercase_values = array_map('strtolower', $values_array);

                        // Convert the new attribute value to lowercase for comparison
                        $attrivalueLower = strtolower($attrivalue);

                        if (!in_array($attrivalueLower, $lowercase_values)) {
                            $values_array[] = $attrivalue;
                            $existing_attributes[$attrikey]['value'] = implode('|', $values_array);

                            // Update the product attributes for this variation
                            update_post_meta($variation_id, '_product_attributes', $existing_attributes);
                        }
                    } else {
                        // If the attribute doesn't exist for this variation, create a new attribute
                        $attribute = array(
                            'name' => $attrikey,
                            'value' => $attrivalue,
                            'position' => 0,
                            'is_visible' => 1,
                            'is_variation' => 1,
                            'is_taxonomy' => 0,
                        );

                        $existing_attributes[$attrikey] = $attribute;

                        // Update the product attributes for this variation after adding the new attribute
                        update_post_meta($variation_id, '_product_attributes', $existing_attributes);
                    }
                }
            }
            // Define variations and add them to the parent product vairiable
                
            $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
            $regular_price = isset($_POST['regular_price']) ? floatval($_POST['regular_price']) : 0.0;
            $sale_price = isset($_POST['sale_price']) ? floatval($_POST['sale_price']) : 0.0;
            $stock_status = isset($_POST['stock_status']) ? sanitize_text_field($_POST['stock_status']) : '';
            $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 0.0;
            $length = isset($_POST['length']) ? floatval($_POST['length']) : 0.0;
            $width = isset($_POST['width']) ? floatval($_POST['width']) : 0.0;
            $height = isset($_POST['height']) ? floatval($_POST['height']) : 0.0;
            $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
            $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';
            $sku = isset($_POST['sku']) ? sanitize_text_field($_POST['sku']) : '';
            $backorders = isset($_POST['backorders']) ? sanitize_text_field($_POST['backorders']) : '';
            $low_stock_amount = isset($_POST['low_stock_amount']) ? intval($_POST['low_stock_amount']) : 0;
            $download_limit = isset($_POST['download_limit']) ? intval($_POST['download_limit']) : 0;
            $download_expiry = isset($_POST['download_expiry']) ? intval($_POST['download_expiry']) : 0;

            $image_urls = $_POST['image_urls'];

            $attribute = explode('|', $_POST['attri_value']);
            $attribute1 = explode('|',$_POST['attri_value1']);
            $attribute2 = explode('|',$_POST['attri_value2']);
            $attribute3 = explode('|',$_POST['attri_value3']);
            $attribute4 = explode('|',$_POST['attri_value4']);
            $attribute5 = explode('|',$_POST['attri_value5']);
            
            // attributes key
            $attrikeyv = isset($_POST['attri_key']) ? strtolower(sanitize_key($_POST['attri_key'])) : '';
            $attrikeyv1 = isset($_POST['attri_key1']) ? strtolower(sanitize_key($_POST['attri_key1'])) : '';
            $attrikeyv2 = isset($_POST['attri_key2']) ? strtolower(sanitize_key($_POST['attri_key2'])) : '';
            $attrikeyv3 = isset($_POST['attri_key3']) ? strtolower(sanitize_key($_POST['attri_key3'])) : '';
            $attrikeyv4 = isset($_POST['attri_key4']) ? strtolower(sanitize_key($_POST['attri_key4'])) : '';
            $attrikeyv5 = isset($_POST['attri_key5']) ? strtolower(sanitize_key($_POST['attri_key5'])) : '';

            //echo $attribute2[2];

            $variation_ids = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
            $variation_ids1 = isset($_POST['variation_id1']) ? intval($_POST['variation_id1']) : 0;
            $variation_ids2 = isset($_POST['variation_id2']) ? intval($_POST['variation_id2']) : 0;
            $variation_ids3 = isset($_POST['variation_id3']) ? intval($_POST['variation_id3']) : 0;
            $variation_ids4 = isset($_POST['variation_id4']) ? intval($_POST['variation_id4']) : 0;
            $variation_ids5 = isset($_POST['variation_id5']) ? intval($_POST['variation_id5']) : 0;

            //exit;

            $variations = array();

            if (!empty($variation_ids)) {
                $variation = array(
                'ID' => $variation_ids,
                'price' => $price,
                'regular_price' => $regular_price,
                'sale_price' => $sale_price,
                'stock_status' => $stock_status,
                'weight' => $weight,
                'length' => $length,
                'width' => $width,
                'height' => $height,
                'stock' => $stock,
                'description' => $description,
                'sku' => $sku,
                'backorders' => $backorders,
                'low_stock_amount' => $low_stock_amount,
                'download_limit' => $download_limit,
                'download_expiry' => $download_expiry,
                'image_urls' => $image_urls,
                'attribute' => isset($attribute[0]) ? $attribute[0]: '',
                'attribute1' => isset($attribute1[0]) ? $attribute1[0]: '',
                'attribute2' => isset($attribute2[0]) ? $attribute2[0]: '',
                'attribute3' => isset($attribute3[0]) ? $attribute3[0]: '',
                'attribute4' => isset($attribute4[0]) ? $attribute4[0]: '',
                'attribute5' => isset($attribute5[0]) ? $attribute5[0]: '',

                );
                $variations[] = $variation;
            }
            
            // Update variations
            foreach ($variations as $variation_data) {
               $variation_id = $variation_data['ID'];

                $variation_args = array(
                    'ID' => $variation_id,
                    'post_title' => 'Variation #' . $product_id,
                    'post_parent' => $product_id,
                    'post_type' => 'product_variation',
                    'post_status' => 'publish',
                );
                // Update the variation post
                wp_update_post($variation_args);
                if ($variation_id) {
        
                    // Set the attributes using update_post_meta
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv, $variation_data['attribute']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv1, $variation_data['attribute1']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv2, $variation_data['attribute2']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv3, $variation_data['attribute3']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv4, $variation_data['attribute4']);
                    update_post_meta($variation_id, 'attribute_'.$attrikeyv5, $variation_data['attribute5']);
        
                    // Set other variation data
                    update_post_meta($variation_id, '_manage_stock', 'yes');
                    update_post_meta($variation_id, '_price', $variation_data['price']);
                    update_post_meta($variation_id, '_regular_price', $variation_data['regular_price']);
                    update_post_meta($variation_id, '_sale_price', $variation_data['sale_price']);
                    update_post_meta($variation_id, '_stock_status', $variation_data['stock_status']);
                    update_post_meta($variation_id, '_weight', $variation_data['weight']);
                    update_post_meta($variation_id, '_length', $variation_data['length']);
                    update_post_meta($variation_id, '_width', $variation_data['width']);
                    update_post_meta($variation_id, '_height', $variation_data['height']);
                    update_post_meta($variation_id, '_stock', $variation_data['stock']);
                    update_post_meta($variation_id, '_variation_description', $variation_data['description']);
                    update_post_meta($variation_id, '_sku', $variation_data['sku']);
                    update_post_meta($variation_id, '_backorders', $variation_data['backorders']);
                    update_post_meta($variation_id, '_low_stock_amount', $variation_data['low_stock_amount']);
                    update_post_meta($variation_id, '_wc_variation_file_names'.[$variation_id], $variation_data['file_names']);
                    update_post_meta($variation_id, '_wc_variation_file_urls'.[$variation_id], $variation_data['file_urls']);
                    update_post_meta($variation_id, '_download_limit', $variation_data['download_limit']);
                    update_post_meta($variation_id, '_download_expiry', $variation_data['download_expiry']);
                    // Update custom field
                    $custom_field_value = isset($_POST['custom_field']) ? sanitize_text_field($_POST['custom_field']) : '';
                    update_post_meta($variation_id, 'custom_field', $custom_field_value);
                    // Set variation image
                    if (isset($variation_data['image_urls'])) {
                        $image_url = $variation_data['image_urls'];

                        // Use wp_remote_get() to fetch the image
                        $response = wp_remote_get($image_url);

                        if (is_wp_error($response)) {
                            // Handle error
                            $error_message = $response->get_error_message();
                            echo esc_html("Something went wrong: $error_message");
                        } else {
                            // Get the response body
                            $image_data = wp_remote_retrieve_body($response);

                            if (!empty($image_data)) {
                                $image_name = basename($image_url);
                                $upload_file = wp_upload_bits($image_name, null, $image_data);

                                if (!$upload_file['error']) {
                                    $file_path = $upload_file['file'];
                                    $file_name = basename($file_path);
                                    $attachment = array(
                                        'post_mime_type' => wp_check_filetype($file_name)['type'],
                                        'post_title'     => preg_replace('/\.[^.]+$/', '', $file_name),
                                        'post_content'   => '',
                                        'post_status'    => 'inherit'
                                    );

                                    $attach_id = wp_insert_attachment($attachment, $file_path, $variation_id);

                                    if (!is_wp_error($attach_id)) {
                                        // Set the variation's featured image (thumbnail)
                                        set_post_thumbnail($variation_id, $attach_id);
                                    }
                                } else {
                                    // Handle upload error
                                    echo esc_html("Image upload failed: " . $upload_file['error']);
                                }
                            } else {
                                // Handle empty image data
                                echo esc_html("Failed to retrieve image data.");
                            }
                        }
                    }
                }
            }
            if ($variation_id) {   
                return new WP_Error('message', 'Variations update successfully!', array(
                    'status' => 200,
                    'vid' => $variation_id,
                ));
            } else {
                // Handle errors if variations were not created
                echo 'Failed to create variations!';
            }      
        }

    // Thumbnail images api
    function attach_product_thumbnail($post_id, $url, $flag) {
        $image_url = $url;
        $url_array = explode('/', $url);
        $image_name = $url_array[count($url_array) - 1];
    
        // Fetch the image data using wp_remote_get()
        $response = wp_remote_get($image_url);
    
        if (is_wp_error($response)) {
            // Handle the error
            $error_message = $response->get_error_message();
            echo esc_html("Something went wrong: $error_message");
            return;
        }
    
        // Get the image data from the response body
        $image_data = wp_remote_retrieve_body($response);
    
        if (empty($image_data)) {
            // Handle empty image data
            echo esc_html("Failed to retrieve image data.");
            return;
        }
    
        $allowedExts = array("png", "jpg", "jpeg");
        $temp = explode(".", $image_name);
        $extension = end($temp);
    
        if (!in_array($extension, $allowedExts)) {
            // Check file extension
            echo esc_html("Unsupported file type: $extension");
            return;
        }
    
        $upload_dir = wp_upload_dir(); // Set upload folder
        $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
        $filename = basename($unique_file_name); // Create image file name
    
        // Check folder permission and define file location
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
    
        // Get the WP_Filesystem instance
        if (false === ($wp_filesystem = WP_Filesystem())) {
            echo esc_html("Could not initialize the WP_Filesystem.");
            return;
        }

        // Create the image file on the server using WP_Filesystem method
        $wp_filesystem->put_contents($file, $image_data, FS_CHMOD_FILE);

        // Check image file type
        $wp_filetype = wp_check_filetype($filename, null);
    
        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
    
        // Create the attachment
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    
        // Include image.php
        require_once ABSPATH . 'wp-admin/includes/image.php';
    
        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        // Assign metadata to attachment
        wp_update_attachment_metadata($attach_id, $attach_data);
    
        // Assign to feature image
        if ($flag == 0) {
            // And finally assign featured image to post
            set_post_thumbnail($post_id, $attach_id);
        }
    
        // Assign to the product gallery
        // $flag == 1
        if ($flag == 1) {
            // Add gallery image to product
            $attach_id_array = get_post_meta($post_id, '_product_image_gallery', true);
            $attach_id_array .= ',' . $attach_id;
            update_post_meta($post_id, '_product_image_gallery', $attach_id_array);
        }
    }
    

    function delete_product($request){
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
        	$product = wc_get_product($request['product_id']);
        	if(empty($product)){
                // Translators: %1$s is the entity (e.g., product), %2$d is the ID.
                return new WP_Error(400, sprintf(__('No %1$s is associated with #%2$d', 'woocommerce'), 'product', $request['product_id']));

        	}

        	$deleted = wp_delete_post($request['product_id']);
        	if($deleted) {
        		 return new WP_Error('message', 'Product deleted.', array(
                'status' => 200
            ));
        	}
            else {
                // Translators: %1$s is the entity (e.g., product).
                return new WP_Error(404, sprintf(__('This %1$s cannot be deleted', 'woocommerce'), 'product'));
            }
            
        	
        }else{
        	return new WP_Error('error', 'Sorry, you are not allowed to do.please check authentication', array(
                'status' => 404
            ));
        }
     	
    }

    function add_variation_image($product_id, $url) {
        $image_url = $url;
        if (!empty($url)) {
            $url_array = explode('/', $url);
        }
    
        $image_name = $url_array[count($url_array) - 1];
    
        // Fetch the image data using wp_remote_get()
        $response = wp_remote_get($image_url);
    
        if (is_wp_error($response)) {
            // Handle the error
            $error_message = $response->get_error_message();
            echo esc_html("Something went wrong: $error_message");
            return;
        }
    
        // Get the image data from the response body
        $image_data = wp_remote_retrieve_body($response);
    
        if (empty($image_data)) {
            // Handle empty image data
            echo esc_html("Failed to retrieve image data.");
            return;
        }
    
        $allowedExts = array("png", "jpg", "jpeg");
        $temp = explode(".", $image_name);
        $extension = end($temp);
    
        if (!in_array($extension, $allowedExts)) {
            // Check file extension
            echo esc_html("Unsupported file type: $extension");
            return;
        }
    
        $upload_dir = wp_upload_dir(); // Set upload folder
        $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
        $filename = basename($unique_file_name); // Create image file name
    
        // Get the WP_Filesystem instance
        if (false === ($wp_filesystem = WP_Filesystem())) {
            echo esc_html("Could not initialize the WP_Filesystem.");
            return;
        }
    
        // Define the file location
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
    
        // Create the image file on the server using WP_Filesystem method
        $wp_filesystem->put_contents($file, $image_data, FS_CHMOD_FILE);
    
        // Check image file type
        $wp_filetype = wp_check_filetype($filename, null);
    
        // Set attachment data
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
    
        // Create the attachment
        $attach_id = wp_insert_attachment($attachment, $file, $product_id);
    
        if (!is_wp_error($attach_id)) {
            // Include image.php
            require_once ABSPATH . 'wp-admin/includes/image.php';
    
            // Define attachment metadata
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    
            // Assign metadata to attachment
            wp_update_attachment_metadata($attach_id, $attach_data);
    
            // Assign to the product's featured image
            update_post_meta($product_id, '_thumbnail_id', $attach_id);
        } else {
            // Handle attachment creation error
            echo esc_html("Failed to create attachment.");
        }
    }
    

    // its used to update product
    function update_product($request){
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
        	$product = wc_get_product($request['product_id']);
        	if(empty($product)){
                // Translators: %1$s is the entity (e.g., product), %2$d is the ID.
                return new WP_Error(400, sprintf(__('No %1$s is associated with #%2$d', 'woocommerce'), 'product', $request['product_id']));

        	}
        	$param = $request->get_params();
           // $post_title = $param['name'];
            $post_title = $param['name'] ? $param['name'] : $param['name'];
            $post_content = $param['description'] ? $param['description'] : $param['description'];
            $short_description = $param['short_description']? $param['short_description'] : $param['short_description'] ;
            $status = $param['status'] ? $param['status'] : $param['status'];
            //meta key
            $metakey = isset($_POST['meta_key']) ? sanitize_key($_POST['meta_key']) : '';
            $metakey1 = isset($_POST['meta_key1']) ? sanitize_key($_POST['meta_key1']) : '';
            $metakey2 = isset($_POST['meta_key2']) ? sanitize_key($_POST['meta_key2']) : '';
            $metakey3 = isset($_POST['meta_key3']) ? sanitize_key($_POST['meta_key3']) : '';
            $metakey4 = isset($_POST['meta_key4']) ? sanitize_key($_POST['meta_key4']) : '';
            $metakey5 = isset($_POST['meta_key5']) ? sanitize_key($_POST['meta_key5']) : '';
            $metakey6 = isset($_POST['meta_key6']) ? sanitize_key($_POST['meta_key6']) : '';
            $metakey7 = isset($_POST['meta_key7']) ? sanitize_key($_POST['meta_key7']) : '';
            $metakey8 = isset($_POST['meta_key8']) ? sanitize_key($_POST['meta_key8']) : '';
            $metakey9 = isset($_POST['meta_key9']) ? sanitize_key($_POST['meta_key9']) : '';
            $metakey10 = isset($_POST['meta_key10']) ? sanitize_key($_POST['meta_key10']) : '';
            $metakey11 = isset($_POST['meta_key11']) ? sanitize_key($_POST['meta_key11']) : '';
            $metakey12 = isset($_POST['meta_key12']) ? sanitize_key($_POST['meta_key12']) : '';
            $metakey13 = isset($_POST['meta_key13']) ? sanitize_key($_POST['meta_key13']) : '';
            $metakey14 = isset($_POST['meta_key14']) ? sanitize_key($_POST['meta_key14']) : '';
            $metakey15 = isset($_POST['meta_key15']) ? sanitize_key($_POST['meta_key15']) : '';
            $metakey16 = isset($_POST['meta_key16']) ? sanitize_key($_POST['meta_key16']) : '';
            $metakey17 = isset($_POST['meta_key17']) ? sanitize_key($_POST['meta_key17']) : '';
            $metakey18 = isset($_POST['meta_key18']) ? sanitize_key($_POST['meta_key18']) : '';
            $metakey19 = isset($_POST['meta_key19']) ? sanitize_key($_POST['meta_key19']) : '';
            //meta value
            $metavalue = isset($_POST['meta_value']) ? sanitize_text_field($_POST['meta_value']) : '';
            $metavalue1 = isset($_POST['meta_value1']) ? sanitize_text_field($_POST['meta_value1']) : '';
            $metavalue2 = isset($_POST['meta_value2']) ? sanitize_text_field($_POST['meta_value2']) : '';
            $metavalue3 = isset($_POST['meta_value3']) ? sanitize_text_field($_POST['meta_value3']) : '';
            $metavalue4 = isset($_POST['meta_value4']) ? sanitize_text_field($_POST['meta_value4']) : '';
            $metavalue5 = isset($_POST['meta_value5']) ? sanitize_text_field($_POST['meta_value5']) : '';
            $metavalue6 = isset($_POST['meta_value6']) ? sanitize_text_field($_POST['meta_value6']) : '';
            $metavalue7 = isset($_POST['meta_value7']) ? sanitize_text_field($_POST['meta_value7']) : '';
            $metavalue8 = isset($_POST['meta_value8']) ? sanitize_text_field($_POST['meta_value8']) : '';
            $metavalue9 = isset($_POST['meta_value9']) ? sanitize_text_field($_POST['meta_value9']) : '';
            $metavalue10 = isset($_POST['meta_value10']) ? sanitize_text_field($_POST['meta_value10']) : '';
            $metavalue11 = isset($_POST['meta_value11']) ? sanitize_text_field($_POST['meta_value11']) : '';
            $metavalue12 = isset($_POST['meta_value12']) ? sanitize_text_field($_POST['meta_value12']) : '';
            $metavalue13 = isset($_POST['meta_value13']) ? sanitize_text_field($_POST['meta_value13']) : '';
            $metavalue14 = isset($_POST['meta_value14']) ? sanitize_text_field($_POST['meta_value14']) : '';
            $metavalue15 = isset($_POST['meta_value15']) ? sanitize_text_field($_POST['meta_value15']) : '';
            $metavalue16 = isset($_POST['meta_value16']) ? sanitize_text_field($_POST['meta_value16']) : '';
            $metavalue17 = isset($_POST['meta_value17']) ? sanitize_text_field($_POST['meta_value17']) : '';
            $metavalue18 = isset($_POST['meta_value18']) ? sanitize_text_field($_POST['meta_value18']) : '';
            $metavalue19 = isset($_POST['meta_value19']) ? sanitize_text_field($_POST['meta_value19']) : '';
            
            // Define the product update data
            /*$update_product = array(
                'ID' => $product->get_id(),
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_excerpt' => $short_description,
                'post_status' => $status,
                'post_type' => 'product',
                'attributes' => $attributes,
            );
           // print_r($update_product);
            //exit;
            $product_id = $product->get_id();
        	$response = wp_update_post($update_product);*/
            $update_product = array(
                'ID' => $product->get_id(),
                'post_type' => 'product',
                'attributes' => $attributes,
            );
            
            // Check if $post_title is not empty before adding it to $update_product
            if (!empty($post_title)) {
                $update_product['post_title'] = $post_title;
            }
            if (!empty($status)) {
                $update_product['post_status'] = $status;
            }
            if (!empty($post_content)) {
                $update_product['post_content'] = $post_content;
            }
            if (!empty($short_description)) {
                $update_product['post_excerpt'] = $short_description;
            }
            
            $product_id = $product->get_id();

            $response = wp_update_post($update_product);

        	$visibility = $param['_visibility'] ? $param['_visibility']:get_post_meta($product_id, '_visibility',true);
        	$regular_price = $param['_regular_price'] ? $param['_regular_price']: get_post_meta($product_id, '_regular_price',true);

            $total_sales = $param['total_sales'] ? $param['total_sales']: get_post_meta($product_id, 'total_sales', true);
            $downloadable = $param['_downloadable'] ? $param['_downloadable'] :get_post_meta($product_id, '_downloadable', true);
            $virtual =  $param['_virtual'] ? $param['_virtual']:get_post_meta($product_id, '_virtual', true);
            $sale_price = $param['_sale_price'] ? $param['_sale_price'] :get_post_meta($product_id, '_sale_price', true);
            $purchase_note = $param['_purchase_note'] ? $param['_purchase_note'] : get_post_meta($product_id, '_purchase_note', true);
            $featured = $param['_featured'] ? $param['_featured'] : get_post_meta($product_id, '_featured', true);
            $weight = $param['_weight'] ? $param['_weight'] : get_post_meta($product_id, '_weight', true);
            $length = $param['_length'] ? $param['_length'] : get_post_meta($product_id, '_length', true);
            $width = $param['_width'] ? $param['_width'] : get_post_meta($product_id, '_width', true);
            $height =  $param['_height'] ? $param['_height'] : get_post_meta($product_id, '_height', true);
            $sku = $param['_sku'] ? $param['_sku'] :get_post_meta($product_id, '_sku', true);
            $sale_price_dates_from  = $param['_sale_price_dates_from'] ? $param['_sale_price_dates_from']: get_post_meta($product_id, '_sale_price_dates_from', true);
            $sale_price_dates_to = $param['_sale_price_dates_to'] ? $param['_sale_price_dates_to'] :get_post_meta($product_id, '_sale_price_dates_to', true);
            $sold_individually = $param['_sold_individually'] ? $param['_sold_individually'] : get_post_meta($product_id, '_sold_individually',true);
            $manage_stock = $param['_manage_stock'] ? $param['_manage_stock'] :get_post_meta($product_id, '_manage_stock', true);
            $stock = $param['_stock'] ? $param['_stock'] : get_post_meta($product_id, '_stock',true);

            $short_description = $param['short_description'] ? $short_description : get_post_meta($product_id, 'short_description');

           $categories = $param['categories'];
           //$categories = explode(',', $categories);
           $categories = array();
            if (is_array($category) && !empty($category)) {
                foreach ($category as $id)
                {
                    $cat = get_term_by('id', $id, 'product_cat');
                    array_push($categories, $cat->slug);
                }
            }
            if (!empty($attrikey) && !empty($attrivalue)) {
              update_post_meta($product_id, '_product_attributes',  $attributes , true);
            } else {
                update_post_meta($product_id, '_product_attributes',  $attributes);
            }

            if($param['images']){
                $attach_id_array = update_post_meta($product_id,'_product_image_gallery', array());
                foreach($param['images'] as $url){
                    $this->attach_product_thumbnail($product_id, $url['src'], 0);
                }
            }
            $categorie = $param['categories'];
            $categories = explode(',', $categorie);

            if($categorie!=""){
             
              wp_set_object_terms($product_id, (int)$categorie, 'product_cat', false);

            } else {
              
              wp_set_object_terms($product_id, $categorie, 'product_cat', true);
            }
            $stock = $param['_stock'];
            $short_description1 = $short_description ? $short_description : get_post_meta($product_id, 'short_description');

            // Define an array of custom fields
            $custom_fields = array(
                    $metakey => $metavalue,
                    $metakey1 => $metavalue1,
                    $metakey2 => $metavalue2,
                    $metakey3 => $metavalue3,
                    $metakey4 => $metavalue4,
                    $metakey5 => $metavalue5,
                    $metakey6 => $metavalue6,
                    $metakey7 => $metavalue7,
                    $metakey8 => $metavalue8,
                    $metakey9 => $metavalue9,
                    $metakey10 => $metavalue10,
                    $metakey11 => $metavalue11,
                    $metakey12 => $metavalue12,
                    $metakey13 => $metavalue13,
                    $metakey14 => $metavalue14,
                    $metakey15 => $metavalue15,
                    $metakey16 => $metavalue16,
                    $metakey17 => $metavalue17,
                    $metakey18 => $metavalue18,
                    $metakey19 => $metavalue19
            );

            // Add custom fields to the product
            foreach ($custom_fields as $key => $value) {
                update_post_meta($product_id, $key, $value);
            }

            wp_set_object_terms($product_id, 'simple', 'product_type');
            update_post_meta($product_id, '_visibility', $visibility);
            update_post_meta($product_id, '_stock_status', 'instock');
            update_post_meta($product_id, 'total_sales', $total_sales);
            update_post_meta($product_id, '_downloadable', $downloadable);
            update_post_meta($product_id, '_virtual', $virtual);
            update_post_meta($product_id, '_regular_price', $regular_price);
            update_post_meta($product_id, '_sale_price', $sale_price);
            update_post_meta($product_id, '_purchase_note', $purchase_note);
            update_post_meta($product_id, '_featured', $featured);
            update_post_meta($product_id, '_weight', $weight);
            update_post_meta($product_id, '_length', $length);
            update_post_meta($product_id, '_width', $width);
            update_post_meta($product_id, '_height', $height);
            update_post_meta($product_id, '_sku', $sku);
            update_post_meta($product_id, '_sale_price_dates_from', $sale_price_dates_from);
            update_post_meta($product_id, '_sale_price_dates_to', $sale_price_dates_to);
            update_post_meta($product_id, '_price', $regular_price);
            update_post_meta($product_id, '_sold_individually', $sold_individually);
            update_post_meta($product_id, '_manage_stock', $manage_stock);
            update_post_meta($product_id, '_backorders', $backorders);
            if($stock!='') {
            update_post_meta($product_id, '_stock', $stock, false);
            } else {
             update_post_meta($product_id, '_stock', $stock, true);
            }
            update_post_meta($product_id, '_downloadable_files', $downloadable_files);
            update_post_meta($product_id, '_download_limit', $download_limit);
            update_post_meta($product_id, '_download_expiry', $download_expiry);
            update_post_meta($product_id, '_download_type', $download_type);  

            // Update stock status based on stock quantity
            if ($manage_stock && isset($param['_stock'])) {
                if ($param['_stock'] > 0) {
                    wc_update_product_stock_status($product_id, 'instock');
                } else {
                    wc_update_product_stock_status($product_id, 'outofstock');
                }
            }
            
            function upload_image_from_url($image_url, $post_id, $description = null) {
                $response = wp_remote_get($image_url);
            
                if (is_wp_error($response)) {
                    return $response;
                }
            
                $image = wp_upload_bits(basename($image_url), null, $response['body']);
            
                if (empty($image['error'])) {
                    $file_path = $image['file'];
                    $file_name = basename($file_path);
                    
                    $attachment = array(
                        'post_mime_type' => wp_check_filetype($file_name)['type'],
                        'post_title' => sanitize_file_name($file_name),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );
            
                    $attach_id = wp_insert_attachment($attachment, $file_path, $post_id);
                    
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                    wp_update_attachment_metadata($attach_id, $attach_data);
            
                    return $attach_id;
                }
            
                return null;
            }

            $product_id =  $_POST['product_id'];// Replace with the actual product ID
            $featured_image_url = $_POST['featured'];  // Replace with the featured image URL
            $product_gallery_image_urls =   explode(',',$_POST['src']);
            
            function update_product_images($product_id, $featured_image_url, $gallery_image_urls) {
                if (filter_var($featured_image_url, FILTER_VALIDATE_URL)) {
                    $featured_image_id = upload_image_from_url($featured_image_url, $product_id, 'Featured Image Update');
                    if (!is_wp_error($featured_image_id)) {
                        set_post_thumbnail($product_id, $featured_image_id);
                    }
                }
            
                if (!empty($gallery_image_urls)) {
                    $gallery_image_ids = array();
                    foreach ($gallery_image_urls as $gallery_image_url) {
                        if (filter_var($gallery_image_url, FILTER_VALIDATE_URL)) {
                            $gallery_image_id = upload_image_from_url($gallery_image_url, $product_id, 'Gallery Image Update');
                            if (!is_wp_error($gallery_image_id)) {
                                $gallery_image_ids[] = $gallery_image_id;
                            }
                        }
                    }
                    if (!empty($gallery_image_ids)) {
                        update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery_image_ids));
                    }
                }
            }
            
            // Usage: Provide the product ID, the featured image URL, and the image URLs to update the product
            
            update_product_images($product_id, $featured_image_url, $product_gallery_image_urls);
            
           
            return new WP_Error('message', 'Product updated.', array(
                'status' => 200
            ));
        }
        else
        {
        	return new WP_Error('error', 'Sorry, you are not allowed to do please check authentication', array(
                'status' => 404
            ));
        }
     	
     }

}
new Wc_Product_PI();

// Add custom field input @ Product Data > Variations > Single Variation
add_action( 'woocommerce_product_after_variable_attributes', 'appy_pie_connect_custom_field_to_variations', 10, 3 );
 
function appy_pie_connect_custom_field_to_variations( $loop, $variation_data, $variation ) {
  woocommerce_wp_text_input( array(
    'id' => 'custom_field[' . $loop . ']',
    'class' => 'short',
    'label' => __( 'Custom Field', 'woocommerce' ),
    'value' => get_post_meta( $variation->ID, 'custom_field', true )
  ));
}
?>