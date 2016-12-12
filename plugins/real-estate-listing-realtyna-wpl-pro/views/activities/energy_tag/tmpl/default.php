<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$image_width       = isset($params['image_width'])       ? $params['image_width'] 	    : '';
$image_height      = isset($params['image_height'])      ? $params['image_height'] 	    : '';
$bar_width         = isset($params['bar_width']) 		 ? $params['bar_width'] 		: '';
$first_bar_length  = isset($params['first_bar_length'])  ? $params['first_bar_length'] 	: '';
$length_increment  = isset($params['length_increment'])  ? $params['length_increment'] 	: '';
$vertical_distance = isset($params['vertical_distance']) ? $params['vertical_distance'] : '';
$peak              = isset($params['peak']) 			 ? $params['peak'] 				: '';
$energy_font_size  = isset($params['energy_font_size'])  ? $params['energy_font_size'] 	: '';
$font_size 		   = isset($params['font_size']) 		 ? $params['font_size'] 		: '';
$show_energy_value = isset($params['show_energy_value']) ? $params['show_energy_value'] : '';
$wpl_properties    = isset($params['wpl_properties'])    ? $params['wpl_properties']    : NULL;
$energy_value 	   = $wpl_properties['current']['data']['energy_tag'];

if($energy_value != '' and $energy_value > 0)
{
    $texts[] = '≤50';
    $texts[] = '51 '. __('to', 'wpl').' 90';
    $texts[] = '91 '. __('to', 'wpl').' 150';
    $texts[] = '151 '. __('to', 'wpl').' 230';
    $texts[] = '231 '. __('to', 'wpl').' 330';
    $texts[] = '331 '. __('to', 'wpl').' 450';
    $texts[] = '>450';
    $texts[] = __('Energy Consumption', 'wpl');
    $texts[] = __('More Efficient', 'wpl');
    $texts[] = __('Less Efficient', 'wpl');
    $texts[] = __('Measuring index: ', 'wpl').' kWhEP/m².y';

    /** creating library class for energy tag **/
    $wpl_energy_tag = new wpl_energy_tag();

    /** initializing energy tag object variables **/
    $wpl_energy_tag->pid 				 = $wpl_properties['current']['data']['id'];
    $wpl_energy_tag->image_width 		 = $image_width;
    $wpl_energy_tag->image_height		 = $image_height;
    $wpl_energy_tag->energy_value		 = $energy_value;
    $wpl_energy_tag->text_font_size		 = $font_size;
    $wpl_energy_tag->bar_width           = $bar_width;
    $wpl_energy_tag->first_bar_length    = $first_bar_length;
    $wpl_energy_tag->length_increment    = $length_increment;
    $wpl_energy_tag->vertical_distance   = $vertical_distance;
    $wpl_energy_tag->peak				 = $peak;
    $wpl_energy_tag->energy_font_size	 = $energy_font_size;
    $wpl_energy_tag->texts               = $texts;
    $wpl_energy_tag->show_energy_value   = $show_energy_value;
    /** creating energy tag image **/
    $energy_tag_image = $wpl_energy_tag->create_energy_tag();
}
else
    $energy_tag_image = '';

/** return **/
if(!trim($energy_tag_image)) return NULL;
?>
<div class="wpl_energy_tag">
    <?php echo $energy_tag_image; ?>
</div>