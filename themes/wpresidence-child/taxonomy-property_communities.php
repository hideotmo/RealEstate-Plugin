<?php
get_header();

$options        =   wpestate_page_details('');

$filtred        =   0;
$show_compare   =   1;
$compare_submit =   get_compare_link();

// get curency , currency position and no of items per page
global $current_user;
global $custom_post_type;
global $col_class;
get_currentuserinfo();
$currency           =   esc_html( get_option('wp_estate_currency_symbol','') );
$where_currency     =   esc_html( get_option('wp_estate_where_currency_symbol','') );
$prop_no            =   intval( get_option('wp_estate_prop_no','') );
$userID             =   $current_user->ID;
$user_option        =   'favorites'.$userID;
$curent_fav         =   get_option($user_option);

$prop_unit          =   esc_html ( get_option('wp_estate_prop_unit','') );
$prop_unit_class    =   '';
$align_class        =   '';
if($prop_unit=='list'){
    $prop_unit_class="ajax12";
    $align_class=   'the_list_view';
}

$col_class=4;
if($options['content_class']=='col-md-12'){
    $col_class=3;
}

$taxonomy    = get_query_var('taxonomy');
$taxonmy    = get_query_var('taxonomy');
$term       = get_query_var( 'term' );



$custom_post_type = 'estate_property';

if( $taxonomy == 'property_category_agent' || 
    $taxonomy == 'property_action_category_agent' || 
    $taxonomy == 'property_city_agent' || 
    $taxonomy == 'property_area_agent' ||
    $taxonomy == 'property_county_state_agent'){
    
    
    $custom_post_type = 'estate_agent';
}

$tax_array  = array(
                'taxonomy'  => $taxonomy,
                'field'     => 'slug',
                'terms'     => $term
                );
 
$mapargs = array(
            'post_type'  => 'estate_property',
            'nopaging'   => true,
            'tax_query'  => array(
                                  'relation' => 'AND',
                                  $tax_array
                               )
           );



if ( get_option('wp_estate_readsys','') =='yes' ){
    $path=estate_get_pin_file_path();
    $selected_pins=file_get_contents($path);
    
}else{
    $selected_pins = wpestate_listing_pins($mapargs);//call the new pins  
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


if($custom_post_type =='estate_agent'){
     $args = array(
                  'post_type'         => 'estate_agent',
                  'post_status'       => 'publish',
                  'paged'             => $paged,
                  'posts_per_page'    => $prop_no ,
                  'tax_query'  => array(
                       'relation' => 'AND',
                       $tax_array
                    )
                );	

    $prop_selection = new WP_Query($args);
    $counter = 0;
}else{

    $args = array(
                  'post_type'         => 'estate_property',
                  'post_status'       => 'publish',
                  'paged'             => $paged,
                  'posts_per_page'    => $prop_no ,
                  'meta_key'          => 'prop_featured',
                  'orderby'           => 'meta_value',
                  'order'             => 'DESC',
                  'tax_query'  => array(
                       'relation' => 'AND',
                       $tax_array
                    )
                );	

    add_filter( 'posts_orderby', 'wpestate_my_order' );
    $prop_selection = new WP_Query($args);
    remove_filter( 'posts_orderby', 'wpestate_my_order' );
    $counter = 0;
}



$property_list_type_status =    esc_html(get_option('wp_estate_property_list_type',''));
 

if($custom_post_type =='estate_agent'){
    get_template_part('templates/normal_map_core'); 
}else{
	$taxonomy_name  = 'property_communities';
    $queried_object = get_queried_object();
    $term_id        = $queried_object->term_id;
	$termchildren   = get_term_children( $term_id, $taxonomy_name );
	$count          = count($termchildren);

	$description_one = get_field('description_one', $taxonomy . '_' . $term_id);
	$description_two = get_field('description_two', $taxonomy . '_' . $term_id);
	$title           = get_field('title', $taxonomy . '_' . $term_id);	

	if($count > 0):

	$description_one = get_field('description_one', $taxonomy . '_' . $term_id);
	$description_two = get_field('description_two', $taxonomy . '_' . $term_id);
	$title           = get_field('title', $taxonomy . '_' . $term_id);	
?>

	<?php
    global $prop_selection;
    global $options;
    global $num;
    global $args;
    global $custom_advanced_search;
    global $adv_search_what;
    global $adv_search_how;
    global $adv_search_label;
    global $prop_unit_class;
    global $show_compare_only;
    global $property_unit_slider;
    global $custom_post_type;
    global $col_class;
    $taxonomy              = get_query_var('taxonomy');
    $term                 = get_query_var( 'term' );
    $property_unit_slider = get_option('wp_estate_prop_list_slider','');
    ?>

   <style type="text/css">
     ul.search-by-parent-categorys{}
     ul.search-by-parent-categorys li{ width:50%; float:left;}
	 ul.search-by-parent-categorys li a{ color:#9ed172 !important;}
	 h1, h2, h3{color:#398fc0!important;}
	 .category_search_list{ overflow:hidden;}
	 .listing_filters_head{ display:none; }
	 @media(max-width:480px){
		ul.search-by-parent-categorys li{ width:100%; float:left;} 
	 }
   </style>

   <div class="row">
    <?php get_template_part('templates/breadcrumbs'); ?>	
    <div class=" <?php print $options['content_class'];?> ">
    
        <?php  
        if( is_page_template('advanced_search_results.php') ) {
        
            while (have_posts()) : the_post();
                if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) == 'yes') { ?>
                    <h1 class="entry-title title_prop"><?php the_title(); print " (".$num.")" ?></h1>                
                <?php } ?>
                <div class="single-content">
            
                <?php 
                the_content();
                $show_save_search            =   get_option('wp_estate_show_save_search','');
    
                if ($show_save_search=='yes' ){
                    if( is_user_logged_in() ){
                        print '<div class="search_unit_wrapper advanced_search_notice">';
                        print '<div class="search_param"><strong>'.__('Search Parameters: ','wpestate').'</strong>';
                            wpestate_show_search_params($args,$custom_advanced_search, $adv_search_what,$adv_search_how,$adv_search_label);
                        print'</div>';
                        print'</div>';


                        print '<div class="saved_search_wrapper"> <span id="save_search_notice">'.__('Save this Search?','wpestate').'</span>'; 
                        print '<input type="text" id="search_name" class="new_search_name" placeholder="'.__('Search name','wpestate').'">';
                        print '<button class="wpb_button  wpb_btn-info wpb_btn-large" id="save_search_button">'.__('Save Search','wpestate').'</button>';
                        print  "<input type='hidden' id='search_args' value=' ";
                        print json_encode($args,JSON_HEX_TAG);
                        print "'>";
                        print '<input type="hidden" name="save_search_nonce" id="save_search_nonce"  value="'. wp_create_nonce( 'save_search_nonce' ).'" />';
                        print '';
                        print '</div>';
                    }else{
                        print '<div class="vc_row wpb_row vc_row-fluid vc_row">
                                <div class="vc_col-sm-12 wpb_column vc_column_container vc_column">
                                    <div class="wpb_wrapper">
                                        <div class="wpb_alert wpb_content_element vc_alert_rounded wpb_alert-info wpestate_message vc_message">
                                            <div class="messagebox_text"><p>'.__('Login to save search and and you will receive an email notification when new properties matching your search will be published.','wpestate').'</p>
                                        </div>
                                        </div>
                                    </div> 
                                </div> 
                        </div>';

                    }

                }

            
            ?>
        
            </div>
                            
        <?php endwhile; // end of the loop.  
         
        get_template_part('templates/property_list_filters_search');
		}
		?>    
 
         <!--Filters starts here-->     
        <?php   
        if($custom_post_type!='estate_agent'){
            get_template_part('templates/property_list_filters');
        }
        ?>    
  
     <div class="single-content">
      <h1 class="entry-title title_prop"><?php echo $title;?></h1>
      
      <?php echo $description_one ;?>
      
      <hr />
     
       <h3> 
			<?php
                _e('Search ','wpestate');
                single_cat_title();
            ?>       
       </h3>
       <div class="category_search_list">
		<?php	
            echo '<ul class="search-by-parent-categorys">';
                foreach ( $termchildren as $child ) {
                    $term = get_term_by( 'id', $child, $taxonomy_name );
                    echo '<li><a href="'.get_term_link( $child, $taxonomy_name ).'">'.$term->name.'</a></li>';
                }
            echo '</ul>';
       ?>
      </div>
      <hr />
      
 
      <?php echo $description_two;?>
      
       
       
      </div>      
        
     </div>
       <?php  include(locate_template('sidebar.php')); ?>
    
   </div> 

<?php	
	else:
		if ( $property_list_type_status == 2 ){
			get_template_part('templates/half_map_core');
		}else{
            get_template_part('templates/normal_property_communities'); 
			//get_template_part('templates/normal_map_core'); 
		}
	endif;
	
}

wp_reset_query();               
/*
wp_localize_script('googlecode_regular', 'googlecode_regular_vars2', 
            array('markers2'          =>  $selected_pins,
                  'taxonomy'          =>  $taxonomy,
                  'term'              =>  $term));
 * 
 */
get_footer(); 
?>