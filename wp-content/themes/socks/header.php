<?php
/**
 * header.php
 *
 * The header for the theme.
 */
?>

<?php
// Get the favicon.
//$favicon = IMAGES . '/icon.svg';
$favicon = get_field('favicon_icon','option') ? get_field('favicon_icon','option') : IMAGES . '/icon.svg';
$top_notice  = get_field('top_notice','option');
?>

<!DOCTYPE html>
<!--[if IE 8]> <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="description" content="<?php bloginfo( 'description' )?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Favicon and Apple Icons -->
    <link rel="shortcut icon" sizes="32x32" href="<?php echo $favicon; ?>">
    <link rel="apple-touch-icon-precomposed" sizes="192x192" href="<?php echo $favicon; ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if ($top_notice):?>
<section id="top-panel" class="top-panel pl-8 pr-12 py-3 visible text-center bg-base-color relative"><!-- Start Top Panel -->
    <span class="text-white text-csm absolute top-2.5 right-2.5 cursor-pointer" id="top-close-btn">X</span>
    <p class="font-lato md:text-csm text-xs tracking-widest text-white"><?=__($top_notice,'socks')?> <a class="uppercase text-white" href="<?=home_url('/shop')?>">Shop now ></a></p>
</section><!-- End Top Panel -->
<?php endif;?>
<header class="main-header"><!-- Start Main Header -->
    <div class="container-full mx-auto px-4 relative">
        <div class="flex">
            <?php get_template_part('/templates/partials/main_logo','part')?>
            <?php get_template_part('/templates/partials/main_menu','part')?>
            <div class="header-right md:w-3/12 my-3.5 md:flex-shrink flex-auto">
                <div class="header-right-nav flex items-center h-full justify-end">
                    <ul class="flex content-center items-center">
                        <li class="hidden md:block">
                            <a href="#">Help</a>
                        </li>
                        <li class="group relative user-menu">
                            <?php if (is_user_logged_in()):?>
                                <a href="javaScript:void(0)">
                                    <i class="far fa-user text-lg"></i>
                                </a>
                                <ul class="transition duration-300 ease-in-out w-36 opacity-0 group-hover:opacity-100 bg-gray-100 absolute top-6 right-0 rounded-lg px-2 py-3 text-center z-50">
                                    <li>
                                        <a href="<?= esc_url( home_url('/my-account') ); ?>">
                                            Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= esc_url( home_url('/my-account/orders') ); ?>">
                                            Orders
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= esc_url( home_url('/my-account/edit-address') ); ?>">
                                            Edit Address
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= esc_url( home_url('/my-account/edit-account') ); ?>">
                                            Account Details
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= esc_url( wp_logout_url() ); ?>">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            <?php else:?>
                                <a id="socks-user-login-btn" href="#">
                                    <i class="far fa-user text-lg"></i>
                                </a>
                            <?php endif;?>
                        </li>
                        <li class="hidden md:block">
                            <a href="#">
                                <span class="menuicon">
                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31 27"><path d="M27.5 25h-25c-.6 0-1-.4-1-1V8c0-.6.4-1 1-1h25c.6 0 1 .4 1 1v16c0 .6-.4 1-1 1z"></path><path d="M2 8l14 8 12-8"></path></svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="toggleBtn">
                                <span class="menuicon">
                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31 27"><circle cx="13.7" cy="11.1" r="9"></circle><path d="M20.1 17.5l6.8 6.8"></path></svg>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" id="btn-modal" title="<?php _e( 'View your shopping cart' )?>">
                                <?php
                                $cart_counter = WC()->cart->get_cart_contents_count();
                                $cart_counter_class = '';
                                if ($cart_counter == 0){
                                    $cart_counter_class = 'hidden';
                                }
                                ?>
                                <div id="mini-cart-count" class="<?=$cart_counter_class?>">
                                     <span><?php echo $cart_counter ? $cart_counter : '&nbsp;'; ?></span>
                                </div>
                                <span class="MiniCart_CartIndicator">
                                    <svg viewBox="0 0 31 27">
                                        <circle cx="13" cy="24" r="2"></circle>
                                        <circle cx="24" cy="24" r="2"></circle>
                                        <path d="M1.5 2h3s1.5 0 2 2l4 13s.4 1 1 1h13s3.6-.3 4-4l1-5s0-1-2-1h-19"></path>
                                    </svg>
                                </span>
                            </a>
                        </li>
                        <li class="block md:hidden">
                            <a href="javascript:void(0)" id="menu_btn">
                                <i class="fal fa-bars text-lg"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php get_template_part('/templates/partials/main_search','part')?>
        <?php get_template_part('/templates/partials/cart_modal','part')?>
    </div>
</header><!-- End Main Header -->