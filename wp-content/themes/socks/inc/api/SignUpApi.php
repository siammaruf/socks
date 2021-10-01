<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

class SignUpApi
{

    protected $sign_up_key;
    protected $sign_in_key;
    protected $sign_out_key;

    function __construct()
    {
        $this->sign_up_key = 'nNmf36jS6tk';
        $this->sign_in_key = 'uCgH4LmNtV';
        $this->sign_out_key = 'slKe2jlqq1';

        add_action('rest_api_init',[$this,'socks_rest_sign_up_api']);
        add_action('rest_api_init',[$this,'socks_rest_sign_up_active_api']);
        add_action('rest_api_init',[$this,'socks_rest_sign_in_api']);
        add_action('rest_api_init',[$this,'socks_rest_sign_out_api']);
    }

    public function socks_verify_email($user_email,$content,$subject = ''){
        $site_description = get_option( 'blogdescription' );
        $site_name = get_option( 'blogname' );
        $template = '
            <table style="border:none !important;width:100%;max-width:700px;margin:0 auto;">
                <tbody>
                    <tr>
                        <td style="text-align:center;;padding:20px;">
                            <a style="display:inline-block;width:150px;text-align:center;" href="'.home_url('/').'">
                                <img style="max-width:150px;height:auto;" src="https://www.socks.com.bd/wp-content/uploads/2021/07/logo.webp" alt="'.$site_name.'">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#000000;background:#ffffff;">
                            <h1>Confirm your email</h1>
                            <p>
                                '.$content.'
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;background:#fe5f27;padding:20px;">
                            <p style="display:block;font-size:13px;margin-bottom:3px;font-family:Arial,Helvetica,sans-serif;color:#ffffff;">'.$site_description.'</p>
                            <p style="display:block;font-size:13px;font-family:Arial,Helvetica,sans-serif;color:#ffffff;">&copy;'.date( 'Y' ).' <a href="'.home_url().'">'.$site_name.'</a>,
                                '.__( 'All rights reserved.', 'socks' ).'</p>
                        </td>
                    </tr>
                </tbody>
            </table>';

        $to      = $user_email;
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        wp_mail( $to, $subject, $template, $headers );

    }

    function socks_rest_sign_up_api(){
        register_rest_route('wp/v3', 'users/register', array(
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => [$this,'socks_wc_rest_user_endpoint_permission_handler'],
            'callback' => [$this,'socks_wc_rest_user_endpoint_handler'],
        ));
    }

    function socks_rest_sign_up_active_api(){
        register_rest_route('wp/v3', 'users/register/active', array(
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => [$this,'socks_wc_rest_user_endpoint_active_permission_handler'],
            'callback' => [$this,'socks_wc_rest_user_endpoint_active_handler'],
        ));
    }

    function socks_rest_sign_in_api(){
        register_rest_route('wp/v3', 'users/signin', array(
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => [$this,'socks_wc_rest_user_endpoint_signin_permission_handler'],
            'callback' => [$this,'socks_wc_rest_user_endpoint_signin_handler'],
        ));
    }

    function socks_rest_sign_out_api(){
        register_rest_route('wp/v3', 'users/signout', array(
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => [$this,'socks_wc_rest_user_endpoint_sign_out_permission_handler'],
            'callback' => [$this,'socks_wc_rest_user_endpoint_sign_out_handler'],
        ));
    }

    function socks_wc_rest_user_endpoint_permission_handler(WP_REST_Request $request){
        if ( $this->sign_up_key == $request->get_param( 'auth' ) ) {
            return true;
        } else {
            return false;
        }
    }

    function socks_wc_rest_user_endpoint_handler(WP_REST_Request $request = null){
        $response = array();
        //$parameters = $request->get_json_params();
        $parameters = $request->get_body_params();
        $username = sanitize_text_field($parameters['username']);
        $email = sanitize_text_field($parameters['email']);
        $password = sanitize_text_field($parameters['password']);
        $role = sanitize_text_field($parameters['role']);
        $error = new WP_Error();

        if (empty($username)) {
            $error->add(400, __("Username field 'username' is required.", 'socks'), array('status' => 400));
            return $error;
        }
        if (empty($email)) {
            $error->add(401, __("Email field 'email' is required.", 'socks'), array('status' => 400));
            return $error;
        }
        if (empty($password)) {
            $error->add(404, __("Password field 'password' is required.", 'socks'), array('status' => 400));
            return $error;
        }

         if (empty($role)) {
          $role = 'customer';
         } else {
             if ($GLOBALS['wp_roles']->is_role($role)) {
              // Silence is gold
             } else {
                $error->add(405, __("Role field 'role' is not a valid. Check your User Roles from Dashboard.", 'socks'), array('status' => 400));
                return $error;
             }
         }

        $user_id = username_exists($username);
        if (!$user_id && email_exists($email) == false) {
            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {

                $bytes = random_bytes(20);
                $random_code  = bin2hex($bytes);
                $fourRandomDigit = mt_rand(1000,9999);

                update_user_meta( $user_id, '_auth_key', $random_code );
                update_user_meta( $user_id, '_verify_key', $fourRandomDigit );
                update_user_meta( $user_id, '_verify_status', 'inactive' );

                $user = get_user_by('id', $user_id);
                $user->set_role($role);
                //$user->set_role('subscriber');
                // WooCommerce specific code
//                if (class_exists('WooCommerce')) {
//                    $user->set_role('customer');
//                }
                // Ger User Data (Non-Sensitive, Pass to front end.)
                $email_content = 'Please confirm your email . Your verification code is : <strong>'.$fourRandomDigit.'</strong>';
                $email_subject = 'Email Activation from ' . get_bloginfo( 'name' );
                $this->socks_verify_email(sanitize_text_field($parameters['email']),__($email_content,'socks'),__($email_subject,'socks'));

                $response['code'] = 200;
                $response['message'] = __("Registration successfully completed. Your email verification is pending. Please verify your email to continue", "socks");
                $response['response_key'] = __($random_code, "socks");
                $response['user_id'] = __($user_id, "socks");
                $response['verify_status'] = __("inactive", "socks");
                $response['verify_code'] = __($fourRandomDigit, "socks");
            } else {
                return $user_id;
            }
        } else {
            $error->add(406, __("Email already exists, please try again with another one", 'socks'), array('status' => 400));
            return $error;
        }
        return new WP_REST_Response($response, 200);
    }

    function socks_wc_rest_user_endpoint_active_permission_handler(WP_REST_Request $request){
        if ( $this->sign_up_key == $request->get_param( 'auth' ) ) {
            return true;
        } else {
            return false;
        }
    }

    function socks_wc_rest_user_endpoint_active_handler(WP_REST_Request $request = null){
        $response = array();
        $parameters = $request->get_body_params();
        $user_id = $parameters['user_id'];
        $activation_key = $parameters['activation_key'];

        $get_key = get_user_meta($user_id,'_verify_key');
        $error = new WP_Error();

        if ($get_key[0] != $activation_key){
            $error->add(400, __("You are not authorized. Key was not matched !", 'socks'), array('status' => 400));
            return $error;
        }else{
            $verify_status = get_user_meta($user_id,'_verify_status');
            if ($verify_status[0] != 'active'){
                update_user_meta( $user_id, '_verify_status', 'active' );
                $response['message'] = __("Your email verification request is successfully processed !", 'socks');
                $response['status'] = get_user_meta($user_id,'_verify_status')[0];
            }else{
                $response['message'] = __("Your email was already verified !", 'socks');
                $response['status'] = get_user_meta($user_id,'_verify_status')[0];
            }
            $response['auth_key'] = get_user_meta($user_id,'_auth_key')[0];
        }

        return new WP_REST_Response($response, 200);
    }

    function socks_wc_rest_user_endpoint_signin_permission_handler(WP_REST_Request $request){
        if ( $this->sign_in_key == $request->get_param( 'auth' ) ) {
            return true;
        } else {
            return false;
        }
    }

    function socks_wc_rest_user_endpoint_signin_handler(WP_REST_Request $request = null){
        $response = array();
        $parameters = $request->get_body_params();
        $username = sanitize_text_field($parameters['username']);
        $password = sanitize_text_field($parameters['password']);
        $bytes = random_bytes(20);
        $random_code  = bin2hex($bytes);

        $error = new WP_Error();

        if (empty($username)) {
            $error->add(400, __("Username or email is required.", 'socks'), array('status' => 400));
            return $error;
        }
        if (empty($password)) {
            $error->add(404, __("Password is required.", 'socks'), array('status' => 400));
            return $error;
        }

        $user = wp_authenticate( $username, $password );

        if ( !is_wp_error($user)) {
            $user_id = $user->ID;
            $verify_status = get_user_meta($user_id,'_verify_status');

            if ($verify_status[0] == 'active'){
                update_user_meta( $user_id, '_auth_key', $random_code );
                $response['message'] = __('Successfully logged in','socks');
                $response['response_key'] = __($random_code, "socks");
                $response['status'] = __($verify_status[0], "socks");
                $response['user_id'] = __($user_id, "socks");
            }else{
                $error->add(404, __("Verify your email address.", 'socks'), array('status' => 400));
                return $error;
            }

        }else{
            $error->add(404, __("Username or password is incorrect", 'socks'), array('status' => 400));
            return $error;
        }

        return new WP_REST_Response($response, 200);
    }

    function socks_wc_rest_user_endpoint_sign_out_permission_handler(WP_REST_Request $request){
        if ( $this->sign_out_key == $request->get_param( 'auth' ) ) {
            return true;
        } else {
            return false;
        }
    }

    function socks_wc_rest_user_endpoint_sign_out_handler(WP_REST_Request $request = null){
        $response = array();
        $parameters = $request->get_body_params();
        $user_id = sanitize_text_field($parameters['user_id']);
        $error = new WP_Error();

        if (!empty($user_id)){
            update_user_meta( $user_id, '_auth_key', '' );
            $response['message'] = __('Successfully logged out','socks');
        }else{
            $error->add(404, __("Something went wrong. please try again !", 'socks'), array('status' => 400));
            return $error;
        }

        return new WP_REST_Response($response, 200);
    }
}

new SignUpApi();
