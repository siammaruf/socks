<?php
/**
 * sidebar-footer.php
 *
 * The footer sidebar.
 */
?>

<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
    <!-- Start Footer Widget  -->
    <section class="widget_panel py-7 bg-custom-black">
        <div class="container_custom">
            <div class="grid grid-cols-6 gap-4">
                <?php dynamic_sidebar( 'sidebar-footer' ); ?>

                <?php if( have_rows('social_area_list', 'option') ): ?>
                    <div class="social_link col-span-2">
                        <ul id="footer-social-list" class="flex justify-end gap-6">
                            <?php while( have_rows('social_area_list', 'option') ): the_row(); ?>
                            <?php
                                $icon_type = get_sub_field('icon_type');
                                $_icon = get_sub_field('icon');
                             ?>
                            <li>
                                <a target="_blank" href="<?=esc_url(get_sub_field('link'))?>" data-auto-id="true" <?=get_sub_field('id') ? 'id="socks-social-'.get_sub_field('id').'"' : ''?>>
                                    <?php if ($icon_type == 'svg'):?>
                                        <?=get_sub_field('svg_code');?>
                                    <?php else:?>
                                        <img class="social-svg-icon hidden" src="<?=$_icon['url']?>" alt="<?=$_icon['title']?>">
                                    <?php endif;?>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <!-- End Footer Widget  -->
<?php endif; ?>