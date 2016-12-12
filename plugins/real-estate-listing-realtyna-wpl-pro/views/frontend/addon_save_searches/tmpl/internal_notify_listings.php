<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<ul class="wpl-save-searches-container">
    <?php foreach($property_ids as $property_id): $property_data = wpl_property::get_property_raw_data($property_id); ?>
    <li class="wpl-save-searches-listing">
        <div class="wpl-save-searches-listing-row">
            <a href="<?php echo wpl_property::get_property_link($property_data); ?>"><?php echo wpl_property::update_property_title($property_data); ?></a>
        </div>
        <div class="wpl-save-searches-listing-row">
            <span><?php echo wpl_property::generate_location_text($property_data); ?></span>
        </div>
    </li>
    <?php endforeach; ?>
</ul>