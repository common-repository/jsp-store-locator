<?php
/*
Author: Ajay Lulia
Version: 1.0
Author URI: http://www.joomlaserviceprovider.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class JSPSL_Model {



	public function jspsl_get_categories(){

		global $wpdb;

		return $wpdb->get_results("select * from ".JSPSL_DB_PREFIX."store_categories");

	}

	public function jspsl_getstorerow($id){

		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("SELECT * FROM  ".JSPSL_DB_PREFIX."stores WHERE ID = %d", $id));

	}



	public function jspsl_getstores(){

		global $wpdb;

		return $wpdb->get_results("SELECT * FROM  ".JSPSL_DB_PREFIX."stores");

	}



	public function jspsl_getcategoryerow($id){

		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("SELECT * FROM  ".JSPSL_DB_PREFIX."store_categories WHERE ID = %d", $id));

	}

	



	public function jspsl_getConfig(){

		global $wpdb;

		return $wpdb->get_row("SELECT * FROM  ".JSPSL_DB_PREFIX."configuration_table");

	}



	public function jspsl_getGoogleplaces(){

		global $wpdb;

		return $wpdb->get_results("select * from ".JSPSL_DB_PREFIX."google_places_table");

	}



	public function jspsl_add_category(){



		if( isset( $_POST['jsp_category_nonce'] ) && wp_verify_nonce( $_POST['jsp_category_nonce'], 'jsp_category_nonce') ) {

			global $wpdb;

			$category_name = sanitize_text_field($_POST['category_name']);

			$category_description = sanitize_text_field($_POST['category_description']);

			$id = sanitize_text_field($_POST['category_id']);
			
			if($id==0){

				$wpdb->insert(JSPSL_DB_PREFIX."store_categories", array("CATEGORY_NAME"=>$category_name,

																"DESCRIPTION"=>$category_description));

			}

			else{

				$wpdb->update(JSPSL_DB_PREFIX."store_categories", array("CATEGORY_NAME"=>$category_name,

																"DESCRIPTION"=>$category_description), array("ID"=>$id));

			}

			wp_redirect(get_admin_url(get_current_blog_id(), 'admin.php?page=jspsl-categories'));

		}

	}

	

	public function jspsl_add_store_form(){

		if( isset( $_POST['jsp_add_store_form_nonce'] ) && wp_verify_nonce( $_POST['jsp_add_store_form_nonce'], 'jsp_add_store_form') ) {

			global $wpdb;



			$branch_name = sanitize_text_field($_POST['branch_name']);

			$category = sanitize_text_field($_POST['category']);

			$country = sanitize_text_field($_POST['country']);

			$state = sanitize_text_field($_POST['state']);

			$city = sanitize_text_field($_POST['city']);

			$area = sanitize_text_field($_POST['area']);

			$zip = sanitize_text_field($_POST['zip']);

			$address = sanitize_text_field($_POST['address']);

			$latitude = sanitize_text_field($_POST['latitude']);

			$lognitude = sanitize_text_field($_POST['lognitude']);



			$contact_person = sanitize_text_field($_POST['contact_person']);

			$gender = sanitize_text_field($_POST['gender']);

			$email = sanitize_email($_POST['email']);

			$website = sanitize_url($_POST['website']);

			$contact_number = sanitize_text_field($_POST['contact_number']);

			$description = sanitize_text_field($_POST['description']);

			$facebook = sanitize_text_field($_POST['facebook']);

			$twitter = sanitize_text_field($_POST['twitter']);

			$video_url = sanitize_text_field($_POST['video_url']);

			$pointer_icon = sanitize_text_field($_POST['pointer_icon']);

			$address1 = $zip. ' ' .$address. ' ' . $area . ' ' . $city . ' ' . $state . ' ' . $country;	



			$symbol = array(" ", ",", "_");

			$keyword = str_replace($symbol, "+", $address1);

			$google_key = $this->jspsl_getConfig()->GOOGLE_MAP_KEY;

			$geocode 	= $this->jspsl_file_get_contents_curls('https://maps.google.com/maps/api/geocode/json?key='.$google_key.'&address=' . $keyword . '&sensor=false');

			$geocode = json_decode($geocode);



			if(isset($geocode->results[0]->geometry->location->lat) or isset($geocode->results[0]->geometry->location->lng))

			{

				$latitude=$geocode->results[0]->geometry->location->lat;

				$lognitude=$geocode->results[0]->geometry->location->lng;

			}



			$id = sanitize_text_field($_POST['id']);

			if($id==0){

			$wpdb->insert(JSPSL_DB_PREFIX."stores", array("ID"=>NULL,

													"NAME"=>$branch_name,

													"CATEGORY_ID"=>$category,

													"COUNTRY"=>$country,

													"STATE"=>$state,

													"CITY"=>$city,

													"AREA"=>$area,

													"ZIP"=>$zip,

													"LATITUDE"=>$latitude,

													"LONGITUDE"=>$lognitude,

													"ADDRESS"=>$address,



													"CONTACT_PERSON"=>$contact_person,

													"GENDER"=>$gender,

													"EMAIL"=>$email,

													"WEBSITE"=>$website,

													"CONTACT_NUMBER"=>$contact_number,

													"DESCRIPTION"=>$description,

													"FACEBOOK"=>$facebook,

													"TWITTER"=>$twitter,

													"STORE_VIDEO_URL"=>$video_url,

													"POINTER_IMAGE"=>$pointer_icon

													));

			}else{

				$wpdb->update(JSPSL_DB_PREFIX.'stores', array("NAME"=>$branch_name,

													"CATEGORY_ID"=>$category,

													"COUNTRY"=>$country,

													"STATE"=>$state,

													"CITY"=>$city,

													"AREA"=>$area,

													"LATITUDE"=>$latitude,

													"LONGITUDE"=>$lognitude,

													"ADDRESS"=>$address,

													"CONTACT_PERSON"=>$contact_person,

													"GENDER"=>$gender,

													"EMAIL"=>$email,

													"WEBSITE"=>$website,

													"CONTACT_NUMBER"=>$contact_number,

													"DESCRIPTION"=>$description,

													"FACEBOOK"=>$facebook,

													"TWITTER"=>$twitter,

													"STORE_VIDEO_URL"=>$video_url,

													"POINTER_IMAGE"=>$pointer_icon,

													"CUSTOM_FIELD"=>$custom_field

													), array('ID' => $id));

			}

			wp_redirect(get_admin_url(get_current_blog_id(), 'admin.php?page=jspsl-manage-stores'));

			

		}

	}



	public function jspsl_configuration_setting(){



		if( isset( $_POST['jsp_configuration_nonce'] ) && wp_verify_nonce( $_POST['jsp_configuration_nonce'], 'jsp_configuration_nonce') ) {

			global $wpdb;

			//print_r(sanitize_text_field($_POST['map_type']));die;

			$data = array(

							'MAP_TITLE'=>sanitize_text_field($_POST['map_title']),

							'MAP_TYPE'=>sanitize_text_field($_POST['map_type']),

							'GOOGLE_MAP_KEY'=>sanitize_text_field($_POST['google_map_key']),

							'Bing_MAP_KEY'=>sanitize_text_field($_POST['bing_map_key']),

							'MAP_HEIGHT'=>sanitize_text_field($_POST['map_height']),

							'MAP_ZOOM'=>sanitize_text_field($_POST['map_zoom']),

							'POINTER_TYPE'=>sanitize_text_field($_POST['pointer_type']),

							'DEFAULT_LOCATION_OVERRIDE'=>sanitize_text_field($_POST['default_location_override']),

							'LATITUDE_OVERRIDE'=>sanitize_text_field($_POST['latitude']),							

							'LONGITUDE_OVERRIDE'=>sanitize_text_field($_POST['longitude']),

							'MAP_LANGUAGE'=>sanitize_text_field($_POST['map_language']),

							'GOOGLE_MAP_STYLES_JSON'=>sanitize_text_field($_POST['mapstyle']),

							'DISPLAY_SEARCH_BOX'=>sanitize_text_field($_POST['search_box']),

							'SEARCH_UNIT'=>sanitize_text_field($_POST['unit']),

							'LOCATE_ME'=>sanitize_text_field($_POST['locate_me']),

							'LOCATE_ME_RADIUS_RANGE'=>sanitize_text_field($_POST['locate_me_range']),

							'GOOGLE_AUTOCOMPLETE'=>sanitize_text_field($_POST['autocomplete']),
							'FRONTEND_MAP_TYPE'=>sanitize_text_field($_POST['frontend_map_type']),
							'MAP_TEMPLATE'=>sanitize_text_field($_POST['map_template']),

						);

			$wpdb->update(JSPSL_DB_PREFIX.'configuration_table', $data, array("ID"=>1));



			wp_redirect(get_admin_url(get_current_blog_id(), 'admin.php?page=jspsl-configuration'));

		}



	}


	public function jspsl_file_get_contents_curls($url)

		{
			$data = wp_remote_request($url);
			return $data['body'];

		}



	public function jspsl_add_store_media(){

		if( isset( $_POST['jsp_store_media'] ) && wp_verify_nonce( $_POST['jsp_store_media'], 'jsp_store_media') ){

			global $wpdb;			



			if(isset($_POST['type'])&&sanitize_text_field($_POST['type'])=="locations"){

				$target_path = JSPSL_PLUGIN_PATH."/uploads/locations/";

				$id=sanitize_text_field($_POST['id']);

				$previous_data = $this->jspsl_getstorerow($id)->STORE_IMAGE;

				$table=JSPSL_DB_PREFIX."stores";

				$column = "STORE_IMAGE";

				$redirect = get_admin_url(get_current_blog_id(), 'admin.php?page=jspsl-add-new-store&id='.$id);

			}






			if(isset($_POST['upload'])&&sanitize_text_field($_POST['upload'])=="upload"){				

				$arr = array();	

				
				// $_FILES array has been sanitized below
				foreach ($_FILES['media']['name'] as $key => $value) { 


					list($name, $ext) = explode('.', sanitize_file_name($value));   // sanitizing file name value

					$new_name = $id."_".time()."_".$key.".".$ext;

					if(move_uploaded_file($_FILES["media"]["tmp_name"][$key], $target_path.$new_name)){

						array_push($arr, $new_name);

					}

				}


				$str = implode($arr, ",");

				if($previous_data!=""){

					$str = $previous_data.",".$str;

				}

				

				$data = array($column=>$str);

				$wpdb->update($table, $data, array("ID"=>$id));

				wp_redirect($redirect);

			}



			if(isset($_POST['delete'])&&sanitize_text_field($_POST['delete'])=="delete"){

				if(isset($_POST['list'])){

					$delete_list = $_POST['list']; // $_POST['list'] array has been sanitized in foreach loop.
					$list = explode(",", $previous_data);

					foreach ($delete_list as $key => $value) {

						$delete_key = array_search(sanitize_text_field($value), $list); // sanitizing the $_POST array values

						unset($list[$delete_key]);

						unlink($target_path.$value);

					}

					$str = implode($list, ",");

					$data = array($column=>$str);

					$wpdb->update($table, $data, array("ID"=>$id));

				}

				wp_redirect($redirect);

			}

		}

}



public function jspsl_add_sample_data(){

	if( isset( $_POST['jsp_sample_data'] ) && wp_verify_nonce( $_POST['jsp_sample_data'], 'jsp_sample_data') ){

		$row = $this->jspsl_getstores();

		global $wpdb;

		if(count($row)==0){



$sample_data = array(

  array('NAME' => '1 Wall Street','ADDRESS' => '1 Wall St., New York, NY, United States','LATITUDE' => '40.707222','LONGITUDE' => '-74.011667','ZIP' => '10286','STORE_IMAGE' => 'wall-street.jpg','COUNTRY' => 'United States','STATE' => 'Alabama','CITY' => 'Alabaster','AREA' => 'Southside Baptist Church', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => '125 Old Broad Street','ADDRESS' => '125 Old Broad St London, UK','LATITUDE' => '51.5145558','LONGITUDE' => '-0.0859578','ZIP' => 'EC2N 1AR','STORE_IMAGE' => '3_125 old broad street.jpg','COUNTRY' => 'United Kingdom','STATE' => 'London','CITY' => 'City of London','AREA' => 'old broad street', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'BMW Headquarters','ADDRESS' => 'Petuelring 124, M','LATITUDE' => '48.176871','LONGITUDE' => '11.562567','ZIP' => '80809','STORE_IMAGE' => '6_bmw_headquarters.jpg','COUNTRY' => 'Germany','STATE' => 'Munich','CITY' => 'Munich','AREA' => 'Petuelring', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'Bombay Stock Exchange','ADDRESS' => 'Phiroze Jeejeebhoy Towers, Dalal Street, Kala Ghoda, Fort','LATITUDE' => '18.9294644','LONGITUDE' => '72.8331099','ZIP' => '400001','STORE_IMAGE' => '10_bombay stock exchange','COUNTRY' => 'India','STATE' => 'Maharashtra','CITY' => 'Mumbai','AREA' => 'Andheri', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'Constitution Hill Johannesburg','ADDRESS' => '11 Kotze St, Johannesburg, 2017, South Africa','LATITUDE' => '-26.188885','LONGITUDE' => '28.042514','ZIP' => '2001','STORE_IMAGE' => '8_constitution hill johannesburg.jpg','COUNTRY' => 'South Africa','STATE' => 'Johannesburg','CITY' => 'Braamfontein','AREA' => 'Queens rd', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'Dubai World Trade Centre','ADDRESS' => 'Sheikh Zayed Road, Dubai, United Arab Emirates','LATITUDE' => '25.0627184','LONGITUDE' => '55.1307613','ZIP' => '9292','STORE_IMAGE' => '4_dubai world trade centre.jpg','COUNTRY' => 'United Arab Emirates','STATE' => 'Dubai','CITY' => 'Dubai','AREA' => '2nd Zabeel road', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'Googleplex California','ADDRESS' => '600 Amphitheatre Pkwy, Mountain View, United States','LATITUDE' => '37.4232059','LONGITUDE' => '-122.2854036','ZIP' => 'CA 94043','STORE_IMAGE' => '9_googleplex california.jpg','COUNTRY' => 'United States','STATE' => 'Alabama','CITY' => 'Alabaster','AREA' => 'Southside Baptist Church', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'M.C.G. Australia','ADDRESS' => 'Yarra Park, Melbourne, Victoria','LATITUDE' => '-37.7940597','LONGITUDE' => '145.0103998','ZIP' => '8002','STORE_IMAGE' => '5_m.c.g. australia.jpg','COUNTRY' => 'Australia','STATE' => 'Victoria','CITY' => 'Melbourne','AREA' => 'Brunton Ave', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'Otkrytie Arena Moscow','ADDRESS' => 'Volokolamskoye sh., 69, Moskva, Russia, 125424','LATITUDE' => '55.817945','LONGITUDE' => '37.440669','ZIP' => '69','STORE_IMAGE' => '7_otkrytie arena moscow.jpg','COUNTRY' => 'Russia','STATE' => 'Moscow','CITY' => 'Moscow','AREA' => 'Volokolamskoype sh', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png'),

  array('NAME' => 'The World Bank Headquaters','ADDRESS' => '1818 H Street Northwest, Washington, United States','LATITUDE' => '38.8990253','LONGITUDE' => '-77.0424279','ZIP' => 'DC 20433','STORE_IMAGE' => '2_the world bank headquaters.jpg','COUNTRY' => 'United States','STATE' => 'Alabama','CITY' => 'Alabaster','AREA' => 'Southside Baptist Church', 'CATEGORY_ID'=>1,'POINTER_IMAGE'=>'pointer.png')

);



			foreach ($sample_data as $key => $value) {

				$wpdb->insert(JSPSL_DB_PREFIX.'stores', $value);

			}

			die(json_encode(array("status"=>"OK")));

		}

	}



}



}