<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
$product_id = get_the_ID();
$attachment_ids = $product->get_gallery_image_ids();
$product = wc_get_product( $product_id );
$rating  = $product->get_average_rating();
$count   = $product->get_rating_count();
?>

<style type="text/css">
    .reset_variations{
        display: none;
    }
</style>

<section id="product-<?php the_ID(); ?>" <?php wc_product_class( 'shop_body_panel', $product ); ?>>
    <div class="container mx-auto px-4 py-8">
        <div class="flex">
            <div class="w-3/5">
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach ($attachment_ids as $attachment_id):
                        $image_link = wp_get_attachment_image_src( $attachment_id, 'large' );
                        $large_img_link = wp_get_attachment_image_src($attachment_id, 'full');
                        ?>
                    <a data-fancybox="gallery" href="<?=$large_img_link[0]?>"><img src="<?=$image_link[0]?>" alt=""></a>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="w-2/5 pl-8">
                <div class="product_details">
                    <div class="product_details_header">
                        <h2 class="text-2xl text-black font-medium"><?=get_the_title(get_the_ID())?></h2>
                        <!--<h3 class="text-xl text-gray-400 font-light pb-5">Men White Solid Round Neck T-shirt</h3>-->
                        <a href="#" class="flex items-center p-2 border border-gray-300 rounded-sm w-max text-xs hover:border-gray-600 mb-3">
                            <span class="font-bold pr-2 mr-2 border-r-2 border-gray-400">
                                <?=$rating?>
                                <i class="fas fa-star text-green-700 ml-px"></i>
                            </span>
                            <span class="text-gray-500 font-light"><?=$count?>  Ratings</span>
                        </a>

                        <?php woocommerce_template_single_rating();?>

                        <div class="border-t border-gray-300 pt-3 pb-6">
                            <div class="flex items-center">
                                <?php woocommerce_template_single_price();?>
                                <span id="single-discount-area" class="text-xl text-base-color font-medium"></span>
                            </div>
                            <span class="block text-green-600 font-medium text-xs">inclusive of all taxes</span>
                        </div>

                        <div class="wc-socks-add-cart-btn-wrap">
                            <?php woocommerce_template_single_add_to_cart();?>
                        </div>

                        <?php if (have_rows('delivery_options')):?>
                        <!-- Start Delivery Option  -->
                        <div class="border-b border-gray-100 mb-4 pb-4">
                            <div class="flex items-center pb-2 mb-2">
                                <span class="text-base font-medium text-black">
                                    DELIVERY OPTIONS
                                    <i class="fal fa-truck ml-2"></i>
                                </span>
                            </div>
                            <p class="text-base font-extralight leading-8 text-black">
                                <?php while( have_rows('delivery_options') ) : the_row();?>
                                <?=__(get_sub_field('sub_field'),'socks')?> <br />
                                <?php endwhile;?>
                            </p>
                        </div>
                        <!-- End Delivery Option  -->
                        <?php endif;?>

                        <div class="border-b border-gray-100 mb-4 pb-4">
                            <!-- Start WC Short Description -->
                            <div class="wc-short-description">
                                <div class="flex items-center pb-2 mb-2">
                                <span class="text-base font-medium text-black uppercase">
                                    PRODUCT DETAILS
                                    <i class="fal fa-file-alt ml-2"></i>
                                </span>
                                </div>
                                <article>
                                    <?php woocommerce_template_single_excerpt();?>
                                </article>
                            </div>
                            <!-- End WC Short Description -->

                            <!-- Start Specifications and tabs -->
                            <?php get_template_part('/templates/wc-templates/single/single_tab','part');?>
                            <!-- Start Specifications and tabs -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

