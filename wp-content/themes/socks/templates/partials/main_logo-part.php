<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

$logo_id = get_theme_mod( 'custom_logo' );
$logo = wp_get_attachment_image_src( $logo_id , 'full' );
?>

<div class="main-logo-wrap w-28 my-3.5">
    <a class="main-logo" href="<?=home_url('/')?>">
        <?php
        if ( has_custom_logo() ):
            echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '">';
        else:
            echo '<h1>'. get_bloginfo( 'name' ) .'</h1>';
        endif;
        ?>
    </a>
</div>
