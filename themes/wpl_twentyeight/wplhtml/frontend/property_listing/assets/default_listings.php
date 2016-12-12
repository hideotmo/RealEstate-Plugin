<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$theme_options = wpl_theme::get_wpl_theme_options();

if($theme_options['layout_listing_list_boxes'] == 'flat')
    include_once wpl_global::get_wpl_root_path() . 'views/frontend/property_listing/tmpl/assets/default_listings.php';
else
{
    $description_column = 'field_308';
    if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, $this->kind)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

    // Membership ID of current user
    $current_user_membership_id = wpl_users::get_user_membership();

    foreach($this->wpl_properties as $key => $property)
    {
        if($key == 'current') continue;

        /** unset previous property **/
        unset($this->wpl_properties['current']);

        /** set current property **/
        $this->wpl_properties['current'] = $property;

        if(isset($property['materials']['bedrooms']['value']) and ($_bedrooms = intval($property['materials']['bedrooms']['value']))) $room = sprintf('<div class="bedroom">%d<span>%s</span></div>', $_bedrooms, __("Bedroom", "wpl"));
        elseif(isset($property['materials']['rooms']['value']) and ($_rooms = intval($property['materials']['rooms']['value']))) $room = sprintf('<div class="room">%d<span>%s</span></div>', $_rooms, __("Room", "wpl"));
        else $room = '';

        $bathroom = (isset($property['materials']['bathrooms']['value']) and ($_bathrooms = intval($property['materials']['bathrooms']['value']))) ? sprintf('<div class="bathroom">%d<span>%s</span></div>', $_bathrooms, __("Bathroom", "wpl")) : '';

        $parking = (isset($property['materials']['f_150']['values'][0]) and ($_parkings = intval($property['materials']['f_150']['values'][0]))) ? sprintf('<div class="parking">%d<span>%s</span></div>', $_parkings, __("Parking", "wpl")) : '';

        $pic_count = (isset($property['raw']['pic_numb']) and ($_pic_count = intval($property['raw']['pic_numb']))) ? sprintf('<div class="pic_count">%d<span>%s</span></div>', $_pic_count, __("Picture", "wpl")) : '';

        $build_up_area = isset($property['materials']['living_area']['value']) ? '<div class="built_up_area">'.$property['materials']['living_area']['value'].'</div>' : (isset($property['materials']['lot_area']['value']) ? '<div class="built_up_area">'.$property['materials']['lot_area']['value'].'</div>' : '');

        $property_price = (isset($property['materials']['price']['value']) and intval(preg_replace("/[^0-9]/", "", $property['materials']['price']['value']))) ? $property['materials']['price']['value'] : '&nbsp;';

        $description = stripslashes(strip_tags($property['raw'][$description_column]));

        $mls_id = $property['materials']['mls_id']['value'];
        $cut_position = strrpos(substr($description, 0, 400), '.', -1);
        if(!$cut_position) $cut_position = 399;

        $property_id = $property['data']['id'];

        $property_css_class = (isset($this->property_css_class) and in_array($this->property_css_class, array('row_box', 'grid_box', 'map_box'))) ? $this->property_css_class : '';
        ?>
        <div class="wpl-column">
            <div class="wpl_prp_cont wpl_prp_cont_v2 <?php echo $property_css_class; ?>" id="wpl_prp_cont<?php echo $property['data']['id']; ?>" itemscope itemtype="https://schema.org/TradeAction">

                <div class="wpl-plisting-grid-only">
                    <?php
                        echo '<a id="prp_link_id_' . $property['data']['id'] . '_view_detail" href="' . $property['property_link'] . '" class="view_detail" title="' . $property['property_title'] . '">
                                 <h3 class="wpl_prp_title" itemprop="name">' . $property['property_title'] . '</h3>
                              </a>';

                        echo '<h4 class="wpl_prp_listing_location" itemprop="location">' . $property['location_text'] . '</h4>';
                    ?>
                </div>

                <div class="wpl_prp_top">
                    <div class="wpl_prp_top_boxes front">
                        <?php wpl_activity::load_position('wpl_property_listing_image', array('wpl_properties' => $this->wpl_properties)); ?>
                    </div>
                    <div class="wpl_prp_top_boxes back">
                        <a itemprop="url" id="prp_link_id_<?php echo $property['data']['id']; ?>"
                           <?php if(isset($this->property_css_class) and $this->property_css_class == 'map_box'): ?> href="javascript:void(0)" onclick="return wpl_property_preview_html(<?php echo $property['data']['id']; ?>)" <?php else: ?> href="<?php echo $property['property_link']; ?>" <?php endif; ?> class="view_detail" rel="<?php echo $property['property_link']; ?>" ><?php echo __('More Details', 'wplt'); ?></a>
                    </div>
                </div>

                <div class="wpl_prp_bot">

                    <div class="wpl-plisting-grid-only">

                        <div class="wpl_prp_listing_icon_box"><?php echo $room . $bathroom . $parking . $build_up_area; ?></div>

                        <div class="wpl_prp_desc" itemprop="description"><?php echo substr($description, 0, $cut_position + 1); ?></div>

                        <div class="price_box">
                            <div class="wpl_prp_listing_like">
                                <?php //wpl_activity::load_position('wpl_property_listing_like', array('wpl_properties' => $this->wpl_properties)); ?>
                                <div class="wpl_listing_links_container">
                                    <ul>
                                        <?php $find_favorite_item = in_array($property_id, wpl_addon_pro::favorite_get_pids()); ?>
                                        <li class="favorite_link<?php echo ($find_favorite_item ? ' added' : '') ?>">
                                            <a href="#" style="<?php echo ($find_favorite_item ? 'display: none;' : '') ?>" class="wpl_favorite_add_<?php echo $property_id; ?>" onclick="return wpl_favorite_control<?php echo $property_id; ?>(<?php echo $property_id; ?>, 1);" title="<?php echo __('Add to list', 'wplt'); ?>"></a>
                                            <a href="#" style="<?php echo (!$find_favorite_item ? 'display: none;' : '') ?>" class="wpl_favorite_remove_<?php echo $property_id; ?>" onclick="return wpl_favorite_control<?php echo $property_id; ?>(<?php echo $property_id; ?>, 0);" title="<?php echo __('Remove from list', 'wplt'); ?>"></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <span itemprop="price" content="<?php echo $property_price; ?>"><?php echo $property_price; ?></span>
                        </div>

                    </div>

                    <div class="wpl-plisting-row-only">

                        <div class="wpl-plisting-row-left">

                            <?php
                            echo '<a id="prp_link_id_' . $property['data']['id'] . '_view_detail" href="' . $property['property_link'] . '" title="' . $property['property_title'] . '">
                                    <h3 class="wpl_prp_title" itemprop="name">' . $property['property_title'] . '</h3>
                                  </a>';
                            ?>

                            <div class="wpl_prp_listing_location" itemprop="location"><?php echo $property['location_text']; ?></div>

                            <div class="wpl-plisting-row-info-wp">

                                <?php if($room): ?>
                                <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                    <?php echo $room; ?>
                                </div>
                                <?php endif; ?>

                                <?php if($bathroom): ?>
                                <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                    <?php echo $bathroom; ?>
                                </div>
                                <?php endif; ?>
                                <?php if($parking): ?>
                                    <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                        <?php echo $parking; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if($build_up_area): ?>
                                    <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                        <?php echo $build_up_area; ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <div class="wpl_prp_desc" itemprop="description"><?php echo substr($description, 0, $cut_position + 1); ?></div>

                        </div>

                        <div class="wpl-plisting-row-right">

                            <?php if(isset($property['materials']['price'])): ?>
                            <div class="price_box">
                                <span itemprop="price" content="<?php echo $property['materials']['price']['value']; ?>">
                                    <?php echo $property['materials']['price']['value']; ?>
                                </span>
                            </div>
                            <?php endif; ?>

                            <div class="wpl-plisting-pos">
                                <?php
                                $activities = wpl_activity::get_activities('plisting_position_links');
                                foreach($activities as $activity)
                                {
                                    $content = wpl_activity::render_activity($activity, array('wpl_properties'=>$this->wpl_properties));
                                    if(trim($content) == '') continue;

                                    $activity_title =  explode(':', $activity->activity);
                                    ?>
                                    <div class="wpl_prp_right_boxes <?php echo $activity_title[0]; ?>">
                                        <?php
                                        if($activity->show_title and trim($activity->title) != '')
                                        {
                                            $activity_box_title = NULL;
                                            $title_parts = explode(' ', __($activity->title, 'wplt'));
                                            $i_part = 0;

                                            foreach($title_parts as $title_part)
                                            {
                                                if($i_part == 0) $activity_box_title .= '<span>'.$title_part.'</span> ';
                                                else $activity_box_title .= $title_part.' ';

                                                $i_part++;
                                            }

                                            echo '<div class="wpl_prp_right_boxes_title">'.$activity_box_title.'</div>';
                                        }
                                        ?>
                                        <div class="wpl_prp_right_boxes_content clearfix">
                                            <?php echo $content; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>

                    <div class="wpl-plisting-map-only">

                        <a href="<?php echo $property['property_link']; ?>"  class="wpl-plisting-name view_detail" title="<?php echo $property['property_title']; ?>">
                            <h3 class="wpl_prp_title" itemprop="name"><?php echo $property['property_title']; ?></h3>
                        </a>
                        <div class="wpl_prp_listing_location" itemprop="location"><?php echo $property['location_text']; ?></div>
                        <div class="wpl-plisting-rooms-wp">

                            <?php if($room): ?>
                            <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                <?php echo $room; ?>
                            </div>
                            <?php endif; ?>

                            <?php if($bathroom): ?>
                            <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                <?php echo $bathroom; ?>
                            </div>
                            <?php endif; ?>

                            <?php /* if($parking): ?>
                            <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                <?php echo $parking; ?>
                            </div>
                            <?php endif; ?>

                            <?php if($build_up_area): ?>
                            <div class="wpl-plisting-room-wp wpl-plisting-row-col">
                                <?php echo $build_up_area; ?>
                            </div>
                            <?php endif; */ ?>

                        </div>

                        <?php if($property_price): ?>
                        <div class="price_box">
                            <span itemprop="price" content="<?php echo $property_price; ?>">
                                <?php echo $property_price; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
