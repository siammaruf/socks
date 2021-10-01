<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

class ACFSettings{

    function __construct(){
        add_filter( 'acf/settings/dir' , array( $this, 'define_plugin_path' ));
        add_filter( 'acf/location/rule_types', array($this,'acf_role_type'),10);
        add_filter( 'acf/location/rule_values/wc_prod_attr', array($this,'acf_role_value'),10);
        add_filter( 'acf/location/rule_match/wc_prod_attr', array($this,'acf_matching_the_custom_role'), 10, 3);

        $this->socks_acf_theme_options();
    }

    function define_plugin_path($dir){
        $dir = get_template_directory_uri() . '/framework/plugins/advanced-custom-fields-pro/';
        return $dir;
    }

    function socks_acf_theme_options(){
        acf_add_options_page(array(
            'page_title' 	=> 'Theme General Settings',
            'menu_title'	=> 'Theme Settings',
            'menu_slug' 	=> 'theme-general-settings',
            'capability'	=> 'edit_posts',
            'redirect'		=> false,
            'icon_url'      => 'dashicons-buddicons-groups',
            'position'      => 7
        ));

        acf_add_options_sub_page(array(
            'page_title' 	=> 'Home feature box',
            'menu_title'	=> 'Home feature box setting',
            'parent_slug'	=> 'theme-general-settings',
        ));

        acf_add_options_sub_page(array(
            'page_title' 	=> 'Theme Header Settings',
            'menu_title'	=> 'Header Settings',
            'parent_slug'	=> 'theme-general-settings',
        ));

        acf_add_options_sub_page(array(
            'page_title' 	=> 'Theme Footer Settings',
            'menu_title'	=> 'Footer Settings',
            'parent_slug'	=> 'theme-general-settings',
        ));
    }

    function acf_role_type($choices){
        $choices[ __("Other",'acf') ]['wc_prod_attr'] = 'WC Product Attribute';
        return $choices;
    }

    function acf_role_value($choices){
        foreach ( wc_get_attribute_taxonomies() as $attr ) {
            $pa_name = wc_attribute_taxonomy_name( $attr->attribute_name );
            $choices[ $pa_name ] = $attr->attribute_label;
        }
        return $choices;
    }

    function acf_matching_the_custom_role($match, $rule, $options){
        if ( isset( $options['taxonomy'] ) ) {
            if ( '==' === $rule['operator'] ) {
                $match = $rule['value'] === $options['taxonomy'];
            } elseif ( '!=' === $rule['operator'] ) {
                $match = $rule['value'] !== $options['taxonomy'];
            }
        }
        return $match;
    }

}

new ACFSettings();