<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

class ForgetPasswordApi
{
    protected $socks_auth_key;

    function __construct()
    {
        $this->socks_auth_key = 'mGB3SskjO2';
        add_action('rest_api_init',[$this,'socks_rest_request_pass_api']);
        add_action('rest_api_init',[$this,'socks_rest_request_check_api']);
        add_action('rest_api_init',[$this,'socks_rest_request_change_pass_api']);
    }

    function socks_rest_request_pass_api(){
        register_rest_route('wp/v3', 'users/new/password/request', array(
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => [$this,'socks_wc_rest_pass_req_endpoint_permission_handler'],
            'callback' => [$this,'socks_wc_rest_pass_req_endpoint_handler'],
        ));
    }

    function socks_rest_request_check_api(){
        register_rest_route('wp/v3', 'users/new/password/request/verify/check', array(
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => [$this,'socks_wc_rest_pass_req_endpoint_permission_handler'],
            'callback' => [$this,'socks_wc_rest_req_check_endpoint_handler'],
        ));
    }

    function socks_rest_request_change_pass_api(){
        register_rest_route('wp/v3', 'users/new/password/request/change', array(
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => [$this,'socks_wc_rest_pass_req_endpoint_permission_handler'],
            'callback' => [$this,'socks_wc_rest_req_change_endpoint_handler'],
        ));
    }

    function socks_wc_rest_pass_req_endpoint_permission_handler(WP_REST_Request $request){
        if ( $this->socks_auth_key == $request->get_param( 'auth' ) ) {
            return true;
        } else {
            return false;
        }
    }

    function socks_wc_rest_pass_req_endpoint_handler(WP_REST_Request $request = null){
        $response = array();
        $parameters = $request->get_body_params();
        $email = sanitize_text_field($parameters['email']);
        $fourRandomDigit = mt_rand(1000,9999);
        $email_temp = new SignUpApi();
        $exists = email_exists( $email );
        $error = new WP_Error();

        if (empty($email)) {
            $error->add(401, __("Email field is required.", 'socks'), array('status' => 400));
            return $error;
        }

        if ( $exists ) {
            update_user_meta( $exists, '_verify_key', $fourRandomDigit );
            $content = 'Please enter the verification code to confirm your identity. Your verification code is : <strong>'.$fourRandomDigit.'</strong>';
            $subject = 'Password change request from ' . get_bloginfo( 'name' );
            $email_temp->socks_verify_email($email,__($content,'socks'),__($subject,'socks'));
            $response['user_id'] = $exists;
            $response['verify_code'] = $fourRandomDigit;
            $response['message'] = __('Please verify your identity.','socks');
        }else{
            $error->add(400, __("Ops ! The user has no account here.", 'socks'), array('status' => 400));
            return $error;
        }

        return new WP_REST_Response($response, 200);
    }

    function socks_wc_rest_req_check_endpoint_handler(WP_REST_Request $request = null){
        $response = array();
        $parameters = $request->get_body_params();
        $user_id = sanitize_text_field($parameters['user_id']);
        $verify_code = sanitize_text_field($parameters['verify_code']);
        $verify_status = get_user_meta($user_id,'_verify_key');
        $error = new WP_Error();

        if ($verify_status[0] == $verify_code){
            update_user_meta( (int)$user_id, '_verify_key', '' );
            $response['user_id'] = (int)$user_id;
            $response['message'] = __('User verified','socks');
        }else{
            $error->add(400, __("You are not authorized to change the password !.", 'socks'), array('status' => 400));
            return $error;
        }

        return new WP_REST_Response($response, 200);
    }

    function socks_wc_rest_req_change_endpoint_handler(WP_REST_Request $request = null){
        $response = array();
        $parameters = $request->get_body_params();
        $user_id = sanitize_text_field($parameters['user_id']);
        $password = sanitize_text_field($parameters['password']);
        $user_info = get_userdata((int)$user_id);
        $exists = email_exists( $user_info->user_email );
        $error = new WP_Error();

        if ($exists){
            wp_set_password( $password, $user_id );
            $response['user_id'] = $user_id;
            $response['message'] = __('Your password change successfully','socks');
        }else{
            $error->add(400, __("User not found !.", 'socks'), array('status' => 400));
            return $error;
        }

        return new WP_REST_Response($response, 200);
    }
}

new ForgetPasswordApi();