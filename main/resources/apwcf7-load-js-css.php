<?php

/* frontend style and script */
add_action( 'wp_enqueue_scripts','apwcf7_load_front_script_style');
 function apwcf7_load_front_script_style() {
    wp_enqueue_script('jquery', false, array(), false, false);
    wp_enqueue_style( 'APWCF7-front-style', APWCF7_PLUGIN_DIR . '/assets/css/front_style.css', false, '1.0.0' );
    wp_enqueue_script( 'APWCF7-front-script', APWCF7_PLUGIN_DIR . '/assets/js/front.js', false, '1.0.0' );
}

/* backend style and script */
add_action( 'admin_enqueue_scripts', 'apwcf7_load_admin_script_style');
function apwcf7_load_admin_script_style() {
    wp_enqueue_style( 'APWCF7-back-style', APWCF7_PLUGIN_DIR . '/assets/css/back_style.css', false, '1.0.0' );
     wp_enqueue_script( 'APWCF7-back-script', APWCF7_PLUGIN_DIR . '/assets/js/back_script.js', false, '1.0.0' );
      $apwcf7_array_img = APWCF7_PLUGIN_DIR;
            wp_localize_script( 'APWCF7-back-script', 'apwcf7_name', array('apwcf7_array_img'=>$apwcf7_array_img) );
}