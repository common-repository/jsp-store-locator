<?php
/*
Author: Ajay Lulia
Version: 1.0
Author URI: http://www.joomlaserviceprovider.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class JSPSL_JspStoreLocator {

	

	public function __construct(){

		$this->jspsl_load_dependencies();

		$this->plugin_public = new JSPSL_Public();

		$this->plugin_admin = new JSPSL_Admin();

		$this->admin_model = new JSPSL_Model();



		

		if (is_admin())

			$this->jspsl_define_admin_hooks();

		else

			$this->jspsl_define_public_hooks();

	}



	public function jspsl_define_public_hooks() {

		add_shortcode( 'jsp-store-locator', array($this->plugin_public,'jspsl_frontendStoreLocator'));

	}



	public function jspsl_define_admin_hooks() {

		add_action('admin_menu', array($this,'jspsl_add_admin_menu'));

		add_action('admin_post_jspsl_add_store_form', array($this->admin_model,'jspsl_add_store_form'));

		add_action('admin_post_jspsl_configuration', array($this->admin_model,'jspsl_configuration_setting'));

		add_action('admin_post_jspsl_import_export', array($this->admin_model,'jspsl_import_export_locations'));

		add_action('admin_post_jspsl_add_category', array($this->admin_model,'jspsl_add_category'));

		add_action('admin_post_jspsl_add_store_media', array($this->admin_model,'jspsl_add_store_media'));

		add_action('admin_post_jspsl_google_places', array($this->admin_model,'jspsl_google_places_search'));

		add_action('wp_ajax_jspsl_add_google_places', array($this->admin_model,'jspsl_add_google_places'));

		add_action('wp_ajax_jspsl_add_sample_data', array($this->admin_model,'jspsl_add_sample_data'));

	}



	private function jspsl_load_dependencies() {

		require_once JSPSL_PLUGIN_PATH . 'public/class_jsp_store_locator_public.php';

		require_once JSPSL_PLUGIN_PATH . 'admin/class_jsp_store_locator_admin.php';

		require_once JSPSL_PLUGIN_PATH . 'admin/class_jsp_store_locator_Model.php';

	}



	public function jspsl_add_admin_menu(){

		add_Menu_page('JSP Store Locator', 'JSP Store Locator', 'delete_posts', 'jspsl-plugin', array($this->plugin_admin, 'jspsl_manage_stores'));

		add_submenu_page( 'jspsl-plugin', 'Add New Store', 'Add New Store', 'delete_posts', 'jspsl-add-new-store', array($this->plugin_admin, 'jspsl_add_new_store'));

		add_submenu_page( 'jspsl-plugin', 'Manage Stores', 'Manage Stores', 'delete_posts', 'jspsl-manage-stores', array($this->plugin_admin, 'jspsl_manage_stores'));

		add_submenu_page( 'jspsl-plugin', 'Categories', 'Categories', 'delete_posts', 'jspsl-categories', array($this->plugin_admin, 'jspsl_manage_category'));

		add_submenu_page( 'jspsl-plugin', 'Add New Categories', 'Add New Categories', 'delete_posts', 'jspsl-add-new-categories', array($this->plugin_admin, 'jspsl_add_new_category'));

		add_submenu_page( 'jspsl-plugin', 'Configuration', 'Configuration', 'delete_posts', 'jspsl-configuration', array($this->plugin_admin,'jspsl_configuration'));

		add_submenu_page( 'jspsl-plugin', 'Import/Export', 'Import/Export (Pro)', 'delete_posts', 'jspsl-import-export', array($this->plugin_admin, 'jspsl_import_export_form'));

		add_submenu_page( 'jspsl-plugin', 'Google Places', 'Google Places (Pro)', 'delete_posts', 'jspsl-google-places', array($this->plugin_admin, 'jspsl_google_places'));

		add_submenu_page( 'jspsl-plugin', 'Branch Hit', 'Branch Hit (Pro)', 'delete_posts', 'jspsl-branch-hit', array($this->plugin_admin, 'jspsl_branch_hit'));

		add_submenu_page( 'jspsl-plugin', 'Search Hit', 'Search Hit (Pro)', 'delete_posts', 'jspsl-search-hit', array($this->plugin_admin, 'jspsl_search_hit'));

		

	}



}