<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $product;
?>

<?php get_header(); ?>
<!-- Start Shop Notice Area -->
<?php //woocommerce_output_all_notices() ?>
<!-- End Shop Notice -->
<!-- Start Shop Products Panel  -->
<?php while ( have_posts() ) : ?>
    <?php the_post(); ?>
    <?php get_template_part('/templates/wc-templates/content', 'single-product');?>
<?php endwhile; // end of the loop. ?>
<!-- End Shop Products Panel  -->
<?php get_footer(); ?>
