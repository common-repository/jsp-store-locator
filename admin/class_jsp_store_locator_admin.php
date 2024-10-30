<?php
/*
Author: Ajay Lulia
Version: 1.0
Author URI: http://www.joomlaserviceprovider.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once (JSPSL_PLUGIN_PATH . 'admin/class_List_Table.php');



class JSPSL_Admin {



	public function __construct(){


		require_once JSPSL_PLUGIN_PATH . 'admin/class_jsp_store_locator_Model.php';

		$pages = array('jspsl-plugin','jspsl-add-new-store','jspsl-manage-stores', 'jspsl-categories','jspsl-add-new-categories','jspsl-configuration', 'jspsl-import-export', 'jspsl-google-places', 'jspsl-branch-hit', 'jspsl-search-hit');

		$current_page = sanitize_text_field($_REQUEST['page']);

		if(in_array($current_page, $pages)){

			wp_enqueue_style('custom_style', JSPSL_PLUGIN_URL."admin/css/custom_style.css");
		}

		$this->model = new JSPSL_Model();
	}

	public function jspsl_get_modal_window(){
						echo '<div id="myModal" class="modal">

					  <!-- Modal content -->
						<div class="modal-content">
							<span class="close">&times;</span>
							<p>Please download the <a href="http://www.joomlaserviceprovider.com" target="_blank">pro version </a></p>
						</div>

					</div>';
	}



	public function jspsl_sample_data(){

		if(count($this->model->jspsl_getstores())!=0){

			return false;

		}

?>

	 <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="jsp_add_sample_data">

	            <?php settings_fields('wporg_options');

	                do_settings_sections('wporg');	                

	                $jsp_sample_data = wp_create_nonce('jsp_sample_data');

	                ?>

	            <input type="hidden" name="action" value="jspsl_add_sample_data">

	            <input type="hidden" name="jsp_sample_data" value="<?php echo $jsp_sample_data ?>">



			<div class="notice" style="padding:15px;">

				<button class="button action" id="jsp_sample_data_submit_form">Import Sample Data</button>

		    </div>

	</form>

		    <div id="message" class="notice is-dismissible" style="display:none;"><p id="msg_content"></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>





	<script type="text/javascript">

		jQuery("#jsp_sample_data_submit_form").click(function(){

			var data = jQuery("#jsp_add_sample_data").serialize();			

			event.preventDefault();

			jQuery.ajax({

				url : ajaxurl,

				type: 'POST',

				data : data,

				dataType : 'json',

				success: function (response) {

					console.log(response);

					if(response.status=="OK"){

						jQuery("#message").addClass('notice-success');

						jQuery("#message").show();

						jQuery('#msg_content').text("Sample Data Imported");

						location.reload();

					}

					else{

						jQuery("#message").addClass('notice-error');

						jQuery("#message").show();

						jQuery('#msg_content').text("Sample Data Cannot be Imported now");

					}



				}

			});

		})

	</script>

<?php

	}



    	public function jspsl_manage_stores(){

    		$this->jspsl_sample_data();

    		global $wpdb;

    		$table = new JSPSL_Link_List_Table(); 

    		$table->prepare_items();

    		$message = '';

        if ('delete' === $table->current_action()) {
            $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted', 'cltd_example')) . '</p></div>';

        }

        ?>



	<div class="wrap">

	    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>

	    <h2><?php _e('Stores', 'cltd_example')?> <a class="add-new-h2"

	        href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jspsl-add-new-store');?>"><?php _e('Add new', 'cltd_example')?></a>

	    </h2>

	    <?php echo $message; ?>

	    <form id="stores-table" method="GET">

	        <input type="hidden" name="page" value="<?php echo sanitize_text_field($_REQUEST['page']) ?>"/>

	        <?php $table->display() ?>

	    </form>

	</div>

	<?php

	    }







	    	public function jspsl_add_new_store() {

	    		 $default = array(

	            'ID' => 0,

	            'NAME' => '',

	            'COUNTRY' => '',

	            'STATE' => '',

	            'CITY' => '',

	            'AREA' => '',

	            'ADDRESS' => '',

	            'LATITUDE' => '',

	            'LONGITUDE' => '',

	            'POINTER_IMAGE'=> 'pointer.png'

	        	);

	    		$item = json_decode(json_encode($default), FALSE);

	    		if(isset($_REQUEST['id'])){

	    			$item = $this->model->jspsl_getstorerow(sanitize_text_field($_REQUEST['id']));

	    			if (!$item) {

	                    $item = json_decode(json_encode($default), FALSE);

	                    $notice = __('Item not found', 'cltd_example');

	                    echo "<h1>$notice</h1>";die;

	                }

	    		}

	    		 if (!current_user_can('manage_options')) {

	            		return;

	        		}

	    		$categories = $this->model->jspsl_get_categories();

	    		?>

	<h2 class="nav-tab-wrapper wp-clearfix">

	    <a href="#" class="nav-tab nav-tab-active" id="show_form">Add Store details</a>

	    <a href="#" class="nav-tab" id="show_media">Add Media</a>

	</h2>

	<div class="main" id="form_section" style="width:100%;display: inline-block;">

	    <div class="form-group" style="margin:50px 0px;">

	        <h1><?= esc_html(get_admin_page_title()); ?></h1>

	        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="jsp_add_store_form">

	            <?php settings_fields('wporg_options');

	                do_settings_sections('wporg');

	                submit_button('Save Settings');

	                $jsp_add_store_nonce = wp_create_nonce('jsp_add_store_form');

	                ?>

	            <input type="hidden" name="action" value="jspsl_add_store_form">

	            <input type="hidden" name="jsp_add_store_form_nonce" value="<?php echo $jsp_add_store_nonce ?>" />

	            <input type="hidden" name="id" value="<?php echo $item->ID; ?>">

	            <div class="col-2">

	                <fieldset>

	                    <legend class="legend">Location Name</legend>

	                    <input type="text" placeholder="Location Name" value="<?php echo $item->NAME; ?>" name="branch_name"  >

	                    <legend>Category</legend>

	                    <select name="category" id="">

	                    <?php foreach ($categories as $key => $value) {

	                        echo "<option value='$value->ID'";

	                        if($value->ID == $item->CATEGORY_ID){

	                        	echo "selected";

	                        }

	                        echo ">$value->CATEGORY_NAME</option>";

	                        } ?>

	                    </select>

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">Location</legend>

	                    <input type="text" class="col-input-2" placeholder="Country"  value="<?php echo $item->COUNTRY; ?>" name="country" >

	                    <input type="text" class="col-input-2" placeholder="State"  value="<?php echo $item->STATE; ?>" name="state" >

	                    <br>

	                    <input type="text" class="col-input-2" placeholder="City"  value="<?php echo $item->CITY; ?>" name="city" >

	                    <input type="text" class="col-input-2" placeholder="Area"  value="<?php echo $item->AREA; ?>" name="area" >

	                    <br>

	                    <input type="text" placeholder="Zip Code"  value="<?php echo $item->AREA; ?>" name="zip" >

	                </fieldset>

	            </div>

	            <div class="col-2">

	                <fieldset>

	                    <legend class="legend">Store Address</legend>

	                    <textarea name="address" id="" cols="30" rows="5" placeholder="Store Address" ><?php echo $item->ADDRESS; ?></textarea>

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">Latitude and Longitude</legend>

	                    <input type="text" placeholder="Latitude" value="<?php echo $item->LATITUDE; ?>" name="latitude" >

	                    <input type="text" placeholder="Longitude" value="<?php echo $item->LONGITUDE; ?>" name="lognitude" >

	                </fieldset>

	            </div>

	            <div class="col-3">

	                <fieldset>

	                    <legend class="legend">Contact Person</legend>

	                    <input type="text" placeholder="Contact Person" value="<?php echo $item->CONTACT_PERSON; ?>" name="contact_person">

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">GENDER</legend>

	                    <select name="gender">

	                        <option value="male" <?php if($config->GENDER=="male"){ echo "selected";} ?> >Male</option>

	                        <option value="female" <?php if($config->GENDER=="female"){ echo "selected";} ?>>Female</option>

	                    </select>

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">Email</legend>

	                    <input type="text" placeholder="Email" value="<?php echo $item->EMAIL; ?>" name="email">

	                </fieldset>

	            </div>

	            <div class="col-3">

	                <fieldset>

	                    <legend class="legend">WEBSITE</legend>

	                    <input type="text" placeholder="Website" value="<?php echo $item->WEBSITE; ?>" name="website">

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">Contact Number</legend>

	                    <input type="text" placeholder="Contact Number" onkeypress = "return event.charCode >= 48 && event.charCode <= 57" value="<?php echo $item->CONTACT_NUMBER; ?>" name="contact_number">

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">Description</legend>

	                    <input type="text" placeholder="Description" value="<?php echo $item->DESCRIPTION; ?>" name="description">

	                </fieldset>

	            </div>

	            <div class="col-3">

	                <fieldset>

	                    <legend class="legend">Facebook</legend>

	                    <input type="text" placeholder="Facebook" value="<?php echo $item->FACEBOOK; ?>" name="facebook">

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">Twitter</legend>

	                    <input type="text" placeholder="Twitter" value="<?php echo $item->TWITTER; ?>" name="twitter">

	                </fieldset>

	                <fieldset>

	                    <legend class="legend">Videos Url</legend>

	                    <label> Please download the <a href="http://www.joomlaserviceprovider.com" target="_blank">pro version</a></label>

	                </fieldset>

	            </div>

	            <fieldset>

	                <legend class="legend">Pointer Icon</legend>

	                <select name="pointer_icon" id="pointer_icon">

	                    <option value="pointer.png" <?php if($item->POINTER_IMAGE=="pointer.png"){ echo "selected";} ?>>Select default</option>

	                    <?php

	                        $configData = $this->model->jspsl_getConfig();

	                        $images = $configData->MAP_POINTER_IMAGE;

	                        

	                        if(!empty($images)){

	                        $images = explode(",", $images);							

	                        foreach ($images as $key => $value) {

	                        ?>

	                    <option value="<?php echo $value; ?>" <?php if($item->POINTER_IMAGE==$value){ echo "selected";} ?>><?php echo $value; ?></option>

	                    <?php } } ?>	

	                </select>

	                <div class="pointer-image">

	                	<img src="<?php echo JSPSL_PLUGIN_URL.'/uploads/pointer/'.$item->POINTER_IMAGE; ?>" width="60px" id="pointer_icon_img"/>

	                </div>

	            </fieldset>

	            <fieldset>

	                <legend class="legend">Add Custom Field</legend>

	                <label> Please download the <a href="http://www.joomlaserviceprovider.com" target="_blank">pro version</a></label>

	            </fieldset>

	           

	        </form>

	    </div>

	</div>

	<div class="main" id="media_section" style="width:100%;display: none;">

	    <?php if(isset($_REQUEST['id'])){ ?>

	    <div class="form-group" style="margin:50px 0px;">

	        <h1>Add Media</h1>

	        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">

	            <?php settings_fields('wporg_options');

	                do_settings_sections('wporg');

	                $jsp_store_media = wp_create_nonce('jsp_store_media');

	                ?>

	            <fieldset>

	                <legend class="legend">Upload Location Image</legend>

	                <input type="file" name="media[]" multiple accept="image/x-png,image/gif,image/jpeg" style="width: 30%;margin: 10px 10px;">

	                <input type="hidden" name="id" value="<?php echo sanitize_text_field($_REQUEST['id']); ?>">

	                <input type="submit" value="upload" name="upload" class="button button-primary">

	            </fieldset>

	            <input type="hidden" name="action" value="jspsl_add_store_media">

	            <input type="hidden" name="type" value="locations">

	            <input type="hidden" name="jsp_store_media" value="<?php echo $jsp_store_media ?>" />

	            <input type="hidden" name="id" value="<?php echo $item->ID; ?>">

	            <div class="pointer-container">

	                <?php 

	                    $storedata = $this->model->jspsl_getstorerow(sanitize_text_field($_REQUEST['id']));

	                    

	                    $images = $storedata->STORE_IMAGE;

	                    

	                    if(!empty($images)){

	                    $images = explode(",", $images);

	                    

	                    foreach ($images as $key => $value) {

	                    ?>

	                 <div class="pointer-div">

	                    <input type="checkbox" name="list[]" value="<?php echo $value; ?>">

	                    <img width="150px" src="<?php echo JSPSL_PLUGIN_URL.'/uploads/locations/'.$value; ?>">

	                </div>

	    <?php } } ?>



	    </div>	

	    <p><input type="submit" value="delete" name="delete" class="button button-primary"></p>

	    </form>

	    </div>

	    <?php } 

	        else{

	        	echo "<h1>Please Save Location First</h1>";

	        }

	    ?>

	</div>

	<script type="text/javascript">



	function jspsl_is_url(str)

	{

	  regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;

	        if (regexp.test(str))

	        {

	          return true;

	        }

	        else

	        {

	          return false;

	        }

	}



	function jspsl_validateEmail(email) {

    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return re.test(String(email).toLowerCase());

	}



	jQuery("#jsp_add_store_form").submit(function(){

			jQuery('.err_message').remove();

			location_name = jQuery("input[name=branch_name]").val();

			country = jQuery("input[name=country]").val();

			state = jQuery("input[name=state]").val();

			city = jQuery("input[name=city]").val();

			area = jQuery("input[name=area]").val();

			zip = jQuery("input[name=zip]").val();

			address = jQuery("textarea[name=address]").val();



			latitude = jQuery("input[name=latitude]").val();

			lognitude = jQuery("input[name=lognitude]").val();

			contact_person = jQuery("input[name=contact_person]").val();

			email = jQuery("input[name=email]").val();

			website = jQuery("input[name=website]").val();

			contact_number = jQuery("input[name=contact_number]").val();

			description = jQuery("input[name=description]").val();

			facebook = jQuery("input[name=facebook]").val();

			twitter = jQuery("input[name=twitter]").val();

			video_url = jQuery("input[name=video_url]").val();

			flag=1;



			if(location_name==""||country==""||state==""||city==""){

				flag=0;

				if(location_name==""){

					jQuery("fieldset:eq(0)").append("<p class='err_message'>Location Name Cannot be empty.</p>");

				}

				if(country==""||state==""||city==""){

					jQuery("fieldset:eq(1)").append("<p class='err_message'>Country State and City Name cannot be empty.</p>");

				}

			}

			

			if (!((latitude <= 90 && latitude >= -90)&&(lognitude <= 90 && lognitude >= -90))){

				flag=0;

				jQuery("fieldset:eq(3)").append("<p class='err_message'>Enter Valid Longitude and Latitude Value.</p>");

			}

			

			if(email!="" && !jspsl_validateEmail(email)){

				flag=0;

				jQuery("fieldset:eq(6)").append("<p class='err_message'>Enter Email Address.</p>");

			}



			if(!jspsl_is_url(website)&&website!=""){

				flag=0;

				jQuery("fieldset:eq(7)").append("<p class='err_message'>Enter Valid Url for Website.</p>");	

			}



			if(!jspsl_is_url(facebook)&&facebook!=""){

				flag=0;

				jQuery("fieldset:eq(10)").append("<p class='err_message'>Enter Valid Url for Facebook.</p>");	

			}

			if(!jspsl_is_url(twitter)&&twitter!=""){

				flag=0;

				jQuery("fieldset:eq(11)").append("<p class='err_message'>Enter Valid Url for Twitter.</p>");	

			}

			
			if(flag==0){

				return false;

			}

		})



	    jQuery("#show_media").click(function(){

	    	jQuery("#media_section").show();

	    	jQuery("#form_section").hide();

	    });

	    jQuery("#show_form").click(function(){

	    	jQuery("#media_section").hide();

	    	jQuery("#form_section").show();

	    });

	    jQuery("#pointer_icon").change(function(){

	    	jQuery("#pointer_icon_img").attr('src',"<?php echo JSPSL_PLUGIN_URL.'/uploads/pointer/'?>"+jQuery(this).val());

	    })

	    function jspsl_IsJsonString(str) {

	    	try{

	    		json = JSON.parse(str);

	    	}

	    	catch (e){

	    		return false;

	    	}

	    	return json;

	    }

	    					if(jspsl_IsJsonString(jQuery("#custom_field_json").val())!=false){

	    						custom_field_array =jspsl_IsJsonString(jQuery("#custom_field_json").val());

	    					}

	    					else{

	    						custom_field_array =[];

	    					}

	    	console.log(custom_field_array);

	    	jQuery("#add_custom_field").click(function(){

	    		event.preventDefault();					

	    		var custom_field = new Object();

	    		if(jQuery("#c_value").val()!==""&&jQuery("#c_key").val()!==""){

	    			img = '<?php echo JSPSL_PLUGIN_URL.'images/delete.png'; ?>';

	    			tr = "<tr><td>"+jQuery("#c_key").val()+"</td><td>"+jQuery("#c_value").val()+"</td><td><img src='"+img+"' class='remove_this'></td></tr>";

	    			jQuery("#custom_field_table").append(tr);

	    			custom_field.key=jQuery("#c_key").val();

	    			custom_field.value=jQuery("#c_value").val();

	    			custom_field_array.push(custom_field);

	    			jQuery("#custom_field_json").val(JSON.stringify(custom_field_array));

	    			jQuery("#c_key").val("");

	    			jQuery("#c_value").val("");

	    

	    		}

	    	})

	    

	    	jQuery(document).on('click', '.remove_this', function(){

	    		event.preventDefault();

	    		indexValueOfArray = jQuery(this).closest("tr").index();

	    		jQuery(this).closest("tr").remove();

	    		console.log(custom_field_array[indexValueOfArray-1]);

	    		custom_field_array.splice(indexValueOfArray-1,1);

	    		console.log(custom_field_array);

	    		jQuery("#custom_field_json").val(JSON.stringify(custom_field_array));

	    		

	    	});

	</script>

	<?php

		}





		    public function jspsl_manage_category() {

	    	global $wpdb;

	    	$table = new JSPSL_Category_List_Table(); 

	    	$table->prepare_items();

	    	$message = '';

	       if ('delete' === $table->current_action()) {

	           $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted', 'cltd_example')) . '</p></div>';

	       }

	       ?>

	<div class="wrap">

	    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>

	    <h2><?php _e('JSP Category', 'cltd_example')?><a class="add-new-h2"

	        href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=jspsl-add-new-categories');?>"><?php _e('Add new', 'cltd_example')?></a>

	    </h2>

	    <?php echo $message; ?>

	    <form id="category-table" method="GET">
	        <input type="hidden" name="page" value="<?php echo sanitize_text_field($_REQUEST['page']) ?>"/>

	        <?php $table->display() ?>

	    </form>

	</div>

	<?php

	  }





		    	public function jspsl_add_new_category() {

	    		$default = array(

	            'ID' => 0,

	            'CATEGORY_NAME' => '',

	            'DESCRIPTION' => ''

	        	);

	    

	    		$item = json_decode(json_encode($default), FALSE);

	    		if(isset($_REQUEST['id'])){

	    			$item = $this->model->jspsl_getcategoryerow(sanitize_text_field($_REQUEST['id']));

	    

	    			if (!$item) {

	                    $item = json_decode(json_encode($default), FALSE);

	                    $notice = __('Item not found', 'cltd_example');

	                }

	    		}

	    		 if (!current_user_can('manage_options')) {

	            		return;

	        		}

	    ?>

	<div class="main" style="width:100%;display:inline-block;">

	    <div class="form-group" style="margin:50px 0px;">

	        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">

	            <?php settings_fields('wporg_options');

	                do_settings_sections('wporg');

	                submit_button('Save Settings');

	                $jsp_category_nonce = wp_create_nonce('jsp_category_nonce');

	                ?>

	            <input type="hidden" name="action" value="jspsl_add_category">

	            <input type="hidden" name="jsp_category_nonce" value="<?php echo $jsp_category_nonce ?>" />

	            <input type="hidden" name="category_id" value="<?php echo $item->ID; ?>">

	            <fieldset>

	                <legend class="legend">Category Name</legend>

	                <input type="text" required name="category_name" required value="<?php echo $item->CATEGORY_NAME; ?>">

	            </fieldset>

	            <fieldset>

	                <legend class="legend">Description</legend>

	                <textarea name="category_description" required><?php echo $item->DESCRIPTION; ?></textarea>

	            </fieldset>

	        </form>

	    </div>

	</div>

	<?php		

	   }



		    public function jspsl_configuration() {

	    		$config = $this->model->jspsl_getConfig();

	    		$stores = $this->model->jspsl_getstores();

	    ?>

	<h2 class="nav-tab-wrapper wp-clearfix">

	    <a href="#" class="nav-tab nav-tab-active" id="show_form">Configuration</a>

	    <a href="#" class="nav-tab" id="show_media">Add Pointer</a>

	</h2>

	<div class="main" style="width:100%;display:inline-block;" id="form_section">

	    <div class="form-group" style="margin:50px 0px;">

	        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="jsp_configuration" enctype="multipart/form-data">

	            <?php settings_fields('wporg_options');

	                do_settings_sections('wporg');

	                submit_button('Save Settings');

	                $jsp_configuration_nonce = wp_create_nonce('jsp_configuration_nonce');

	                ?>

	            <input type="hidden" name="action" value="jspsl_configuration">

	            <input type="hidden" name="jsp_configuration_nonce" value="<?php echo $jsp_configuration_nonce ?>" />

	            <div class="col-2">

	                <fieldset>

	                    <legend class="legend">Map Title</legend>

	                    <input type="text" placeholder="MAP Title" value="<?php echo $config->MAP_TITLE;?>" name="map_title">

	                </fieldset>

	                <fieldset>

	                <legend class="legend">Map Type</legend>

	                <select name="map_type">

	                    <option value="0" <?php if($config->MAP_TYPE==0){ echo "selected";} ?>>Google</option>

	                    <option value="1" <?php if($config->MAP_TYPE==1){ echo "selected";} ?>>Bing</option>

	                </select>

	                </fieldset>

	            </div>

	            <div class="col-4">

		            <fieldset <?php if($config->MAP_TYPE==1){echo 'style="display:none;"';} ?>>

		                <legend class="legend">Googel Map Key</legend>

		                <input type="text" placeholder="Google Map Key" value="<?php echo $config->GOOGLE_MAP_KEY;?>" name="google_map_key" >

		            </fieldset>

		            <fieldset <?php if($config->MAP_TYPE==0){echo 'style="display:none;"';} ?>>

		                <legend class="legend">Bing Map Key</legend>

		                <input type="text" placeholder="Bing Map Key" value="<?php echo $config->BING_MAP_KEY;?>" name="bing_map_key" >

		            </fieldset>

		        </div>

	            <div class="col-2">

		            <fieldset>

		                <legend class="legend">Map Details</legend>

		                <div class="col-input-2">

			                <legend>Map Height</legend>

			                <input type="text" placeholder="Map Height" value="<?php echo $config->MAP_HEIGHT;?>" name="map_height" >

			            </div>

			            <div class="col-input-2">

			                <legend>Map Zoom Level</legend>

			                <input type="text" placeholder="Map Zoom Level" value="<?php echo $config->MAP_ZOOM;?>" name="map_zoom" >

			            </div>

		                <br>

		                <legend>Default Location on Load</legend>

		                <select name="default_location_override">

		                    <option value="default" title="Default Map location Overide">Default Map location Overide</option>

		                    <?php

		                        foreach ($stores as $key => $value) {

		                        	echo "<option value='$value->ID'";

		                        		if($value->ID==$config->DEFAULT_LOCATION_OVERRIDE){

		                        			echo "selected";

		                        		}

		                        	echo ">".$value->NAME."</option>";

		                        }

		                        ?>

		                </select>

		                <div id="lat_long_over">

			                <legend>Latitude Override</legend>

			                <input type="text" placeholder="Latitude" value="<?php echo $config->LATITUDE_OVERRIDE;?>" name="latitude" >

			                <legend>Longitude Override</legend>

			                <input type="text" placeholder="Longitude" value="<?php echo $config->LONGITUDE_OVERRIDE;?>" name="longitude" >

		                </div>

		                <legend>Google Map Language</legend>

		                <select name="map_language">

		                    <option value="Select Language" title="With this option pointers will be generated dynamically Ex. A1, A2, etc.">Select Language</option>

		                    <option value="ar" <?php if($config->MAP_LANGUAGE=="ar"){ echo "selected";} ?>>Arabic</option>

		                    <option value="bg" <?php if($config->MAP_LANGUAGE=="bg"){ echo "selected";} ?>>Bulgarian</option>

		                    <option value="Chinese" <?php if($config->MAP_LANGUAGE=="Chinese"){ echo "selected";} ?>>Chinese</option>

		                    <option value="English"  <?php if($config->MAP_LANGUAGE=="English"){ echo "selected";} ?>>English</option>

		                    <option value="French" <?php if($config->MAP_LANGUAGE=="French"){ echo "selected";} ?>>French</option>

		                    <option value="Galician" <?php if($config->MAP_LANGUAGE=="Galician"){ echo "selected";} ?>>Galician</option>

		                    <option value="German" <?php if($config->MAP_LANGUAGE=="German"){ echo "selected";} ?>>German</option>

		                    <option value="Greek" <?php if($config->MAP_LANGUAGE=="Greek"){ echo "selected";} ?>>Greek</option>

		                    <option value="Hindi" <?php if($config->MAP_LANGUAGE=="Hindi"){ echo "selected";} ?>>Hindi</option>

		                    <option value="ja" <?php if($config->MAP_LANGUAGE=="ja"){ echo "selected";} ?>>Japanese</option>

		                    <option value="Korean" <?php if($config->MAP_LANGUAGE=="Korean"){ echo "selected";} ?>>Korean</option>

		                    <option value="Polish" <?php if($config->MAP_LANGUAGE=="Polish"){ echo "selected";} ?>>Polish</option>

		                    <option value="Portuguese" <?php if($config->MAP_LANGUAGE=="Portuguese"){ echo "selected";} ?>>Portuguese</option>

		                    <option value="Russian" <?php if($config->MAP_LANGUAGE=="Russian"){ echo "selected";} ?>>Russian</option>

		                    <option value="Thai" <?php if($config->MAP_LANGUAGE=="Thai"){ echo "selected";} ?>>Thai</option>

		                </select>

		            </fieldset>

		       

		        <fieldset>

		            <legend class="legend">Google MAP Style JSON</legend>
		            <label> Please download the <a href="http://www.joomlaserviceprovider.com" target="_blank">pro version</a></label>

	            </fieldset>

	            </div>

	            <div class="col-2">

		            <fieldset>

		                <legend class="legend">Locate Me Radius Range</legend>

		                <input type="text" placeholder="Locate Me Radius Range" value="<?php echo $config->LOCATE_ME_RADIUS_RANGE;?>" name="locate_me_range">

		            </fieldset>





	           	 <fieldset>

					<legend>Pointer Type</legend>
						<input type="hidden" name="pointer_type" value="default">
						<label> Please download the <a href="http://www.joomlaserviceprovider.com" target="_blank">pro version</a></label>
				</fieldset>



		     

		        </div>



		        		        <div class="col-2">

		        <fieldset>

		                <legend class="legend"> Select Front End Templates Options</legend>
		                <input type="hidden" name="map_template" value="classic">
		                <label> Please download the <a href="http://www.joomlaserviceprovider.com" target="_blank">pro version</a></label>

		            </fieldset>

		  

		        	<div>

		            <img src="<?php echo JSPSL_PLUGIN_URL."images/".$config->MAP_TEMPLATE.".jpg"; ?>" id="theme_image">

		    		</div>

		            </div>

	            

 				<fieldset>

	                <label>Get Direction Search Units Option</label>

	                <div class="input-option">

		                <input type="radio" name="unit" value="miles" <?php if($config->SEARCH_UNIT=="miles"){echo "checked";} ?>> Miles 

		                <input type="radio" name="unit" value="km" <?php if($config->SEARCH_UNIT=="km"){echo "checked";} ?>> KM

		            </div>

	            </fieldset>

	            <fieldset>

	                <label>Show Search Box Option </label>

	                <div class="input-option">

		                <input type="radio" name="search_box" value="1" <?php if($config->DISPLAY_SEARCH_BOX=="1"){echo "checked";} ?>> Yes 

		                <input type="radio" name="search_box" value="0" <?php if($config->DISPLAY_SEARCH_BOX=="0"){echo "checked";} ?>> No

		            </div>

	            </fieldset>

	            <fieldset>

	                <label>Google Autocomplete Address</label>

	                <div class="input-option">

		                <input type="radio" name="autocomplete" value="1" <?php if($config->GOOGLE_AUTOCOMPLETE=="1"){echo "checked";} ?>> Yes

		                <input type="radio" name="autocomplete" value="0" <?php if($config->GOOGLE_AUTOCOMPLETE=="0"){echo "checked";} ?>> No

		            </div>

	            </fieldset>

	            <fieldset>

	                <label>Display Locate Me Button</label>

	                <div class="input-option">

		                <input type="radio" name="locate_me" value="1" <?php if($config->LOCATE_ME=="1"){echo "checked";} ?>> Yes

		                <input type="radio" name="locate_me" value="0" <?php if($config->LOCATE_ME=="0"){echo "checked";} ?>> No

		            </div>

	            </fieldset>


	            <fieldset>

	                <label>Display MAP Type on Frontend</label>

	                <div class="input-option">

		                <input type="radio" name="frontend_map_type" value="1" <?php if($config->FRONTEND_MAP_TYPE=="1"){echo "checked";} ?>> Yes

		                <input type="radio" name="frontend_map_type" value="0" <?php if($config->FRONTEND_MAP_TYPE=="0"){echo "checked";} ?>> No

		            </div>

	            </fieldset>

	        </form>

	    </div>

	</div>

	<div class="main" style="width:100%;display:none;" id="media_section">

	    <div class="form-group" style="margin:50px 0px;">

	        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="jsp_configuration" enctype="multipart/form-data">

	            <?php settings_fields('wporg_options');

	                do_settings_sections('wporg');

	                $jsp_store_media = wp_create_nonce('jsp_store_media');

	                ?>

	            <fieldset>

	                <legend class="legend">Upload Pointer Image</legend>

	                <label> Please download the <a href="http://www.joomlaserviceprovider.com" target="_blank">pro version</a></label>

	            </fieldset>

	            <input type="hidden" name="action" value="jspsl_add_store_media">

	            <input type="hidden" name="jsp_store_media" value="<?php echo $jsp_store_media ?>" />

	            <input type="hidden" name="type" value="pointer">

	           

	    	

	    

	    </form>

	</div>

	</div>

	<script type="text/javascript">

	if(jQuery("select[name=default_location_override]").val()!="default"){

		jQuery("#lat_long_over").hide();

	}



			jQuery("select[name=map_template]").change(function(){

			name = jQuery(this).val();

			jQuery("#theme_image").attr('src','<?php echo JSPSL_PLUGIN_URL."images/"; ?>'+name+'.jpg');

		})





		jQuery("select[name=map_type]").change(function(){

			if(this.value==0){

				jQuery("fieldset:eq(2)").show();

				jQuery("fieldset:eq(3)").hide();

			}else{

				jQuery("fieldset:eq(2)").hide();

				jQuery("fieldset:eq(3)").show();

			}

		})

		jQuery("select[name=default_location_override]").change(function(){

			if(this.value=="default"){

				jQuery("#lat_long_over").show();

			}else{

				jQuery("#lat_long_over").hide();

			}

		})



	    jQuery("#show_media").click(function(){

	    	jQuery("#media_section").show();

	    	jQuery("#form_section").hide();

	    	});

	    jQuery("#show_form").click(function(){

	    	jQuery("#media_section").hide();

	    	jQuery("#form_section").show();

	    });

	    jQuery("select[name=mapstyle]").change(function(){

	    	var value = jQuery(this).val().toLowerCase();

	   		if(value=="custom"){

	    		jQuery(".optional_content").show();

	    	}else{

	    		jQuery(".optional_content").hide();

	    	}

	    })

	</script>

	<?php

	    }





	 public function jspsl_import_export_form() {
	 	$this->jspsl_get_modal_window();
    	echo "<h1>".esc_html(get_admin_page_title())."</h1>";

    ?>

	<div class="main" style="width:100%;display:inline-block;">

	    <div class="form-group" style="margin:50px 0px;">

	        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="import_export_form" enctype="multipart/form-data">

	            <?php settings_fields('wporg_options');

	                do_settings_sections('wporg');

	                $import_export_nonce = wp_create_nonce('import_export_nonce');

	                ?>

	            <input type="hidden" name="action" value="jspsl_import_export">

	            <input type="hidden" name="import_export_nonce" value="<?php echo $import_export_nonce ?>" />

	            <input type="hidden" name="import_or_export" id="import_or_export" value="default">

	            <h2>Import Location</h2>

	            <fieldset style="border:2px solid #968e8e;padding:34px;width: 40%;">

	                <label>Select File</label>

	                <input type="file" name="importfile">

	                <input type="submit" value="Import" name="importdata" class="button-primary">

	                <?php if(isset($_REQUEST['import']))

	                    echo "<h3 class='ie_message'>".sanitize_text_field($_REQUEST['import'])." Rows are inserted into database.</h3>";

	                    ?>

	            </fieldset>

	            <h2>Export Location</h2>

	            <fieldset>

	                <a href="#" class="sample-file" onclick="document.getElementById('myModal').style.display = 'block';">Sample File <span>*</span></a>

	                <input type="submit" value="Export" name="exportdata" class="button-primary">					

	                <h3 class="ie_message"></h3>

	            </fieldset>

	        </form>

	    </div>

	</div>
	<script type="text/javascript">
		jQuery( document ).ready(function() {
			var modal = document.getElementById('myModal');
			var span = document.getElementsByClassName("close")[0];
			jQuery('input[type=submit]').bind('click',function(e){
				event.preventDefault();
			    modal.style.display = "block"; 
		   	});
			span.onclick = function() {
			    modal.style.display = "none";
			}
			window.onclick = function(event) {
			    if (event.target == modal) {
			        modal.style.display = "none";
			    }
			}
		});
	</script>
	<?php

	    }



	public function jspsl_google_places() {
		$this->jspsl_get_modal_window();

?>

		<div class="main" style="width:100%;display:inline-block;">

			<div class="form-group" style="margin:50px 0px;">

				<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">

					<?php settings_fields('wporg_options');

						do_settings_sections('wporg');

						$jsp_google_places_nonce = wp_create_nonce('jsp_google_places_nonce');

					?>

					<input type="hidden" name="action" value="jspsl_google_places">
					

					<input type="hidden" name="jsp_google_places_nonce" value="<?php echo $jsp_google_places_nonce ?>" />

					<fieldset class="search-store">

						<label>Search Stores </label>

						<input type="text" name="search" placeholder="E.g. Nike store in mumbai">

						<input type="submit" value="Search" class="button button-primary">

					</fieldset>

				</form>







				<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="google_places_form" enctype="multipart/form-data">

					<?php settings_fields('wporg_options');

						do_settings_sections('wporg');

						$jsp_google_places_nonce = wp_create_nonce('jsp_google_places_nonce');

					?>

					<input type="hidden" name="action" value="jspsl_add_google_places">

					<input type="hidden" name="jsp_google_places_nonce" value="<?php echo $jsp_google_places_nonce ?>" />

				<table class="search-store-table">

					<input type="hidden" name="location_id">

					<tr>

						<th>No.</th>

						<th>Name</th>

						<th>Address</th>

						<th></th>

					</tr>

					<?php

						$google_places = $this->model->jspsl_getGoogleplaces();

						foreach ($google_places as $key => $value) {

							echo "<tr><td>".$value->ID."</td>";

							echo "<td>".$value->NAME."</td>";

							echo "<td>".$value->ADDRESS."</td>";

							echo "<td><button class='add_location button button-primary' value='$value->ID'>Create Location</td></tr>";

						}

					?>



				</table>

			</form>

			</div>

		</div>

		<script type="text/javascript">

			jQuery(".add_location").click(function(){

				event.preventDefault();

				var id = jQuery(this).val();

				jQuery("[name=location_id]").val(id);

				data = jQuery("#google_places_form").serializeArray();				

				jQuery.ajax({

					url: ajaxurl,

					type: 'POST',

					data: data,

					dataType : 'json',

					context : this,

                    success: function (response) {

                     if(response.status=="ok"){

                     	jQuery(this).attr('disabled','disabled');

                     	jQuery(this).text('Location Added');

                     	jQuery(this).removeClass('button-primary');

                     }

                     else{

                     	jQuery(this).attr('disabled','disabled');

                     	jQuery(this).text('Location Cannot be Created');

                     	jQuery(this).removeClass('button-primary');

                     }

                 }

				});

			});


			var modal = document.getElementById('myModal');
			var span = document.getElementsByClassName("close")[0];
			jQuery('input[type=submit]').bind('click',function(e){
				event.preventDefault();
			    modal.style.display = "block"; 
		   	});
			span.onclick = function() {
			    modal.style.display = "none";
			}
			window.onclick = function(event) {
			    if (event.target == modal) {
			        modal.style.display = "none";
			    }
			}
		</script>





<?php

	}





	public function jspsl_branch_hit(){
		$this->jspsl_get_modal_window();
?>
		<style>
			.notification {
			font-size:18px;
			color:blue;
			display:block;
			line-height:300px;
			}
		</style>

		<div id="button">
			Records In Time Period :
			<button class="btn1 btn1mini gray" name="btdaily" value="Daily" onclick="showDaily();">Daily</button>
			<button class="btn1 btn1mini gray" name="btweekly" value="Weekly" onclick="showWeekly();">Weekly</button>
			<button class="btn1 btn1mini gray" name="btmonthly" value="Monthly" onclick="showMonthly();">Monthly</button>
		</div>

		<script type="text/javascript">
			var modal = document.getElementById('myModal');
			var span = document.getElementsByClassName("close")[0];
			jQuery('button').bind('click',function(e){
				event.preventDefault();
			    modal.style.display = "block"; 
		   	});
			span.onclick = function() {
			    modal.style.display = "none";
			}
			window.onclick = function(event) {
			    if (event.target == modal) {
			        modal.style.display = "none";
			    }
			}
		</script>
<?php
	}	



	public function jspsl_search_hit(){
		$this->jspsl_get_modal_window();

?>



		<style>

		.notification {

		font-size:18px;

		color:blue;

		display:block;

		line-height:300px;

		}

		</style>


		<div id="button">

			Records In Time Period :

		     <button class="btn1 btn1mini gray" name="btdaily" value="Daily" onclick="showDaily();">Daily</button>

			 <button class="btn1 btn1mini gray" name="btweekly" value="Weekly" onclick="showWeekly();">Weekly</button>

			 <button class="btn1 btn1mini gray" name="btmonthly" value="Monthly" onclick="showMonthly();">Monthly</button>

		</div>

		<script type="text/javascript">
			var modal = document.getElementById('myModal');	
			var span = document.getElementsByClassName("close")[0];
			jQuery('button').bind('click',function(e){
				event.preventDefault();
			    modal.style.display = "block"; 
		   	});
			span.onclick = function() {
			    modal.style.display = "none";
			}
			window.onclick = function(event) {
			    if (event.target == modal) {
			        modal.style.display = "none";
			    }
			}
	 	</script>





	



<?php

	}

}



?>