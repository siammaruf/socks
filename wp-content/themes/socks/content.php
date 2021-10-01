<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/**
 * content.php
 *
 * The default template for displaying content.
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('grid-item'); ?>>
    <div class="blog-content">
        <h1 class="blog-title"><?php the_title()?></h1>

        <div class="entry-content">
            <?php the_content();?>
        </div>
    </div>
</div>
