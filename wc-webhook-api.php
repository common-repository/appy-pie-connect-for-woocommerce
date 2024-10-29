<?php
/**
 * Endpoint Create Webhook
*/

if (!defined('ABSPATH')) exit;

add_action('rest_api_init', function () {
    register_rest_route('wp/v3', 'insert_webhook', array(
        'methods' => 'POST',
        'callback' => 'appy_pie_connect_insert_webhook_data',
        'permission_callback' => 'appy_pie_connect_authenticate_users_permission',
    ));

    register_rest_route('wp/v3', 'update_webhook/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'appy_pie_connect_update_webhook_data',
        'permission_callback' => 'appy_pie_connect_authenticate_users_permission',
    ));

    register_rest_route('wp/v3', 'delete_webhook/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'appy_pie_connect_delete_webhook_data',
        'permission_callback' => 'appy_pie_connect_authenticate_users_permission',
    ));
});

// Permission callback function
function appy_pie_connect_authenticate_users_permission($request) {
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

    if (!empty($user_info)) {
        $userRole = implode(', ', $user_info->roles);

        if ($userRole == 'administrator' && wp_check_password($password, $user->data->user_pass, $user->ID)) {
            return true;
        }
    }

    return new WP_Error('rest_forbidden', esc_html__('Invalid username or password.', 'text-domain'), array('status' => 401));
}

// Create Webhook in Woocommerce
function appy_pie_connect_create_woocommerce_webhook($data) {
    // Check if WooCommerce is active
    if (in_array('woocommerce/woocommerce.php', get_option('active_plugins', array()))) {
        // Load WooCommerce
        if (!class_exists('WooCommerce')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
            include_once WC()->plugin_path() . '/includes/class-woocommerce.php';
        }

        // Check if the required class exists
        if (class_exists('WC_Webhook')) {

            $existing_webhook = appy_pie_connect_get_existing_webhook_by_delivery_url(esc_url_raw($data['delivery_url']));

            if ($existing_webhook) {
                // Webhook with the same delivery URL already exists
                $response_message = 'Webhook already exists with ID: ' . $existing_webhook['webhook_id'] . PHP_EOL . 'Delivery URL: ' . $existing_webhook['delivery_url'];
                return new WP_REST_Response($response_message, 200);
            }

            $webhook = new WC_Webhook();
            $webhook->set_user_id(1);
            $webhook->set_name(sanitize_text_field($data['name']));
            $webhook->set_status(sanitize_text_field($data['status']));
            $webhook->set_topic(sanitize_text_field($data['topic']));
            $webhook->set_delivery_url(esc_url_raw($data['delivery_url']));
            $webhook->set_secret(sanitize_text_field($data['secret']));

            // Additional properties
            $webhook->set_api_version(isset($data['api_version']) ? absint($data['api_version']) : 3);
            $webhook->set_pending_delivery(false);

            // Save the webhook
            $webhook_id = $webhook->save();

            if ($webhook_id) {
                return new WP_REST_Response($webhook_id, 200);
            } else {
                return new WP_REST_Response('Error creating webhook', 500);
            }
        } else {
            return new WP_REST_Response('WC_Webhook class not available in WooCommerce', 500);
        }
    } else {
        return new WP_REST_Response('WooCommerce is not active', 500);
    }
}

// check webhook already exists or not
function appy_pie_connect_get_existing_webhook_by_delivery_url($delivery_url) {
    global $wpdb;

    // Query the wp_woocommerce_webhooks table for an existing webhook
    $existing_webhook = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}wc_webhooks WHERE delivery_url = %s", $delivery_url),
        ARRAY_A
    );

    return $existing_webhook;
}

// Update Webhook in Woocommerce
function appy_pie_connect_update_woocommerce_webhook($webhook_id, $data) {
    $webhook_id = absint($webhook_id);

    // Check if the webhook exists
    $webhook = wc_get_webhook($webhook_id);

    if ($webhook) {
        // Update properties
        $webhook->set_name(isset($data['name']) ? sanitize_text_field($data['name']) : $webhook->get_name());
        $webhook->set_status(isset($data['status']) ? sanitize_text_field($data['status']) : $webhook->get_status());
        $webhook->set_topic(isset($data['topic']) ? sanitize_text_field($data['topic']) : $webhook->get_topic());
        $webhook->set_delivery_url(isset($data['delivery_url']) ? esc_url_raw($data['delivery_url']) : $webhook->get_delivery_url());
        $webhook->set_secret(isset($data['secret']) ? sanitize_text_field($data['secret']) : $webhook->get_secret());

        // Save the updated webhook
        $updated = $webhook->save();

        if ($updated) {
            return new WP_REST_Response('Webhook updated successfully! ID: ' . $webhook_id, 200);
        } else {
            return new WP_REST_Response('Error updating webhook', 500);
        }
    } else {
        return new WP_REST_Response('Webhook not found', 404);
    }
}

// Delete Webhook from Woocommerce
function appy_pie_connect_delete_woocommerce_webhook($webhook_id) {
    global $wpdb;

    $webhook_id = absint($webhook_id);

    // Check if the webhook exists
    $webhook = wc_get_webhook($webhook_id);

    if ($webhook) {
        // Attempt to delete the webhook from the dedicated table
        $result = $wpdb->delete($wpdb->prefix . 'wc_webhooks', array('webhook_id' => $webhook_id), array('%d'));

        if ($result) {
            return new WP_REST_Response('Webhook deleted successfully! ID: ' . $webhook_id, 200);
        } else {
            $error_message = 'Error deleting webhook: ' . $webhook_id;
            error_log($error_message);
            return new WP_REST_Response($error_message, 500);
        }
    } else {
        return new WP_REST_Response('Webhook not found', 404);
    }
}

// Insert webhook data in Woocommerce Webhook
function appy_pie_connect_insert_webhook_data($request) {
    $data = $request->get_params();

    return appy_pie_connect_create_woocommerce_webhook($data);
}

// Update webhook data in Woocommerce Webhook
function appy_pie_connect_update_webhook_data($request) {
    $webhook_id = $request->get_param('id');

    if (!$webhook_id) {
        return new WP_REST_Response('Webhook ID is missing in the request', 400);
    }

    $data = $request->get_params();
    return appy_pie_connect_update_woocommerce_webhook($webhook_id, $data);
}

// Delete webhook data from Woocommerce Webhook
function appy_pie_connect_delete_webhook_data($request) {
    $webhook_id = $request->get_param('id');

    if (!$webhook_id) {
        return new WP_REST_Response('Webhook ID is missing in the request', 400);
    }

    return appy_pie_connect_delete_woocommerce_webhook($webhook_id);
}