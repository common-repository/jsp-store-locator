<?php

/*
Plugin Name: JSP Store Locator
Plugin URI: http://wordpress.org/plugins/jsp-store-locator/
Description: JSP Store Locator Extension for Wordpress provides a rich interface to search and display dealer/office locations for a company who owns offices in multiple locations using Google Maps or Bing Maps. To display Store Locator on any page/post enter the shortcode [jsp-store-locator]
Author: Ajay Lulia
Version: 1.0
Author URI: http://www.joomlaserviceprovider.com
*/

global $wpdb;

define('JSPSL_PLUGIN_PATH', plugin_dir_path(__FILE__));

define( 'JSPSL_PLUGIN_URL', plugin_dir_url( __FILE__ ));

define( 'JSPSL_DB_PREFIX', $wpdb->prefix."jsp_" );

/* Activation plugin */

register_activation_hook( __FILE__, 'JSPSL_activate' );

function JSPSL_activate() {    

	require_once JSPSL_PLUGIN_PATH . 'includes/class_jsp_store_locator_activation.php';

	JSPSL_Activation::jspsl_activate();

}





require JSPSL_PLUGIN_PATH . 'includes/class_jsp_store_locator.php';

function jspsl_store_locator() {
	$plugin = new JSPSL_JspStoreLocator();
}

jspsl_store_locator();