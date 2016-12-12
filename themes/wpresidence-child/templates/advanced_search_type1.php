<?php 
global $post;
global $adv_search_type;
$adv_search_what            =   get_option('wp_estate_adv_search_what','');
$show_adv_search_visible    =   get_option('wp_estate_show_adv_search_visible','');
$close_class                =   '';

if($show_adv_search_visible=='no'){
    $close_class='adv-search-1-close';
}

$extended_search    =   get_option('wp_estate_show_adv_search_extended','');
$extended_class     =   '';

if ($adv_search_type==2){
     $extended_class='adv_extended_class2';
}

if ( $extended_search =='yes' ){
    $extended_class='adv_extended_class';
    if($show_adv_search_visible=='no'){
        $close_class='adv-search-1-close-extended';
    }
       
}

?>

<?php if(is_home() || is_front_page()):?>
<script type="text/javascript">
 jQuery(document).ready(function(e) {
	jQuery('#adv-search-1').removeClass('adv-search-1-close');
	jQuery('#adv-search-1').removeClass('adv-search-1-close-extended');
    jQuery('.search_options_setting .srctad').click(function(e) {
       jQuery('.search_options_setting > div').removeClass('active');
	   jQuery(this).addClass('active'); 
	   var atb = jQuery(this).attr('data-id');
	   jQuery('.opsrctab').hide(1);
	   jQuery('.opsrctab#src'+atb).fadeIn();
    }); 
	jQuery('a.asearch').click(function(e) {
        var atd = jQuery(this).attr('data-id');
		jQuery('.opsrctab').hide(1);
		jQuery('.opsrctab#src'+atd).fadeIn();
		jQuery('.search_options_setting > div').removeClass('active');
    }); 
 });
</script> 
<?php endif;?>

 <div class="adv-search-1 <?php echo $close_class.' '.$extended_class;?>" id="adv-search-1" > 
 
   <?php if(is_home() || is_front_page()):?>
     <div class="search_options_setting">
       <div id="adv-search-header-buy" class="srctad active" data-id="buy">  <?php _e('Buy','wpestate');?></div> 
       <div id="adv-search-header-sell" class="srctad" data-id="sell"> <?php _e('Sell','wpestate');?></div>
       <div id="adv-search-header-condo" class="srctad" data-id="condo"> <?php _e('Condo Directory','wpestate');?></div> 
     </div>
   <?php else:?>
        <div id="adv-search-header-1"> <?php _e('Advanced Search','wpestate');?></div> 
   <?php endif;?>
   
   <?php if(is_home() || is_front_page()):?> 
    
       <div class="advance-search-options-xox opsrctab" id="srcbuy">
         <form role="search" method="get"   action="<?php print $adv_submit; ?>" >
            <div class="adv1-holder">
               <!--<input type="text" id="adv_location" class="form-control" name="adv_location"  placeholder="<?php //_e('Search by City, Neighbourhood, Address, Postal Code or MLS#','wpestate');?>" value="">-->
               <?php echo do_shortcode('[autocomplete-post-search id="1" pholder="Search by City, Neighbourhood, Address, Postal Code or MLS#"]'); ?>
               <div class="adv_search_slider">
                 <p><label for="amount">Price range:</label> 
                    <span style="border:0; color:#f6931f; font-weight:bold;" id="amount2">$ 100,000 to $ 2,000,000</span>
                 </p>
                 <div id="slider_price2"></div>
                 <input type="hidden" name="searchbox" value="buy" />
                 <input type="hidden" value="buy" name="property-types">
                 <input type="hidden" value="100000" name="price_low" id="price_low2">
                 <input type="hidden" value="2000000" name="price_max" id="price_max2">
               </div>
               <a href="javascript::void();" class="asearch" data-id="aso">ADVANCED SEARCH</a><a href="/prop-list-half-map/" class="gsearch">MAP SEARCH</a>
            </div>  
          

            <input name="submit" type="submit" class="wpb_button  wpb_btn_adv_submit wpb_btn-large" id="advanced_submit_2" value="<?php _e('SEARCH PROPERTIES','wpestate');?>">
         </form> 
       </div>  
         
       <div class="advance-search-options-sell opsrctab" id="srcsell" style="display:none">
          <form role="search" method="get"   action="<?php print $adv_submit; ?>" >
            <div class="adv1-holder">
              <div class="worth">What Is Your Home Worth?</div>
              <div class="input-worth">
                <!--<input type="text" id="adv_location" class="form-control" name="adv_location"  placeholder="<?php //_e('Enter Your Address, City or Postal Code','wpestate');?>" value="">-->
                <?php echo do_shortcode('[autocomplete-post-search id="2" pholder="Enter Your Address, City or Postal Code"]'); ?>
              </div>
              <div class="input-worth-email">
                <input type="text" id="email_location" class="form-control" name="email_location"  placeholder="<?php _e('Email Address','wpestate');?>" value="">
                
              </div>
               <input type="hidden" name="searchbox" value="sell" />
              <input type="hidden" value="sales" name="property-types">
            </div>
            <input name="submit" type="submit" class="wpb_button  wpb_btn_adv_submit wpb_btn-large" id="advanced_submit_2" value="<?php _e('SEARCH PROPERTIES','wpestate');?>">
          </form>
       </div>
          
       <div class="advance-search-options-condo opsrctab" id="srccondo"  style="display:none">
          <form role="search" method="get"   action="<?php print $adv_submit; ?>" >
            <div class="adv1-holder">
               <!--<input type="text" id="adv_location" class="form-control" name="adv_location"  placeholder="<?php //_e('Search by City, Neighbourhood, Development Name, Developer or Address','wpestate');?>" value="">-->
               <?php echo do_shortcode('[autocomplete-post-search id="3" pholder="Search by City, Neighbourhood, Development Name, Developer or Address"]'); ?>
               <input type="hidden" name="searchbox" value="condobox" />
              <div class="property_types_area">
               <div class="dropdown form-control" >
                    <div data-toggle="dropdown" id="adv_actions" class="filter_menu_trigger" data-value="<?php //echo $adv_actions_value1; ?>"> 
                        <?php _e('Property Type','wpestate');?> 
                        <span class="caret caret_filter"></span> </div>           

                    <input type="hidden" name="propertytypes" value="all">
                    
                    <ul  class="dropdown-menu filter_menu" role="menu" aria-labelledby="adv_actions">
                        <li data-value="all" role="presentation">Property Type</li>
                        <?php
						// print $action_select_list;
						    $availableTags='';
							$args = array( 'hide_empty'=> false );
							$terms = get_terms( 'propertytypes', $args );
							foreach ( $terms as $term ) {
							   echo '<li data-value="'.$term->slug.'" role="presentation">'.$term->name.'</li>';
							}

						 ?>
                    </ul>        
                </div> 
              </div>
              <div class="reange_area">
               <div class="adv_search_slider">
                 <p><label for="amount">Price range:</label> 
                    <span style="border:0; color:#f6931f; font-weight:bold;" id="amount3">$ 100,000 to $ 2,000,000</span>
                 </p>
                 <div id="slider_price3"></div>
                 <input type="hidden" value="100000" name="price_low" id="price_low3">
                 <input type="hidden" value="2000000" name="price_max" id="price_max3">
               </div>
              </div>
               <a href="javascript::void();" class="asearch" data-id="aso">ADVANCED SEARCH</a><a href="/prop-list-half-map/" class="gsearch">MAP SEARCH</a>
            </div>  
            <input name="submit" type="submit" class="wpb_button  wpb_btn_adv_submit wpb_btn-large" id="advanced_submit_2" value="<?php _e('SEARCH PROPERTIES','wpestate');?>">
         </form>
       </div>
       <div class="advance-search-options-aso opsrctab" id="srcaso" style="display:none">
         <form role="search" method="get"   action="<?php print $adv_submit; ?>" >
            <div class="adv1-holder">
                <?php
                $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
                if ( $custom_advanced_search == 'yes'){
                    foreach($adv_search_what as $key=>$search_field){
                        wpestate_show_search_field('mainform',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list);
                    }
                }else{
                    $search_form = wpestate_show_search_field_classic_form('main',$action_select_list,$categ_select_list ,$select_city_list,$select_area_list);
                    print $search_form;
                }
    
                if($extended_search=='yes'){
                   show_extended_search('adv');
                }
                ?>
            </div>
           
            <input name="submit" type="submit" class="wpb_button  wpb_btn_adv_submit wpb_btn-large" id="advanced_submit_2" value="<?php _e('SEARCH PROPERTIES','wpestate');?>">
            <?php if ($adv_search_type!=2) { ?>
            <div id="results">
                <?php _e('We found ','wpestate'); ?> <span id="results_no">0</span> <?php _e('results.','wpestate'); ?>  
                <span id="showinpage"> <?php _e('Do you want to load the results now ?','wpestate');?> </span>
            </div>
            <?php } ?>

       </form>
       </div>
   <?php else:?>   
     <form role="search" method="get"   action="<?php print $adv_submit; ?>" >
            <div class="adv1-holder">
                <?php
                $custom_advanced_search= get_option('wp_estate_custom_advanced_search','');
                if ( $custom_advanced_search == 'yes'){
                    foreach($adv_search_what as $key=>$search_field){
                        wpestate_show_search_field('mainform',$search_field,$action_select_list,$categ_select_list,$select_city_list,$select_area_list,$key,$select_county_state_list);
                    }
                }else{
                    $search_form = wpestate_show_search_field_classic_form('main',$action_select_list,$categ_select_list ,$select_city_list,$select_area_list);
                    print $search_form;
                }
    
                if($extended_search=='yes'){
                   show_extended_search('adv');
                }
                ?>
            </div>
           
            <input name="submit" type="submit" class="wpb_button  wpb_btn_adv_submit wpb_btn-large" id="advanced_submit_2" value="<?php _e('SEARCH PROPERTIES','wpestate');?>">
            <?php if ($adv_search_type!=2) { ?>
            <div id="results">
                <?php _e('We found ','wpestate'); ?> <span id="results_no">0</span> <?php _e('results.','wpestate'); ?>  
                <span id="showinpage"> <?php _e('Do you want to load the results now ?','wpestate');?> </span>
            </div>
            <?php } ?>

       </form>
   <?php endif;?> 
       <div style="clear:both;"></div>
</div>  