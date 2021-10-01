<?php
/**
 * sidebar-shop.php
 *
 * The primary sidebar.
 */
?>

<?php if ( is_active_sidebar( 'sidebar-shop' ) ) : ?>
    <?php dynamic_sidebar( 'sidebar-shop' ); ?>
<?php endif; ?>