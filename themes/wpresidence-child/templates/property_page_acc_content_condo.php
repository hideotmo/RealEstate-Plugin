<?php
global $property_adr_text;
global $property_details_text;
global $property_features_text;
global $feature_list_array;
global $use_floor_plans;
global $post;
$content = get_the_content();
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

if($content!=''){                            
    print $content;     
}

get_template_part ('/templates/download_pdf');
$show_graph_prop_page= esc_html( get_option('wp_estate_show_graph_prop_page', '') );
?>

<div class="panel-group property-panel" id="accordion_prop_addr">
  <div class="panel panel-default">
    <div class="panel-heading"> <a data-toggle="collapse" data-parent="#accordion_prop_addr" href="#collapseTwo">
      <h4 class="panel-title">
        <?php if($property_adr_text!=''){
                    echo esc_html($property_adr_text);
                } else{
                    _e('Location','wpestate');
                }
                ?>
      </h4>
      </a> </div>
    <div id="collapseTwo" class="panel-collapse collapse in">
      <div class="panel-body"> <?php /* print estate_listing_address($post->ID); */ ?> 
      <div class="listing_detail col-md-4"><strong>Address:</strong> <?php echo get_post_meta( get_the_ID(), 'property_address', true );?></div>
      <div class="listing_detail col-md-4"><strong>City:</strong> <?php 
	  $terms2 = get_the_terms( $post->ID , 'property_city' );
foreach ( $terms2 as $term2 ) {
 $city=$term2->name;

}
echo $city;?></div>
      <div class="listing_detail col-md-4"><strong>Community:</strong> <?php 
	  $terms3 = get_the_terms( $post->ID , 'property_communities' );
foreach ( $terms3 as $term3 ) {
 $community=$term3->name;}
 
 echo $community;?></div>
      <div class="listing_detail col-md-4"><strong>District:</strong> <?php echo get_post_meta( get_the_ID(), 'district', true );?></div>
      
      </div>
    </div>
  </div>
</div>
<div class="panel-group property-panel" id="accordion_prop_details">
  <div class="panel panel-default">
    <div class="panel-heading">
      <?php                      
             if($property_details_text=='') {
                 print'<a data-toggle="collapse" data-parent="#accordion_prop_details" href="#collapseOne"><h4 class="panel-title" id="prop_det">'.__('Development Information', 'wpestate').'  </h4></a>';
             }else{
                 print'<a data-toggle="collapse" data-parent="#accordion_prop_details" href="#collapseOne"><h4 class="panel-title"  id="prop_det">'.$property_details_text.'  </h4></a>';
             }
             ?>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
      <div class="panel-body"> <?php /* print estate_listing_details($post->ID); */ ?> 
      
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Property ID:</strong> <?php echo get_post_meta( get_the_ID(), '', true );
        ?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Development Name:</strong> <?php echo get_post_meta( get_the_ID(), 'development_name', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Developer:</strong> <?php echo get_post_meta( get_the_ID(), 'developer_name', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Development Team:</strong> <?php echo get_post_meta( get_the_ID(), 'development_team', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Construction Season:</strong> <?php echo get_post_meta( get_the_ID(), 'construction_season', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Completion:</strong> <?php echo get_post_meta( get_the_ID(), 'completion', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Year Built:</strong> <?php echo get_post_meta( get_the_ID(), 'property-year', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Construction Type:</strong> <?php echo get_post_meta( get_the_ID(), 'construction_type', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Total Floors:</strong> <?php echo get_post_meta( get_the_ID(), 'number_of_floors', true );?></div>
        <div class="listing_detail col-md-4" id="propertyid_display"><strong>Pricing:</strong> <?php $money= get_post_meta( get_the_ID(), 'property_price', true );
        echo "$".number_format($money, 2);?></div>
     
      </div>
    </div>
  </div>
</div>
<!-- Features and Ammenties -->
<?php          
if ( count( $feature_list_array )!= 0 && count($feature_list_array)!=1 ){ //  if are features and ammenties
?>
<div class="panel-group property-panel" id="accordion_prop_features">
  <div class="panel panel-default">
    <div class="panel-heading" id="myDiv"> <a data-toggle="collapse" data-parent="#accordion_prop_features" href="#collapseThree">
      <?php
                if($property_features_text ==''){
                    print '<h4 class="panel-title" id="prop_ame">'.__('Amenities and Features', 'wpestate').'</h4>';
                }else{
                    print '<h4 class="panel-title" id="prop_ame">'. $property_features_text.'</h4>';
                }
              ?>
      </a> </div>
    <div id="collapseThree" class="panel-collapse collapse in">
      <div class="panel-body"> <?php print estate_listing_features($post->ID); ?> </div>
    </div>
  </div>
</div>
<?php
} // end if are features and ammenties
?>

<!-- END Features and Ammenties -->

<div class="panel-group property-panel" id="accordion_prop_finishes">
  <div class="panel panel-default">
    <div class="panel-heading"> <a data-toggle="collapse" data-parent="#accordion_prop_finishes" href="#finishes">
      <h4 class="panel-title" id="prop_ame">
        <?php _e('Features & Finishes', 'wpestate');?>
      </h4>
      </a> </div>
    <div id="collapsemap" class="panel-collapse collapse in">
      <div class="panel-body"> <?php the_field( "features_and_finishes" );/*echo get_post_meta( get_the_ID(), 'features_and_finishes', true ); */?></div>
    </div>
  </div>
</div>



<?php
    $prpg_slider_type_status= esc_html ( get_option('wp_estate_global_prpg_slider_type','') );    
    $local_pgpr_slider_type_status  =   get_post_meta($post->ID, 'local_pgpr_slider_type', true);
  //  if( ($local_pgpr_slider_type_status == 'global' && $prpg_slider_type_status == 'full width header') ||
       // $local_pgpr_slider_type_status  == 'full width header' ){        
   
    ?>
<?php
   // }
?>
<!-- Walkscore -->
<?php
    $walkscore_api= esc_html ( get_option('wp_estate_walkscore_api','') );
    if($walkscore_api!=''){?>
<div class="panel-group property-panel" id="accordion_walkscore">
  <div class="panel panel-default">
    <div class="panel-heading"> <a data-toggle="collapse" data-parent="#accordion_walkscore" href="#collapseFour">
      <?php
                    print '<h4 class="panel-title" id="prop_ame">'.__('WalkScore', 'wpestate').'</h4>';
                ?>
      </a> </div>
    <div id="collapseFour" class="panel-collapse collapse in">
      <div class="panel-body">
        <?php wpestate_walkscore_details($post->ID); ?>
      </div>
    </div>
  </div>
</div>
<?php       
    }
?>
<?php // floor plans
//if ( $use_floor_plans==1 ){ 
?>
<div class="panel-group property-panel" id="accordion_prop_floor_plans">
  <div class="panel panel-default" id="myDiv3">
    <div class="panel-heading"> <a data-toggle="collapse" data-parent="#accordion_prop_floor_plans" href="#collapseflplan">
      <?php
                    print '<h4 class="panel-title" id="prop_ame">'.__('Floor Plans', 'wpestate').'</h4>';
                ?>
      </a> </div>
    <div id="collapseflplan" class="panel-collapse collapse in">
      <div class="panel-body"> <?php print estate_floor_plan($post->ID); ?> </div>
    </div>
  </div>
</div>
<?php
//}
?>
<div class="panel-group property-panel" id="accordion_prop_pricing">
  <div class="panel panel-default" id="myDiv4">
    <div class="panel-heading"> <a data-toggle="collapse" data-parent="#accordion_prop_pricing" href="#collapsemap">
      <h4 class="panel-title" id="prop_ame">
        <?php _e('Pricing', 'wpestate');?>
      </h4>
      </a> </div>
    <div id="collapsemap" class="panel-collapse collapse in">
      <div class="panel-body">
        <div class="listing_detail col-md-6" id="propertyid_display"><strong>Price:</strong> <?php $money= get_post_meta( get_the_ID(), 'property_price', true );
        echo "$".number_format($money, 2);?></div>
        <div class="listing_detail col-md-6" id="propertyid_display"><strong>Price Per Square Feet:</strong> <?php echo get_post_meta( get_the_ID(), 'price_per_square_feet_range', true );?></div>
        <div class="listing_detail col-md-6" id="propertyid_display"><strong>Studio Pricing:</strong> <?php echo get_post_meta( get_the_ID(), 'studio_pricing_range', true );?></div>
        <div class="listing_detail col-md-6" id="propertyid_display"><strong>Estimated Condo Fee Per Sq Ft:</strong> <?php echo get_post_meta( get_the_ID(), 'estimated_condo_fee', true );?></div>
        <div class="listing_detail col-md-6" id="propertyid_display"><strong>One Bedroom Pricing:</strong> <?php echo get_post_meta( get_the_ID(), 'one_bedroom_pricing', true );?></div>
        <div class="listing_detail col-md-6" id="propertyid_display"><strong>Condo Fee Includes:</strong> <?php echo get_post_meta( get_the_ID(), 'condo_fee_includes', true );?></div>
        <div class="listing_detail col-md-6" id="propertyid_display"><strong>Two Bedroom Pricing:</strong> <?php echo get_post_meta( get_the_ID(), 'two_bedroom_pricing', true );?></div>
      </div>
    </div>
  </div>
</div>
<?php
if($show_graph_prop_page=='yes'){
?>
<div class="panel-group property-panel" id="accordion_prop_stat">
  <div class="panel panel-default">
    <div class="panel-heading"> <a data-toggle="collapse" data-parent="#accordion_prop_stat" href="#collapseSeven">
      <h4 class="panel-title">
        <?php 
                    _e('Page Views Statistics','wpestate');
               
                ?>
      </h4>
      </a> </div>
    <div id="collapseSeven" class="panel-collapse collapse in">
      <div class="panel-body">
        <canvas id="myChart"></canvas>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    //<![CDATA[
        jQuery(document).ready(function(){
             wpestate_show_stat_accordion();
        });
    
    //]]>
    </script>
<?php
}

?>
<div class="panel-group property-panel" id="accordion_prop_map">
  <div class="panel panel-default" id="myDiv5">
    <div class="panel-heading"> <a data-toggle="collapse" data-parent="#accordion_prop_map" href="#collapsemap">
      <h4 class="panel-title" id="prop_ame">
        <?php _e('Map', 'wpestate');?>
      </h4>
      </a> </div>
    <div id="collapsemap" class="panel-collapse collapse in">
      <div class="panel-body"> <?php print do_shortcode('[property_page_map propertyid="'.$post->ID.'"][/property_page_map]') ?> </div>
    </div>
  </div>
</div>
