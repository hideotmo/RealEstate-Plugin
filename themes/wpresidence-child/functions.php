<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:     
if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css' ); 
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css' );

/**
 * 
 * Dave TESTING Area 
 * 
 *
 * 
 * 
*/
function estate_listing_address($post_id){
    $property_address   = esc_html( get_post_meta($post_id, 'property_address', true) );
    $property_city      = get_the_term_list($post_id, 'property_city', '', ', ', '');
    $property_county    = get_the_term_list($post_id, 'property_county_state', '', ', ', '') ;
    $property_zip       = esc_html(get_post_meta($post_id, 'property_zip', true) );
    //$property_state     = esc_html(get_post_meta($post_id, 'property_state', true) );
    $property_country   = esc_html(get_post_meta($post_id, 'property_country', true) );
    $property_community = esc_html(get_post_meta($post_id, 'property_community', true) );
    //$property_area      = get_the_term_list($post_id, 'property_area', '', ', ', '');
    $property_area      = esc_html(get_post_meta($post_id, 'property_area', true) );
    $return_string='';
    if ($property_address != ''){
        $return_string.='<div class="listing_detail col-md-4"><strong>'.__('Address','wpestate').':</strong> ' . $property_address . '</div>'; 
    }
    if ($property_city != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('City','wpestate').':</strong> ' .$property_city. '</div>';  
    }  
    if ($property_area != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Community','wpestate').':</strong> ' .$property_area. '</div>';
    }    
    if ($property_county != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('State/County','wpestate').':</strong> ' . $property_county . '</div>'; 
    }
    if ($property_zip != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Zip','wpestate').':</strong> ' . $property_zip . '</div>';
    }  
    if ($property_country != '') {
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Country','wpestate').':</strong> ' . $property_country . '</div>'; 
    } 
    //if ($property_community != ''){
    //    $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Community','wpestate').':</strong> ' . $property_community . '</div>';
    //}
    return  $return_string;
}

function estate_listing_details($post_id){
    $currency       =   esc_html( get_option('wp_estate_currency_symbol', '') );
    $where_currency =   esc_html( get_option('wp_estate_where_currency_symbol', '') );
    $measure_sys    =   esc_html ( get_option('wp_estate_measure_sys','') ); 
    $property_size  =   floatval( get_post_meta($post_id, 'property_size', true) );
    $property_mls   =	esc_html( get_post_meta($post_id, 'property_mls', true) );
    if ($property_size  != '') {
        $property_size  = wpestate_sizes_no_format($property_size) . ' '.$measure_sys.'<sup>2</sup>';
    }
    $property_lot_size = floatval( get_post_meta($post_id, 'property_lot_size', true) );
    if ($property_lot_size != '') {
        $property_lot_size = wpestate_sizes_no_format($property_lot_size) . ' '.$measure_sys.'<sup>2</sup>';
    }
    $property_rooms     = floatval ( get_post_meta($post_id, 'property_rooms', true) );
    $property_bedrooms  = floatval ( get_post_meta($post_id, 'property_bedrooms', true) );
    $property_bathrooms = floatval ( get_post_meta($post_id, 'property_bathrooms', true) );     
    $price              = floatval   ( get_post_meta($post_id, 'property_price', true) );
    if ($price != 0) {
        $price =wpestate_show_price($post_id,$currency,$where_currency,1);           
    }else{
        $price='';
    } 
    $return_string='';
    //$return_string.='<div class="listing_detail col-md-4" id="propertyid_display"><strong>'.__('Property Id ','wpestate'). ':</strong> '.$post_id.'</div>';
    $return_string.= '<div class="listing_detail col-md-4"><strong>MLS</strong>: ' . $property_mls . '</div>';
    if ($price !='' ){ 
        $return_string.='<div class="listing_detail col-md-4"><strong>'.__('Price','wpestate'). ':</strong> '. $price.'</div>';
    }
  //  print '<div class="listing_detail col-md-4"><strong>'.__('Listing Id: ','wpestate').' LST-'.$post_id.'</div>';
    if ($property_size != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Property Size','wpestate').':</strong> ' . $property_size . '</div>';
    }               
    if ($property_lot_size != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Property Lot Size','wpestate').':</strong> ' . $property_lot_size . '</div>';
    }      
    if ($property_rooms != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Rooms','wpestate').':</strong> ' . $property_rooms . '</div>'; 
    }      
    if ($property_bedrooms != ''){
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Bedrooms','wpestate').':</strong> ' . $property_bedrooms . '</div>'; 
    }     
    if ($property_bathrooms != '')    {
        $return_string.= '<div class="listing_detail col-md-4"><strong>'.__('Bathrooms','wpestate').':</strong> ' . $property_bathrooms . '</div>'; 
    }      
    // Custom Fields 
    $i=0;
    $custom_fields = get_option( 'wp_estate_custom_fields', true); 
    if( !empty($custom_fields)){  
        while($i< count($custom_fields) ){
           $name =   $custom_fields[$i][0];
           $label=   $custom_fields[$i][1];
           $type =   $custom_fields[$i][2];
       //    $slug =   sanitize_key ( str_replace(' ','_',$name) );
           $slug         =   wpestate_limit45(sanitize_title( $name ));
           $slug         =   sanitize_key($slug);
           $value=esc_html(get_post_meta($post_id, $slug, true));
           if (function_exists('icl_translate') ){
                $label     =   icl_translate('wpestate','wp_estate_property_custom_'.$label, $label ) ;
                $value     =   icl_translate('wpestate','wp_estate_property_custom_'.$value, $value ) ;                                      
           }
           if(($value!='') && ($name !== 'property_mls')){
               $return_string.= '<div class="listing_detail col-md-4"><strong>'.ucwords($label).':</strong> ' .$value. '</div>'; 
           }
           $i++;       
        }
    }
     //END Custom Fields 
    return $return_string;
}

function estate_listing_features($post_id){
    $return_string='';    
    $counter            =   0;                          
    $feature_list_array =   array();
    $feature_list       =   esc_html( get_option('wp_estate_feature_list') );
    $feature_list_array =   explode( ',',$feature_list);
    $total_features     =   round( count( $feature_list_array )/2 );
     $show_no_features= esc_html ( get_option('wp_estate_show_no_features','') );
        if($show_no_features!='no'){
            foreach($feature_list_array as $checker => $value){
                    $counter++;
                    $post_var_name  =   str_replace(' ','_', trim($value) );
                    //$post_var_name  =  str_replace(' ','-', trim($post_var_name) );
                    $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                    $input_name     =   sanitize_key($input_name);
                    if (function_exists('icl_translate') ){
                        $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
                    }
                    if (esc_html( get_post_meta($post_id, $input_name, true) ) == 1) {
                         $return_string .= '<div class="listing_detail col-md-4"><i class="fa fa-check"></i>' . trim($value) . '</div>';
                    }else{
                        $return_string  .=  '<div class="listing_detail col-md-4"><i class="fa fa-times"></i>' . trim($value). '</div>';
                    }
              }
        }else{
            foreach($feature_list_array as $checker => $value){
                $post_var_name  =  str_replace(' ','_', trim($value) );
                //$post_var_name  =  str_replace(' ','-', trim($post_var_name) );
                $input_name     =   wpestate_limit45(sanitize_title( $post_var_name ));
                $input_name     =   sanitize_key($input_name);
                if (function_exists('icl_translate') ){
                    $value     =   icl_translate('wpestate','wp_estate_property_custom_amm_'.$value, $value ) ;                                      
                }
                if ($input_name!='' && esc_html( get_post_meta($post_id, $input_name, true) ) == 1) {
                    $svalue  =  str_replace('-',' ', trim($value) );
                    $svalue = ucwords($svalue);
                    $return_string .=  '<div class="listing_detail col-md-4"><i class="fa fa-check"></i>' . trim($svalue) . '</div>';
                }
            }
       }
    return $return_string;
}


///////////////////////////////////////////////////////////////////////////////////////////
//End Dave Section 00
//
///////////////////////////////////////////////////////////////////////////////////////////





function condo_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Condo Sldebar', 'wpestate' ),
		'id'            => 'sidebar-condo',
		'description'   => __( 'Add widgets here to appear in your Condo sidebar.', 'wpestate' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widget-title-sidebar">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'condo_widgets_init' );

include_once('aq_resize.php');
function get_the_popular_excerpt($limit){
	$excerpt = get_the_content();
	$excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
	$excerpt = strip_shortcodes($excerpt);
	$excerpt = strip_tags($excerpt);
	$excerpt = substr($excerpt, 0, $limit);
	$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
	$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
	$excerpt = $excerpt;
	return $excerpt;
}
function footer_latest_news(){
	global $wpdb;
	$args     = array( 'post_type'=>array('post'), 'orderby' => 'menu_order', 'order' => 'DESC', 'posts_per_page'=>3);
	$my_query = null;
	$my_query = new WP_Query($args);
	$output = '';
	if( $my_query->have_posts() ) : 
	    //
		$output .= '<div class="latest_listings">';
	    while ($my_query->have_posts()) : $my_query->the_post();
		  //
		  $post_id = $post->ID;
		  if (has_post_thumbnail( $post_id) ):
			 $image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id ), 'single-post-thumbnail' );
		  endif;		
		
		  $img = aq_resize($image[0], 105, 70, true, true);
	      $output .= '<div data-link="'.get_permalink().'" class="widget_latest_internal">
		              <div class="widget_latest_listing_image">
                       <a href="'.get_permalink().'">
					    <img width="105" height="70" class="lazyload img_responsive" data-original="'.$img.'" alt="slider-thumb" src="'.$img.'">
					   </a>
					  <div class="listing-cover"></div>
					   <a href="'.get_permalink().'"> 
					    <span class="listing-cover-plus">+</span>
					   </a>
					  </div>
					  <div class="listing_name">
					  <span class="widget_latest_price ">
					   <a href="'.get_permalink().'" style="color:#3c8fbe">'.get_the_title().'</a>
					  </span>
					  <span class="widget_latest_title">
					      '.get_the_popular_excerpt(30).'
					  </span>
					 </div>
					</div>';
	    endwhile; 
		$output .= '</div>';
	    wp_reset_query();
		//
    endif;
	return $output;
}
add_shortcode('latest_news', 'footer_latest_news');
// [lawyer_slider limit=4 post_type="" order="DESC" height="500"]
function wp_prorerty_featured(){	
	$property = new WP_Query(
	            array( 
				     'post_type'=>array('estate_property'), 
					 'meta_query' => array( array( 'key' => 'prop_featured', 'value' => '1' ) ),
					 'orderby' => 'menu_order', 
					 'order' => 'DESC', 
					 'posts_per_page'=>-1 
					 ) 
				);
	$output  = '';
	if( $property->have_posts() ) :
	    $output  .= '<div class="article_container slider_container bottom-estate_property nobutton"><div class="slider_control_left"><i class="fa fa-angle-left"></i></div><div class="slider_control_right"><i class="fa fa-angle-right"></i></div><h2 class="shortcode_title title_slider">Featured</h2><div class="shortcode_slider_wrapper wp_prorerty_featured" data-auto="0"><ul class="shortcode_slider_list">';
	    while ($property->have_posts()) : $property->the_post();
		$post_id = $post->ID;
		$image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id ), 'single-post-thumbnail' );
		$img = aq_resize($image[0], 525, 328, true, true);
		//$img = aq_resize($image[0], 105, 70, true, true);
		$output  .= '<li>  
			   <div data-listid="'.$post_id.'" data-org="4" class="col-md- shortcode-col listing_wrapper"> 
				 <div data-link="'.get_permalink().'" class="property_listing">
					   <div class="listing-unit-img-wrapper">
						   <a href="'.get_permalink().'">
							 <img width="525" height="328" data-original="'.$img.'" alt="file000332309677" class="lazyload img-responsive wp-post-image" src="'.$img.'">
						   </a>
						   <div class="listing-cover"></div>
						   
						   <div class="cercle-box">
						     <div class="box">
							    <span>FEATURED '.get_field('type_of_featured').'</span>
								<h5>'.get_the_title().'</h5>
							 </div>
						   </div>
						</div>
							  
						<div class="listing_details the_grid_view">'.get_the_popular_excerpt(100).'</div>
			
						<div class="listing_unit_price_wrapper">                           
						  <a href="'.get_permalink().'">LEARN MORE</a>
						</div>           
					</div>          
				  </div>
			</li>';		
		
		endwhile;
		$output  .= '</ul> </div></div>';
	endif;
	return $output;
}
add_shortcode('prorerty_featured', 'wp_prorerty_featured');
//
function wp_testimonial_views_property($atts){
	$myatts = shortcode_atts( array(
			     'order'     => '',
		         'limit'     => '', 
				 'orderby'   => '',    
		      ), $atts );
	
	$order       = $myatts['order'];
	$show        = $myatts['limit'];
	$orderby     = $myatts['orderby'];
	if($show!=''){$limit = $show;}else{$limit = 2;}	
	if($order!=''){$od = $order;}else{ $od = 'ASC';}	
	
	global $wpdb;
	$args     = array( 'post_type'=>array('testimonial'), 'orderby' => $orderby, 'order' => $od, 'posts_per_page'=>$limit);
	$my_query = null;
	$my_query = new WP_Query($args);
	$output = '';
	if( $my_query->have_posts() ) :
	
	  $output .= '<div class="vc_row wpb_row vc_inner vc_row-fluid vc_row_inner">';
	  
      while ($my_query->have_posts()) : $my_query->the_post();
		  $post_id  = $post->ID;
		  $image    = wp_get_attachment_image_src( get_post_thumbnail_id($post_id ), 'single-post-thumbnail' );
		  $img      = aq_resize($image[0], 120, 120, true, true);
		  if($img!=''):
		     $images = $img;
		  else: 
		     $images = '/wp-content/themes/wpresidence-child/images/no-user-image.gif';
		  endif;
		  $client   = get_post_meta( get_the_ID(), '_ikcf_client', true );
		  $position = get_post_meta( get_the_ID(), '_ikcf_position', true );
		  
		  $output .= '<div class="wpb_column vc_column_container vc_col-sm-6 vc_column_inner"  style="padding-left:15px; padding-right:15px;">
		   <div class="wpb_wrapper"> 
			<div class="testimonial-container">     
			 <div style="background-image:url('.$images.')" class="testimonial-image"></div>     
			 <div class="testimonial-text">'.get_the_content().'</div>     
			 <div class="testimonial-author-line">
			  <span class="testimonial-author">'.$client.', '.$position.'</span>  
			 </div> 
			</div>
		   </div>
		  </div>';	 
		   
	  endwhile;
	  
	  $output .='</div>'; 
	endif;
	return $output;	
}
add_shortcode('testimonial_custom', 'wp_testimonial_views_property');
function is_blog() {
    return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type();
}
//hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_topics_hierarchical_taxonomy', 0 );
//create a custom taxonomy name it topics for your posts
function create_topics_hierarchical_taxonomy() {
  $labels = array(
    'name' => _x( 'All Community', 'taxonomy general name' ),
    'singular_name' => _x( 'Community', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Community' ),
    'all_items' => __( 'All Community' ),
    'parent_item' => __( 'Parent Community' ),
    'parent_item_colon' => __( 'Parent Community:' ),
    'edit_item' => __( 'Edit Community' ), 
    'update_item' => __( 'Update Community' ),
    'add_new_item' => __( 'Add New Community' ),
    'new_item_name' => __( 'New Community Name' ),
    'menu_name' => __( 'All Community' ),
  ); 	
  register_taxonomy(
    'property_communities',
	array('estate_property'), array(
	    'has_archive' => true,
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'community','with_front' => true ),
    )
  );
   flush_rewrite_rules();
}
function social_link($atts){
	$myatts = shortcode_atts( array(
			     'field' => '', 
		      ), $atts );	
    $order    = $myatts['field'];
	return get_post_meta( get_the_ID(), $field, true );
}
add_shortcode('slink', 'social_link');
// [lawyer_slider limit=4 post_type="" order="DESC" height="500"] [testimonial_custom orderby="rand"]
// END ENQUEUE PARENT ACTION 


// BEGIN STUFF FROM DAVE
function rk_distgen() {
  $output = "<h3>Generating Communities</h3> <br /> <hr>";
  
$neighborhoods = array(
'Abbeydale' => 'W',
'Acadia' => 'W',
'Albert Park/Radisson Heights' => 'W',
'Altadore' => 'W',
'Alyth/Bonnybrook' => 'W',
'Applewood Park' => 'W',
'Arbour Lake' => 'W',
'Aspen Woods' => 'W',
'Auburn Bay' => 'W',
'Aurora Business Park' => 'W',
'Banff Trail' => 'W',
'Bankview' => 'W',
'Bayview' => 'W',
'Beddington Heights' => 'W',
'Bel-Aire' => 'W',
'Beltline' => 'W',
'Bonavista Downs' => 'W',
'Bowness' => 'W',
'Braeside' => 'W',
'Brentwood' => 'W',
'Bridgeland/Riverside' => 'W',
'Bridlewood' => 'W',
'Britannia' => 'W',
'Burns Industrial' => 'W',
'Calgary International Airport' => 'W',
'Cambrian Heights' => 'W',
'Canyon Meadows' => 'SE',
'Capitol Hill' => 'SE',
'Carrington' => 'SE',
'Castleridge' => 'SE',
'Cedarbrae' => 'SE',
'CFB Currie' => 'SE',
'Chaparral' => 'SE',
'Charleswood' => 'SE',
'Chinatown' => 'SE',
'Chinook Park' => 'SE',
'Christie Park' => 'SE',
'Citadel' => 'SE',
'Cityscape' => 'SE',
'Cliff Bungalow' => 'SE',
'Coach Hill' => 'S',
'Collingwood' => 'S',
'Copperfield' => 'S',
'Coral Springs' => 'S',
'Cougar Ridge' => 'S',
'Country Hills' => 'S',
'Country Hills Village' => 'S',
'Coventry Hills' => 'S',
'Cranston' => 'S',
'Crescent Heights' => 'S',
'Crestmont' => 'S',
'Dalhousie' => 'S',
'Deer Ridge' => 'S',
'Deer Run' => 'S',
'Deerfoot Business Centre' => 'S',
'Diamond Cove' => 'S',
'Discovery Ridge' => 'S',
'Douglasdale/Glen' => 'S',
'Dover' => 'S',
'Downtown Commercial Core' => 'S',
'Downtown East Village' => 'S',
'Downtown West End' => 'S',
'Eagle Ridge' => 'S',
'East Fairview Industrial' => 'S',
'East Shepard Industrial' => 'S',
'Eastfield' => 'S',
'Eau Claire' => 'S',
'Edgemont' => 'S',
'Elbow Park' => 'S',
'Elboya' => 'S',
'Erin Woods' => 'S',
'Erlton' => 'S',
'Evanston' => 'S',
'Evergreen' => 'S',
'Fairview' => 'S',
'Fairview Industrial' => 'S',
'Falconridge' => 'S',
'Foothills' => 'S',
'Forest Heights' => 'S',
'Forest Lawn' => 'S',
'Forest Lawn Industrial' => 'S',
'Franklin' => 'NW',
'Garrison Green' => 'NW',
'Garrison Woods' => 'NW',
'Glamorgan' => 'NW',
'Glenbrook' => 'NW',
'Glendale' => 'NW',
'Glendeer Business Park' => 'NW',
'Golden Triangle' => 'NW',
'Great Plains' => 'NW',
'Greenview' => 'NW',
'Greenwood/Greenbriar' => 'NW',
'Greeview Industrial Park' => 'NW',
'Hamptons' => 'NW',
'Harvest Hills' => 'NW',
'Hawkwood' => 'NW',
'Haysboro' => 'NW',
'Hidden Valley' => 'NW',
'Highfield' => 'NW',
'Highland Park' => 'NW',
'Highwood' => 'NW',
'Hillhurst' => 'NW',
'Horizon' => 'NW',
'Hounsfield Heights/Briar Hill' => 'NW',
'Huntington Hills' => 'NE',
'Inglewood' => 'NE',
'Kelvin Grove' => 'NE',
'Killarney/Glengarry' => 'NE',
'Kincora' => 'NE',
'Kingsland' => 'NE',
'Lake Bonavista' => 'NE',
'Lakeview' => 'NE',
'Legacy' => 'NE',
'Lincoln Park' => 'NE',
'Livingston' => 'NE',
'Lower Mount Royal' => 'NE',
'Macewan Glen' => 'NE',
'Mahogany' => 'NE',
'Manchester' => 'NE',
'Manchester Industrial' => 'NE',
'Maple Ridge' => 'NE',
'Marlborough' => 'NE',
'Marlborough Park' => 'NE',
'Martindale' => 'NE',
'Mayfair' => 'NE',
'Mayland' => 'NE',
'Mayland Hieghts' => 'NE',
'McCall' => 'NE',
'McKenzie Lake' => 'NE',
'McKenzie Towne' => 'NE',
'Meadowland Park' => 'NE',
'Meridian' => 'NE',
'Midnapore' => 'NE',
'Millrise' => 'NE',
'Mission' => 'NE',
'Monterey Park' => 'NE',
'Montgomery' => 'NE',
'Mount Pleasant' => 'NE',
'New Brighton' => 'NE',
'Nolan Hill' => 'NE',
'None' => 'NE',
'North Airways' => 'N',
'North Glenmore Park' => 'N',
'North Haven' => 'N',
'North Haven Upper' => 'N',
'Oakridge' => 'N',
'Ogden' => 'N',
'Ogden Shops' => 'N',
'Palliser' => 'N',
'Panorama Hills' => 'N',
'Parkdale' => 'N',
'Parkhill' => 'N',
'Parkland' => 'N',
'Patterson' => 'N',
'Pegasus' => 'N',
'Penbrooke Meadows' => 'N',
'Pineridge' => 'N',
'Point McKay' => 'N',
'Pump Hill' => 'N',
'Queens Park Village' => 'N',
'Queensland' => 'N',
'Ramsay' => 'N',
'Ranchlands' => 'N',
'Red Carpet' => 'N',
'Redstone' => 'E',
'Renfrew' => 'E',
'Richmond' => 'E',
'Rideau Park' => 'E',
'Riverbend' => 'E',
'Rocky Ridge' => 'E',
'Rosedale' => 'E',
'Rosemont' => 'E',
'Rosscarrock' => 'E',
'Roxboro' => 'E',
'Royal Oak' => 'E',
'Royal Vista' => 'E',
'Rundle' => 'E',
'Rutland Park' => 'E',
'Saddle Ridge' => 'E',
'Saddle Ridge Industrial' => 'E',
'Sage Hill' => 'E',
'Sandstone Valley' => 'CC',
'Scarboro' => 'CC',
'Scarboro/Sunalta West' => 'CC',
'Scenic Acres' => 'CC',
'Seton' => 'CC',
'Shaganappi' => 'CC',
'Shawnee Slopes' => 'CC',
'Shawnessy' => 'CC',
'Shepard Industrial' => 'CC',
'Sherwood' => 'CC',
'Signal Hill' => 'CC',
'Silver Springs' => 'CC',
'Silverado' => 'CC',
'Skyline East' => 'CC',
'Skyline West' => 'CC',
'Skyview Ranch' => 'CC',
'Somerset' => 'CC',
'South Airways' => 'CC',
'South Calgary' => 'CC',
'South Foothills' => 'CC',
'Southview' => 'CC',
'Southwood' => 'CC',
'Springbank Hill' => 'CC',
'Spruce Cliff' => 'CC',
'St. Andrews Heights' => 'CC',
'Starfield' => 'CC',
'Stonegate Landing' => 'CC',
'Stoney 1' => 'CC',
'Stoney 2' => 'CC',
'Stoney 3' => 'CC',
'Stoney 4' => 'CC',
'Strathcona Park' => 'CC',
'Sunalta' => 'CC',
'Sundance' => 'CC',
'Sunnyside' => 'CC',
'Sunridge' => 'CC',
'Taradale' => 'CC',
'Temple' => 'CC',
'Thorncliffe' => 'CC',
'Tuscany' => 'CC',
'Tuxedo Park' => 'CC',
'University District' => 'CC',
'University Heights' => 'CC',
'University of Calgary' => 'CC',
'Upper Mount Royal' => 'CC',
'Valley Ridge' => 'CC',
'Valleyfield' => 'CC',
'Varsity' => 'CC',
'Vista Heights' => 'CC',
'Walden' => 'CC',
'West Hillhurst' => 'CC',
'West Springs' => 'CC',
'Westgate' => 'CC',
'Westwinds' => 'CC',
'Whitehorn' => 'CC',
'Wildwood' => 'CC',
'Willow Park' => 'CC',
'Windsor Park' => 'CC',
'Winston Heights/Mountainview' => 'CC',
'Woodbine' => 'CC',
'Woodlands' => 'No District',
);  
  
foreach($neighborhoods as $x=>$x_value)
{

  $term = term_exists($x_value, 'property_communities');
  if ($term !== 0 && $term !== null) {
    $output .= "the ".$x_value." term exists!<br />";
  } else {
    $output .= "the term did not exist, creating ".$x_value."<br />";
    $y_value = sanitize_title($x_value);
    wp_insert_term(
        $x_value,
        'property_communities',
        array(
            'description' => 'region',
            'slug' => $y_value,
            )
        );
  }
  //now we know for sure the parent exists!
  $parent_term = term_exists( $x_value, 'property_communities' ); // array is returned if taxonomy is given
  $parent_term_id = $parent_term['term_id']; // get numeric term id
  $output .= "Inserting: ".$x." <br />";
  $y = sanitize_title($x);
  wp_insert_term(
    $x, // the term
    'property_communities', // the taxonomy
    array(
      'description'=> 'the community',
      'slug' => $y,
      'parent'=> $parent_term_id
    )
  );
}
return $output;
}
add_shortcode( 'addthemnow', 'rk_distgen' );
function custom_texonomy_show($texo, $dfv, $typ, $id, $open ){
         $categories = get_categories('taxonomy='.$texo.'&post_type=estate_property');
		  
		 $output = '<div class="custom-form-new"><div class="dropdown form-control "><div data-value="all" xx="" class=" sidebar_filter_menu" id="sidebar-adv-'.$id.'" data-toggle="dropdown">'.$open.'<span class="caret  caret_sidebar "></span></div> <input type="hidden" value="all" name="'.$id.'"><ul id="sidebar-adv-search-'.$id.'" class="dropdown-menu filter_menu" aria-labelledby="sidebar-'.$id.'" role="menu">';
		 if($typ==1):
		   $output .='<li data-value2="all" data-value="all" role="presentation">All '.$dfv.'</li>';
		 else:
		   $output .='<li data-value="all" data-value="all">'.$dfv.'</li>';
		 endif;
         foreach ($categories as $category) :
		   if($typ==1):
             $output .='<li data-value2="'.$category->slug.'" data-value="'.$category->slug.'" role="presentation">'.$category->name.'( '.$category->count.' )</li>';
		   else:
		     $output .='<li data-value="'.$category->cat_ID.'" data-value="'.$category->cat_ID.'">'.$category->name.'( '.$category->count.' )</li>';
		   endif;
         endforeach;
		 $output .='</ul></div></div>';	
	return $output;
}
// Creating the widget 
class Property_New_Search extends WP_Widget {

function __construct() {
	parent::__construct(
	'property_new_search', 
	__('Wp Estate: New Advanced Searcht', 'property_new_search_domain'), 
	array( 'description' => __( 'New Advanced Search Widget', 'property_new_search_domain' ), ) 
	);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
		extract($args);
                $display='';
                $select_tax_action_terms='';
                $select_tax_category_terms='';
                
		$title = apply_filters('widget_title', $instance['title']);

		print $before_widget.'<div class="advanced_search_sidebar">';

		if($title) {
                    print $before_title.$title.$after_title;
		}else{
                    print '<div class="widget-title-sidebar_blank"></div>';
                }
                
                $adv_submit=get_adv_search_link();
                //show cities or areas that are empty ?
                $args = wpestate_get_select_arguments();
                $action_select_list =   wpestate_get_action_select_list($args);
                $categ_select_list  =   wpestate_get_category_select_list($args);
                $select_city_list   =   wpestate_get_city_select_list($args); 
                $select_area_list   =   wpestate_get_area_select_list($args);
                $select_county_state_list   =   wpestate_get_county_state_select_list($args);
				
				//property_area
				//$district      = view_data_condo('district', 1, '', 'district', 'Subdivision', 1);
				$development   = view_data_condo('development_team', 1, '', 'development_team', 'Development', 1);
				$developer     = view_data_condo('developer_name', 1, '', 'developer_name', 'Developer', 1);
				$property_mls  = '<div class="custom-form-new"><input type="text" value="" name="property_mls" class="form-control" placeholder="Property MLS"></div>';
				
				//view_data_condo('property-mls', 1, '', 'property_mls', 'Property MLS', 1);
				//property_bedrooms
				$property_bedrooms  = beds_and_baths(10,'bedrooms','Bedrooms');
				//property_bathrooms 
				$property_bathrooms  = beds_and_baths(10,'bathrooms','Bathrooms');
				$district = custom_texonomy_show('property_county_state','district','1','district','Subdivision');
				$neighborhoot  = custom_texonomy_show('property_area',' ','1','property_type','Neighborhood name');
				$propertytypes = custom_texonomy_show('propertytypes','Property','1','propertytypes','Property Type');
				//
                //property_action_category
				$salesstatus = custom_texonomy_show('property_action_category','Sales Status','1','sales_status','Sales Status');
                // property_category
				$propertystyle = custom_texonomy_show('property_action_category','Property Style','1','property_style','Property Style');
				$property_category = custom_texonomy_show('property_category','Property Sub Type','1','property_sub_types','Property Sub Type');
				
				//property-mls
    
                $adv_search_what        =   get_option('wp_estate_adv_search_what','');
                $adv_search_label       =   get_option('wp_estate_adv_search_label','');
                $adv_search_how         =   get_option('wp_estate_adv_search_how','');
                
                $custom_advanced_search =   get_option('wp_estate_custom_advanced_search','');
                print '<form role="search" method="get"   action="'.$adv_submit.'" >';
				            //City
				            print '<div class="custom-form-new"><div class="dropdown form-control "><div data-value="all" xx="" class=" sidebar_filter_menu  " id="sidebar-adv-city" data-toggle="dropdown">City<span class="caret  caret_sidebar "></span></div> <input type="hidden" value="all" name="advancedcity"><ul id="sidebar-adv-search-city" class="dropdown-menu filter_menu" aria-labelledby="sidebar-county-state" role="menu">'.$select_city_list.'</ul></div></div>';
							

							print $district;
							print $propertytypes;
							print $property_category;
							print $propertystyle;
							
							print '<div class="adv_search_slider"> 
									<p><label for="amount">Price range:&nbsp;</label><span style="border:0; color:#f6931f; font-weight:bold;" id="amount4">$ 100,000 to $ 2,000,000</span></p>
										<div id="slider_price_widget_custom"></div>
										<input type="hidden" value="100000" name="price_low" id="price_low_widget4">
										<input type="hidden" value="2000000" name="price_max" id="price_max_widget4">
								   </div>';							
							
							print '<div class="custom-row-dubble"><label>Property Size:</label><div class="custom_field">';
							print view_data_condo('property_size', 1, '', 'min_size_property', 'Min','',1);
							print view_data_condo('property_size', 1, '', 'max_size_property', 'Max','',1);
							print '</div></div>';								

							print $property_bedrooms; // Beds
							print $property_bathrooms;

							print '<div class="custom-row-dubble"><label>Built Year:</label><div class="custom_field">';
							print view_data_condo('property-year', 1, '', 'min_property_year', 'Min','',1);
							print view_data_condo('property-year', 1, '', 'max_property_year', 'Max','',1);
							print '</div></div>';

							print '<div class="custom-form-new"><input type="hidden" name="searchbox" value="newsearch" /><input type="text" value="" name="address" class="form-control" placeholder="Address"></div>';

							//print $development;
							//print $developer;
															   
/*							print '<div class="custom-row-dubble"><label>Est. Condo Fee Per Sq. Ft.:</label><div class="custom_field">';
							print view_data_condo('estimated_condo_fee', 1, '', 'min-condo-fee', 'Min','',1);
							print view_data_condo('estimated_condo_fee', 1, '', 'max-condo-fee', 'Max','',1);
							print '</div></div>';*/	
							
							print $property_mls;	
							
							//print_r(get_option('wp_estate_adv_search_what','dfsdfs'));						   
								   

                            if($custom_advanced_search=='yes'){
                                //$this->new_custom_fields_widget($adv_search_what,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$adv_search_how,$adv_search_label,$select_county_state_list);
                                
                                
                            }else{ // not custom search
                                //$this->new_normal_fields_widget($action_select_list,$categ_select_list,$select_city_list,$select_area_list);
                  
                            }
                $extended_search = get_option('wp_estate_show_adv_search_extended','');
                if($extended_search=='yes'){            
                    show_extended_search('widget');
                }
                
                if (function_exists('icl_translate') ){
                    print do_action( 'wpml_add_language_form_field' );
                }
                
                print'<button class="wpb_button  wpb_btn-info wpb_btn-large" id="advanced_submit_widget">'.__('Search','wpestate').'</button>
                </form>  
                '; 
		print '</div>'.$after_widget;
}
		
// Widget Backend 
public function form( $instance ) {
		$defaults = array('title' => 'Advanced Search' );
		$instance = wp_parse_args((array) $instance, $defaults);
		$display='
                <p>
                    <label for="'.$this->get_field_id('title').'">Title:</label>
		</p><p>
                    <input id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.$instance['title'].'" />
		</p>';
		print $display;
}

public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		
		return $instance;
}


	function new_custom_fields_widget($adv_search_what,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$adv_search_how,$adv_search_label,$select_county_state_list){
		foreach($adv_search_what as $key=>$search_field){
			wpestate_show_search_field('sidebar',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list);
		} 
	}//end custom fields function
	 
	function new_normal_fields_widget($action_select_list,$categ_select_list,$select_city_list,$select_area_list){
		$form = wpestate_show_search_field_classic_form('sidebar',$action_select_list,$categ_select_list ,$select_city_list,$select_area_list);
		print $form;
	}

} 

function wpb_load_widget() {
	register_widget( 'Property_New_Search' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
function get_meta_values( $key = '', $type = '', $status = 'publish' ) {
    global $wpdb;
    if( empty( $key ) )
        return;
    $r = $wpdb->get_results( $wpdb->prepare( "
        SELECT p.ID, pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        WHERE pm.meta_key = '%s'
        AND p.post_status = '%s'
        AND p.post_type = '%s'
    ", $key, $status, $type ));

    foreach ( $r as $my_r )
        $metas[$my_r->ID] = $my_r->meta_value;

    return $metas;
}
function beds_and_baths($mum='', $id='', $label=''){
    $otp ='';
	$otp .='<div class="custom-form-new full-content"><div class="dropdown form-control "><div data-value="all" xx="" class=" sidebar_filter_menu  " id="sidebar-adv-'.$id.'" data-toggle="dropdown">'.$label.'<span class="caret  caret_sidebar "></span></div> <input type="hidden" value="" name="'.$id.'"><ul id="sidebar-adv-search-'.$id.'" class="dropdown-menu filter_menu" aria-labelledby="sidebar-'.$id.'" role="menu">';	
	for ($x = 1; $x <= $mum; $x++) {
		$otp .='<li data-value="'.$x.'" data-value="'.$x.'">'.$x.'</li>';
	} 
	$otp .='</ul></div></div>';	
	return $otp;
}
function view_data_condo($kye='', $typ='', $l='', $id='', $df='', $w='', $r=''){
	//price_per_square_feet_range
	$val = get_meta_values($kye,'estate_property');
	$v   = array_unique($val);
	//$price = array();
	$i = 0;
    foreach($v as $kay){
	   if($i==0): $x = ''; else: $x=', '; endif;
		$price[]= $kay;
		$i++;
	}
	$numbers = $price;
	sort($numbers);
	$arrlength=count($numbers);
	
	$otp =''; if($w=='1'){$wid = 'full-content';}else{$wid = 'half-content';}
	
	if($w==2){$label = '<label>'.$l.'</label>';}else{$label = '';}
	
	$otp .='<div class="custom-form-new '.$wid.'">'.$label.'<div class="dropdown form-control "><div data-value="all" xx="" class=" sidebar_filter_menu  " id="sidebar-adv-'.$id.'" data-toggle="dropdown">'.$df.'<span class="caret  caret_sidebar "></span></div> <input type="hidden" value="" name="'.$id.'"><ul id="sidebar-adv-search-'.$id.'" class="dropdown-menu filter_menu" aria-labelledby="sidebar-'.$id.'" role="menu">';
	 if($typ==1):
	   $otp .='<li data-value2="all" data-value="all" role="presentation">'.$df.'</li>';
	 else:
	   $otp .='<li data-value="all" data-value="all">'.$df.'</li>';
	 endif;	
	for($x=0;$x<$arrlength;$x++){
	   if($typ==1):
	     if($numbers[$x]!='' || $numbers[$x] !=0):
		  if($r ==1){$numvl = round($numbers[$x]);}else{$numvl = $numbers[$x];}
		  $otp .='<li data-value2="'.$numvl.'" data-value="'.$numvl.'" role="presentation">'.$numvl.'</li>';
		 endif;
	   else:
	     if($numbers[$x]!='' || $numbers[$x] !=0):
		  if($r ==1){$numvl = round($numbers[$x]);}else{$numvl = $numbers[$x];}
		  $otp .='<li data-value="'.$numbers[$x].'" data-value="'.$numbers[$x].'">'.$numvl.'</li>';
		 endif; 
	   endif;
	}
	$otp .='</ul></div></div>';
	return $otp;
}



function slugify ($text) {

    $replace = array(
        '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
        '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä'=> 'Ae',
        '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
        'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
        'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
        'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
        'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
        'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
        'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
        'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
        'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
        'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
        'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
        'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
        'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
        '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
        'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
        'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
        'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
        'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
        'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
        'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
        'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
        'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
        'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
        'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
        'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
        'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
        '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
        'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
        'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
        'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
        'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
        'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
        'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
        'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
        'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
        'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
        'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
        'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
        'ю' => 'yu', 'я' => 'ya'
    );
    // make a human readable string
    $text = strtr($text, $replace);
    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d.]+~u', '-', $text);
    // trim
    $text = trim($text, '-');
    // remove unwanted characters
    $text = preg_replace('~[^-\w.]+~', '', $text);
    $text = strtolower($text);
    return $text;
}