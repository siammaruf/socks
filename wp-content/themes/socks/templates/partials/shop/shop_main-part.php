<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$term_slug = '';
$page_title = get_the_title();
if(is_product_category()){
    $term = get_queried_object();
    $term_slug = $term->slug;
    $page_title = $term->name;
}elseif (is_shop()){
    $page_title = 'Shop';
}

?>

<!-- Start Page Hero Panel  -->
<section class="page_hero_panel">
    <div class="container-full mx-auto px-4 py-8">
        <div class="flex flex-col">
            <div class="w-full">
                <?php ThemeFunction::socks_get_breadcrumb();?>
            </div>
            <div class="w-full text-center">
                <h1 class="page-title"><?=$page_title?></h1>
                <p class="page_top_content">
                    Limited time only. Prices as marked.
                    <a href="#" class="text-xs block hover:underline text-gray-500 mt-2">Details</a>
                </p>
            </div>
        </div>
    </div>
</section>
<!-- End Hero Panel  -->

<!-- Start Shop Products Panel  -->
<section id="shop_body_panel" class="shop_body_panel" data-page-term="<?=$term_slug?>">
    <div class="container mx-auto px-4 py-8">
        <?php get_template_part('/templates/partials/shop/top_filter','part')?>
        <div class="flex py-8 gap-5">
            <?php get_template_part('/templates/partials/shop/sidebar','part')?>
            <div class="flex-auto transition duration-500 ease-in">

                <div class="student-blog-posts">
                    <div id="socksLoadProducts"></div>
                    <div class="loading-anim h-100" style="justify-content:center">
                        <div class="text-center">
                            <div class="loading-anim">
                                <div class="animation-bar">
                                    <div class="circle"></div>
                                    <p>Loading</p>
                                </div>
                            </div>
                            <span>Please wait....</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>