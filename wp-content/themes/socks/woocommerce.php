<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}
?>

<?php get_header(); ?>

<?php
    if (is_single()){
        get_template_part('/templates/wc-templates/global/breadcrumb-part', 'part');
        get_template_part('/templates/partials/shop/single_product', 'part');
    }else{
        get_template_part('/templates/partials/shop/shop_main', 'part');
    }
?>

<?php get_footer();?>
