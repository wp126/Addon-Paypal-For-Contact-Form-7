<?php
/**
* Plugin Name: Addon Paypal For Contact Form 7
* Description: This plugin allows create Addon Paypal With Contact Form 7 plugin.
* Version: 1.0
* Copyright: 2020
* Text Domain: addon-paypal-for-contact-form-7
* Domain Path: /languages 
*/

if (!defined('APWCF7_PLUGIN_DIR')) {
    define('APWCF7_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('APWCF7_PAYPAL_VERSION')) {
    define('APWCF7_PAYPAL_VERSION', '1.1.8');
}

include_once('main/backend/apwcf7-backend.php');
include_once('main/backend/apwcf7-export-csv.php');
include_once('main/backend/apwcf7-cf7-panel.php');
include_once('main/backend/apwcf7-payment.php');
include_once('main/resources/apwcf7-createtable.php');
include_once('main/resources/apwcf7-installation-require.php');
include_once('main/resources/apwcf7-language.php');
include_once('main/resources/apwcf7-load-js-css.php');
