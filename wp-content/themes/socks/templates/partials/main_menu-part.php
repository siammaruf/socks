<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}
?>

<div class="main-nav-wrap md:w-9/12 pl-8 hidden md:block">
    <?php
        wp_nav_menu(
            array(
                'theme_location' => 'main-menu',
                'menu_class' => 'main-nav flex items-center',
                'container_id' => 'main-nav',
                'container' => 'nav'
            )
        );
    ?>
</div>
<div id="mobile-navigation" class="mobile-navigation block hidden fixed left-0 top-0 z-50 bg-white h-screen w-full overflow-y-auto bg-custom-black">
    <div class="relative pt-7 px-4 pb-4">
        <a href="javaScript:void(0)" id="btn-menu-close" class="absolute top-5 right-5 text-base-color text-lg"><i class="fal fa-window-close"></i></a>
        <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'main-menu',
                    'menu_class' => 'mobile-nav',
                    'container_id' => 'mobile-nav',
                    'container' => 'nav'
                )
            );
        ?>
    </div>
</div>
