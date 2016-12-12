<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js');
_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap wpl-wp settings-wp">
    <header>
        <div id="icon-settings" class="icon48">
        </div>
        <h2><?php echo __('WPL sample', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_item_list"><div class="wpl_show_message"></div></div>
        <div class="panel-wp margin-top-1p" id="wpl_sample_data_container">
            <h3><?php echo __('Generate sample listings', WPL_TEXTDOMAIN); ?></h3>
            <table class="wpl_sample_data_table">
                <tbody>
                    <tr>
                        <td style="width: 150px;"><label for="wpl_sample_count"><?php echo __('Count', WPL_TEXTDOMAIN); ?></label></td>
                        <td><input type="text" name="wpl_sample[count]" id="wpl_sample_count" value="20" /></td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_kind"><?php echo __('Kind', WPL_TEXTDOMAIN); ?></label></td>
                        <td>
                        	<select name="wpl_sample[kind]" id="wpl_sample_kind">
                            	<?php foreach($this->kinds as $kind): ?>
                            	<option value="<?php echo $kind['id']; ?>"><?php echo __($kind['name'], WPL_TEXTDOMAIN); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_listing_type"><?php echo __('Listing type', WPL_TEXTDOMAIN); ?></label></td>
                        <td>
                        	<select name="wpl_sample[listing_type]" id="wpl_sample_listing_type">
                            	<?php foreach($this->listings as $listing): ?>
                            	<option value="<?php echo $listing['id']; ?>"><?php echo __($listing['name'], WPL_TEXTDOMAIN); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_property_type"><?php echo __('Property type', WPL_TEXTDOMAIN); ?></label></td>
                        <td>
                            <select name="wpl_sample[property_type]" id="wpl_sample_property_type">
                            	<?php foreach($this->property_types as $property_type): ?>
                            	<option value="<?php echo $property_type['id']; ?>"><?php echo __($property_type['name'], WPL_TEXTDOMAIN); ?></option>
                                <?php endforeach; ?>
                            </select>
						</td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_user_id"><?php echo __('Agent', WPL_TEXTDOMAIN); ?></label></td>
                        <td>
                            <select name="wpl_sample[user_id]" id="wpl_sample_user_id">
                            	<?php foreach($this->users as $user): ?>
                            	<option value="<?php echo $user->ID; ?>"><?php echo $user->user_login; ?></option>
                                <?php endforeach; ?>
                            </select>
						</td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_geo_center"><?php echo __('Geo center', WPL_TEXTDOMAIN); ?></label></td>
                        <td><input type="text" name="wpl_sample[geo_center]" id="wpl_sample_geo_center" value="United States" /><span class="wpl_description"><?php echo __('Example: Los Angeles, California, United States', WPL_TEXTDOMAIN); ?></span></td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_radius"><?php echo __('Radius', WPL_TEXTDOMAIN); ?></label></td>
                        <td><input type="text" name="wpl_sample[radius]" id="wpl_sample_radius" value="200" /><span class="wpl_description"><?php echo __('Miles', WPL_TEXTDOMAIN); ?></span></td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_property_tag"><?php echo __('Property tag', WPL_TEXTDOMAIN); ?></label></td>
                        <td>
                            <select name="wpl_sample[property_tag]" id="wpl_sample_property_tag">
                            	<option value="">-----</option>
                                <option value="random" selected="selected">- <?php echo __('Random', WPL_TEXTDOMAIN); ?> -</option>
                                <option value="sp_featured"><?php echo __('Featured', WPL_TEXTDOMAIN); ?></option>
                                <option value="sp_hot"><?php echo __('Hot', WPL_TEXTDOMAIN); ?></option>
                                <option value="sp_openhouse"><?php echo __('Open house', WPL_TEXTDOMAIN); ?></option>
                                <option value="sp_forclosure"><?php echo __('Foreclosure', WPL_TEXTDOMAIN); ?></option>
                            </select>
						</td>
                    </tr>
                    <tr>
                        <td><label for="wpl_sample_images"><?php echo __('Image count', WPL_TEXTDOMAIN); ?></label></td>
                        <td><input type="text" name="wpl_sample[image_count]" id="wpl_sample_images" value="5" /><span class="wpl_description"><?php echo __('Maximum is 10', WPL_TEXTDOMAIN); ?></span></td>
                    </tr>
            	</tbody>
            </table>
            <p class="submit">  
                <input type="button" value="<?php echo __('Insert', WPL_TEXTDOMAIN); ?>" class="wpl-button button-1" onclick="wpl_sample_data();" /> <span id="wpl_ajax_loader_sample_data"></span>
            </p>
        </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>
<script type="text/javascript">
    (function($){$(function(){isWPL();})})(jQuery);
</script>