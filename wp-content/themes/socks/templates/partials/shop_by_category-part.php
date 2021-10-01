<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

$product_categories = get_terms( array(
    'taxonomy' => 'product_cat',
    'parent'   => 0,
    'hide_empty' => false
) );

$shop_by_category_shop_all_icon = get_field('shop_by_category_-_shop_all_icon','option');
?>

<?php if ( !empty($product_categories) ): ?>
<section class="home-categories-panel bg-custom-gray-1 py-9"><!-- Start home categories panel -->
    <div class="container mx-auto">
        <h3 class="text-center uppercase font-lato font-bold tracking-widest mb-3">Shop by category</h3>
        <div class="flex gap-4 content-center justify-between flex-wrap">
            <?php foreach ($product_categories as $category):?>
                <?php
                $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                $category_url = wp_get_attachment_url($thumbnail_id);
                $category_link = get_term_link( $category->term_id, 'product_cat' );
                ?>
                <?php if ( $category->slug != 'uncategorized'):?>
                    <a class="category text-center base-hover cursor-pointer <?=$category->slug?>" href="<?=$category_link?>">
                        <img class="w-24 mx-auto" src="<?=$category_url?>" alt="<?php _e($category->name,'socks');?>">
                        <span class="block font-lato text-csm font-light text-xs text-center tracking-widest mt-4 text-gray-600 hover-layer"><?php _e($category->name,'socks');?></span>
                    </a>
                <?php endif;?>
            <?php endforeach;?>
            <?php if ($shop_by_category_shop_all_icon):?>
            <div class="category text-center base-hover cursor-pointer">
                <img class="w-24 mx-auto" src="<?=$shop_by_category_shop_all_icon?>" alt="">
                <span class="block font-lato text-csm font-light text-xs text-center tracking-widest mt-4 text-gray-600 hover-layer">Shop all</span>
            </div>
            <?php endif;?>
            <div class="category text-center -mt-2.5 base-hover cursor-pointer hidden">
                <strong class="inline-block font-lato text-csm font-light text-xs bg-tag-color text-white px-1 rounded">New</strong>
                <img class="mx-auto block rounded-full h-20 w-20 object-cover object-center" src="images/header-image-mobile.jpeg" alt="">
                <span class="block font-lato text-csm font-light text-xs text-center tracking-widest mt-4 text-gray-600 hover-layer">New Releases</span>
            </div>

        </div>
    </div>
</section><!-- End home categories panel -->

<?php endif; ?>