<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view this page.');
}

class ClassWCSettings
{
    function __construct()
    {
        add_action( 'wp_footer', array($this,'socks_product_page_ajax_add_to_cart_js') );
        add_action( 'wc_ajax_socks_add_to_cart', array($this,'socks_ajax_add_to_cart_handler') );
        add_action( 'wc_ajax_nopriv_socks_add_to_cart', array($this,'socks_ajax_add_to_cart_handler') );
        add_filter( 'woocommerce_add_to_cart_fragments', array($this,'socks_ajax_add_to_cart_add_fragments') );
        add_filter( 'woocommerce_add_to_cart_fragments', array($this,'wc_header_add_to_cart_fragment') );
        add_filter( 'woocommerce_add_to_cart_fragments', array($this,'wc_add_mini_cart_content') );
        add_filter( 'woocommerce_add_to_cart_fragments', array($this,'wc_add_side_cart_counter') );
        add_filter( 'woocommerce_currencies', array($this,'socks_custom_currency') );
        add_filter('woocommerce_currency_symbol', array($this,'socks_custom_currency_symbol'), 10, 2);

        // Wc Add Actions
        add_action('woocommerce_single_product_summary',array($this,'socks_single_product_title'),5);
        add_filter('woocommerce_breadcrumb_defaults',array($this,'socks_custom_breadcrumb'));
        add_filter('woocommerce_format_sale_price',array($this,'socks_wc_format_sale_price'),10,3);
        add_filter('wc_price',array($this,'socks_wc_price'),10,5);
        add_filter('woocommerce_dropdown_variation_attribute_options_html',array($this,'socks_variation_radio_buttons'), 20, 2);
        add_filter('woocommerce_variation_is_active', array($this,'socks_wc_variation_check'), 10, 2);
        add_filter('woocommerce_dropdown_variation_attribute_options_args', array($this,'socks_wc_variation_add_args'));
        add_action('woocommerce_single_variation',array($this,'socks_wc_single_variation_add_to_cart_button'),20);
        add_filter('woocommerce_product_tabs', array($this,'wc_socks_rename_tabs'), 98 );
        add_filter('woocommerce_product_tabs', array($this,'wc_socks_reorder_tabs'), 98 );
        add_action('wp_head', array($this,'wc_socks_theme_s_user'));
        add_filter('woocommerce_product_additional_information_heading', array($this,'wc_product_additional_information_heading') );
        add_filter('woocommerce_product_description_heading', array($this,'wc_product_additional_information_heading') );
        add_action('woocommerce_product_additional_information',array($this,'socks_wc_product_additional_information'),10);

        // Remove WC Core add to cart handler to prevent double-add
        remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );
        remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
        remove_action('woocommerce_single_product_summary','woocommerce_template_single_rating',10);
        remove_action('woocommerce_before_single_product','woocommerce_output_all_notices',10);
        remove_action('woocommerce_before_main_content','woocommerce_breadcrumb',20);
        remove_action('woocommerce_single_variation','woocommerce_single_variation_add_to_cart_button',20);
        remove_action('woocommerce_product_additional_information','wc_display_product_attributes',10);

        add_filter( 'woocommerce_registration_auth_new_customer', '__return_false' );
    }

    function wc_product_additional_information_heading(){
        return;
    }

    function socks_wc_single_variation_add_to_cart_button(){
        get_template_part('/templates/wc-templates/single/add-to-cart/variable','part');
    }

    function socks_single_product_title(){
        the_title( '<h1 class="text-2xl text-black font-medium">', '</h1>' );
    }

    function socks_product_page_ajax_add_to_cart_js() {
        ?><script type="text/javascript" charset="UTF-8">
            jQuery(function($) {
                $('form.cart').on('submit', function(e) {
                    e.preventDefault();

                    let form = $(this);
                    form.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });

                    let formData = new FormData(form[0]);
                    formData.append('add-to-cart', form.find('[name=add-to-cart]').val() );

                    // Ajax action.
                    $.ajax({
                        url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'socks_add_to_cart' ),
                        data: formData,
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        complete: function( response ) {
                            response = response.responseJSON;

                            if ( ! response ) {
                                return;
                            }

                            if ( response.error && response.product_url ) {
                                window.location = response.product_url;
                                return;
                            }

                            // Redirect to cart option
                            if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' ) {
                                window.location = wc_add_to_cart_params.cart_url;
                                return;
                            }

                            let $thisbutton = form.find('.single_add_to_cart_button'); //
                            //	var $thisbutton = null; // uncomment this if you don't want the 'View cart' button

                            // Trigger event so themes can refresh other areas.
                            $( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );

                            // Remove existing notices
                            $( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();

                            // Add new notices
                            form.closest('.product').before(response.fragments.notices_html);

                            form.unblock();
                        }
                    });
                });
            });
        </script><?php
    }

    function socks_ajax_add_to_cart_handler() {
        WC_Form_Handler::add_to_cart_action();
        WC_AJAX::get_refreshed_fragments();
    }

    function socks_ajax_add_to_cart_add_fragments( $fragments ) {
        global $woocommerce;
        $all_notices  = WC()->session->get( 'wc_notices', array() );
        $notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );

        ob_start();
        foreach ( $notice_types as $notice_type ) {
            if ( wc_notice_count( $notice_type ) > 0 ) {
                wc_get_template( "notices/{$notice_type}.php", array(
                    'notices' => array_filter( $all_notices[ $notice_type ] ),
                ) );
            }
        }

        $fragments['notices_html'] = ob_get_clean();
        wc_clear_notices();
        return $fragments;
    }

    function wc_header_add_to_cart_fragment($fragments){
        global $woocommerce;
        ob_start();
        $items_count = WC()->cart->get_cart_contents_count();
        $cart_counter_class = '';
        if ($items_count == 0){
            $cart_counter_class = 'hidden';
        }
        ?>
            <div id="mini-cart-count" class="<?=$cart_counter_class?>"><span><?php echo $items_count ? $items_count : '&nbsp;'; ?></span></div>
        <?php
        $fragments['#mini-cart-count'] = ob_get_clean();
        return $fragments;
    }

    function wc_socks_theme_s_user() {
        If ($_GET['socksUser'] == 'red') {
            
        }
    }

    function wc_add_mini_cart_content($fragments){
        global $woocommerce;
        ob_start();
        ?>
            <div id="socks-side-cart" class="socks-side-cart">
                <?php woocommerce_mini_cart(); ?>
            </div>
        <?php
        $fragments['#socks-side-cart'] = ob_get_clean();
        return $fragments;
    }

    function wc_add_side_cart_counter($fragments){
        global $woocommerce;
        ob_start();
        ?>
        <span id="socks-side-cart-counter" class="font-light">
            <?php
                if ($woocommerce->cart->cart_contents_count != 0){
                    echo sprintf(_n('( %d item )', '( %d items )', $woocommerce->cart->cart_contents_count, 'socks'), $woocommerce->cart->cart_contents_count);
                }
            ?>
        </span>
        <?php
        $fragments['#socks-side-cart-counter'] = ob_get_clean();
        return $fragments;
    }

    function socks_custom_breadcrumb($defaults){

        $defaults['wrap_before'] = '<nav class="breadcrumb-nav"><ul class="breadcrumb flex">';
        $defaults['wrap_after'] = '</ul></nav>';
        $defaults['before'] = '<li>';
        $defaults['after'] = '</li>';
        $defaults['delimiter'] = '';

        return $defaults;
    }

    function socks_wc_format_sale_price( $price, $regular_price, $sale_price ){
        $r_price = ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price );
        $s_price = ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price );

        $price = '<div class="wc-socks-price-area">
                    <span id="socks-single-sale-price" class="text-2xl text-black font-medium">Price : '.$s_price.'</span>
                    <span id="socks-regular-sale-price" class="text-xl text-gray-400 font-light px-3 line-through">'.$r_price.'</span>
                 </div>';

        return $price;
    }

    function socks_wc_price($return, $price, $args, $unformatted_price, $original_price){

        $negative          = $price < 0;
        $formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="woocommerce-Price-currencySymbol wc-price-symb">' . get_woocommerce_currency_symbol( $args['currency'] ) . '</span>', '<span class="socks-wc-price-amount">'.$price.'</span>' );
        $return          = '<span class="woocommerce-Price-amount amount"><bdi>' . $formatted_price . '</bdi></span>';

        if ( $args['ex_tax_label'] && wc_tax_enabled() ) {
            $return .= ' <small class="woocommerce-Price-taxLabel tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
        }

        return $return;
    }

    function socks_wc_variation_add_args($args){
        $args['class'] = 'hidden';
        return $args;
    }

    function socks_variation_radio_buttons($html, $args) {

        if(false === $args['selected'] && $args['attribute'] && $args['product'] instanceof WC_Product) {
            $selected_key     = 'attribute_'.sanitize_title($args['attribute']);
            $args['selected'] = isset($_REQUEST[$selected_key]) ? wc_clean(wp_unslash($_REQUEST[$selected_key])) : $args['product']->get_variation_default_attribute($args['attribute']);
        }

        $options               = $args['options'];
        $product               = $args['product'];
        $attribute             = $args['attribute'];
        $name                  = $args['name'] ? $args['name'] : 'attribute_'.sanitize_title($attribute);
        $id                    = $args['id'] ? $args['id'] : sanitize_title($attribute);
        $class                 = $args['class'];
        $show_option_none      = (bool)$args['show_option_none'];
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce');

        if(empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[$attribute];
        }

        $radios = '<div class="variation-radios size_list py-3"><div class="variation-item-holder flex items-center content-center gap-3 flex-wrap filter-form">';

        if(!empty($options)) {
            if($product && taxonomy_exists($attribute)) {
                $terms = wc_get_product_terms($product->get_id(), $attribute, array(
                    'fields' => 'all',
                ));

                foreach($terms as $term) {
                    if(in_array($term->slug, $options, true)) {
                        $id = $name.'-'.$term->slug;
                        $term_id = $term->term_id;
                        $term_color = get_field('color_code',$term);
                        $term_name = esc_html(apply_filters('woocommerce_variation_option_name', $term->name));

                        if ($term_color){
                            $term_style = 'style="color:'.$term_color.'"';
                            $radios .= '<div class="color-list item-attr relative text-indigo-600" data-term-id="'.$term_id.'">
                                            <input type="radio" class="opacity-0 invisible absolute top-0 left-0 w-full h-full" id="'.esc_attr($id).'" name="'.esc_attr($name).'" value="'.esc_attr($term->slug).'" '.checked(sanitize_title($args['selected']), $term->slug, false).'>
                                            <label for="'.esc_attr($id).'" class="check">
                                                <i '.$term_style.' class="fas fa-circle"></i>
                                            </label>
                                        </div>';

                        }else{
                            $radios .= '<div class="size-list item-attr relative" data-term-id="'.$term_id.'" >
                                            <input type="radio" class="opacity-0 invisible absolute top-0 left-0 w-full h-full" id="'.esc_attr($id).'" name="'.esc_attr($name).'" value="'.esc_attr($term->slug).'" '.checked(sanitize_title($args['selected']), $term->slug, false).'>
                                            <label class="px-4" for="'.esc_attr($id).'">'.$term_name.'</label>
                                        </div>';
                        }

                    }
                }
            } else {
                foreach($options as $option) {
                    $id = $name.'-'.$option;
                    $checked    = sanitize_title($args['selected']) === $args['selected'] ? checked($args['selected'], sanitize_title($option), false) : checked($args['selected'], $option, false);
                    $radios    .= '<div class="size-list relative">
                                    <input type="radio" class="opacity-0 invisible absolute top-0 left-0 w-full h-full" id="'.esc_attr($id).'" name="'.esc_attr($name).'" value="'.esc_attr($option).'" id="'.sanitize_title($option).'" '.$checked.'>
                                    <label for="'.esc_attr($id).'">'.esc_html(apply_filters('woocommerce_variation_option_name', $option)).'</label>
                                  </div>';
                }
            }
        }

        $radios .= '</div></div>';

        return $html.$radios;
    }

    function socks_wc_variation_check($active, $variation) {
        if(!$variation->is_in_stock() && !$variation->backorders_allowed()) {
            return false;
        }
        return $active;
    }

    function wc_socks_rename_tabs( $tabs ) {
        $tabs['description']['title'] = __( 'More Information' );
        $tabs['reviews']['title'] = __( 'Ratings' );
        $tabs['additional_information']['title'] = __( 'Specifications' );
        return $tabs;
    }

    function wc_socks_reorder_tabs( $tabs ) {
        $tabs['additional_information']['priority'] = 5;
        $tabs['description']['priority'] = 10;
        $tabs['reviews']['priority'] = 15;
        return $tabs;
    }

    function socks_wc_product_additional_information($product){
        $product_attributes = array();

        // Display weight and dimensions before attribute list.
        $display_dimensions = apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() );

        if ( $display_dimensions && $product->has_weight() ) {
            $product_attributes['weight'] = array(
                'label' => __( 'Weight', 'woocommerce' ),
                'value' => wc_format_weight( $product->get_weight() ),
            );
        }

        if ( $display_dimensions && $product->has_dimensions() ) {
            $product_attributes['dimensions'] = array(
                'label' => __( 'Dimensions', 'woocommerce' ),
                'value' => wc_format_dimensions( $product->get_dimensions( false ) ),
            );
        }

        // Add product attributes to list.
        $attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );

        foreach ( $attributes as $attribute ) {
            $values = array();

            if ( $attribute->is_taxonomy() ) {
                $attribute_taxonomy = $attribute->get_taxonomy_object();
                $attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

                foreach ( $attribute_values as $attribute_value ) {
                    $value_name = esc_html( $attribute_value->name );

                    if ( $attribute_taxonomy->attribute_public ) {
                        $values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
                    } else {
                        $values[] = $value_name;
                    }
                }
            } else {
                $values = $attribute->get_options();

                foreach ( $values as &$value ) {
                    $value = make_clickable( esc_html( $value ) );
                }
            }

            $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ] = array(
                'label' => wc_attribute_label( $attribute->get_name() ),
                'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ),
            );
        }

        /**
         * Hook: woocommerce_display_product_attributes.
         *
         * @since 3.6.0.
         * @param array $product_attributes Array of atributes to display; label, value.
         * @param WC_Product $product Showing attributes for this product.
         */
        $product_attributes = apply_filters( 'woocommerce_display_product_attributes', $product_attributes, $product );

        get_template_part('/templates/wc-templates/product-attributes',null,
            array(
                'product_attributes' => $product_attributes,
                // Legacy params.
                'product'            => $product,
                'attributes'         => $attributes,
                'display_dimensions' => $display_dimensions,
            )
        );

    }

    function socks_custom_currency($currencies){
        $currencies['BDT'] = __( 'Bangladeshi Taka', 'socks' );
        return $currencies;
    }

    function socks_custom_currency_symbol($currency_symbol, $currency){
        switch( $currency ) {
            case 'BDT': $currency_symbol = 'Tk'; break;
        }
        return $currency_symbol;
    }

}

new ClassWCSettings();