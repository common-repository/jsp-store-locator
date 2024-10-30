<?php
/*
Author: Ajay Lulia
Version: 1.0
Author URI: http://www.joomlaserviceprovider.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class JSPSL_Activation {

	public static function jspsl_activate() {

		JSPSL_Activation::jspsl_add_initial_tables();

	}

	public static function jspsl_add_initial_tables() {

		global $wpdb;

		$charset_collate = 'utf8';

		$prefix = JSPSL_DB_PREFIX;
		
		/* Stores Table */

		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}stores` (

				`ID` INT(11) NOT NULL AUTO_INCREMENT,

				`NAME` varchar(50) NOT NULL,

				`COUNTRY` varchar(50) NOT NULL,

				`STATE` varchar(50) NOT NULL,

				`CITY` varchar(50) NOT NULL,

				`ZIP` varchar(50) NOT NULL,

				`AREA` varchar(50) NOT NULL,

				`ADDRESS` varchar(256) NOT NULL,

				`LATITUDE` varchar(256) NOT NULL,

				`LONGITUDE` varchar(256) NOT NULL,

				`CATEGORY_ID` INT(11) NOT NULL,

				`CONTACT_PERSON` varchar(256) NOT NULL,

				`GENDER` varchar(256) NOT NULL,

				`EMAIL` varchar(30) NOT NULL,

				`WEBSITE` varchar(30) NOT NULL,

				`CONTACT_NUMBER` varchar(256) NOT NULL,

				`DESCRIPTION` varchar(300) NOT NULL,

				`FACEBOOK` varchar(50) NOT NULL,

				`TWITTER` varchar(50) NOT NULL,

				`STORE_IMAGE` varchar(256) NOT NULL,

				`STORE_IMAGE_DISPLAY` INT(2) NOT NULL,

				`STORE_VIDEO_URL` varchar(256) NOT NULL,

				`POINTER_IMAGE` varchar(256) NOT NULL,

				`CUSTOM_FIELD` TEXT NOT NULL,

				PRIMARY KEY (`ID`)

				);";


		require_once(ABSPATH.'wp-admin/includes/upgrade.php');


		dbDelta($sql);


		$sql = "ALTER TABLE {$prefix}stores CHARACTER SET utf8;";

		$wpdb->query( $sql );


			/* Categories Fields Table */


		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}store_categories` (

				`ID` INT(11) NOT NULL AUTO_INCREMENT,

				`CATEGORY_NAME` varchar(256) NOT NULL,

				`DESCRIPTION` varchar(256) NOT NULL,

				`PUBLISHED` INT(11) NOT NULL,

				PRIMARY KEY (`ID`)

			);";

			

			dbDelta($sql);

			$wpdb->insert($prefix.'store_categories', array("ID"=>1, "CATEGORY_NAME"=>"Uncategorised", "DESCRIPTION"=>"Uncategorised"));	


			$sql = "ALTER TABLE {$prefix}store_categories CHARACTER SET utf8;";

			$wpdb->query( $sql );

			/* configuration Fields Table */


		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}configuration_table` (

				`ID` INT(11) NOT NULL AUTO_INCREMENT,

				`MAP_TITLE` varchar(256) NOT NULL,

				`MAP_TYPE` INT(11) NOT NULL DEFAULT 0,

				`GOOGLE_MAP_KEY` varchar(200) NOT NULL,

				`BING_MAP_KEY` varchar(200) NOT NULL,

				`GOOGLE_MAP_STYLES_JSON` TEXT NOT NULL,

				`POINTER_TYPE` varchar(256) NOT NULL,

				`MAP_POINTER_IMAGE` varchar(256) NOT NULL,

				`MAP_HEIGHT` varchar(20) NOT NULL,

				`MAP_ZOOM` varchar(20) NOT NULL,

				`MAP_LANGUAGE` varchar(256) NOT NULL,

				`DEFAULT_LOCATION_OVERRIDE` INT(11) NOT NULL, 

				`LATITUDE_OVERRIDE` varchar(256) NOT NULL,

				`LONGITUDE_OVERRIDE` varchar(256) NOT NULL,

				`SEARCH_UNIT` varchar(256) NOT NULL DEFAULT 'miles',

				`DISPLAY_SEARCH_BOX` BOOLEAN NOT NULL DEFAULT '1',

				`LOCATE_ME` tinyint(1) NOT NULL DEFAULT '1',

				`LOCATE_ME_RADIUS_RANGE` varchar(20) NOT NULL,

				`GOOGLE_AUTOCOMPLETE` int(200) NOT NULL,
				
				`FRONTEND_MAP_TYPE` int(200) NOT NULL,

				`MAP_TEMPLATE` varchar(256) NOT NULL,
				
				PRIMARY KEY (`ID`)

			);";

			dbDelta($sql);

			$wpdb->insert($prefix.'configuration_table', array("ID"=>1,"MAP_TYPE" => 0,"POINTER_TYPE" => "default", "MAP_HEIGHT" => 400, "MAP_ZOOM" => 12, "MAP_LANGUAGE" => "English","LATITUDE_OVERRIDE" => 40.7128, "LONGITUDE_OVERRIDE" => 74.0060, "SEARCH_UNIT" => "miles", "DISPLAY_SEARCH_BOX" => 1,"LOCATE_ME" => 1,"LOCATE_ME_RADIUS_RANGE" => 100, "GOOGLE_AUTOCOMPLETE" => 1, "FRONTEND_MAP_TYPE" => 0, "MAP_TEMPLATE" => "classic"));	
			$sql = "ALTER TABLE {$prefix}configuration_table CHARACTER SET utf8;";

			$wpdb->query( $sql );

			/* Map Language Table */

		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}map_language` (

				`ID` INT(11) NOT NULL AUTO_INCREMENT,

				`MAP_LANGUAGE` varchar(256) NOT NULL,

				`MAP_LANGUAGE_CODE` varchar(256) NOT NULL,

				PRIMARY KEY (`ID`)

			);";

			dbDelta($sql);

			$sql = "ALTER TABLE {$prefix}map_language CHARACTER SET utf8;";

			$wpdb->query( $sql );


		$sql = "CREATE TABLE IF NOT EXISTS `{$prefix}mapstyle` (

				`ID` INT(11) NOT NULL AUTO_INCREMENT,

				`THEME_NAME` varchar(256) NOT NULL,

				`THEME_JSON` text NOT NULL,

				PRIMARY KEY (`ID`)

			);";


			dbDelta($sql);

			$silverJson = '[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]';

			$retroJson = '[{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}]';

			$darkJson = '[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]';

			$nightJson = '[{"elementType":"geometry","stylers":[{"color":"#242f3e"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#746855"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#242f3e"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#263c3f"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#6b9a76"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#38414e"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#212a37"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#9ca5b3"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#746855"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#1f2835"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#f3d19c"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#2f3948"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#17263c"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#515c6d"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#17263c"}]}]';

			$aubergineJson = '[{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#64779e"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#334e87"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#023e58"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#283d6a"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#6f9ba5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#023e58"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#3C7680"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#304a7d"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2c6675"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#255763"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#b0d5ce"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#023e58"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#283d6a"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#3a4762"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0e1626"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#4e6d70"}]}]';
			

			$sql = "ALTER TABLE {$prefix}mapstyle CONVERT TO CHARACTER SET utf8;";

			$wpdb->query($sql);
			

			$sql = "INSERT INTO {$prefix}mapstyle (THEME_NAME,THEME_JSON) 

			VALUES ('Standard','[]'),('Silver','{$silverJson}'),('Retro','{$retroJson}'),('Dark','{$darkJson}'),('Night','{$nightJson}'),('Aubergine','{$aubergineJson}'),('Custom','[]');";

			$wpdb->query($sql);

	}

}

?>