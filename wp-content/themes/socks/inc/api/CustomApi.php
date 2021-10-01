<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

class CustomApi
{
    protected $socks_auth_key;

    function __construct()
    {
        $this->socks_auth_key = 'Fy54v9QI84';
        add_action('rest_api_init',[$this,'socks_rest_featurebox_api']);
    }

    function socks_rest_featurebox_api(){
        register_rest_route('wp/v3', 'socks/featurebox', array(
            'methods' => WP_REST_Server::READABLE,
            'permission_callback' => [$this,'socks_wc_rest_featurebox_endpoint_permission_handler'],
            'callback' => [$this,'socks_wc_rest_featurebox_endpoint_handler'],
        ));
    }

    function socks_wc_rest_featurebox_endpoint_permission_handler(WP_REST_Request $request){
        if ( $this->socks_auth_key == $request->get_param( 'auth' ) ) {
            return true;
        } else {
            return false;
        }
    }

    function socks_wc_rest_featurebox_endpoint_handler(WP_REST_Request $request){
        $records = array();
        $response = array();
        $error = new WP_Error();

        if( have_rows('feature_box', 'option') ):
            while( have_rows('feature_box', 'option') ): the_row();
                $data = [];
                $img = get_sub_field('background');
                $data['title'] = __(get_sub_field('title'),'socks');
                $data['sub_title'] = __(get_sub_field('sub_title'),'socks');
                $data['link'] = esc_url(get_sub_field('link'));
                $data['img'] = esc_url($img['url']);
                array_push($response,(object)$data);
            endwhile;
            //$response['data'] = $records;
        else:
            $error->add(400, __("Data not found !.", 'socks'), array('status' => 400));
            return $error;
        endif;

        return new WP_REST_Response($response, 200);
    }
}

new CustomApi();