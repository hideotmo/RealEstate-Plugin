<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'languages' and !$done_this)
{
    $languages = wpl_addon_pro::get_wpl_languages();
    $lang_options = wpl_addon_pro::get_wpl_language_options();
    $max_langs = max(wpl_global::get_setting('max_wpllangs'), count($languages));
    $wp_pages = wpl_global::get_wp_pages();
?>
<div class="prow wpl_setting_form_container wpl-setting-langs wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
    <div class="languages-wp">
        <div class="wpl-btns-wp">
            <button onclick="wpl_languages_save();" class="wpl-button button-1">
                <?php echo __('Save', 'wpl'); ?>
                <span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
            </button>
            <div id="wpl_language_show_message" class="wpl-notify-msg"></div>
        </div>

        <div id="wpl_languages_container" class="wpl-lang-table-wp">

            <ul class="wpl-languages-labels">
                <li><?php echo __('Language Locale', 'wpl'); ?></li>
                <li><?php echo __('Language Code', 'wpl'); ?></li>
                <li><?php echo __('Language Main page', 'wpl'); ?></li>
            </ul>

            <?php for ($i = 1; $i <= $max_langs; $i++): ?>
            <div class="wpl-language-row">
                <label for="wpl_language_full_code<?php echo $i; ?>"><?php echo $i; ?></label>

                <input type="text" id="wpl_language_full_code<?php echo $i; ?>"
                       name="wpllangs[<?php echo $i; ?>][full_code]" class="wpllangs wpl-langs-full"
                       placeholder="<?php echo __('en_US, fr_FR, de_DE', 'wpl'); ?>" autocomplete="off"
                       value="<?php echo(isset($lang_options[$i]) ? $lang_options[$i]['full_code'] : ''); ?>" />

                <input type="text" id="wpl_language_shortcode<?php echo $i; ?>"
                       name="wpllangs[<?php echo $i; ?>][shortcode]" class="wpllangs wpl-langs-short"
                       placeholder="<?php echo __('en, fr, de', 'wpl'                        ); ?>" autocomplete="off"
                       value="<?php echo (isset($lang_options[$i]) ? $lang_options[$i]['shortcode'] : ''); ?>" />

                <select name="wpllangs[<?php echo $i; ?>][main_page]" autocomplete="off">
                    <option value="">---</option>
                    <?php foreach($wp_pages as $wp_page): ?>
                    <option value="<?php echo $wp_page->ID; ?>" <?php if(isset($lang_options[$i]) and isset($lang_options[$i]['main_page']) and $wp_page->ID == $lang_options[$i]['main_page']) echo 'selected="selected"'; ?>><?php echo $wp_page->post_title; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <?php endfor; ?>
        </div>

        <div class="wpl-languages-generate-keywords wpl-btns-wp">
            <span class="wpl-button button-2" onclick="wpl_generate_keywords();">
                <?php echo __('Generate WPL dynamic strings for using in translation plugins like WPML.', 'wpl'); ?>
            </span>
            <span id="wpl_languages_generate_keywords_ajax_loader"></span>
        </div>

    </div>
</div>
<script type="text/javascript">
function wpl_generate_keywords()
{
    var ajax_loader_element = "#wpl_languages_generate_keywords_ajax_loader";
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    var request_str = 'wpl_format=b:settings:ajax&wpl_function=generate_language_keywords&_wpnonce=<?php echo $nonce; ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
        dataType: 'json',
		success: function(data)
		{
            wplj(ajax_loader_element).html('');
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_languages_save()
{
    wpl_remove_message();
    
    var ajax_loader_element = "#wpl_ajax_loader_<?php echo $setting_record->id; ?>";
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
    
    var wpllangs = '';
    var request_str = '';
    
    /** general options **/
	wplj("#wpl_languages_container input:text, #wpl_languages_container select").each(function (index, element)
	{
        var value = wplj(element).val();
        if(value == '') return;
        
		wpllangs += element.name+"="+value+"&";
	});
    
    request_str = 'wpl_format=b:settings:ajax&wpl_function=save_languages&'+wpllangs+'&_wpnonce=<?php echo $nonce; ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
        dataType: 'json',
		success: function(data)
		{
            if(data.success)
            {
                wpl_show_messages(data.message, '#wpl_language_show_message', 'wpl_green_msg');
                wplj(ajax_loader_element).html('');
            }
            else
            {
                wpl_show_messages(data.message, '#wpl_language_show_message', 'wpl_red_msg');
                wplj(ajax_loader_element).html('');
            }
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
            wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '#wpl_language_show_message', 'wpl_red_msg');
			wplj(ajax_loader_element).html('');
		}
	});
}
</script>
<?php
    $done_this = true;
}
elseif($type == 'sitemap' and !$done_this)
{
    $supported_plugins = array('google-sitemap-generator'=>'Google Sitemap Generator', 'yoast-seo'=>'Yoast SEO', 'all-in-one-seo'=>'All In One SEO Pack');
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">
	<div class="text-wp">
		<label for="wpl_st_form_element<?php echo $setting_record->id; ?>"><?php echo $setting_title; ?>&nbsp;<span class="wpl_st_citation">:</span></label>
        <select name="wpl_st_form<?php echo $setting_record->id; ?>" id="wpl_st_form_element<?php echo $setting_record->id; ?>" onchange="wpl_setting_save('<?php echo $setting_record->id; ?>', '<?php echo $setting_record->setting_name; ?>', this.value, '<?php echo $setting_record->category; ?>');" autocomplete="off">
            <option value="0"><?php echo __('Disabled', 'wpl'); ?></option>
            <?php foreach ($supported_plugins as $key=>$supported_plugin): ?>
            <option value="<?php echo $key; ?>" <?php if ($key == $value) echo 'selected="selected"'; ?>><?php echo $supported_plugin; ?></option>
            <?php endforeach; ?>
        </select>
        
		<?php if (isset($params['tooltip'])): ?>
        <span class="wpl_setting_form_tooltip wpl_help" id="wpl_setting_form_tooltip_container<?php echo $setting_record->id; ?>">
            <span class="wpl_help_description" style="display: none;"><?php echo __($params['tooltip'], 'wpl'); ?></span>
        </span>
		<?php endif; ?>

		<span class="ajax-inline-save" id="wpl_ajax_loader_<?php echo $setting_record->id; ?>"></span>
	</div>
</div>
<?php
    $done_this = true;
}
elseif($type == 'wpl_ui_customizer' and !$done_this)
{
    wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
            
    $google_fonts = json_decode(wpl_file::read(WPL_ABSPATH.'assets'.DS.'fonts'.DS.'googlefonts.json'), true);
    $google_fonts = $google_fonts['items'];
    $unit_types = array('px', 'em', '%', 'pt', 'rem', 'vw', 'vh', 'vmax', 'vmin');
    
    $ui_configs = json_decode($value, true);

    $js[] = (object) array('param1'=>'CodeMirror.js', 'param2'=>'packages/code_mirror/codemirror.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.CSS.js', 'param2'=>'packages/code_mirror/modes/css.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.addon.CloseBrackets.js', 'param2'=>'packages/code_mirror/addons/closebrackets.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.addon.CloseTags.js', 'param2'=>'packages/code_mirror/addons/closetag.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.addon.MatchBrackets.js', 'param2'=>'packages/code_mirror/addons/matchbrackets.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.addon.MatchTags.js', 'param2'=>'packages/code_mirror/addons/matchtags.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.addon.ShowHint.js', 'param2'=>'packages/code_mirror/addons/show-hint.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.addon.CSSHints.js', 'param2'=>'packages/code_mirror/addons/css-hint.min.js');
    $js[] = (object) array('param1'=>'CodeMirror.addon.ActiveLine.js', 'param2'=>'packages/code_mirror/addons/active-line.min.js');

    foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

    $css[] = (object) array('param1'=>'CodeMirror.css', 'param2'=>'packages/code_mirror/codemirror.css');
    $css[] = (object) array('param1'=>'CodeMirror.Neo.css', 'param2'=>'packages/code_mirror/theme/neo.css');
    $css[] = (object) array('param1'=>'CodeMirror.ShowHint.css', 'param2'=>'packages/code_mirror/addons/show-hint.css');
    
    foreach($css as $style) wpl_extensions::import_style($style);
?>
<div class="prow wpl_setting_form_container wpl_st_type<?php echo $setting_record->type; ?> wpl_st_<?php echo $setting_record->setting_name; ?>" id="wpl_st_<?php echo $setting_record->id; ?>">

	<div class="wpl-customizer-addon-wp">

        <div class="wpl-customizer-btns-wp">

            <button onclick="wpl_customizer_save();" class="wpl-button button-1" id="wpl_customizer_save_button">
                <?php echo __('Save Changed Styles', 'wpl'); ?>
            </button>

            <div id="wpl_customizer_show_message" class="wpl-notify-msg"></div>

        </div>

        <div id="wpl_customizer_container" class="wpl-customizer-fields-wp">

            <h4 class="separator-name"><?php echo __('Colors', 'wpl'); ?></h4>

            <div class="wpl-customizer-row">

                <label for="wpl_st_form_element<?php echo $setting_record->id; ?>_main_color"><?php echo __('Main Color', 'wpl'); ?>&nbsp;<span class="wpl_st_citation">:</span></label>

                <input type="text" name="wplcustomizer[main_color]" value="<?php echo (isset($ui_configs['main_color']) ? $ui_configs['main_color'] : '#29a9df'); ?>" class="wpl-color-field" data-default-color="#29a9df" id="wpl_customizer_main_color" />

            </div>

            <h4 class="separator-name"><?php echo __('Fonts', 'wpl'); ?></h4>

            <div class="wpl-customizer-row">

                <label for="wpl_st_form_element<?php echo $setting_record->id; ?>_main_font"><?php echo __('Main Font', 'wpl'); ?>&nbsp;<span class="wpl_st_citation">:</span></label>

                <select name="wplcustomizer[main_font]" id="wpl_customizer_main_font">
                    <?php foreach($google_fonts as $google_font): ?>
                    <option value="<?php echo $google_font['family']; ?>"<?php echo (((isset($ui_configs['main_font']) and $ui_configs['main_font'] == $google_font['family']) or (!isset($ui_configs['main_font']) and 'Lato' == $google_font['family'])) ? ' selected="selected"' : ''); ?>><?php echo $google_font['family']; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>


            <div class="wpl-customizer-row">

                <label for="wpl_st_form_element<?php echo $setting_record->id; ?>_main_font"><?php echo __('Second Font', 'wpl'); ?>&nbsp;<span class="wpl_st_citation">:</span></label>

                <select name="wplcustomizer[sec_font]" id="wpl_customizer_sec_font">
                    <?php foreach($google_fonts as $google_font): ?>
                        <option value="<?php echo $google_font['family']; ?>"<?php echo (((isset($ui_configs['sec_font']) and $ui_configs['sec_font'] == $google_font['family']) or (!isset($ui_configs['sec_font']) and 'BenchNine' == $google_font['family'])) ? ' selected="selected"' : ''); ?>><?php echo $google_font['family']; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>

            <h4 class="separator-name"><?php echo __('Properties Listing', 'wpl'); ?></h4>

            <div class="wpl-customizer-row">

                <label for="wpl_st_form_element<?php echo $setting_record->id; ?>_main_font"><?php echo __('Property Header', 'wpl'); ?>&nbsp;<span class="wpl_st_citation">:</span></label>

                <input type="text" name="wplcustomizer[property_header_font_size]" value="<?php echo (isset($ui_configs['property_header_font_size']) ? $ui_configs['property_header_font_size'] : '3'); ?>" style="width: 50px" />

                <select name="wplcustomizer[property_header_font_size_unit]">
                    <?php for($i=0; $i<count($unit_types); $i++): ?>
                        <option value="<?php echo $unit_types[$i]; ?>"<?php echo (((isset($ui_configs['property_header_font_size_unit']) and $ui_configs['property_header_font_size_unit'] == $unit_types[$i]) or (!isset($ui_configs['property_header_font_size_unit']) and 'em' == $unit_types[$i])) ? ' selected="selected"' : ''); ?>><?php echo $unit_types[$i]; ?></option>
                    <?php endfor; ?>
                </select>

            </div>

            <div class="wpl-customizer-row">

                <label for="wpl_st_form_element<?php echo $setting_record->id; ?>_main_font"><?php echo __('Property Location', 'wpl'); ?>&nbsp;<span class="wpl_st_citation">:</span></label>

                <input type="text" name="wplcustomizer[property_location_font_size]" value="<?php echo (isset($ui_configs['property_location_font_size']) ? $ui_configs['property_location_font_size'] : '18'); ?>" style="width: 50px" />

                <select name="wplcustomizer[property_location_font_size_unit]">
                    <?php for($i=0; $i<count($unit_types); $i++): ?>
                        <option value="<?php echo $unit_types[$i]; ?>"<?php echo (((isset($ui_configs['property_location_font_size_unit']) and $ui_configs['property_location_font_size_unit'] == $unit_types[$i]) or (!isset($ui_configs['property_location_font_size_unit']) and 'px' == $unit_types[$i])) ? ' selected="selected"' : ''); ?>><?php echo $unit_types[$i]; ?></option>
                    <?php endfor; ?>
                </select>

            </div>

        </div>

	</div>
</div>
<script type="text/javascript">
wplj(document).ready(function()
{
    wplj('.wpl-color-field').wpColorPicker();

    var editor = CodeMirror.fromTextArea(document.getElementById('wpl_st_form_element163'),
    {
        mode: "css",
        lineNumbers: true,
        autoCloseBrackets: true,
        autoCloseTags: true,
        matchBrackets: true,
        showTrailingSpace: true,
        styleActiveLine: true,
        theme: 'neo',
        extraKeys: {
            "Ctrl-Space": "autocomplete"
        }
    });

    editor.on("blur", function()
    {
        wplj('#wpl_st_form_element163').val(editor.getValue()).trigger('change');
    });

});

function wpl_customizer_save()
{
    /** Show AJAX loader **/
    var wpl_ajax_loader = Realtyna.ajaxLoader.show("#wpl_customizer_save_button", 'tiny', 'rightOut');
    
    var wplcustomizer = '';
    
    /** general options **/
	wplj("#wpl_customizer_container input:text, #wpl_customizer_container select").each(function (index, element)
	{
        var value = wplj(element).val();
        if(value == '') return;
        
		wplcustomizer += element.name+"="+value+"&";
	});
    
    var request_str = 'wpl_format=b:settings:ajax&wpl_function=save_customizer&'+wplcustomizer+'&_wpnonce=<?php echo $nonce; ?>';
	
	/** run ajax query **/
	wplj.ajax(
	{
		type: "POST",
		url: '<?php echo wpl_global::get_full_url(); ?>',
		data: request_str,
        dataType: 'json',
		success: function(data)
		{
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
            wpl_show_messages('<?php echo __('Error Occured.', 'wpl'); ?>', '#wpl_language_show_message', 'wpl_red_msg');
			
            /** Remove AJAX loader **/
            Realtyna.ajaxLoader.hide(wpl_ajax_loader);
		}
	});
}
</script>
<?php
    $done_this = true;
}