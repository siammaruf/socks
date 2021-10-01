<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
);
$loop = new WP_Query( $args );
$products_counter = $loop->found_posts;
?>

<div class="shop_body_panel_top flex gap-4">
    <div class="flex-shrink">
        <button id="filter" class="bg-mate-black font-lato text-white px-3 py-2 rounded-sm w-36 h-10 flex items-center open focus:outline-none">
            <span class="block flex-shrink font-medium uppercase text-base">Filter</span>
            <span class="flex-auto text-right block">
                <span class="open-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" class="ml-auto">
                        <path fill="#FFF" fill-rule="nonzero" stroke="#FFF" stroke-width=".5" d="M1.362 3.935A.362.362 0 0 1 1 3.575c0-.198.162-.36.362-.36H7.33V1.36a.362.362 0 0 1 .723 0v1.855h9.585c.2 0 .362.162.362.36 0 .199-.162.36-.362.36H8.053v1.856a.362.362 0 0 1-.723 0V3.935H1.362zM17.638 9.14c.2 0 .362.162.362.36 0 .198-.162.36-.362.36h-4.16v1.855a.362.362 0 0 1-.723 0V9.86H1.362A.362.362 0 0 1 1 9.5c0-.198.162-.36.362-.36h11.393V7.285a.362.362 0 0 1 .724 0V9.14h4.16zm0 5.925c.2 0 .362.162.362.36 0 .198-.162.36-.362.36H6.245v1.855a.362.362 0 0 1-.724 0v-1.855h-4.16a.362.362 0 0 1-.361-.36c0-.199.162-.36.362-.36h4.16v-1.856a.362.362 0 0 1 .723 0v1.856h11.393z"></path>
                    <title>Filter</title></svg>
                </span>
                <span class="close-icon text-xl"><i class="fal fa-times"></i></span>
            </span>
        </button>
    </div>
    <div class="flex-auto">
        <ul id="activeFilters" class="flex flex-wrap items-center pl-10"></ul>
    </div>
    <div class="flex-shrink">
        <span id="socks-wc-product-counter" class="font-lato text-sm text-gray-500 font-light"><?=$products_counter?> Items</span>
        <select id="socks-wc-shorting" class="shorting text-mate-black font-lato text-right text-sm inline-block">
            <option value="newly-added">Newest</option>
            <option value="featured">Featured</option>
            <option value="low-to-height">Price Low To High</option>
            <option value="high-to-low">Price High To Low</option>
            <option value="top-rated">Top Rated</option>
        </select>
    </div>
</div>
