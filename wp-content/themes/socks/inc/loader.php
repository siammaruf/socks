<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to view the page.');
}

// Load Theme Classes
require_once (dirname(__FILE__) . '/classes/ThemeFunction.php');
require_once (dirname(__FILE__) . '/classes/ACFSettings.php');
require_once (dirname(__FILE__) . '/classes/ClassCustomPostType.php');
require_once (dirname(__FILE__) . '/classes/ClassAjaxProductLoad.php');
require_once (dirname(__FILE__) . '/classes/ClassOnPageScripts.php');
require_once (dirname(__FILE__) . '/classes/ClassMainSearch.php');

// Load WC Settings
require_once (dirname(__FILE__) . '/classes/ClassWCSettings.php');

// Load Widgets
require_once (dirname(__FILE__) . '/widgets/ClassWidgetFilterByPrice.php');
require_once (dirname(__FILE__) . '/widgets/ClassWidgetFilterByAttributes.php');
require_once (dirname(__FILE__) . '/widgets/ClassWidgetCustomNav.php');

// Load Api
require_once (dirname(__FILE__) . '/api/SignUpApi.php');
require_once (dirname(__FILE__) . '/api/CustomApi.php');
require_once (dirname(__FILE__) . '/api/ForgetPasswordApi.php');