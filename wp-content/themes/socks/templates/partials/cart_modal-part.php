<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

global $woocommerce;
?>

<!-- Cart Modal  -->
<div id="overlay" class="fixed top-0 left-0 w-full h-full invisible z-20">
    <div class="relative z-30">
        <div id="backdrop" class="bg-black bg-opacity-50 top-0 left-0 w-full h-full absolute z-0"></div>
        <div id="modal" class="w-2/6 min-h-screen bg-white ml-auto relative animated py-5">
            <button id="close-btn" class="px-5 text-gray-400 flex justify-end items-center text-right w-full focus:outline-none">
                <span class="text-xs font-semibold text-gray-500">Close</span>
                <span class="text-xl ml-2"><i class="fal fa-times"></i></span>
            </button>
            <h3 class="py-6 px-2 text-center font-bold font-lato text-base text-custom-gray tracking-wider">Your shopping cart
                <span id="socks-side-cart-counter" class="font-light">
                    <?php
                        if ($woocommerce->cart->cart_contents_count != 0){
                            echo sprintf(_n('( %d item )', '( %d items )', $woocommerce->cart->cart_contents_count, 'socks'), $woocommerce->cart->cart_contents_count);
                        }
                    ?>
                </span>
            </h3>

            <div id="socks-side-cart">
                <?php woocommerce_mini_cart();?>
            </div>
        </div>
    </div>
</div>
<!-- End Cart Modal  -->
