<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

$args = array(
    'post_type'         => 'product',
    'post_status '      => 'publish',
    'orderby'           => 'post_date',
    'order'             => 'DESC',
    'tax_query'        => array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        )
    )
);

$the_query = new WP_Query($args);
if ($the_query->have_posts()):
?>

<main class="main-body" role="main"><!-- Start main body -->
    <div class="container py-16 mx-auto">
        <div class="text-center mb-14"><h2 class="section_title">Our Must Have</h2></div>
        <div class="home_products">
            <div id="home_products_slider" class="splide">
                <div class="splide__arrows">
                    <button class="splide__arrow splide__arrow--prev h-10 w-10">
                        <i class="fal fa-caret-left"></i>
                    </button>
                    <button class="splide__arrow splide__arrow--next h-10 w-10">
                        <i class="fal fa-caret-right"></i>
                    </button>
                </div>
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php while ( $the_query->have_posts() ) : $the_query->the_post();?>
                        <?php
                            $product = wc_get_product( get_the_ID() );
                            $image_id  = $product->get_image_id();
                            $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                            $term_list = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
                        ?>
                        <li class="splide__slide">
                            <div class="product_card">
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
                        </li>
                        <?php endwhile;?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main><!-- End main body -->
<?php endif;?>