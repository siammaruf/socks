<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;
$product_attributes = $args['product_attributes'];
if ( ! $product_attributes ) {
    return;
}
?>

<!-- Specifications -->
<ul class="grid grid-cols-2 gap-5 my-2 woocommerce-product-attributes shop_attributes">
    <?php foreach ( $product_attributes as $product_attribute_key => $product_attribute ) : ?>
    <li class="text-sm text-black font-light pb-2 border-b border-gray-200 woocommerce-product-attributes-item woocommerce-product-attributes-item--<?php echo esc_attr( $product_attribute_key ); ?>">
        <span class="block text-xs text-gray-400 mb-1 woocommerce-product-attributes-item__label">
            <?php echo wp_kses_post( $product_attribute['label'] ); ?>
        </span>
        <?php echo wp_kses_post( $product_attribute['value'] ); ?>
    </li>
    <?php endforeach; ?>
</ul>

<a href="javaScript:void(0)" id="socks-single-p-more-info" class="text-red-600 text-sm font-light">See More</a>