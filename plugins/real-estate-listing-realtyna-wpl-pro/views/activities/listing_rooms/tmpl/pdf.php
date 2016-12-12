<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id    = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;
$rooms          = isset($wpl_properties['current']['items']['rooms']) ? $wpl_properties['current']['items']['rooms'] : NULL;

if(!count($rooms) or !is_array($rooms)) return;
?>
<div class="wpl_rooms_container" id="wpl_rooms_container<?php echo $property_id; ?>">
	<table class="wpl_rooms_list_container clearfix">
    <?php
        $i = 0;
        $room_num = count($rooms);
        foreach($rooms as $room)
        {
            $icon = wpl_room_types::get_icon($room_types[$room->item_cat]['icon']);

            if($i!=0 and $i%2==0) echo '</tr>';
            if($i==0 or $i%2==0) echo '<tr>';
            echo '
                <td class="rooms_item" style="width:50%;">
                    <table>
                        <tr>
                            <td style="width:20px;text-align:center;"><img src="'.wpl_pdf::get_fixed_url($icon["url"]).'" /></td>
                            <td style="width:80%;">
                                <div class="room_name">'.__($room->item_name, 'wpl').'</div>
                                '.(($room->item_extra1 and $room->item_extra2) ? '<div class="room_size">('.$room->item_extra1.'x'.$room->item_extra2.')</div>' : '').'
                            </td>
                        </tr>
                    </table>
                </td>';

            $i++;
            if($i == $room_num) echo '</tr>';
        }
    ?>
    </table>
</div>