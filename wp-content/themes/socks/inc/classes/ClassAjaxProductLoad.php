<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

class ClassAjaxProductLoad{
    function __construct(){
        add_action( 'wp_ajax_socks_load_products', array($this,'load_shop_products') );
        add_action( 'wp_ajax_nopriv_socks_load_products', array($this,'load_shop_products') );
    }

    private function getTaxonomyQueryArray($taxonomies, $is_cat_page = ''){

        $data_args = array('relation' => 'AND');

        if (!empty($is_cat_page)){
            $tax_arr = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $is_cat_page,
            );
            array_push($term_slugs,$tax_arr);
        }

        foreach ($taxonomies as $taxonomy){
            $tax_name = $taxonomy['taxonomy'];
            $tax_terms = $taxonomy['terms'];
            $term_slugs = array();

            foreach ($tax_terms as $term){
                array_push($term_slugs,$term);
            }

            $tax_arr = array(
                'taxonomy' => $tax_name,
                'field' => 'slug',
                'terms' => $term_slugs,
            );

            array_push($data_args, $tax_arr);
        }

        return $data_args;

    }

    function load_shop_products(){
        check_ajax_referer('socks-nonce', 'socksSecurity');
        $msg = '';
        $pag_container = '';
        $p_numbers = array();
        $data = $_POST['data'];
        $page_term = $_POST['pageTerm'];

        if (isset($_POST['page'])){
            $page = sanitize_text_field($_POST['page']);
            $cur_page = $page;
            $page -= 1;
            $per_page = 6;
            $previous_btn = true;
            $next_btn = true;
            $last_btn = true;
            $start = $page * $per_page;

            $data_taxonomies = $data['taxonomies'];
            $data_price = $data['price'];
            $data_shorting = $data['shorting'];

            $args = array(
                'post_type'         => 'product',
                'post_status '      => 'publish',
                'orderby'           => 'post_date',
                'order'             => 'DESC',
                'posts_per_page'    => $per_page,
                'offset'            => $start,
            );


            if (isset($data_price) && !isset($data_taxonomies)){

                if (isset($data_shorting)){
                    $args['meta_query'] = array(
                        'relation' => 'AND',
                        array(
                            'key' => '_price',
                            'value' => array($data_price[0]['minPrice'], $data_price[0]['maxPrice']),
                            'compare' => 'BETWEEN',
                            'type' => 'NUMERIC'
                        )
                    );
                }else{
                    $args['meta_query'] = array(
                        array(
                            'key' => '_price',
                            'value' => array($data_price[0]['minPrice'], $data_price[0]['maxPrice']),
                            'compare' => 'BETWEEN',
                            'type' => 'NUMERIC'
                        )
                    );
                }

                if (isset($page_term)){
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $page_term,
                        )
                    );
                }

            }elseif (isset($data_taxonomies) && !isset($data_price)){

                $data_args = $this->getTaxonomyQueryArray($data_taxonomies, $page_term);
                $args['tax_query'] = $data_args;

            }elseif (isset($data_taxonomies) && isset($data_price)){

                $data_args = $this->getTaxonomyQueryArray($data_taxonomies, $page_term);
                $args['tax_query'] = $data_args;

                $args['meta_query'] = array(
                    'relation' => 'OR',
                    array(
                        'key' => '_price',
                        'value' => array($data_price[0]['minPrice'], $data_price[0]['maxPrice']),
                        'compare' => 'BETWEEN',
                        'type' => 'NUMERIC'
                    )
                );

            }else{

                if (isset($page_term)){
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $page_term,
                        )
                    );
                }

            }

            // Get Shorting Data
            if (isset($data_shorting)){
                switch ($data_shorting){
                    case 'featured':
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => 'featured',
                                'operator' => 'IN',
                            )
                        );
                        break;

                    case 'low-to-height':
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'asc';
                        break;

                    case 'high-to-low':
                        $args['meta_key'] = '_price';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'desc';
                        break;

                    case 'top-rated':
                        $args['meta_key'] = '_wc_average_rating';
                        $args['orderby'] = 'meta_value_num';
                        $args['order'] = 'desc';
                        break;

                    default:
                        $args['orderby']  = 'post_date';
                        $args['order']    = 'DESC';
                        break;
                }
            }

            $the_query = new WP_Query($args);
            $count = $the_query->found_posts;
            $paging_v = $per_page < $count ? 'd-show' : 'd-none hidden';

            if ($the_query->have_posts()):
                while ( $the_query->have_posts() ) : $the_query->the_post();

                    $gallery_images = '';
                    $gallery_html = '';
                    $price_html = '';

                    $products_id = get_the_ID();
                    $product = wc_get_product( $products_id );

                    $rating  = $product->get_average_rating();
                    $count_rating   = $product->get_rating_count();

                    // Simple Product Price
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    $get_price = $product->get_price();

                    $attachment_ids = $product->get_gallery_image_ids();
                    $image_id  = $product->get_image_id();
                    $image_url = wp_get_attachment_image_url( $image_id, 'full' );

                    // Variable product prices
                    $wcv_reg_min_price = $product->get_variation_regular_price( 'min', true );
                    $wcv_min_sale_price = $product->get_variation_sale_price( 'min', true );
                    $wcv_max_price = $product->get_variation_price( 'max', true );
                    $wcv_min_price = $product->get_variation_price( 'min', true );

                    if ($wcv_min_sale_price == $wcv_reg_min_price){
                        $price_html .= '<span class="block capitalize text-black md:font-light font-semibold md:text-lg text-sm font-Condensed">'.__(wc_price( $wcv_reg_min_price ),'socks').'</span>';
                    }else{
                        $price_html .= '<span class="block capitalize text-black md:font-light font-semibold md:text-lg text-sm font-Condensed">
                                            <span class="line-through mr-3">'.__(wc_price( $wcv_reg_min_price ),'socks').'</span>
                                            '.__(wc_price( $wcv_min_sale_price ),'socks').'
                                        </span>';
                    }

                    if ( $product->is_type( 'variable' ) ) {
                        $discount_percent = (($wcv_reg_min_price - $wcv_min_sale_price)*100) / $wcv_min_sale_price ;
                    }else{
                        $discount_percent = (($regular_price - $sale_price)*100) / $get_price ;
                    }

                    if (!empty($attachment_ids)){
                        foreach ($attachment_ids as $key => $attachment_id){
                            $image_url = wp_get_attachment_url( $attachment_id );
                            if ($key >= 1){
                                $gallery_images .= '
                                    <div class="product_card">
                                        <img class="w-full object-cover object-center" src="'.$image_url.'" alt=""/>
                                    </div>';
                            }
                        }
                        $gallery_html .= '
                                        <!-- Product Gallery Slider   -->
                                        <div class="shop_products_slider slick absolute top-0 -left-full w-full h-full opacity-0 invisible transition duration-500 ease-in-out">
                                            '.$gallery_images.'
                                        </div>
                                        <!-- End Product Gallery Slider -->
                                    ';
                    }

                    $featured = '';
                    if (ThemeFunction::socks_wc_check_latest_product($products_id) == true){
                        $featured .= '<span class="block md:relative absolute bg-base-color md:bg-transparent top-1 right-1  capitalize md:text-black text-white rounded md:px-0 px-3 md:py-0 py-2 font-light md:text-lg text-xs font-Condensed md:pb-1">
                                        New Arrival
                                     </span>';
                    }

                    $msg .= '
                        <div class="product_card relative transition-all duration-500 ease-in">
                            <div class="product_card_img relative overflow-hidden">
                                <a href="'.get_the_permalink($products_id).'" class="absolute top-0 left-9 w-full z-10 h-full mx-auto block"></a>
                                <img class="w-full object-cover object-center" src="'.$image_url.'" alt="'.get_the_title().'" />
                                '.$gallery_html.'
                            </div>
                            <div class="text-left py-6">
                                <div class="flex">
                                    <div class="flex-auto">
                                        '.$price_html.'
                                    </div>
                                    <div class="flex-auto text-right">
                                        <span class="block text-black md:font-light font-semibold md:text-lg text-sm font-Condensed uppercase">
                                            YOU SAVE '.number_format($discount_percent,0,'.',',').'%
                                        </span>
                                    </div>
                                </div>
                                <h3 class="lg:text-base text-sm">
                                    <a href="'.get_the_permalink($products_id).'" class="hover:text-black hover:underline block font-light text-custom-dark-gray font-Condensed">
                                        '.get_the_title().'
                                    </a>
                                </h3>
                                <a class="hover:text-black block lg:text-base text-xs font-light text-custom-dark-gray hover:underline font-Condensed pb-1" href="'.get_the_permalink($products_id).'">
                                    + More colors available
                                </a>
                                '.$featured.'
                                <div class="md:block flex items-center justify-center">
                                '.wc_get_rating_html( $rating, $count_rating ).'
                                </div>
                            </div>
                        </div>';
                endwhile;
            else:
                $msg .= '<div class="no-product-notice">'.__('Oops ! No product / products found','socks').'</div>';
            endif;

            $msg = "<div id='search-results' data-product-counter='".$count."' class='search-results grid md:grid-cols-3 grid-cols-2 md:gap-5 gap-4'>" . $msg . "</div>";

            $no_of_paginations = ceil($count / $per_page);

            if ($cur_page >= 7) {
                $start_loop = $cur_page - 3;
                if ($no_of_paginations > $cur_page + 3)
                    $end_loop = $cur_page + 3;
                else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                    $start_loop = $no_of_paginations - 6;
                    $end_loop = $no_of_paginations;
                } else {
                    $end_loop = $no_of_paginations;
                }
            } else {
                $start_loop = 1;
                if ($no_of_paginations > 7)
                    $end_loop = 7;
                else
                    $end_loop = $no_of_paginations;
            }

            // Pagination Buttons logic
            $pag_container .= "
            <div class='pagination-wrap'>
                <ul>";
                if ($previous_btn && $cur_page > 1) {
                    $pre = $cur_page - 1;
                    $pag_container .= "<li data-page='$pre' class='active'>Prev</li>";
                } else if ($previous_btn) {
                    $pag_container .= "<li class='inactive'>Prev</li>";
                }
                for ($i = $start_loop; $i <= $end_loop; $i++) {
                    array_push($p_numbers,$i);
                    if ($cur_page == $i)
                        $pag_container .= "<li data-page='$i' class = 'selected' >{$i}</li>";
                    else
                        $pag_container .= "<li data-page='$i' class='active'>{$i}</li>";
                }

                if ($last_btn && $cur_page < $no_of_paginations) {
                    if (!in_array($no_of_paginations,$p_numbers)){
                        if (in_array($no_of_paginations-1,$p_numbers)){
                            $pag_container .= "<li class='more-dot'>...</li>";
                        }else{
                            $pag_container .= "<li class='more-dot'>...</li>";
                            $pag_container .= "<li data-page='$no_of_paginations' class='active'>{$no_of_paginations}</li>";
                        }
                    }
                } else if ($last_btn) {
                    $pag_container .= "<li data-page='$no_of_paginations' class='inactive hidden'>Last</li>";
                }

                if ($next_btn && $cur_page < $no_of_paginations) {
                    $nex = $cur_page + 1;
                    $pag_container .= "<li data-page='$nex' class='active'>Next</li>";
                } else if ($next_btn) {
                    $pag_container .= "<li class='inactive'>Next</li>";
                }

                $pag_container = $pag_container . "
                </ul>
            </div>";

            // We echo the final output
            echo '<div class = "products-holder">' . $msg . '</div>' .
                '<div class = "pagination-nav '.$paging_v.'">' . $pag_container . '</div>';
        }
        die();
    }
}

new ClassAjaxProductLoad();