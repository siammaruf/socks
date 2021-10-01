<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

class ThemeFunctions
{
    function __construct()
    {
        add_action( 'wp_enqueue_scripts', array($this, 'socks_register_child_theme_scripts') );
    }

    function socks_register_child_theme_scripts(){
        wp_register_script( 'socks-custom-script', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'socks-custom-script' );
        wp_enqueue_style( 'socks-custom-style', get_stylesheet_directory_uri() . '/css/custom.css' );
    }
}

new ThemeFunctions();