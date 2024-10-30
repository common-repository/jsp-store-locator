
<?php

/*
Author: Ajay Lulia
Version: 1.0
Author URI: http://www.joomlaserviceprovider.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$GLOBALS['map_type'];
class Jspsl_Public {

    private $pointer_img;   

    function jspsl_get_current_url(){
          global $wp;
          return home_url(add_query_arg(array(),$wp->request));
    }
    
    public function jspsl_frontendStoreLocator() {

        $loadStores = array();      

        $config = $this->jspsl_getConfig();
        $loadDependencies = $this->jspsl_loadDependencies($theme=$config[0]->MAP_TEMPLATE);

        $setMapType = sanitize_text_field($_GET['map_type']);
        if($setMapType != ''){
            $map_type = $setMapType;
        }
        else{
            $map_type = $config[0]->MAP_TYPE;
        }
        if(sanitize_text_field($_GET['page']) == 'jspsl_redirectviewinfo'){

        $redirectviewinfo = $this->jspsl_redirectviewinfo_page();
        
        }
        elseif(sanitize_text_field($_GET['page']) == 'jspsl_getDirections'){
            // $map_type = 0;
            if($map_type == 1){
            $jspsl_getDirections = $this->jspsl_getBingDirections($config);    
            }
            else{
            $jspsl_getDirections = $this->jspsl_jspsl_getDirections($config);
            }
        }
        elseif(sanitize_text_field($_POST['category'])){
            $loadStores = $this->jspsl_loadCategories(sanitize_text_field($_POST['category']));
            //print_r($loadStores); die; 
            $selectedbranchlist = $this->jspsl_selectedbranchlist($loadStores);    
        }
        elseif(sanitize_text_field($_GET['page']) == 'jspsl_fullbranchlist'){            

            $fullbranchlist = $this->jspsl_fullbranchlist($loadStores);
        }        
        else{          

            $data = $this->jspsl_checkstores();
            
            $template = $this->jspsl_getTemplate($data,$config);
            
            $setMapType = sanitize_text_field($_GET['map_type']);
            if($setMapType != ''){
                $map_type = $setMapType;
            }
            else{
                $map_type = $config[0]->MAP_TYPE;
            }   

            if($map_type == 1){
                return $this->jspsl_getBingmap($data,$config);              
            }
            else{
                return $this->jspsl_getGooglemap($data,$config);
            }
        }
    } 

    public function jspsl_getConfig(){
        global $wpdb;

        $query="select * from ".JSPSL_DB_PREFIX."configuration_table";
        $result = $wpdb->get_results($query);
        return $result;
    }

    public function jspsl_loadCategories($category){
        global $wpdb;

        $query="select ID from ".JSPSL_DB_PREFIX."store_categories where CATEGORY_NAME='".$category."'";

        $result = $wpdb->get_results($query);

        $category_id = $result[0]->ID;

        $store_query = "select * from ".JSPSL_DB_PREFIX."stores where CATEGORY_ID=".$category_id;

        $store_result = $wpdb->get_results($store_query);

        return $store_result;

    }   

    public function jspsl_selectedbranchlist($loadStores){
           global $wpdb; 

           $query="select CATEGORY_NAME from ".JSPSL_DB_PREFIX."store_categories";

            $result = $wpdb->get_results($query);

            $mCustomScrollbar_url = plugins_url().'/jsp-store-locator/public/js/jquery.mCustomScrollbar.concat.min.js'; 
            $bootstrap_css_url = plugins_url().'/jsp-store-locator/public/css/bootstrap.min.css';
            $mCustomScrollbar_css_url = plugins_url().'/jsp-store-locator/public/css/jquery.mCustomScrollbar.css';  
            $fontawesome_url = 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

        wp_enqueue_style('fontawesome_url', $fontawesome_url);
        wp_enqueue_style('bootstrap_css_url', $bootstrap_css_url);
        wp_enqueue_style('mCustomScrollbar_css_url', $mCustomScrollbar_css_url);

        wp_enqueue_script('mCustomScrollbar_url', $mCustomScrollbar_url);

            ?>
            

        <script>
            
        function formsubmitfn(){
            document.getElementById("category_form").submit();

        }


        </script>
<div class="mapholder jsp_main">
            <div class="jsp_head" style="font-weight:500">
                <div class="jsp_info_titlewrap">
                    <div class="list_title" style="font-weight:500;">
                        Complete Branch List 
                    </div>
                    <div class="country">

        <form action="" method="post" name="category_form" id="category_form" onchange="return formsubmitfn();">
            <select name="category" id="category" class="selectpicker form-control">                       
                        <option value="CAT">Choose a category</option>
                          <?php                       
                        
                            for($i=0;$i<count($result);$i++){
                            if($result[$i]->CATEGORY_NAME == $this->Category_Selected){
                            ?>
                            
                            <option selected>
                            <?php
                            echo $result[$i]->CATEGORY_NAME;
                            ?>
                             </option>
                            <?php
                            }
                            else{
                            ?>
                            
                
                            <option>
                            <?php
                            echo $result[$i]->CATEGORY_NAME;
                            ?>
                             </option>
                            
                            <?php
                            }
                            }
                            ?>
            </select>
        </form>      

</div>    
                </div>
            </div>
            <div class="jsp_locator_branches">
            <div class="info_wrap">
            <div class="info_wrap1 mCustomScrollbar">
                <div class="list_left">
                    <div class="list_info">
                       <p class="list_info_title">Branch List</p>
                        
                        <?php 
                        
                        echo '<div class="row">';
                        
                        for($i=0,$k=1;$k<=count($loadStores);$i++,$k++){
                        
                        echo '<div class="col-md-3">';
                        
                        $branchid = $loadStores[$i]->ID;
                        $branchname = $loadStores[$i]->NAME;
                        $cityname = $loadStores[$i]->CITY;
                        $countryname = $loadStores[$i]->COUNTRY;                      
                        
                        
                        if($branchid == ""){
                        
                        break;
                        
                        }                        
                        
                        ?>
                         <a href=""><?php echo $branchname;?></a><?php echo ' - '.$cityname.', '.$countryname; ?> 
                        <?php     
                        
                        echo '</div>';
                        
                        if($k%4 == 0){                        
                        echo '</div></br><div class="row">';
                        }
                        
                        }
                        
                        ?>
                        
                   </div>
                    
                </div>
                
            </div>
            <div class="bottom_footer">
                <div class="full_left">
                    <a href="<?php echo $this->jspsl_get_current_url();?>"><i class="fa fa-home"></i></a>
                </div>
               </div>
        

        <div class="info_wrap1 mCustomScrollbar">
                <div class="list_left">
                    <div class="list_info">
                       
                        
                      
                    
                </div>
                
            </div>

        <?php
    }

    public function jspsl_fullbranchlist($loadStores){
        global $wpdb; 
        $query="select CATEGORY_NAME from ".JSPSL_DB_PREFIX."store_categories";
        $categoryname_result = $wpdb->get_results($query);

        $fullbranchlist_url = $this->jspsl_get_current_url().'?page=jspsl_fullbranchlist';

         $mCustomScrollbar_url = plugins_url().'/jsp-store-locator/public/js/jquery.mCustomScrollbar.concat.min.js'; 
            $bootstrap_css_url = plugins_url().'/jsp-store-locator/public/css/bootstrap.min.css';
            $mCustomScrollbar_css_url = plugins_url().'/jsp-store-locator/public/css/jquery.mCustomScrollbar.css';  
            $fontawesome_url = 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

            wp_enqueue_style('fontawesome_url', $fontawesome_url);
	        wp_enqueue_style('bootstrap_css_url', $bootstrap_css_url);
	        wp_enqueue_style('mCustomScrollbar_css_url', $mCustomScrollbar_css_url);

	        wp_enqueue_script('mCustomScrollbar_url', $mCustomScrollbar_url);
            ?>


        <script>
            
        function formsubmitfunction(){
            document.getElementById("category_form").submit();
        }


        </script>

     
        <?php 

        if(empty($loadStores)){

        $query="select * from ".JSPSL_DB_PREFIX."stores";

        $result = $wpdb->get_results($query);

        
        ?>


        <div class="mapholder jsp_main">
            <div class="jsp_head" style="font-weight:500">
                <div class="jsp_info_titlewrap">
                    <div class="list_title" style="font-weight:500;">
                        Complete Branch List 
                    </div>
                    <div class="country">
                    <form action="" method="post" name="category_form" id="category_form" onchange="return formsubmitfunction();">
                    
                        <select name="category" id="category" class="selectpicker form-control">                       
                        <option value="CAT">Choose a category</option>
                          <?php                       
                            
                            for($i=0;$i<count($categoryname_result);$i++){
                            if($categoryname_result[$i]->CATEGORY_NAME == $this->jspsl_Category_Selected){
                            ?>
                            
                            <option selected>
                            <?php
                            echo $categoryname_result[$i]->CATEGORY_NAME;
                            ?>
                             </option>
                            <?php
                            }
                            else{
                            ?>
                            
                
                            <option>
                            <?php
                            echo $categoryname_result[$i]->CATEGORY_NAME;
                            ?>
                             </option>
                            
                            <?php
                            }
                            }
                            ?>
            </select>
                       
                    </form> 
                        
                    </div>    
                </div>
            </div>
            <div class="jsp_locator_branches">
            <div class="info_wrap">
            <div class="info_wrap1 mCustomScrollbar">
                <div class="list_left">
                    <div class="list_info">
                       <p class="list_info_title">Branch List</p>
                        
                      <?php 
                        
                        echo '<div class="row">';
                        
                        for($i=0,$k=1;$k<=count($result);$i++,$k++){
                        
                        echo '<div class="col-md-3">';
                        
                        $branchid = $result[$i]->ID;
                        $branchname = $result[$i]->NAME;
                        $cityname = $result[$i]->CITY;
                        $countryname = $result[$i]->COUNTRY;
                        
                        
                        
                        
                        if($branchid == ""){
                        
                        break;
                        
                        }                        
                        
                        ?>
                         <a href="?page=jspsl_redirectviewinfo&id=<?php echo $branchid;?>"><?php echo $branchname;?></a><?php echo ' - '.$cityname.', '.$countryname; ?> 
                        <?php     
                        
                        echo '</div>';
                        
                        if($k%4 == 0){                        
                        echo '</div></br><div class="row">';
                        }
                        
                        }
                        
                        ?>
                       
                    
                </div>
                </div>
                </div>
            </div>
            </div>
            
            <div class="bottom_footer">
                <div class="full_left">
                    <a href="<?php echo $this->jspsl_get_current_url();?>"><i class="fa fa-home"></i></a>
                </div>
               </div>
        

        <div class="info_wrap1 mCustomScrollbar">
                <div class="list_left">
                    <div class="list_info">
                       
                        
                      
                    
                </div>
                
            </div>

        <?php

        }


    }

    public function jspsl_redirectviewinfo_page(){
        global $wpdb;   
        if(sanitize_text_field($_GET['id'])){            

            $query="select * from ".JSPSL_DB_PREFIX."stores where ID=".sanitize_text_field($_GET['id']);
            
            $result = $wpdb->get_results($query);
            
            global $wp;
            $setMapType = sanitize_text_field($_GET['map_type']);
            if($setMapType != ''){
                $jspsl_getDirections_url = $this->jspsl_get_current_url().'?page=jspsl_getDirections&id='.sanitize_text_field($_GET['id']).'&map_type='.$setMapType;
            }
            else{
                $jspsl_getDirections_url = $this->jspsl_get_current_url().'?page=jspsl_getDirections&id='.sanitize_text_field($_GET['id']);
            }
                

            $fullbranchlist_url = $this->jspsl_get_current_url().'?page=jspsl_fullbranchlist';
            $mCustomScrollbar_url = plugins_url().'/jsp-store-locator/public/js/jquery.mCustomScrollbar.concat.min.js'; 
            $bootstrap_css_url = plugins_url().'/jsp-store-locator/public/css/bootstrap.min.css';
            $mCustomScrollbar_css_url = plugins_url().'/jsp-store-locator/public/css/jquery.mCustomScrollbar.css';
            $w3_css_url = plugins_url().'/jsp-store-locator/public/css/w3.css';
            $fontawesome_url = 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';
            $lightboxcss_url = plugins_url().'/jsp-store-locator/public/css/lightbox.css';
            
            wp_enqueue_style('fontawesome_url', $fontawesome_url);
	        wp_enqueue_style('bootstrap_css_url', $bootstrap_css_url);
	        wp_enqueue_style('mCustomScrollbar_css_url', $mCustomScrollbar_css_url);

            wp_enqueue_style('w3c_css', $w3_css_url);
            wp_enqueue_style('lightboxcss_url', $lightboxcss_url);

	        wp_enqueue_script('mCustomScrollbar_url', $mCustomScrollbar_url);
            ?>           

            <!-- HTML of DIV -->
            <div class="mapholder jsp_main">
            <div class="jsp_head" style="font-weight:500">
                <div class="info_title">
                    <?php echo $result[0]->NAME;?></div>
            </div>
            <div class="jsp_locator_branch">
                <div class="location_list_branch mCustomScrollbar" data-mcs-theme="minimal-dark">
                    <ul class="locationbranch">
                            <li class="branch_finder">
                                <div class="info_one">
                                    <div class="address">
                                        <p style="color:#000;font-size: 17px !important;"><?php echo $result[0]->NAME;?></p>
                                        <p><?php echo $result[0]->ADDRESS;?></p>
                                        <p><?php echo $result[0]->CITY;?></p>
                                    </div>

                                    <div class="link">
                                        <p>                                           
                                        </p>
                                    </div>
                                    
                                </div>
                                <div class="info_two">
                                <p class="branch_hrs">Additional Information</p>
                                <p>Contact Person - <?php echo $result[0]->CONTACT_PERSON;?></p>
                                <p>Gender - <?php echo $result[0]->GENDER;?></p><p>E-Mail - <a href="mailto:<?php echo $result[0]->EMAIL;?>" target="_top"><?php echo $result[0]->EMAIL;?></a></p>
                                <p>Contact Number - <a href="tel:<?php echo $result[0]->CONTACT_NUMBER;?>" target="_top"><?php echo $result[0]->CONTACT_NUMBER;?></a></p>
                                <p>Website - <a target="_blank" href="<?php echo $result[0]->WEBSITE;?>"><?php echo $result[0]->WEBSITE;?></a></p>
                                <p>Facebook - <a target="_blank" href="<?php echo $result[0]->FACEBOOK;?>"><?php echo $result[0]->FACEBOOK;?></a></p> 
                                <p>Twitter - <a target="_blank" href="<?php echo $result[0]->TWITTER;?>"><?php echo $result[0]->TWITTER;?></a></p>
                                <?php 
                                    $customFieldValues = json_decode($result[0]->CUSTOM_FIELD);
                                    if($customFieldValues != ''){   
                                        foreach($customFieldValues as $key => $customFieldValue){
                                            print_r('<p>'.ucwords($customFieldValue->key).' - '.$customFieldValue->value.'</p>');
                                        }
                                    }
                                ?>
                                <p>Description - <?php echo $result[0]->DESCRIPTION;?>
                            </div>
                            </li>                       

                    </ul>
                    
                </div>
                <div class="location_photos">
                    <div class="w3-content w3-display-container">

                    <?php

                    $store_images = $result[0]->STORE_IMAGE;
                    if($store_images != ''){
                    $store_images_array = explode(",",$store_images);

                    foreach($store_images_array as $img_name){                                 
                    $img_path = plugin_dir_url( dirname( __FILE__ ) ).'/uploads/locations/'.$img_name;
                    ?>

                        <div class="w3-display-container mySlides" id="mySlides">
                           <a class="example-image-link" href="<?php echo $img_path;?>" data-lightbox="roadtrip">
                                <img class="example-image" src="<?php echo $img_path;?>" alt=""/>
                            </a>
                        </div>

                    <?php
                    }
                    ?>

                                                
                        <button class="w3-button w3-display-left w3-black" onclick="plusDivs(-1)">&#10094;</button>
                        <button class="w3-button w3-display-right w3-black" onclick="plusDivs(1)">&#10095;</button>
                    <?php } ?> 
                    </div>
                </div>
            </div>
            <div class="branchmedia">
                <div class="info_title2"><?php echo $result[0]->NAME;?> Media</div>
                 <div class="container-fluid">
                     <div class="row branch_videos">
                         <div class="col-sm-12 col-md-12">

                         <?php 
                            if($result[0]->STORE_VIDEO_URL){
                         ?>

                         <iframe width="560" height="315" src="<?php echo $result[0]->STORE_VIDEO_URL;?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        <?php 
                            }
                        ?> 
                         </div>
                         
                         
                     </div>
                 </div>
            </div>
            <div class="bottom_footer">
                <div class="full_left">
                    <a href="<?php echo $this->jspsl_get_current_url();?>"><i class="fa fa-home"></i></a>
                </div>
                <a href=""> &nbsp;&nbsp;<img src=""></a></div>
            <div class="full_branch_list"><a href="<?php echo $fullbranchlist_url;?>">Click here for full branch list</a></div>
        </div>

        </div>

            <!-- HTML of DIV -->


            <?php

        }
        else{
            return;
        }

        ?>
         <script>
        lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true
        })
        </script>
<script>
     var slideIndex = 1;
                showDivs(slideIndex);
                
                function plusDivs(n) {
                  showDivs(slideIndex += n);
                }
                
                function showDivs(n) {
                  var i;
                  var element = document.getElementById("mySlides");
                  var x = document.getElementsByClassName("mySlides");

                  if (typeof(element) != 'undefined' && element != null)
                    {
                          if (n > x.length) {slideIndex = 1}    
                          if (n < 1) {slideIndex = x.length}
                          for (i = 0; i < x.length; i++) {
                             x[i].style.display = "none";  
                          }
                          x[slideIndex-1].style.display = "block";
                    }                    
                }

</script>
<?php 
    }

    public function jspsl_checkstores(){

        if(sanitize_text_field($_POST['zipsearch'])){
            $data = $this->jspsl_searchlocationdata(sanitize_text_field($_POST['zipsearch']));
        }
        else{            
            $data = $this->jspsl_getstores();
        }

        return $data;
    }

    public function jspsl_haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo,$earthRadius)
    {
      // convert from degrees to radians
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);

      $latDelta = $latTo - $latFrom;
      $lonDelta = $lonTo - $lonFrom;

      $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
      return $angle * $earthRadius;
    }

    public function jspsl_searchlocationdata($data){
        global $wpdb;
 
        $searchquery = urlencode($data);
 
        if($searchquery!=''){
            $config = $this->jspsl_getConfig();
            $GoogleAPI = $config[0]->GOOGLE_MAP_KEY;
 
         /** If Yahoo API Doesn't return any result then search by Google API **/
 
           $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$searchquery.'&key='.$GoogleAPI;
            $output = json_decode(file_get_contents($url));
             
            if($output->status == 'OK'){
            $lat = $output->results[0]->geometry->location->lat;
            $long = $output->results[0]->geometry->location->lng;
            $latfrom = $output->results[0]->geometry->bounds->southwest->lat;
            $latto = $output->results[0]->geometry->bounds->northeast->lat;
            $longfrom = $output->results[0]->geometry->bounds->southwest->lng;
            $longto = $output->results[0]->geometry->bounds->northeast->lng;
            }
 
            $search_radius = 0;
 
            if($search_radius == 0){
            $distance = $this->jspsl_haversineGreatCircleDistance($latfrom,$longfrom,$latto,$longto,$earthRadius = 3959);
            $radius = $distance;
 
            }
            else{
            $radius = $search_radius;
            }
 
            $query="SELECT ".JSPSL_DB_PREFIX."stores.*, IFNULL(( 6371* acos( cos( radians('37.09024') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('-95.712891') ) + sin( radians('37.09024') ) * sin( radians( latitude ) ) ) ),0) As distance FROM ".JSPSL_DB_PREFIX."stores WHERE (((ACOS( SIN(RADIANS($lat)) * SIN(RADIANS(".JSPSL_DB_PREFIX."stores.latitude)) + COS(RADIANS($lat)) * COS(RADIANS(".JSPSL_DB_PREFIX."stores.latitude)) * COS(RADIANS(".JSPSL_DB_PREFIX."stores.longitude) - RADIANS($long)) ) * 3963.1676) <= $radius) OR (".JSPSL_DB_PREFIX."stores.latitude = $lat AND ".JSPSL_DB_PREFIX."stores.longitude = $long)) ORDER BY distance ASC";
                        
            $result = $wpdb->get_results($query);
            

            if(!empty($result)){
                $temp = $result;
            }
 
 
        }
 
 
        return $temp;
 
    }

    public function jspsl_getstores() {
        global $wpdb;
        $query = "select * from ".JSPSL_DB_PREFIX."stores";
        return $wpdb->get_results($query);

    }

    public function jspsl_loadDependencies($theme){
        wp_enqueue_script('jquery');
        wp_enqueue_style('bootstrap', JSPSL_PLUGIN_URL."public/css/bootstrap.min.css");
        wp_enqueue_style('mCustomScrollbar', JSPSL_PLUGIN_URL."public/css/jquery.mCustomScrollbar.css");
        wp_enqueue_style('font-awesome', "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
        wp_enqueue_script('locatorjs', JSPSL_PLUGIN_URL."public/js/script.js");
        wp_enqueue_script('scrolljs', JSPSL_PLUGIN_URL."public/js/jquery.mCustomScrollbar.concat.min.js");
        if($theme=="classic"){
            wp_enqueue_style('locator', JSPSL_PLUGIN_URL."public/css/classic.css");
            $this->pointer_img= 'classic.png';
        }
        if($theme=="theme1"){
           wp_enqueue_style('locator', JSPSL_PLUGIN_URL."public/css/theme1.css");
           $this->pointer_img= 'theme1.png';
        }
        if($theme=="theme2"){
            wp_enqueue_style('locator', JSPSL_PLUGIN_URL."public/css/theme2.css");
            $this->pointer_img= 'theme2.png';
        }
        if($theme=="theme3"){
            wp_enqueue_style('locator', JSPSL_PLUGIN_URL."public/css/theme3.css");
            $this->pointer_img= 'theme3.png';
        }
    }



    public function jspsl_getPointerType(){
        global $wpdb;
        $query="select w.POINTER_TYPE from ".JSPSL_DB_PREFIX."configuration_table w";
        $result = $wpdb->get_row($query);
        $result = $result->POINTER_TYPE;
        if($result == 'default')
            {return 0;}
        else
            {return 1;}
    }

    public function jspsl_getTemplate($storelist,$config) {


        $fullbranchlist_url = $this->jspsl_get_current_url().'?page=jspsl_fullbranchlist';
        $display_search_box = $config[0]->DISPLAY_SEARCH_BOX;
        $locate_me = $config[0]->LOCATE_ME;
        $frontend_map_type = $config[0]->FRONTEND_MAP_TYPE;

        
        if(!$display_search_box){
            $display_search_box_class = "style='visibility:hidden;'";
        }
        if($locate_me==0){
            $locate_me_class = "style='visibility:hidden;'";
        }
        if($frontend_map_type==0){
            $frontend_map_type_class = "style='visibility:hidden;'";
        }

    ?>
        <div class="mapholder">
            <div class="head_holder">
                <div class = "map_title"><?= $config[0]->MAP_TITLE ?></div>
                
           <p class="findstore" <?php echo $display_search_box_class; ?>>Find A Store</p>
                <form name="searchform" class="searchform" action="" method="post" <?php echo $display_search_box_class; ?>>
                <input name="zipsearch" id="zipsearch" maxlength="" alt="" class="input_search form-control" type="text" size="" title="Enter ZIP/Postal Code and select appropiate Radius Range to search locations with-in entered ZIP/Postal Code on the current map" value="" placeholder="Enter city, state, country or zip code" autocomplete="off">
               <span id="srch_close" onclick="javascript:(function() { window.parent.location = window.parent.location.href;})()" style = "background: url(<?php echo JSPSL_PLUGIN_URL;?>images/srch_close.png) no-repeat;" title="Click to Reset" class="srch_close"></span>
                </form>
                <select name="map_type" id="map_type" <?php echo $frontend_map_type_class; ?>>
                    <?php
                        $setMapType = sanitize_text_field($_GET['map_type']);
                        if($setMapType != ''){
                            $map_type = $setMapType;
                        }
                        else{
                            $map_type = $config[0]->MAP_TYPE;
                        }
                    ?>
                    <option value="0" <?php if($map_type == 0) echo "selected"; ?>>Google Map</option>
                    <option value="1" <?php if($map_type == 1) echo "selected"; ?>>Bing Map</option>
                </select>   
                
               <a href="javascript:void(0);" id="locateme" class="locateme" <?php echo $locate_me_class; ?>> Locate me </a>
               <span class="locationsetter" <?php echo $locate_me_class; ?>>&nbsp;
               <img class="locate_not" src="<?php echo JSPSL_PLUGIN_URL."images/".$this->pointer_img; ?>">&nbsp;<span><i class="fa fa-angle-right"></i></span></span>
            </div>
            <div class="jsp_locator">
                <div class="location_list mCustomScrollbar" data-mcs-theme="minimal-dark">
                    <ul class="locationfinder">

                    <?php

                   if(!empty($storelist)){

                    ?>

                        <?php foreach ($storelist as $key => $value) { ?>
                        <li class="location_namer" >
                                <input type="hidden" class="latitude" value="<?php echo $value->LATITUDE; ?>">
                                <input type="hidden" class="longitude" value="<?php echo $value->LONGITUDE; ?>">
                                <input type="hidden" class="store_id" value="<?php echo $key; ?>">
                                <a href="javascript:void(0);">
                                        <div class="branch_number">
                                            <div class="pointer"><?php print_r($key+1); ?></div>
                                        </div>
                                        <div class="mapdata">
                                                <p class="branch_del_head"><?php echo $value->NAME; ?></p>
                                                <p><?php echo $value->ADDRESS; ?></p>
                                                 <?php
                                                    $setMapType = sanitize_text_field($_GET['map_type']);
                                                    if($setMapType != ''){
                                                        $redirect_url = $this->jspsl_get_current_url().'?page=jspsl_redirectviewinfo&id='.$value->ID.'&map_type='.$setMapType;
                                                    }
                                                    else{
                                                        $redirect_url = $this->jspsl_get_current_url().'?page=jspsl_redirectviewinfo&id='.$value->ID;
                                                    }
                                                    
                                                ?>
                                                <a href="<?php echo $redirect_url;?>" class="branch_view">View branch details &nbsp;<i class="fa fa-angle-right"></i></a>
                                        </div>
                                    </a>
                                        
                                    
                                </li>                       
                           <?php } ?>
                    <?php } ?>
                        
                    </ul>
                    
                </div>
                 <?php

                   if(!empty($storelist)){

                    ?>
                <div class="location_map">
                    <div id="map" style="height:550px;background:#ddd;"></div>
                </div>
                 <?php } ?>
            </div>
            
            <div class="full_branch_list"><a href="<?php echo $fullbranchlist_url;?>">Click here for full branch list</a></div>
            
        </div>
<?php
    }

public function jspsl_getBingDirections($config){}

public function jspsl_jspsl_getDirections($config){}

        public function jspsl_getBingmap($storelist,$config){
            global $wpdb;              

            $height = $config[0]->MAP_HEIGHT;  
            $zoomlevel = $config[0]->MAP_ZOOM;  
            //$bing_apiKey = 'Au5Vcn_ZQerApNOuiZ7btbtFropkPf-DCZcoxb3X9Wv4HpS5ByGxm0AjSPc39FuX';  
            $bing_apiKey = $config[0]->BING_MAP_KEY;
            $google_map_key = $config[0]->GOOGLE_MAP_KEY;
            $bing_url = "http://www.bing.com/api/maps/mapcontrol?key=".$bing_apiKey."&callback=GetMap";

            wp_enqueue_script('jspsl_bing_maps', $bing_url,array('jquery'), null, true  );

            $default_location_load = $config[0]->DEFAULT_LOCATION_OVERRIDE;
            $latitude_override = $config[0]->LATITUDE_OVERRIDE;
            $longitude_override = $config[0]->LONGITUDE_OVERRIDE;
            $display_search_box = $config[0]->DISPLAY_SEARCH_BOX;              
            $google_autocomplete = $config[0]->GOOGLE_AUTOCOMPLETE;

            if(sanitize_text_field($_POST['zipsearch'])){
            $searchquery    = sanitize_text_field($_POST['zipsearch']);

            $searchquery    = str_replace(" ","%20",$searchquery);

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$searchquery.'&key='.$google_map_key;

            $geocode = wp_remote_request($url);                        
            $output = json_decode($geocode['body']);

            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng; 

             }
             elseif($default_location_load != 0){
                $query="select LATITUDE,LONGITUDE from ".JSPSL_DB_PREFIX."stores where ID=".$default_location_load;
                $result = $wpdb->get_results($query);
                $latitude =  $result[0]->LATITUDE;
                $longitude =  $result[0]->LONGITUDE;    
            }
             elseif($config[0]->LATITUDE_OVERRIDE !=0 && $config[0]->LONGITUDE_OVERRIDE !=0){
                $latitude = $config[0]->LATITUDE_OVERRIDE;
                $longitude = $config[0]->LONGITUDE_OVERRIDE;
             }
             else{
                $latitude = $storelist[0]->LATITUDE;
                $longitude = $storelist[0]->LONGITUDE;
            }   

              ?>
              <style type="text/css">

                .MicrosoftMap .Infobox,

                .MicrosoftMap .infobox-body {
                   width: 350px !important;
                   max-width: 350px !important;
                   height: 150px;
                   top: -60px;
                }

                .MicrosoftMap .Infobox .infobox-stalk {
                   top: auto !important;
                   bottom: -16px !important;
                }



                .MicrosoftMap .Infobox .infobox-info {
                   max-height: 100%  !important;
                }

                </style>
             
             <script>
                var JSPSL_PLUGIN_URL = "<?php echo JSPSL_PLUGIN_URL; ?>";
                var storedata = <?php echo json_encode($storelist);?>;
                function GetMap(){ 
                var display_search_box = <?php echo $display_search_box;?>;
                var google_autocomplete = <?php echo $google_autocomplete;?>;
                var defaultInfobox=null; 
                var style="";
                lat_1 = parseFloat(storedata[0].LATITUDE);
                long_1 = parseFloat(storedata[0].LONGITUDE);
                map = new Microsoft.Maps.Map(document.getElementById('map'), {credentials: '<?php echo $bing_apiKey; ?>'  , showMapTypeSelector:false , center: new Microsoft.Maps.Location(lat_1,long_1) , zoom: <?php echo $zoomlevel; ?> , showBreadcrumb: true ,  width: 438, height: <?php echo $height;?> });
                var i=0;
                
                if(display_search_box && google_autocomplete){

                var autocomplete;            
                autocomplete = new google.maps.places.Autocomplete(
                          /** @type {HTMLInputElement} */(document.getElementById('zipsearch')),
                          { types: ['geocode'] });
                      google.maps.event.addListener(autocomplete, 'place_changed', function() {
                });
                autocomplete.addListener('place_changed', onPlaceChanged);
                }

                jQuery("#locateme").click(function(){

                    if (navigator.geolocation)
                    {   
                      
                        navigator.geolocation.getCurrentPosition(function(position) {
                        var locateme_lat = position.coords.latitude;
                        var locateme_long = position.coords.longitude;
                        var radius = <?php echo $config[0]->LOCATE_ME_RADIUS_RANGE;?>
                        
                        var MM = Microsoft.Maps;
                        var R = 6371; // earth's mean radius in km    
                        
                        var backgroundColor = new Microsoft.Maps.Color(110, 75, 0, 0);
                        var borderColor = new Microsoft.Maps.Color(150, 200, 0, 0);

                        var lat = (locateme_lat * Math.PI) / 180;     
                        var lon = (locateme_long * Math.PI) / 180;

                        var d = parseFloat(radius) / R;
                        var circlePoints = new Array();


                        for (var x = 0; x <= 360; x += 5) {
                        var p2 = new MM.Location(0, 0);
                        var brng = x * Math.PI / 180;
                        p2.latitude = Math.asin(Math.sin(lat) * Math.cos(d) + Math.cos(lat) * Math.sin(d) * Math.cos(brng));

                        p2.longitude = ((lon + Math.atan2(Math.sin(brng) * Math.sin(d) * Math.cos(lat), 
                                         Math.cos(d) - Math.sin(lat) * Math.sin(p2.latitude))) * 180) / Math.PI;
                                p2.latitude = (p2.latitude * 180) / Math.PI;
                                circlePoints.push(p2);
                        }

                        var polygon = new MM.Polygon(circlePoints, { fillColor: backgroundColor, strokeColor: borderColor, strokeThickness: 1 });
                        map.setView({ zoom: <?php echo $zoomlevel; ?>, center: new Microsoft.Maps.Location((locateme_lat), (locateme_long)), animate: true});
                            //custom logic to draw a circle
                                            
                        map.entities.push(polygon);

                        }); 
                                        
                    }

                });



    var dataLayer = new Microsoft.Maps.EntityCollection();
    map.entities.push(dataLayer);

    // Add a layer for the infobox.
    var infoboxLayer = new Microsoft.Maps.EntityCollection();
    map.entities.push(infoboxLayer);

    // Create a global infobox control.
    var infobox = new Microsoft.Maps.Infobox(new Microsoft.Maps.Location(0, 0), {
        visible: false,
        offset: new Microsoft.Maps.Point(0, -10),
        height: 120,
        width: 160
    });
    infoboxLayer.push(infobox);
                function onPlaceChanged() {
                        document.forms["searchform"].submit();
                }

                function createScaledPushpin(imgUrl, callback) {
                    var img = new Image();
                    img.onload = function () {
                        var c = document.createElement('canvas');
                        c.width = '30';
                        c.height = '30';

                        var context = c.getContext('2d');

                        //Draw scaled image
                        context.drawImage(img, 0, 0, c.width, c.height);
                        var pushpinOptions = { icon:c.toDataURL(), visible: true};
                        if (callback) {
                            callback(pushpinOptions);
                        }
                    };

                    img.src = imgUrl;
                }
                var current_url = "<?php echo $this->jspsl_get_current_url(); ?>";
                storedata.forEach(function(element){
                    var pushpinOptions;
                    var issetPointerType = <?php $issetPointerType = $this->jspsl_getPointerType(); echo $issetPointerType; ?>;
                    var location_number = i+1;
                    var marker_color = "black";                     
              

                    if(element.LATITUDE != '' && element.LONGITUDE != ''){
                        var image ="https://cdn0.iconfinder.com/data/icons/small-n-flat/24/678111-map-marker-32.png";

                        if(element.POINTER_IMAGE != '' && issetPointerType != 0){
                            var image =  JSPSL_PLUGIN_URL+"uploads/pointer/"+element.POINTER_IMAGE;
                        }
                        
                        var pushpinOptions = {icon: image, visible: true,roundClickableArea : true, Offset: new Microsoft.Maps.Point(100,100) };
                        var pushpin= new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(element.LATITUDE,element.LONGITUDE), pushpinOptions);
                        var plugin_path = '<?php echo JSPSL_PLUGIN_URL.'uploads/locations/';?>';
                        var img = element.STORE_IMAGE;    
                            
                            var img_array = img.split(',');
                            var infowindow_store_image = plugin_path+img_array[0];
                            var setMapType =<?= (sanitize_text_field($_GET['map_type']) != '')? sanitize_text_field($_GET['map_type']):2;?>;
                            if(setMapType == 0|| setMapType == 1){
                                redirect_url = current_url+"?page=jspsl_redirectviewinfo&id="+element.ID+"&map_type="+setMapType;
                            }
                            else{
                                redirect_url = current_url+"?page=jspsl_redirectviewinfo&id="+element.ID;
                            }
                    pushpin.metadata = {
                        title :element.NAME,
                        description: '<div id="bing-map"><div class="map-img" style="width: 40%; display: inline-block; float: left; height: 80px;"><img style="width: 100%; height: 100%;" src="'+infowindow_store_image+'"></div>'+'<div class="map-add" style="display: inline-block; float: left; width: 60%; line-height: 1.6rem; padding-left: 10px; color: #000;">'+element.ADDRESS+element.ZIP+'<a href="'+redirect_url+'" style="display: block; margin-top: 5px;">View Branch Details &#x3e;</a></div></div>'
                    };
                    dataLayer.push(pushpin);

                    Microsoft.Maps.Events.addHandler(pushpin, 'click', function (e) {
                        displayInfobox(e.target);
                    });
                
                    }
                    i=i+1;
                }); // end foreach
                
            jQuery(".location_namer").click(function(){
                id = jQuery(this).children('.store_id').val();
                pin = dataLayer.get(id);
                map.setView({ center: pin.getLocation(), zoom:10 });
                displayInfobox(pin);
            })

                function displayInfobox(pin){
                      
                    infobox.setLocation(pin.getLocation());
                    infobox.setOptions({ visible: true,title: pin.metadata.title, description: pin.metadata.description });
                }
          
            }                

            
        </script>   
   <?php

    } 
            

public function jspsl_getGooglemap($storelist,$config) {
    global $wpdb;
    $google_map_key = $config[0]->GOOGLE_MAP_KEY;
    $height = $config[0]->MAP_HEIGHT;  
    $zoomlevel = $config[0]->MAP_ZOOM;
    $default_location_load = $config[0]->DEFAULT_LOCATION_OVERRIDE;
    $latitude_override = $config[0]->LATITUDE_OVERRIDE;
    $longitude_override = $config[0]->LONGITUDE_OVERRIDE;
    $locateme_radius = $config[0]->LOCATE_ME_RADIUS_RANGE;
    $display_search_box = $config[0]->DISPLAY_SEARCH_BOX;
    $google_autocomplete = $config[0]->GOOGLE_AUTOCOMPLETE;
    $google_map_url = 'https://maps.googleapis.com/maps/api/js?key='.$google_map_key.'&libraries=places&language='.$map_language;
    wp_enqueue_script('jspsl_google_maps', $google_map_url,array('jquery'), null, true);

    if($default_location_load !=0){

        $query="select LATITUDE,LONGITUDE from ".JSPSL_DB_PREFIX."stores where ID=".$default_location_load;
        $result = $wpdb->get_results($query);
        $lat =  $result[0]->LATITUDE;
        $long =  $result[0]->LONGITUDE;    
    }
    elseif($latitude_override !=0 && $longitude_override != 0){
        $lat = $latitude_override;
        $long = $longitude_override;
    }
    else{
        $lat = $storelist[0]->LATITUDE;
        $long = $storelist[0]->LONGITUDE;
    }   

    $map_language = $config[0]->MAP_LANGUAGE;
    //if(!empty($storelist) ){
    ?>

    
    <?php// } ?>
    <script>
        jQuery(document).ready(function() {
    initMap();
    });
    function tryParseJSON (jsonString){
        try {
            var o = JSON.parse(jsonString);
            // Handle non-exception-throwing cases:
            // Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
            // but... JSON.parse(null) returns null, and typeof null === "object", 
            // so we must check for that, too. Thankfully, null is falsey, so this suffices:
            if (o && typeof o === "object") {
                return o;
            }
        }
        catch (e) { }

        return false;
    };

    var storedata = <?php echo json_encode($storelist);?>;
    
    var map;
    var infowindow;
    var display_search_box = <?php echo $display_search_box;?>;
    var google_autocomplete = <?php echo $google_autocomplete;?>;


    function initMap() {

            lat_1 = parseFloat(storedata[0].LATITUDE);
            long_1 = parseFloat(storedata[0].LONGITUDE);

            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat:lat_1,lng:long_1},
                zoom: <?php echo $zoomlevel;?>,
            });


        if(display_search_box && google_autocomplete){
        var autocomplete;            
        autocomplete = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */(document.getElementById('zipsearch')),
                  { types: ['geocode'] });
              google.maps.event.addListener(autocomplete, 'place_changed', function() {
        });
        autocomplete.addListener('place_changed', onPlaceChanged);
        }

        function onPlaceChanged() {
                document.forms["searchform"].submit();
        }
             

    
    var markers = new Array();   
    var JSPSL_PLUGIN_URL = '<?php echo JSPSL_PLUGIN_URL;?>';    
    var plugin_path = '<?php echo JSPSL_PLUGIN_URL.'uploads/locations/';?>';    
    var infowindow = new google.maps.InfoWindow({
            maxWidth: 380
    });

     var current_url = "<?php echo $this->jspsl_get_current_url(); ?>";
        storedata.forEach(function(element,i){
        var issetPointerType = <?php $issetPointerType = $this->jspsl_getPointerType(); echo $issetPointerType; ?>;
        if(element.POINTER_IMAGE != '' && issetPointerType != 0){
            var image = {
                url: JSPSL_PLUGIN_URL+"uploads/pointer/"+element.POINTER_IMAGE,
                scaledSize: new google.maps.Size(30, 30)
            };
        }
        else{
            var image = JSPSL_PLUGIN_URL+"uploads/pointer/pointer.png";
        }
        var img = element.STORE_IMAGE;    
        var img_array = img.split(',');
        var infowindow_store_image = plugin_path+img_array[0];
        
        var id = element.ID; 
        var marker = new google.maps.Marker(
                                        {
                                            map: map,
                                            title: element.NAME,
                                            icon:image,
                                            position:{lat: parseFloat(element.LATITUDE), lng: parseFloat(element.LONGITUDE)},
                                            id:id
                                        }
                                    );
        markers.push(marker);   

            jQuery(".location_namer").click(function(){
        i = jQuery(this).children('.store_id').val();
       google.maps.event.trigger(markers[i], "click");
    });          

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            
        return function() {
            var setMapType =<?= (sanitize_text_field($_GET['map_type']) != '')?sanitize_text_field($_GET['map_type']):2;?>;
            if(setMapType == 0|| setMapType == 1){
                redirect_url = current_url+"?page=jspsl_redirectviewinfo&id="+element.ID+"&map_type="+setMapType;
                direction_url = current_url+"?page=jspsl_getDirections&id="+element.ID+"&map_type="+setMapType;
            }
            else{
                redirect_url = current_url+"?page=jspsl_redirectviewinfo&id="+element.ID;
                direction_url = current_url+"?page=jspsl_getDirections&id="+element.ID;
            }

             var cont = "<div class='aligner_map'><img class='pinimg' src='"+infowindow_store_image+"'><div class='content_aligner'><p class='map_smallcnt_title'>"+element.NAME+"</p><p class='jsp_loc_marker_fields'> "+element.ADDRESS+"</p><div class='link_inline'><a href='"+redirect_url+"'>View Branch Details</a>&nbsp;</div></div></div>";    

          infowindow.setContent(cont);
          infowindow.open(map, marker);
          map.setCenter(marker.getPosition());
        }
      })(marker, i));
    });



        jQuery("#locateme").click(function(){

            if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            clat = position.coords.latitude;
            clong = position.coords.longitude;

            var locateme_radius = "<?php echo $locateme_radius;?>";
            /*Converting Radius in Meters*/
            locateme_radius = locateme_radius * 1000;
            var startpoint = new google.maps.LatLng(parseFloat(clat),parseFloat(clong));          

            /* Display Circle in Map */

            var myCity = new google.maps.Circle({
              center:startpoint,
              radius:parseFloat(locateme_radius),
              strokeColor:"#1795E7",
              strokeOpacity:0.4,
              strokeWeight:2,
              fillColor:"#7CA4E6",
              fillOpacity:0.35,
              map:map
            });
            var image = JSPSL_PLUGIN_URL+"uploads/pointer/pointer.png";
            var marker_locate_me = new google.maps.Marker(
                                            {
                                                    map: map,
                                                    title:'Locate Me',
                                                    icon:image,
                                                    position:{lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude)},
                                                    id:'1'
                                            }
                                        );
            
            marker_locate_me.setMap(map);        
            map.setZoom(10);
            map.panTo(pos);

          }, function() {
            
          });

        } else {
          // Browser doesn't support Geolocation
          alert('Your Browser doesnt support Geolocation Service');
          handleLocationError(false, infoWindow, map.getCenter());
          return false;
        }       

        });         

    }
    
    </script>

    <?php

    }

}
