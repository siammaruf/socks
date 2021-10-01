<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

class ThemeFunction
{
    function __construct(){
        add_action( 'wp_enqueue_scripts', array($this, 'socks_register_scripts') );
        add_action( 'after_setup_theme', array($this, 'themeSetup') );
        add_action( 'widgets_init', array( $this, 'socks_widget_init' ) );
        add_action( 'after_setup_theme', array( $this, 'socks_custom_logo' ) );
        add_filter( 'upload_mimes', array( $this, 'socks_mime_types' ));
        add_filter( 'file_is_displayable_image', array( $this, 'socks_file_is_displayable' ),10, 2 );
        add_action( 'login_head', array( $this, 'custom_login_style' ));
        add_action( 'after_setup_theme', array($this,'setup_woocommerce_support') );

        add_action( 'wp_ajax_socks_user_login', array($this,'socks_main_user_login') );
        add_action( 'wp_ajax_nopriv_socks_user_login', array($this,'socks_main_user_login') );
    }

    function socks_register_scripts(){
        wp_register_script( 'wow-script','https://cdn.jsdelivr.net/npm/wowjs@1.1.3/dist/wow.min.js', array( 'jquery' ), false, true );
        //wp_register_script( 'svgConvert-script','https://cdn.jsdelivr.net/npm/svg-convert@1.0.0/dist/svgConvert.min.js', array( 'jquery' ), false, true );
        wp_register_script( 'fancybox-script','https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js', array( 'jquery' ), false, true );
        wp_register_script( 'sweetalert2-script','https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.all.min.js', array( 'jquery' ), false, true );
        wp_register_script( 'splide-script','https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js', array( 'jquery' ), true, false );
        wp_register_script( 'slick-script','https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array( 'jquery' ), false, true );
        wp_register_script( 'socks-script', SCRIPTS . '/script.js', array( 'jquery' ), false, true );

        // Localize Script
        wp_localize_script( 'socks-script', 'socksObj',
            array(
                'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                'socksNonce'      => wp_create_nonce( "socks-nonce" ),
            )
        );

        wp_enqueue_script( 'wow-script' );
        wp_enqueue_script( 'fancybox-script' );
        wp_enqueue_script( 'sweetalert2-script' );
        wp_enqueue_script( 'splide-script' );
        wp_enqueue_script( 'slick-script' );
        wp_enqueue_script( 'socks-script' );

        wp_enqueue_style( 'animate-style', 'https://cdn.jsdelivr.net/npm/wowjs@1.1.3/css/libs/animate.css' );
        wp_enqueue_style( 'fancybox-style', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css' );
        wp_enqueue_style( 'sweetalert2-style', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css' );
        wp_enqueue_style( 'splide-style', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/css/splide.min.css' );
        wp_enqueue_style( 'slick-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css' );
        wp_enqueue_style( 'fontawesome-style', 'https://pro.fontawesome.com/releases/v5.15.1/css/all.css' );
        wp_enqueue_style( 'socks-style', THEMEROOT . '/css/app.css' );
        wp_enqueue_style( 'socks-custom', THEMEROOT . '/css/custom.css' );
    }

    function themeSetup(){
        /**
         * Make the theme available for translation.
         */
        $lang_dir = THEMEROOT . '/languages';
        load_theme_textdomain( 'm4h', $lang_dir );

        /**
         * Add support for post formats.
         */
        add_theme_support( 'post-formats',
            array(
                'gallery',
                'link',
                'image',
                'quote',
                'video',
                'audio'
            )
        );

        /**
         * Add support for automatic feed links.
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * Add support for post thumbnails.
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * Register nav menus.
         */
        register_nav_menus(
            array(
                'main-menu' => __( 'Main Menu', 'socks' )
            )
        );
        /**
         * Add title tag support
         */
        add_theme_support( 'title-tag' );
    }

    function socks_widget_init(){
        if ( function_exists( 'register_sidebar' ) ) {
            register_sidebar(
                array(
                    'name' => __( 'Footer Widget Area', 'socks' ),
                    'id' => 'sidebar-footer',
                    'description' => __( 'Appears on posts and pages footer.', 'socks' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s footer-widget-column ">',
                    'after_widget' => '</div> <!-- end widget -->',
                    'before_title' => '<h4 class="widget_title mb-2">',
                    'after_title' => '</h4>',
                )
            );

            register_sidebar(
                array(
                    'name' => __( 'Shop Sidebar Widget Area ', 'socks' ),
                    'id' => 'sidebar-shop',
                    'description' => __( 'Appears on posts and shop page.', 'socks' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div> <!-- end widget -->',
                    'before_title' => '<h4 class="widget-title hidden">',
                    'after_title' => '</h4>',
                )
            );
        }
    }

    function socks_custom_logo(){
        $defaults = array(
            'height'      => 87,
            'width'       => 205,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
            'unlink-homepage-logo' => true,
        );
        add_theme_support( 'custom-logo', $defaults );
    }

    function socks_mime_types($mimes){
        $mimes['svg'] = 'image/svg';
        $mimes['webp'] = 'image/webp';
        $mimes['ico'] = 'image/ico';
        return $mimes;
    }

    function socks_file_is_displayable($result, $path){
        if ($result === false) {
            $displayable_image_types = array(
                IMAGETYPE_WEBP,
            );
            $info = @getimagesize( $path );

            if (empty($info)) {
                $result = false;
            } elseif (!in_array($info[2], $displayable_image_types)) {
                $result = false;
            } else {
                $result = true;
            }
        }

        return $result;
    }

    function custom_login_style() {
        echo '<style type="text/css">
          body{
            background: rgb(11,47,79) url('.get_bloginfo('template_directory').'/images/admin-bg.jpeg) center !important;
            background-size: cover !important;
          }
          .login h1 {
            background: transparent) !important;
            padding-top: 20px !important;
          }
         h1 a { 
          background-image: url('.get_bloginfo('template_directory'). '/images/socks.png) !important;
          width: 190px !important;
          webkit-background-size: 100% !important;
          background-size: 100% !important;
          height: 110px !important;
          margin: 0 auto !important;
         }
         h1 a:focus{
            outline:none !important;
         }
         #login{
         padding: 5% 0 0 !important;
        }
         .login form{
          margin-top:0px !important;
          background: rgba(255,255,255,0.6) !important;
          border: none !important;
          border-radius: 3px;
         }
         .login #backtoblog a, .login #nav a{
          color: #ffffff !important;
         }
         .wp-core-ui p .button {
         border-radius: 0 !important;
         border: 0 !important;
         background: #ff5f29  !important;
         text-shadow: none !important;
         text-transform: uppercase !important;
         }
         .wp-core-ui .button-primary:hover{
          background: #ff7633  !important;
         }
         </style>';
    }

    function setup_woocommerce_support(){
        add_theme_support('woocommerce');
    }

    public static function socks_wc_check_latest_product($product_id){
        $check_recent = false;
        $args = array(
            'post_type'   => 'product',
            'numberposts' => '5',
            'post_status' => 'publish',
        );
        $recent_products = wp_get_recent_posts($args);

        foreach ($recent_products as $product){
            if ($product['ID'] == $product_id){
                $check_recent = true;
            }
        }

        return $check_recent;
    }

    public static function socks_get_discount_products(){
        $discount_products = array();
        $args = array(
            'post_type'   => 'product',
            'numberposts' => -1,
        );
        $get_products = get_posts( $args );
        foreach ($get_products as $key => $product){
            $get_product = wc_get_product( $product->ID );
            if( $get_product->is_on_sale() ) {
                if ($key <= 12) {
                    array_push($discount_products, $get_product);
                }
            }
        }
        return $discount_products;
    }

    public static function socks_get_breadcrumb(){
        $term = get_queried_object();
        echo '<ul class="breadcrumb flex">';
        echo '<li><a href="'.home_url().'" rel="nofollow">Home</a><li>';
            if (is_category() || is_single()) {
                echo '<li>';
                the_category(' &bull; ');
                echo '</li>';
                if (is_single()) {
                    echo '<li>'.the_title().'</li>';
                }
            } elseif (is_page()) {
                echo '<li>'.the_title().'</li>';
            } elseif (is_search()) {
                echo '<li>';
                echo the_search_query();
                echo '</li>';
            }elseif ($term){
                if (is_shop()){
                    echo '<li>Shop</li>';
                }else{
                    echo '<li>'.$term->name.'</li>';
                }
            }
        echo '</ul>';
    }

    function socks_main_user_login(){
        check_ajax_referer( 'socks-nonce', 'socksSecurity' );
        $data = $_POST['data'];
        $info = array(
            'user_login'    => $data['userName'],
            'user_password' => $data['userPass'],
            'remember'      => false
        );

        if (isset($data['remember'])){
            $info['remember'] = true;
        }

        $user = wp_signon( $info, false );

        if ( !is_wp_error( $user ) ) {
            echo json_encode(
              array(
                  'status' => 'success',
                  'redirect' => home_url('my-account'),
              )
            );
        }else{
            echo json_encode(
                array(
                    'status' => 'error',
                )
            );
        }
        die();
    }
}

new ThemeFunction();