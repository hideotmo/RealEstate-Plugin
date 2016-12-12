<?php

global $prop_selection ;
global $post;
global $is_col_md_12;
global $num;
global $args;
global $custom_advanced_search;
global $adv_search_what;
global $adv_search_how;
global $adv_search_label;
global $prop_unit_class;
global $property_unit_slider;


$args2 = wpestate_get_select_arguments();
$action_select_list         =   wpestate_get_action_select_list($args2);
$categ_select_list          =   wpestate_get_category_select_list($args2);
$select_city_list           =   wpestate_get_city_select_list($args2); 
$select_area_list           =   wpestate_get_area_select_list($args2);
$select_county_state_list   =   wpestate_get_county_state_select_list($args2);
$property_unit_slider       =   get_option('wp_estate_prop_list_slider','');
 
$top_bar_style              =   "";    
if(esc_html ( get_option('wp_estate_show_top_bar_user_menu','') )=="no"){
    $top_bar_style              =   ' half_no_top_bar ';          
}
$logo_header_type    =   get_option('wp_estate_logo_header_type','');
get_template_part('templates/property_ajax_tax_hidden_filters'); 
?>

<div class="row">
    <div  id="google_map_prop_list_wrapper" class="google_map_prop_list <?php echo esc_html( $top_bar_style.' half_'.$logo_header_type); ?>"  >
        <?php get_template_part('templates/google_maps_base'); ?>
    </div>    
    
    
    <div id="google_map_prop_list_sidebar" class="<?php echo esc_html( $top_bar_style.' half_'.$logo_header_type) ;?>">
        <?php 
        

        
        $show_adv_search_general    =   get_option('wp_estate_show_adv_search_general','');
        if($show_adv_search_general ==  'yes' ){
            $show_mobile=1;
            print '<div class="search_wrapper" id="xsearch_wrapper" >  ';
                include(locate_template('templates/advanced_search_type_half.php'));
            print '</div>';
        }
       
        $show_compare_only  =   'yes';
        
        if( is_page_template('advanced_search_results.php') ) {
            
            while (have_posts()) : the_post();
                if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) == 'yes') { ?>
                    <h1 class="entry-title title_prop"><?php the_title(); print " (".$num.")" ?></h1>   
                    
                <?php } ?>
                <div class="single-content">
            
                <?php 
                $show_save_search            =   get_option('wp_estate_show_save_search','');
   
                if ($show_save_search=='yes' ){
                    if( is_user_logged_in() ){
                        print '<div class="search_unit_wrapper advanced_search_notice">';
                        print '<div class="search_param"><strong>'.__('Search Parameters: ','wpestate').'</strong>';
						
						   if($_GET['searchbox']=='newsearch'): //New Add
								if($_GET['advancedcity']!=''): 
								  print '<strong>Contries/State: </strong>'.$_GET['advancedcity'];
								endif;
																
								if($_GET['propertytypes']!=''): 
								  print ', <strong>Property Types: </strong>'.$_GET['propertytypes'];
								endif;
									
								if($_GET['property_sub_types']!=''): 
								  print ', <strong>Property Sub Types: </strong>'.$_GET['property_sub_types'];
								endif;							
	
								if($_GET['property_style']!=''): 
								  print ', <strong>Property Style: </strong>'.$_GET['property_style'];
								endif;								
	
								if($_GET['price_low']!='' && $_GET['price_max']!=''): 
								  print ', <strong>Price Range: </strong>$'.$_GET['price_low'].' to $'.$_GET['price_max'];
								endif;
	
								if($_GET['min_size_property']!='' && $_GET['max_size_property']!=''): 
								  print ', <strong>Property Size: </strong>'.$_GET['min_size_property'].' To '.$_GET['max_size_property'];
								endif;
								
								if($_GET['bedrooms']!=''): 
								  print ', <strong>Bedrooms: </strong>'.$_GET['bedrooms'];
								endif;
	
								if($_GET['bathrooms']!=''): 
								  print ', <strong>Bathrooms: </strong>'.$_GET['bathrooms'];
								endif;	
	
								if($_GET['min_property_year']!='' && $_GET['max_property_year']!=''): 
								  print ', <strong>Built Years: </strong>'.$_GET['min-property-year'].' To '.$_GET['max-property-year'];
								endif;								
															
								if($_GET['address']!=''): 
								  print ', <strong>Address: </strong>'.$_GET['address'];
								endif;																			
								
								if($_GET['property_mls']!=''): 
								  print ', <strong>MLS: </strong>'.$_GET['property_mls'];
								endif;
							
						   elseif($_GET['searchbox']=='buy'):
						   
						   elseif($_GET['searchbox']=='sell'):
						   
						   elseif($_GET['searchbox']=='condobox'):
						   

							 
						   else:
                            wpestate_show_search_params($args,$custom_advanced_search, $adv_search_what,$adv_search_how,$adv_search_label); 
						   endif;	
						 
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
        
        }else if( is_tax()) { ?>
           
            <h1 class="entry-title title_prop"> 
                <?php 
                    _e('Properties listed in ','wpestate');
                    //print '"';
                    single_cat_title();
                    //print '" ';
                ?>
            </h1>
        
        
        <?php
        }else{
            while (have_posts()) : the_post(); ?>
                <?php 
                if (esc_html( get_post_meta($post->ID, 'page_show_title', true) ) == 'yes') { ?>
                    <h1 class="entry-title title_prop"><?php the_title(); ?></h1>
                <?php } 
                ?>
               
            <?php 
            endwhile; // end of the loop.  
        }
        ?>  

       
              
              
        <?php  get_template_part('templates/compare_list'); ?>    
                        <?php  get_template_part('templates/spiner'); ?> 
        <div id="listing_ajax_container" class="ajax-map"> 
            
                  

           <?php
            $counter = 0;

            $is_col_md_12=1;    
            if ( $prop_selection->have_posts() ) {
                while ($prop_selection->have_posts()): $prop_selection->the_post(); 
                    get_template_part('templates/property_unit');
                endwhile;                
            }else{
                print '<h4>'.__('You don\'t have any properties yet!','wpestate').'</h4>';
            }

            wp_reset_query();               
        ?>
        </div>
        <!-- Listings Ends  here --> 
        
        
        <div class="half-pagination">
        <?php kriesi_pagination($prop_selection->max_num_pages, $range =2); ?>       
        </div>    
    </div><!-- end 8col container-->

</div>  