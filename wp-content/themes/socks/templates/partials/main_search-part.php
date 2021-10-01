<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}
?>

<!-- Search Modal  -->
<div id="searchBar" class="searchbar bg-white border-b border-gray-600 absolute top-0 left-0 w-full h-full invisible px-2 opacity-0 transition-all duration-500">
    <div class="relative flex justify-center align-middle">
        <a href="javaScript:void(0)" id="toggleBtnClose" class="absolute right-0 md:top-0 top-5 lg:w-9 lg:h-9 w-7 h-7 flex items-center justify-center bg-black hover:bg-base-color text-gray-400 lg:text-4xl text-xl hover:text-white focus:outline-none text-center"><i class="fal fa-times"></i></a>
    </div>
    <div class="container m-auto py-5">
        <form class="flex" action="javaScript:void(0)">
            <button type="submit" class="flex-shrink lg:text-4xl text-xl pr-5 text-gray-500"><i class="far fa-search"></i></button>
            <input type="text" id="socks-main-search" name="socks-main-search" placeholder="Search" class="flex-auto lg:text-3xl text-xl font-lato font-light focus:outline-none leading-10 placeholder-gray-400 text-gray-500 bg-gray-100 px-2 py-1">
        </form>
    </div>
</div>
<!-- End Search Modal  -->

<!-- Start Search Result Area  -->
<div id="main-search-result" class="relative search-result-container w-full">
    <div id="main-search-result-holder"></div>
    <div id="loading-text" class="absolute w-full bg-gray-100 list-none shadow-2xl z-10 hidden">
        <span class="p-5 block">Searching .....</span>
    </div>
</div>
<!-- End Search Result Area  -->
