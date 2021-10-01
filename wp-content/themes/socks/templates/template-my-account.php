<?php
/**
 * Template Name: Socks My Account
 *
 * @package Socks WordPress theme
 */

if( ! defined('ABSPATH') ) die('No direct access!');
$page_description =  get_field('page_descriptions');
$detail_link =  get_field('detail_link');
get_header();
while (have_posts()) : the_post();?>
    <!-- Start Page Hero Panel  -->
    <section class="page_hero_panel">
        <div class="container-full mx-auto px-4 py-8">
            <div class="flex flex-col">
                <div class="w-full">
                    <?php ThemeFunction::socks_get_breadcrumb();?>
                </div>
                <div class="w-full text-center">
                    <?php if(is_user_logged_in()):?>
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php else:?>
                        <h1 class="page-title"><?php _e('Registration','socks'); ?></h1>
                    <?php endif;?>
                    <p class="page_top_content">
                        <?php if ($page_description):?>
                            <?=__($page_description,'socks');?>
                        <?php endif;?>
                        <?php if ($detail_link):?>
                            <a href="<?=$detail_link?>" class="text-xs block hover:underline text-gray-500 mt-2">Details</a>
                        <?php endif;?>
                    </p>

                </div>
            </div>
        </div>
    </section>
    <!-- End Hero Panel  -->

    <main role="main" id="page-<?php the_ID(); ?>" <?php post_class('my-account-page'); ?>>
        <article class="entry-content">
            <?php the_content(); ?>
        </article> <!-- end entry-content -->
    </main>
<?php endwhile;?>
<?php get_footer()?>