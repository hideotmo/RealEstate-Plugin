<!-- begin sidebar -->
<div class="clearfix visible-xs"></div>
<?php 
$sidebar_name   =   $options['sidebar_name'];
$sidebar_class  =   $options['sidebar_class'];


 
if( ('no sidebar' != $options['sidebar_class']) && ('' != $options['sidebar_class'] ) && ('none' != $options['sidebar_class']) ){
?>    
    <div class="col-xs-12 <?php print esc_html($options['sidebar_class']);?> widget-area-sidebar" id="primary" >
        <ul class="xoxo">
            <?php 
            //generated_dynamic_sidebar( $options['sidebar_name'] ); 
			  dynamic_sidebar( 'sidebar-condo' );
			?>
        </ul>

    </div>   

<?php
}
?>
<!-- end sidebar -->