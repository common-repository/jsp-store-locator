<?php
require_once (PLUGIN_PATH . 'admin/class_List_Table.php');

class JspStoreLocator_Admin {

	public function __construct(){
		require_once PLUGIN_PATH . 'admin/class_jsp_store_locator_Model.php';
		wp_enqueue_style('custom_style', PLUGIN_URL."admin/css/custom_style.css");
		$this->model = new JspStoreLocator_Model();
	}


	public function jsp_dashboard(){
		echo "Dashboard";
	}
	public function manage_stores(){
		global $wpdb;
		$table = new Link_List_Table(); 
		$table->prepare_items();
		$message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'cltd_example'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Stores', 'cltd_example')?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=add-new-store');?>"><?php _e('Add new', 'cltd_example')?></a>
                             
    </h2>
    <?php echo $message; ?>

    <form id="stores-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}



	public function add_new_store() {
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
			$item = $this->model->getstorerow($_REQUEST['id']);
			if (!$item) {
                $item = json_decode(json_encode($default), FALSE);
                $notice = __('Item not found', 'cltd_example');
                echo "<h1>$notice</h1>";die;
            }
		}
		 if (!current_user_can('manage_options')) {
        		return;
    		}
		$categories = $this->model->get_categories();
		?>
		<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="#" class="nav-tab nav-tab-active" id="show_form">Add Store details</a>
		<a href="#" class="nav-tab" id="show_media">Add Media</a></h2>
			<div class="main" id="form_section" style="width:100%;display: inline-block;">
				<div class="form-group" style="margin:50px 0px;">
				<h1><?= esc_html(get_admin_page_title()); ?></h1>
				<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="jsp_add_store_form">
					<?php settings_fields('wporg_options');
						do_settings_sections('wporg');
						submit_button('Save Settings');
						$jsp_add_store_nonce = wp_create_nonce('jsp_add_store_form');
					?>

					<input type="hidden" name="action" value="jsp_add_store_form">
					<input type="hidden" name="jsp_add_store_form_nonce" value="<?php echo $jsp_add_store_nonce ?>" />
					<input type="hidden" name="id" value="<?php echo $item->ID; ?>">
					<fieldset>
					<legend>Location Name</legend>
						<input type="text" required placeholder="Location Name" value="<?php echo $item->NAME; ?>" name="branch_name"  >
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
					<legend>Location</legend>
						<input type="text" placeholder="Country" required value="<?php echo $item->COUNTRY; ?>" name="country" >
						<input type="text" placeholder="State" required value="<?php echo $item->STATE; ?>" name="state" >
						<br>
						<input type="text" placeholder="City" required value="<?php echo $item->CITY; ?>" name="city" >
						<input type="text" placeholder="Area" required value="<?php echo $item->AREA; ?>" name="area" >
						<br>
						<input type="text" placeholder="Zip Code" required value="<?php echo $item->AREA; ?>" name="zip" >
					</fieldset>
				
					<fieldset>
					<legend>Store Address</legend>
						<textarea name="address" id="" cols="30" rows="5" placeholder="Store Address" ><?php echo $item->ADDRESS; ?></textarea>
					</fieldset>
					<fieldset>
					<legend>Latitude and Longitude</legend>
						<input type="text" placeholder="Latitude" value="<?php echo $item->LATITUDE; ?>" name="latitude" >
						<input type="text" placeholder="Longitude" value="<?php echo $item->LONGITUDE; ?>" name="lognitude" >
					</fieldset>
					
					<fieldset>
						<legend>Contact Person</legend>
						<input type="text" placeholder="Contact Person" value="<?php echo $item->CONTACT_PERSON; ?>" name="contact_person">
					</fieldset>

					<fieldset>
						<legend>GENDER</legend>
						<select name="gender">
							<option value="male" <?php if($config->GENDER=="male"){ echo "selected";} ?> >Male</option>
							<option value="female" <?php if($config->GENDER=="female"){ echo "selected";} ?>>Female</option>
						</select>
						
					</fieldset>

					<fieldset>
						<legend>Email</legend>
						<input type="email" placeholder="Email" value="<?php echo $item->EMAIL; ?>" name="email">
					</fieldset>

					<fieldset>
						<legend>WEBSITE</legend>
						<input type="text" placeholder="Website" value="<?php echo $item->WEBSITE; ?>" name="website">
					</fieldset>

					<fieldset>
						<legend>Contact Number</legend>
						<input type="text" placeholder="Contact Number" value="<?php echo $item->CONTACT_NUMBER; ?>" name="contact_number">
					</fieldset>

					<fieldset>
						<legend>Description</legend>
						<input type="text" placeholder="Description" value="<?php echo $item->DESCRIPTION; ?>" name="description">
					</fieldset>

					<fieldset>
						<legend>Facebook</legend>
						<input type="text" placeholder="Facebook" value="<?php echo $item->FACEBOOK; ?>" name="facebook">
					</fieldset>

					<fieldset>
						<legend>Twitter</legend>
						<input type="text" placeholder="Twitter" value="<?php echo $item->TWITTER; ?>" name="twitter">
					</fieldset>


					<fieldset>
						<legend>Pointer Icon</legend>
						<select name="pointer_icon" id="pointer_icon">
						<option value="pointer.png" <?php if($item->POINTER_IMAGE=="pointer.png"){ echo "selected";} ?>>Select default</option>
							<?php
							$configData = $this->model->getConfig();
							$images = $configData->MAP_POINTER_IMAGE;

							if(!empty($images)){
							$images = explode(",", $images);							
							foreach ($images as $key => $value) {
							?>
							<option value="<?php echo $value; ?>" <?php if($item->POINTER_IMAGE==$value){ echo "selected";} ?>><?php echo $value; ?></option>
						<?php } } ?>	
						</select>
						<img src="<?php echo PLUGIN_URL.'/uploads/pointer/'.$item->POINTER_IMAGE; ?>" width="60px" id="pointer_icon_img"/>
					</fieldset>
					<fieldset>
						<legend>Videos Url</legend>
						<input type="text" placeholder="Videos Url" id="" name="video_url" value="<?php echo $item->STORE_VIDEO_URL; ?>" >
					</fieldset>
					<fieldset>
						<legend>Custom </legend>
						<input type="text" placeholder="Key" name="c_key" id="c_key">
						<input type="text" placeholder="Value" name="c_value" id="c_value">
						<input type="hidden" placeholder="Value" name="custom_field_json" id="custom_field_json" value='<?php echo $item->CUSTOM_FIELD; ?>'>
						<button class="button" id="add_custom_field">+</button>
					</fieldset>
					<table border="1" width="40%" id="custom_field_table">
						<tr>
							<th>Key</th>
							<th>Value</th>
						</tr>
						<?php 
							if(isset($item->CUSTOM_FIELD)&&$item->CUSTOM_FIELD!=""){
								$custom_field = json_decode($item->CUSTOM_FIELD);
								foreach ($custom_field as $key => $value) {
									echo "<tr>";
									echo "<td>".$value->key;
									echo "<td>".$value->value;
									echo "<td><button class='remove_this'>x</button>";
									echo "</tr>";
								}
							}
						?>
					</table>
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
							<legend>Upload Location Image</legend>
							<input type="file" name="media[]" multiple accept="image/x-png,image/gif,image/jpeg" style="width: 30%;margin: 10px 10px;">
							<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
						</fieldset>
						<fieldset>
							<input type="submit" value="upload" name="upload" class="button button-primary">
						</fieldset>

						<input type="hidden" name="action" value="jsp_add_store_media">
						<input type="hidden" name="type" value="locations">
						<input type="hidden" name="jsp_store_media" value="<?php echo $jsp_store_media ?>" />
						<input type="hidden" name="id" value="<?php echo $item->ID; ?>">
						<table>
						<?php 
							$storedata = $this->model->getstorerow($_REQUEST['id']);

							$images = $storedata->STORE_IMAGE;

							if(!empty($images)){
							$images = explode(",", $images);
							
							foreach ($images as $key => $value) {
						 ?>
							<tr>
								<td><input type="checkbox" name="list[]" value="<?php echo $value; ?>"></td>
								<td><img width="150px" src="<?php echo PLUGIN_URL.'/uploads/locations/'.$value; ?>"></td>
							</tr>
						</div>
						<?php } } ?>

					</table>
					<input type="submit" value="delete" name="delete" class="button button-primary">
					</form>
				</div>
				
			<?php } 
										else{
									echo "<h1>Please Save Location First</h1>";
								}
			?>
</div>

			<script type="text/javascript">
			jQuery("#show_media").click(function(){
				jQuery("#media_section").show();
				jQuery("#form_section").hide();
			});
			jQuery("#show_form").click(function(){
				jQuery("#media_section").hide();
				jQuery("#form_section").show();
			});
			jQuery("#pointer_icon").change(function(){
				jQuery("#pointer_icon_img").attr('src',"<?php echo PLUGIN_URL.'/uploads/pointer/'?>"+jQuery(this).val());
			})
			function IsJsonString(str) {
				try{
					json = JSON.parse(str);
				}
				catch (e){
					return false;
				}
				return json;
			}
								if(IsJsonString(jQuery("#custom_field_json").val())!=false){
									custom_field_array =IsJsonString(jQuery("#custom_field_json").val());
								}
								else{
									custom_field_array =[];
								}
				console.log(custom_field_array);
				jQuery("#add_custom_field").click(function(){
					event.preventDefault();					
					var custom_field = new Object();
					if(jQuery("#c_value").val()!==""&&jQuery("#c_key").val()!==""){
						tr = "<tr><td>"+jQuery("#c_key").val()+"</td><td>"+jQuery("#c_value").val()+"</td><td><button class='remove_this'>x</button></td></tr>";
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
	
	public function manage_category() {
		global $wpdb;
		$table = new Category_List_Table(); 
		$table->prepare_items();
		$message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'cltd_example'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>

	<div class="wrap">
		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
			<h2><?php _e('JSP Category', 'cltd_example')?><a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=add-new-categories');?>"><?php _e('Add new', 'cltd_example')?></a>
             </h2>
			<?php echo $message; ?>
			<form id="category-table" method="GET">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
				<?php $table->display() ?>
			</form>
	</div>

<?php
}
	public function add_new_category() {
		$default = array(
        'ID' => 0,
        'CATEGORY_NAME' => '',
        'DESCRIPTION' => ''
    	);

		$item = json_decode(json_encode($default), FALSE);
		if(isset($_REQUEST['id'])){
			$item = $this->model->getcategoryerow($_REQUEST['id']);

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
		<input type="hidden" name="action" value="add_jsp_category">
		<input type="hidden" name="jsp_category_nonce" value="<?php echo $jsp_category_nonce ?>" />
		<input type="hidden" name="category_id" value="<?php echo $item->ID; ?>">
		<fieldset>
			<legend>Category Name</legend>
			<input type="text" required name="category_name" required value="<?php echo $item->CATEGORY_NAME; ?>">
		</fieldset>
		<fieldset>
			<legend>Description</legend>
			<textarea name="category_description" required><?php echo $item->DESCRIPTION; ?></textarea>
		</fieldset>
		</form>
	</div>
</div>


<?php		
	}

	public function jsp_configuration() {
			$config = $this->model->getConfig();
			$stores = $this->model->getstores();
					?>

		
		<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="#" class="nav-tab nav-tab-active" id="show_form">Configuration</a>
		<a href="#" class="nav-tab" id="show_media">Add Pointer</a></h2>

			<div class="main" style="width:100%;display:inline-block;" id="form_section">
				<div class="form-group" style="margin:50px 0px;">
				<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="jsp_configuration" enctype="multipart/form-data">
					<?php settings_fields('wporg_options');
						do_settings_sections('wporg');
						submit_button('Save Settings');
						$jsp_configuration_nonce = wp_create_nonce('jsp_configuration_nonce');
					?>
					<input type="hidden" name="action" value="jsp_configuration">
					<input type="hidden" name="jsp_configuration_nonce" value="<?php echo $jsp_configuration_nonce ?>" />
					<div class="row">
						<fieldset>
							<legend>Map Title</legend>
							<input type="text" placeholder="MAP Title" value="<?php echo $config->MAP_TITLE;?>" name="map_title" required>
						</fieldset>

						<fieldset></fieldset>
							<legend>Map Type</legend>
							<select name="map_type">
								<option value="Select">Select Map Type</option>

								<option value="0" <?php if($config->MAP_TYPE==0){ echo "selected";} ?>>Google</option>
								<option value="1" <?php if($config->MAP_TYPE==1){ echo "selected";} ?>>Bing</option>
							</select>
						</fieldset>
					</div>
				<fieldset>
					<legend>Googel Map Key</legend>
					<input type="text" placeholder="Google Map Key" value="<?php echo $config->GOOGLE_MAP_KEY;?>" name="google_map_key" >
				</fieldset>
				<fieldset>
					<legend>Bing Map Key</legend>
					<input type="text" placeholder="Bing Map Key" value="<?php echo $config->BING_MAP_KEY;?>" name="bing_map_key" >
				</fieldset>

				<fieldset>
					<legend>Map Details</legend>
					<input type="text" placeholder="Map Height" value="<?php echo $config->MAP_HEIGHT;?>" name="map_height" >
					<input type="text" placeholder="Map Zoom Level" value="<?php echo $config->MAP_ZOOM;?>" name="map_zoom" >
					<br>
					
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

					<select name="map_language">
						<option value="Select Language" title="With this option pointers will be generated dynamically Ex. A1, A2, etc.">Select Language</option>   
						<option value="Arabic" <?php if($config->MAP_LANGUAGE=="Arabic"){ echo "selected";} ?>>Arabic</option>
						<option value="Bulgarian" <?php if($config->MAP_LANGUAGE=="Bulgarian"){ echo "selected";} ?>>Bulgarian</option>
						<option value="Chinese" <?php if($config->MAP_LANGUAGE=="Chinese"){ echo "selected";} ?>>Chinese</option>
						<option value="English"  <?php if($config->MAP_LANGUAGE=="English"){ echo "selected";} ?>>English</option>
						<option value="French" <?php if($config->MAP_LANGUAGE=="French"){ echo "selected";} ?>>French</option>
						<option value="Galician" <?php if($config->MAP_LANGUAGE=="Galician"){ echo "selected";} ?>>Galician</option>
						<option value="German" <?php if($config->MAP_LANGUAGE=="German"){ echo "selected";} ?>>German</option>
						<option value="Greek" <?php if($config->MAP_LANGUAGE=="Greek"){ echo "selected";} ?>>Greek</option>
						<option value="Hindi" <?php if($config->MAP_LANGUAGE=="Hindi"){ echo "selected";} ?>>Hindi</option>
						<option value="Japanese" <?php if($config->MAP_LANGUAGE=="Japanese"){ echo "selected";} ?>>Japanese</option>
						<option value="Korean" <?php if($config->MAP_LANGUAGE=="Korean"){ echo "selected";} ?>>Korean</option>
						<option value="Polish" <?php if($config->MAP_LANGUAGE=="Polish"){ echo "selected";} ?>>Polish</option>
						<option value="Portuguese" <?php if($config->MAP_LANGUAGE=="Portuguese"){ echo "selected";} ?>>Portuguese</option>
						<option value="Russian" <?php if($config->MAP_LANGUAGE=="Russian"){ echo "selected";} ?>>Russian</option>
						<option value="Thai" <?php if($config->MAP_LANGUAGE=="Thai"){ echo "selected";} ?>>Thai</option>
					</select>
				</fieldset>

				<fieldset>
				<legend>Additional Information</legend>
					<input type="text" placeholder="Latitude" value="<?php echo $config->LATITUDE_OVERRIDE;?>" name="latitude" >
					<input type="text" placeholder="Longitude" value="<?php echo $config->LONGITUDE_OVERRIDE;?>" name="longitude" >
				</fieldset>

				<legend>Google MAP Style JSON</legend>
					<textarea name="map_style_json"><?php echo $config->GOOGLE_MAP_STYLES_JSON; ?></textarea>
				</fieldset>
					
					<fieldset>
						<legend>Locate Me Radius Range</legend>
						<input type="text" placeholder="Locate Me Radius Range" value="<?php echo $config->LOCATE_ME_RADIUS_RANGE;?>" name="locate_me_range">
					</fieldset>
					<fieldset>
					<legend> Select Front End Templates Options</legend>
						<select name="map_template">
							<option value="Select" selected> Select Front End Templates</option>
							<option value="classic" <?php if($config->MAP_TEMPLATE=="classic"){ echo "selected";} ?> >Classic</option>
							<option value="theme1" <?php if($config->MAP_TEMPLATE=="theme1"){ echo "selected";} ?>>Theme1</option>
							<option value="theme2" <?php if($config->MAP_TEMPLATE=="theme2"){ echo "selected";} ?>>Theme2</option>
							<option value="theme3" <?php if($config->MAP_TEMPLATE=="theme3"){ echo "selected";} ?>>Theme3</option>
						</select>
					</fieldset>

					<fieldset>
					<legend>Pointer Type</legend>
						<select name="pointer_type">
							<option value="default" <?php if($config->POINTER_TYPE=="default"){ echo "selected";} ?> >Default</option>
							<option value="custom" <?php if($config->POINTER_TYPE=="custom"){ echo "selected";} ?>>Customised</option>
						</select>
					</fieldset>

				<fieldset>
					<br>
					<fieldset>
						<label>Get Direction Search Units Option</label>&nbsp;&nbsp;
							<input type="radio" name="unit" value="miles" <?php if($config->SEARCH_UNIT=="miles"){echo "checked";} ?>> Miles 
							<input type="radio" name="unit" value="km" <?php if($config->SEARCH_UNIT=="km"){echo "checked";} ?>> KM
					</fieldset>
					<br>
					<fieldset>
						<label>Show Search Box Option </label>&nbsp;&nbsp;
							<input type="radio" name="search_box" value="1" <?php if($config->DISPLAY_SEARCH_BOX=="1"){echo "checked";} ?>> Yes 
							<input type="radio" name="search_box" value="0" <?php if($config->DISPLAY_SEARCH_BOX=="0"){echo "checked";} ?>> No
					</fieldset>
					<br>
					<fieldset>
						<label>Google Autocomplete Address</label>&nbsp;&nbsp;
							<input type="radio" name="autocomplete" value="1" <?php if($config->GOOGLE_AUTOCOMPLETE=="1"){echo "checked";} ?>> Yes
							<input type="radio" name="autocomplete" value="0" <?php if($config->GOOGLE_AUTOCOMPLETE=="0"){echo "checked";} ?>> No
					</fieldset>
					<br>
					<fieldset>
						<label>Display Locate Me Button</label>&nbsp;&nbsp;
							<input type="radio" name="locate_me" value="1" <?php if($config->LOCATE_ME=="1"){echo "checked";} ?>> Yes
							<input type="radio" name="locate_me" value="0" <?php if($config->LOCATE_ME=="0"){echo "checked";} ?>> No
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
							<legend>Upload Pointer Image</legend>
							<input type="file" name="media[]" multiple accept="image/x-png,image/gif,image/jpeg" style="width: 30%;margin: 10px 10px;">
							<input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
						</fieldset>
						<fieldset>
							<input type="submit" value="upload" name="upload" class="button button-primary">
						</fieldset>

						<input type="hidden" name="action" value="jsp_add_store_media">
						<input type="hidden" name="jsp_store_media" value="<?php echo $jsp_store_media ?>" />
						<input type="hidden" name="type" value="pointer">
						<table>
						<?php 
							$storedata = $this->model->getConfig();

							$images = $storedata->MAP_POINTER_IMAGE;

							if(!empty($images)){
							$images = explode(",", $images);
							
							foreach ($images as $key => $value) {
						 ?>
							<tr>
								<td><input type="checkbox" name="list[]" value="<?php echo $value; ?>"></td>
								<td><img width="150px" src="<?php echo PLUGIN_URL.'/uploads/pointer/'.$value; ?>"></td>
							</tr>
						</div>
						<?php } } ?>

					</table>
					<input type="submit" value="delete" name="delete" class="button button-primary">
				</form>
				</div>
			</div>
			<script type="text/javascript">
				jQuery("#show_media").click(function(){
					jQuery("#media_section").show();
					jQuery("#form_section").hide();
					});
				jQuery("#show_form").click(function(){
					jQuery("#media_section").hide();
					jQuery("#form_section").show();
				});
			</script>
		 <?php
	
	}


	public function import_export_form() {
		echo "<h1>".esc_html(get_admin_page_title())."</h1>";
	?>
		<div class="main" style="width:100%;display:inline-block;">
			<div class="form-group" style="margin:50px 0px;">
				<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" id="import_export_form" enctype="multipart/form-data">
					<?php settings_fields('wporg_options');
						do_settings_sections('wporg');
						$import_export_nonce = wp_create_nonce('import_export_nonce');
					?>
					<input type="hidden" name="action" value="jsp_import_export">
					<input type="hidden" name="import_export_nonce" value="<?php echo $import_export_nonce ?>" />
					<input type="hidden" name="import_or_export" id="import_or_export" value="default">
					<h2>Import Location</h2>
					<fieldset style="border:2px solid #968e8e;padding:34px;width: 40%;">
						<label>Select File</label>
						<input type="file" name="importfile">
						<input type="submit" value="Import" name="importdata" class="button-primary">
						<?php if(isset($_REQUEST['import']))
								echo "<h3 class='ie_message'>".$_REQUEST['import']." Rows are inserted into database.</h3>";
						?>
					</fieldset>		

					<h2>Export Location</h2>
					<fieldset style="border:2px solid #968e8e;padding:34px;width: 40%;">
						<a href="" target="_blank">Sample File</a>&nbsp;&nbsp;
						<input type="submit" value="Export" name="exportdata" class="button-primary">					
						<h3 class="ie_message"></h3>
						</fieldset>
				</form>
				
			</div>
		</div>
<?php
	}

	public function google_places() {
?>
		<div class="main" style="width:100%;display:inline-block;">
			<div class="form-group" style="margin:50px 0px;">
				<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">
					<?php settings_fields('wporg_options');
						do_settings_sections('wporg');
						$jsp_google_places_nonce = wp_create_nonce('jsp_google_places_nonce');
					?>
					<input type="hidden" name="action" value="jsp_google_places">
					<input type="hidden" name="jsp_google_places_nonce" value="<?php echo $jsp_google_places_nonce ?>" />
					<fieldset style="border:2px solid #968e8e;padding:34px;width: 40%;">
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
					<input type="hidden" name="action" value="add_google_places">
					<input type="hidden" name="jsp_google_places_nonce" value="<?php echo $jsp_google_places_nonce ?>" />
				<table style="width:100%;border:1px solid #b5abab; margin-top: 2em">
					<input type="hidden" name="location_id">
					<tr>
						<th>No.</th>
						<th>Name</th>
						<th>Address</th>
						<th></th>
					</tr>
					<?php
						$google_places = $this->model->getGoogleplaces();
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
		</script>


<?php
	}


	public function branch_hit(){
		echo "branch hit";
	}

	public function search_hit(){
		echo "search_hit hit";
	}
}

?>