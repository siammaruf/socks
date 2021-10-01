<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="my-5 pb-4 border-b border-gray-100 flex items-center gap-2 woocommerce-variation-add-to-cart variations_button">
    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

    <?php
    do_action( 'woocommerce_before_add_to_cart_quantity' );

    woocommerce_quantity_input(
        array(
            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
        )
    );

    do_action( 'woocommerce_after_add_to_cart_quantity' );

    ?>
    <button type="submit" class="rounded w-2/3 px-6 py-3 text-base font-semibold bg-pink-600 hover:bg-pink-400 text-center text-white uppercase single_add_to_cart_button alt">
        <i class="fal fa-shopping-bag mr-3"></i>
        <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
    </button>

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
    <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />

    <?php if($product->get_stock_quantity()<0):?>
        <button class="rounded w-2/3 px-6 py-3 text-base font-semibold bg-gray-300 text-center text-white uppercase">
            Out Of Stock
        </button>
    <?php endif;?>

</div>
