<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}


class ClassMainSearch
{
    function __construct(){
        add_action( 'wp_ajax_socks_search_products', array($this,'socks_main_search') );
        add_action( 'wp_ajax_nopriv_socks_search_products', array($this,'socks_main_search') );
    }

    function socks_main_search(){
        check_ajax_referer( 'socks-nonce', 'socksSecurity' );
        $msg = '';
        $pag_container = '';
        $data = $_POST['data'];

        if (isset($_POST['page'])){
            $page = sanitize_text_field($_POST['page']);
            $cur_page = $page;
            $page -= 1;
            $per_page = -1;
            $previous_btn = true;
            $next_btn = true;
            $last_btn = true;
            $start = $page * $per_page;

            $args = array(
                'post_type'         => 'product',
                'post_status '      => 'publish',
                's'                 => $data,
                'orderby'           => 'post_date',
                'order'             => 'DESC',
                'posts_per_page'    => $per_page,
                'offset'            => $start,
            );

            $the_query = new WP_Query($args);
            $count = $the_query->found_posts;
            $paging_v = $per_page < $count ? 'd-show' : 'd-none hidden';

            if ($the_query->have_posts()):
                while ( $the_query->have_posts() ) : $the_query->the_post();
                    $gallery_images = '';
                    $price_html = '';

                    $products_id = get_the_ID();
                    $product = wc_get_product( $products_id );

                    // Simple Product Price
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    $get_price = $product->get_price();

                    // Variable product prices
                    $wcv_reg_min_price = $product->get_variation_regular_price( 'min', true );
                    $wcv_min_sale_price = $product->get_variation_sale_price( 'min', true );
                    $wcv_max_price = $product->get_variation_price( 'max', true );
                    $wcv_min_price = $product->get_variation_price( 'min', true );

                    $image_id  = $product->get_image_id();
                    $image_url = wp_get_attachment_image_url( $image_id, 'full' );

                    if ($wcv_min_sale_price == $wcv_reg_min_price){
                        $price_html .= '<span class="block">'.__(wc_price( $wcv_reg_min_price ),'socks').'</span>';
                    }else{
                        $price_html .= '<span class="block">
                                            <span class="line-through mr-3">'.__(wc_price( $wcv_reg_min_price ),'socks').'</span>
                                            '.__(wc_price( $wcv_min_sale_price ),'socks').'
                                        </span>';
                    }

                    if ( $product->is_type( 'variable' ) ) {
                        $discount_percent = (($wcv_reg_min_price - $wcv_min_sale_price)*100) / $wcv_min_sale_price ;
                    }else{
                        $discount_percent = (($regular_price - $sale_price)*100) / $get_price ;
                    }

                    $msg .='<li class="block">
                                <a class="flex px-3 py-2 hover:bg-gray-300" href="'.get_the_permalink($products_id).'">
                                    <img class="flex-initial w-14 mr-4 h-auto" src="'.$image_url.'" alt="">
                                    <div class="flex-initial">
                                        <span class="product-title block">'.__(get_the_title($products_id),'socks').'</span>
                                        '.$price_html.'
                                    </div>
                                </a>
                            </li>';

                endwhile;
            else:
                $msg .= '<li class="no-product-notice p-5">'.__('Oops ! No product / products found','socks').'</li>';
            endif;

            $msg = "<ul id='search-results-wrap' class='absolute w-full bg-gray-100 list-none shadow-2xl z-10'>" . $msg . "</ul>";

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
            echo '<div class = "sp-holder">' . $msg . '</div>' .
                '<div style="display:none" class = "sp-pagination-nav '.$paging_v.'">' . $pag_container . '</div>';
        }

        die();
    }

}

new ClassMainSearch();