<?php
/**
 * index.php
 *
 * The main template file.
 */

?>
<?php get_header(); ?>

    <?php get_template_part('/templates/partials/main_slider','part')?>
    <?php get_template_part('/templates/partials/shop_by_category','part')?>
    <?php get_template_part('/templates/partials/must_have','part')?>

    <?php if( have_rows('feature_box', 'option') ): ?>
        <section class="feature-panel"><!-- Start feature panel -->
            <div class="grid grid-cols-2 gap-0">
            <?php while( have_rows('feature_box', 'option') ): the_row();
                    $img = get_sub_field('background');
                ?>
                <div class="relative">
                    <img class="w-full" src="<?=$img['url']?>" alt="<?=__($img['title'],'socks')?>">
                    <a class="absolute top-0 left-0 h-full w-full p-6 bg-black bg-opacity-0 hover:bg-opacity-10" href="<?=esc_url(get_sub_field('link'))?>">
                        <h4 class="font-frank text-white h-feature-box"><?=__(get_sub_field('title'),'socks')?></h4>
                        <span class="block font-lato text-white"><?=__(get_sub_field('sub_title'),'socks')?></span>
                    </a>
                </div>
            <?php endwhile;?>
            </div>
        </section><!-- End feature panel -->
    <?php endif;?>

    <?php get_template_part('/templates/partials/hot_products','part')?>

<?php get_footer();?>