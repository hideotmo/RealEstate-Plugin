<?php
// Index Page
// Wp Estate Pack
get_header();  ?>


<link href='https://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>
<style type="text/css">
.n_tab a {
    color: #fff;
}
.wpcf7-form-control-wrap textarea {
    width: inherit;
}
li#text-13 {
    border: 1px rgba(158, 158, 158, 0.29) solid;
}
h3.agent_listings_title_similar {
    font-size: 28px;
    font-weight: 600;
}
.header_media.with_search_1 {
	display: none;
}
.container.content_wrapper {
	margin-top: 170px;
}
.dev_name a {
	color: #3C9DD4;
}
.price_area {
    color: #3C90BE;
    font-size: 28px;
    padding: 16px 0px 3px 0px;
    float: right;
    font-family: 'Roboto', sans-serif;
    font-weight: 400;
}
.dev_name a:hover {
	color: #000;
}
.single-content img {
    width: auto;
	}
.n_tab li {
    list-style-type: none;
    color: #fff;
    float: left;
    border-right: 1px #9E9E9E solid;
    margin: 11px;
    padding-right: 10px;
    margin-top: 15px;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
}

textarea.wpcf7-form-control.wpcf7-textarea {
   max-width: 90%;
    margin: 10px;
    width: 100%;
	    height: 120px;
}
.wpcf7-form p {
    margin-bottom: 0;
	}
h4#show_contact {
    text-align: center;
    border-bottom: 1px #f0f0f0 solid;
    padding-bottom: 10px;
	    margin-top: 10px;
    margin-bottom: 10px;
}
.agent_contanct_form {
    border: 1px #f0f0f0 solid;
    margin-left: 10px;
    margin-right: 10px;
}
form.wpcf7-form input {
    max-width: 90%;
    margin: 10px;
    width: 100%;
}
.psd_header {
	background: #347DA4;
	float: left;
	width: 100%;
}
.adres_area {
    clear: both;
    float: left;
    color: #33383c;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
}
span.adres_area a {
    color: #33383c;
}
span.dev_name {
    color: #33383c;
    font-family: 'Roboto', sans-serif;
    font-size: 16px;
    background-color: white;
}
.prop_social_new i.fa {
	color: #fff;
	margin-left: 13px;
}
.prop_social_new {
	      float: right;
    margin: 15px;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
}
.no_views.dashboad-tooltip {
	color: #fff;
}
.n_tab {
	width: 70%;
}
.n_tab ul {
	margin: 0;
}
.add_favorites_new {
	position: relative !important;
	float: left;
	margin-left: 30px;
    font-family: 'Roboto', sans-serif;
}
#add_favorites.isfavorite {
	background-color: #9E9E9E !important
}
.big_box h1 {
	    color: #fff !important;
    font-weight: 600;
    margin: 0;
    font-size: 41px;
    font-family: 'Roboto', sans-serif !important;
}
.big_box ul {
	margin: 0;
	float:left;
	width:100%;
}
.row {
    margin-left: 15px;
    margin-right: 15px;
    font-family: 'Roboto', sans-serif;
}
.big_box LI {
	background: #33383c;
	color: #fff;
	float: left;
	text-align: center;
	margin-left: 5px;
	padding: 10px;
	list-style-type: none;
	width: 202px;
	font-size:14px;
	font-family: 'Roboto', sans-serif;
}
.post-carusel {
	margin-bottom: 10px;
}
@media only screen 
and (min-device-width : 768px) 
and (max-device-width : 1024px) 
and (orientation : landscape) { 
.row {
   margin-top: 75px !important;
	}
	}



@media only screen 
  and (min-device-width: 768px) 
  and (max-device-width: 1024px) 
  and (-webkit-min-device-pixel-ratio: 1) {
.container.content_wrapper {
    margin-top: 10px !important;
}
.big_box LI{
   width: 170px;
}
  
.big_box h1 {
    
    font-size: 34px;
	}
}
</style>
<?php 
global $current_user;
global $feature_list_array;
global $propid ;

$terms = get_the_terms( $post->ID , 'propertytypes' );
foreach ( $terms as $term ) {
 $ptype=$term->name;
}

get_currentuserinfo();
wp_estate_count_page_stats($post->ID);


$propid                     =   $post->ID;
$options                    =   wpestate_page_details($post->ID);
$gmap_lat                   =   esc_html( get_post_meta($post->ID, 'property_latitude', true));
$gmap_long                  =   esc_html( get_post_meta($post->ID, 'property_longitude', true));
$unit                       =   esc_html( get_option('wp_estate_measure_sys', '') );
$currency                   =   esc_html( get_option('wp_estate_currency_symbol', '') );
$use_floor_plans            =   intval( get_post_meta($post->ID, 'use_floor_plans', true) );      


if (function_exists('icl_translate') ){
    $where_currency             =   icl_translate('wpestate','wp_estate_where_currency_symbol', esc_html( get_option('wp_estate_where_currency_symbol', '') ) );
    $property_description_text  =   icl_translate('wpestate','wp_estate_property_description_text', esc_html( get_option('wp_estate_property_description_text') ) );
    $property_details_text      =   icl_translate('wpestate','wp_estate_property_details_text', esc_html( get_option('wp_estate_property_details_text') ) );
    $property_features_text     =   icl_translate('wpestate','wp_estate_property_features_text', esc_html( get_option('wp_estate_property_features_text') ) );
    $property_adr_text          =   icl_translate('wpestate','wp_estate_property_adr_text', esc_html( get_option('wp_estate_property_adr_text') ) );    
}else{
    $where_currency             =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $property_description_text  =   esc_html( get_option('wp_estate_property_description_text') );
    $property_details_text      =   esc_html( get_option('wp_estate_property_details_text') );
    $property_features_text     =   esc_html( get_option('wp_estate_property_features_text') );
    $property_adr_text          =   stripslashes ( esc_html( get_option('wp_estate_property_adr_text') ) );
}


$agent_id                   =   '';
$content                    =   '';
$userID                     =   $current_user->ID;
$user_option                =   'favorites'.$userID;
$curent_fav                 =   get_option($user_option);
$favorite_class             =   'isnotfavorite'; 
$favorite_text              =   __('add to favorites','wpestate');
$feature_list               =   esc_html( get_option('wp_estate_feature_list') );
$feature_list_array         =   explode( ',',$feature_list);
$pinteres                   =   array();
$property_city              =   get_the_term_list($post->ID, 'property_city', '', ', ', '') ;
$property_area              =   get_the_term_list($post->ID, 'property_area', '', ', ', '');
$property_category          =   get_the_term_list($post->ID, 'property_category', '', ', ', '') ;
$property_action            =   get_the_term_list($post->ID, 'property_action_category', '', ', ', '');   
$slider_size                =   'small';
$thumb_prop_face            =   wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'property_full');

if($curent_fav){
    if ( in_array ($post->ID,$curent_fav) ){
        $favorite_class =   'isfavorite';     
        $favorite_text  =   __('favorite','wpestate');
    } 
}

if (has_post_thumbnail()){
    $pinterest = wp_get_attachment_image_src(get_post_thumbnail_id(),'property_full_map');
}


if($options['content_class']=='col-md-12'){
    $slider_size='full';
}


?>
<style>
@media only screen and (max-width: 640px){
.content_wrapper {
    padding: 13px 13px 13px 13px!important;
    margin-top: 10px !important;
}
.n_tab li {
width: 100%;
    text-align: center;
	    border-right: none !important;
}
.add_favorites_new {
margin-left:0 !important;
}
.big_box LI {
width:100% !important;
margin-bottom: 5px !important;
    margin-left: 0 !important;
}
.notice_area {
    height: 90px !important;
}
.n_tab {
    width: 100% !important;
}
.prop_social_new {
    float: right;
    width: 100% !important;
    font-size: 16px;
    font-family: 'Roboto', sans-serif;
    text-align: center;
    margin-top: 25px;
    padding-left: 13%;
    padding-bottom: 10px;
}
.price_area {
float:none;
}
}
@media only screen 
  and (min-device-width: 640px) 
  and (max-device-width: 750px)
  and (-webkit-min-device-pixel-ratio: 2) {

.big_box h1 {
   
    font-size: 32px !important;
    
}
.big_box LI {
  
    width: 165px !important;

}

}
@media only screen and (max-width: 992px){
.container.content_wrapper {
    margin-top: 20px !important;
}}
.wpcf7-form input[type="submit"] {
   
    padding: 9px 8px!important;
}
.n_tab li:nth-child(5) {
    border-right: none !important;
}
.panel-body p {
    margin-top: 0 !important;
}
.big_box LI:nth-child(1) {
    margin-left: 0 !important;
}
@media only screen 
  and (min-device-width: 320px) 
  and (max-device-width: 480px)
  and (-webkit-min-device-pixel-ratio: 2) {
  
  .big_box li {
    width: 100% !important;
	margin-left:0;
	margin-bottom: 5px !important;
	
}
.add_favorites_new {
    
   margin-left:0px; 
	}
	.container.content_wrapper {
    margin-top: 10px !important; 
}

.notice_area {
       height: auto;
    margin-bottom: 20px !important;
}
.prop_social_new {
    float: left;
    margin: 19px;
    width: 100%;
    text-align: center;
}
.n_tab li {
    list-style-type: none;
    color: #fff;
    float: left;
    margin: 0;
    padding: 8px;
	 width: 100%;
    text-align: center;

}
.n_tab {
    width: 100%;
}
}
@media only screen 
  and (min-device-width: 414px) 
  and (max-device-width: 736px) 
  and (-webkit-min-device-pixel-ratio: 3) { 
.n_tab {
    width: 100%;
}
.prop_social_new {
    float: left;
    margin: 19px;
    text-align: center;
    width: 100%;
}
.big_box LI {
   
    float: left;
    margin-bottom: 10px;
}
.container.content_wrapper {
    margin-top: 20px;
}
.big_box LI:nth-child(even) {
    margin-left: 0 !important;
}
}
@media only screen 
  and (min-device-width: 375px) 
  and (max-device-width: 667px) 
  and (-webkit-min-device-pixel-ratio: 2)
  and (orientation: landscape) { 
.container.content_wrapper {
    margin-top: 20px;
}
.big_box LI {
    width: 49% !important;
    margin-bottom: 10px;
}
.big_box LI:nth-child(even) {
    margin-left: 0 !important;
}
}
h1.entry-title.entry-prop {
	    font-size: 28px;
    font-weight: 800;
    color: #33383c !important;
}
.notice_area {
	width: 100%;
	border-top: none;
	border-bottom:none;
}
.shortcode_title {
    padding-left: 15px;
    margin-bottom: 17px;
    font-size: 25px;
    font-weight: 600;
}
#agent_contact_name, #agent_user_email, #agent_phone {
    width: 100%;
    margin-right: 13px;
    float: left;
}
.notice_area {
	margin-top:0;
	margin-bottom:0;
}
div#slider_enable_map {
    display: none !important;
}
div#slider_enable_street {
    display: none !important;
}
div#slider_enable_slider {
    display: none !important;
}
.box {
    display: none !important;
}
.big_box {
    margin-bottom: 137px;
}
</style> 
<!-- test message - - error until this line -->
<div class="row">
  <?php get_template_part('templates/breadcrumbs'); ?>
  <div class=" <?php print $options['content_class'];?> ">
    <?php get_template_part('templates/ajax_container'); ?>
    <?php
        while (have_posts()) : the_post();
            $price          =   floatval   ( get_post_meta($post->ID, 'property_price', true) );
            $price_label    =   esc_html ( get_post_meta($post->ID, 'property_label', true) ); 
            $price_label_before    =   esc_html ( get_post_meta($post->ID, 'property_label_before', true) );  
            $image_id       =   get_post_thumbnail_id();
            $image_url      =   wp_get_attachment_image_src($image_id, 'property_full_map');
            $full_img       =   wp_get_attachment_image_src($image_id, 'full');
            $image_url      =   $image_url[0];
            $full_img       =   $full_img [0];     
            if ($price != 0) {
               $price = wpestate_show_price(get_the_ID(),$currency,$where_currency,1);  
           }else{
               $price='<span class="price_label price_label_before">'.$price_label_before.'</span><span class="price_label ">'.$price_label.'</span>';
               
           }
        ?>
    <h1 class="entry-title entry-prop">
      <?php the_title(); ?>
    </h1>
    <span class="price_area"><?php $money= get_post_meta( get_the_ID(), 'price_low', true );
        echo "$".number_format($money, 2);?> - <?php $money= get_post_meta( get_the_ID(), 'price_high', true );
        echo "$".number_format($money, 2);?></span>
    <div class="single-content listing-content">
      <?php            
      

        $status = esc_html( get_post_meta($post->ID, 'property_status', true) );    
        if (function_exists('icl_translate') ){
            $status     =   icl_translate('wpestate','wp_estate_property_status_'.$status, $status ) ;                                      
        }

        ?>
      <div class="notice_area">
        <!-- <div class="property_categs">
                <?php //print $property_category .' '.__('in','wpestate').' '.$property_action?>
            </div> -->
        <span class="adres_area"><?php print esc_html( get_post_meta($post->ID, 'property_address', true) ). ', ' . $property_city.', '.$property_area; ?></span> <br />
        <span class="dev_name">by <a href="<?php echo get_post_meta($post->ID, 'developer_link', true); ?>"><?php echo get_post_meta($post->ID, 'developer_name', true); ?></a></span>
        <?php /*?><div id="add_favorites" class="<?php print $favorite_class;?>" data-postid="<?php the_ID();?>"><?php echo $favorite_text;?></div><?php */?>
        <div class="download_pdf"></div>
        <?php /*?><div class="prop_social">
                <div class="no_views dashboad-tooltip" data-original-title="<?php _e('Number of Page Views','wpestate');?>"><i class="fa fa-eye-slash "></i><?php echo intval( get_post_meta($post->ID, 'wpestate_total_views', true) );?></div>
                <i class="fa fa-print" id="print_page" data-propid="<?php print $post->ID;?>"></i>
                <a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_facebook"><i class="fa fa-facebook fa-2"></i></a>
                <a href="http://twitter.com/home?status=<?php echo urlencode(get_the_title() .' '. get_permalink()); ?>" class="share_tweet" target="_blank"><i class="fa fa-twitter fa-2"></i></a>
                <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" class="share_google"><i class="fa fa-google-plus fa-2"></i></a> 
                <?php if (isset($pinterest[0])){ ?>
                   <a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo $pinterest[0];?>&amp;description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_pinterest"> <i class="fa fa-pinterest fa-2"></i> </a>      
                <?php } ?>
              
            </div><?php */?>
      </div>
      
        
      <div class="psd_header">
        <div class="n_tab">
          <ul>
            <li><a id="myButton1" href="javascript:void(0)">Summary</a></li>
            <li><a id="myButton" href="javascript:void(0)">Features</a></li>
            <li><a id="myButton3" href="javascript:void(0)">Floor Plans</a></li>
            <li><a id="myButton4" href="javascript:void(0)">Pricing</a></li>
            <li><a id="myButton5" href="javascript:void(0)">Map</a></li>
          </ul>
        </div>
        <div id="add_favorites" class="<?php print $favorite_class;?> add_favorites_new" data-postid="<?php the_ID();?>"><?php echo $favorite_text;?></div>
        <div class="prop_social_new">
          <div class="no_views dashboad-tooltip" data-original-title="<?php _e('Number of Page Views','wpestate');?>"><i class="fa fa-eye-slash "></i><?php echo intval( get_post_meta($post->ID, 'wpestate_total_views', true) );?></div>
          <i class="fa fa-print" id="print_page" data-propid="<?php print $post->ID;?>"></i> <a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_facebook"><i class="fa fa-facebook fa-2"></i></a> <a href="http://twitter.com/home?status=<?php echo urlencode(get_the_title() .' '. get_permalink()); ?>" class="share_tweet" target="_blank"><i class="fa fa-twitter fa-2"></i></a> <a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank" class="share_google"><i class="fa fa-google-plus fa-2"></i></a>
          <?php if (isset($pinterest[0])){ ?>
          <a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo $pinterest[0];?>&amp;description=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="share_pinterest"> <i class="fa fa-pinterest fa-2"></i> </a>
          <?php } ?>
        </div>
      </div>
      <?php //print 'Status:'.$status.'</br>'; ?>
      <?php //get_template_part('templates/listingslider');
        // slider type -> vertical or horizinalt
        $local_pgpr_slider_type_status=  get_post_meta($post->ID, 'local_pgpr_slider_type', true);

        if ($local_pgpr_slider_type_status=='global'){
            $prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') );
            if($prpg_slider_type_status=='vertical'){
                 get_template_part('templates/listingslider-vertical');
            }else{
                 get_template_part('templates/listingslider');
            }
        }elseif($local_pgpr_slider_type_status=='vertical') {    
                      get_template_part('templates/listingslider-vertical');
        }else{
            get_template_part('templates/listingslider');
        }
         
        ?>
       
        
      <div class="big_box" id="myDiv1">
        <ul>
          <li>UNITS
            <h1><?php echo get_post_meta($post->ID, 'number_of_units', true); ?></h1>
          </li>
          <li>FLOORS
            <h1><?php echo get_post_meta($post->ID, 'number_of_floors', true); ?></h1>
          </li>
          <li>SQUARE FEET
            <h1><?php echo get_post_meta($post->ID, 'square_footage_range', true); ?></h1>
          </li>
          <li>SQ FT AVG
            <h1><?php echo get_post_meta($post->ID, 'square_footage_average', true); ?></h1>
          </li>
        </ul>
      </div>
      <?php
        
        // content type -> tabs or accordion
  
        $local_pgpr_content_type_status     =  get_post_meta($post->ID, 'local_pgpr_content_type', true);
        if($local_pgpr_content_type_status =='global'){
            $global_prpg_content_type_status= esc_html ( get_option('wp_estate_global_prpg_content_type','') );
            if($global_prpg_content_type_status=='tabs'){
              
                get_template_part ('/templates/property_page_tab_content'); 
            }else{
               get_template_part ('/templates/property_page_acc_content_condo'); 
				 //get_template_part ('/templates/property_page_acc_content'); 
            }
        }elseif ($local_pgpr_content_type_status =='tabs') {
            get_template_part ('/templates/property_page_tab_content');
        }else{
           get_template_part ('/templates/property_page_acc_content_condo'); 
			//get_template_part ('/templates/property_page_acc_content'); 
        }
         
        ?>
        
      <?php 
        wp_reset_query();
        ?>
      <?php
        endwhile; // end of the loop
        $show_compare=1;
        
        $sidebar_agent_option_value=    get_post_meta($post->ID, 'sidebar_agent_option', true);
        $enable_global_property_page_agent_sidebar= esc_html ( get_option('wp_estate_global_property_page_agent_sidebar','') );
        if ( $sidebar_agent_option_value=='global' ){

            if($enable_global_property_page_agent_sidebar!='yes'){
                get_template_part ('/templates/agent_area');
            }
            
        }
        
        else if($sidebar_agent_option_value !='yes'){
            // get_template_part ('/templates/agent_area');
        }
        
        get_template_part ('/templates/similar_listings');
     
        ?>
    </div>
    <!-- end single content -->
  </div>
  <!-- end 9col container-->
  <?php  include(locate_template('sidebar-condo.php')); ?>
</div>
<?php 

/*$category_detail=get_the_category($post->ID );//$post->ID
echo '<pre>';
print_r($category_detail);
foreach($category_detail as $cd){
echo $cd->cat_name;
}*/
?>
<?php get_footer(); ?>
<script>

jQuery("#myButton1").click(function() { 
jQuery('html, body').animate({ 
scrollTop: jQuery("#myDiv1").offset().top-110 }, 2000); 
});

jQuery("#myButton").click(function() { 
jQuery('html, body').animate({ 
scrollTop: jQuery("#myDiv").offset().top-110 }, 2000); 
});

jQuery("#myButton3").click(function() { 
jQuery('html, body').animate({ 
scrollTop: jQuery("#myDiv3").offset().top-110 }, 2000); 
});

jQuery("#myButton4").click(function() { 
jQuery('html, body').animate({ 
scrollTop: jQuery("#myDiv4").offset().top-110 }, 2000); 
});

jQuery("#myButton5").click(function() { 
jQuery('html, body').animate({ 
scrollTop: jQuery("#myDiv5").offset().top-110 }, 2000); 
});

</script>
