<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;
$openhouse = isset($wpl_properties['current']['data']['sp_openhouse']) ? $wpl_properties['current']['data']['sp_openhouse'] : 0;
$open_dates = isset($wpl_properties['current']['items']['opendates']) ? $wpl_properties['current']['items']['opendates'] : NULL;

/** return if property is not openhouse or open dates are not available **/
if(!$openhouse or !count($open_dates) or !is_array($open_dates)) return;

// Get valid open dates
$valid_dates = wpl_items::render_opendates($open_dates, true);
?>
<div class="wpl_opendates_container" id="wpl_opendates_container<?php echo $property_id; ?>">
	<ul class="wpl_opendates_list_container clearfix">
		<?php foreach($valid_dates as $key=>$valid_date): ?>
        <li class="wpl_opendates_room wpl_opendates_type<?php echo $valid_date['id']; ?>" id="wpl_opendates_room<?php echo $valid_date['id']; ?>">
            <span class="opendate_number"><?php echo $key; ?>. </span>
            <span><?php echo $valid_date['date']; ?></span>
            <?php if(isset($valid_date['comment']) and trim($valid_date['comment']) != ''): ?><span class="comment">(<?php echo $valid_date['comment']; ?>)</span><?php endif; ?>
		</li>
        <?php endforeach; ?>
    </ul>
</div>