<?php
/**
* Plugin Name: Addon Paypal For Contact Form 7
* Description: This plugin allows create Addon Paypal With Contact Form 7 plugin.
* Version: 1.0
* Copyright: 2023
* Text Domain: addon-paypal-for-contact-form-7
* Domain Path: /languages 
*/

// Define Plugin Dir
if (!defined('APWCF7_PLUGIN_DIR')) {
    define('APWCF7_PLUGIN_DIR',plugins_url('', __FILE__));
}

if (!defined('APWCF7_PAYPAL_VERSION')) {
    define('APWCF7_PAYPAL_VERSION', '1.1.8');
}

// Define Plugin File
if (!defined('APWCF7_PLUGIN_FILE')) {
  define('APWCF7_PLUGIN_FILE', __FILE__);
}

// Define Plugin Base Name
if (!defined('APWCF7_BASE_NAME')) {
define('APWCF7_BASE_NAME', plugin_basename(APWCF7_PLUGIN_FILE));
}

include_once('main/backend/apwcf7-backend.php');
include_once('main/backend/apwcf7-export-csv.php');
include_once('main/backend/apwcf7-cf7-panel.php');
include_once('main/backend/apwcf7-payment.php');
include_once('main/resources/apwcf7-createtable.php');
include_once('main/resources/apwcf7-installation-require.php');
include_once('main/resources/apwcf7-language.php');
include_once('main/resources/apwcf7-load-js-css.php');

function APWCF7_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
    if ($plugin_file_name !== plugin_basename(__FILE__)) {
      return $links_array;
    }

    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'paypal-with-contact-form-7') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/addon-paypal-for-contact-form-7/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'paypal-with-contact-form-7') .'</a>';

    return $links_array;

}
add_filter( 'plugin_row_meta', 'APWCF7_support_and_rating_links', 10, 4 );