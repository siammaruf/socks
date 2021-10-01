<?php
/**
 * functions.php
 *
 * The theme's functions and definitions.
 */

/**
 * ----------------------------------------------------------------------------------------
 * 1.0 - Define constants.
 * ----------------------------------------------------------------------------------------
 */
define( 'THEMEROOT', get_template_directory_uri() );
define( 'CHILD_THEME_ROOT', get_stylesheet_directory_uri() );
define( 'IMAGES', THEMEROOT . '/images' );
define( 'SCRIPTS', THEMEROOT . '/js' );

/**
 * ----------------------------------------------------------------------------------------
 * 2.0 - Load the framework.
 * ----------------------------------------------------------------------------------------
 */
require_once (dirname(__FILE__) . '/framework/inc.php');

/**
 * ----------------------------------------------------------------------------------------
 * 3.0 - Load the Theme Classes.
 * ----------------------------------------------------------------------------------------
 */
require_once (dirname(__FILE__) . '/inc/loader.php');