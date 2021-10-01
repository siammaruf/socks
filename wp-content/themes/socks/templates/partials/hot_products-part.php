<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

$discount_products = ThemeFunction::socks_get_discount_products();
?>
<?php if (!empty($discount_products)):?>
<!-- Start Hot Products Panel -->
<section class="hot_products_panel">
    <div class="container mx-auto py-16">
        <div class="text-center mb-14"><h2 class="section_title">Hot Price</h2></div>
        <div class="grid lg:grid-cols-4 md:grid-cols-4 grid-cols-2 gap-4">
            <?php foreach ($discount_products as $product):?>
            <?php
                $image_id  = $product->get_image_id();
                $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                $term_list = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
                ?>
            <div class="product_card relative">
                <img
                    class="w-full object-cover object-center"
                    src="<?=$image_url?>"
                    alt=""
                />
                <div class="text-center py-6 px-4">
                    <a class="product_category_link" href="<?=get_the_permalink($product->get_id())?>"><?=__(join(',',$term_list),'socks')?></a>
                    <h3 class="product_category_title"><a href="<?=get_the_permalink($product->get_id())?>"><?=__(get_the_title($product->get_id()),'socks')?></a></h3>
                    <span class="product_price"><?=__($product->get_price().' Tk','socks')?></span>
                </div>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<!-- End Hot Products Panel -->
<?php endif;?>