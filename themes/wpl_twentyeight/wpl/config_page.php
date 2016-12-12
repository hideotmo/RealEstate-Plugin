<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

$theme_options = wpl_theme::get_wpl_theme_options();
?>
<script type="text/javascript">
    function wpl_save_theme_options() {
        request_str = '';
        jQuery("#wpl_theme_options_container input[type=text], #wpl_theme_options_container input[type=email], #wpl_theme_options_container input[type='hidden'], #wpl_theme_options_container select, #wpl_theme_options_container textarea").each(function (index, element) {
            request_str += '&' + element.name + '=' + encodeURIComponent(jQuery(element).val());
        });

        jQuery("#wpl_theme_options_container input[type=checkbox]").each(function (index, element) {
            request_str += "&" + element.name + "=";
            if (element.checked) request_str += '1';
            else request_str += '0';
        });

        ajax_loader_element = '#wpl_theme_options_ajax_loader';
        jQuery(ajax_loader_element).addClass('loading');

        request_str = 'wpl_function=save_theme_options' + request_str;

        /** run ajax query **/
        ajax = wpl_run_ajax_query('<?php echo wpl_theme::get_full_url(); ?>', request_str, ajax_loader_element, 'JSON', 'POST');

        ajax.success(function (data) {
            jQuery(ajax_loader_element).removeClass('loading');
        });

        return false;
    }

    jQuery(document).ready(function () {

        if (!jQuery('#wpl_theme_options_custom_colors').is(':checked')) {
            var bgColorTr = jQuery('.bg-color-pickers');
            bgColorTr.css({
                'display': 'none'
            });
        }

        jQuery('#wpl_theme_options_custom_colors').on('change', function () {
            if (jQuery(this).is(':checked')) {
                var bgColorTr = jQuery('.bg-color-pickers');
                bgColorTr.css({
                    'display': 'table-row'
                });
            }
            else {
                var bgColorTr = jQuery('.bg-color-pickers');
                bgColorTr.css({
                    'display': 'none'
                });
            }
        });

        if (!jQuery('#wpl_theme_options_body_pattern').is(':checked')) {
            var bgColorTr = jQuery('.pattern-selector');
            bgColorTr.css({
                'display': 'none'
            });
        }

        jQuery('#wpl_theme_options_body_pattern').on('change', function () {
            if (jQuery(this).is(':checked')) {
                var bgColorTr = jQuery('.pattern-selector');
                bgColorTr.css({
                    'display': 'table-row'
                });
            }
            else {
                var bgColorTr = jQuery('.pattern-selector');
                bgColorTr.css({
                    'display': 'none'
                });
            }
        });


    });

</script>

<div class="wpl-28-theme-config-wp section panel wpl-28-config-loading" id="wpl_theme_options_container">

    <header>
        <div id="icon-dashboard" class="icon48"></div>
        <h2 class="wpl-dashboard-wp"><?php _e('WPL Template', 'wplt'); ?> - <span class="wpl-dashboard-ver"><?php echo wpl_theme::get_version(); ?></span></h2>
    </header>

    <form class="panel-body" method="post" enctype="multipart/form-data" onsubmit="wpl_save_theme_options(); return false;">

        <div class="left-panel col-md-2">
            <p class="submit" id="wpl_theme_options_ajax_loader">
                <input type="submit" class="wpl-button button-1" value="<?php _e('Save Changes', 'wplt'); ?>"/>
            </p>
            <ul class="tab-container clearfix">
                <li class="active"><a href="#general-options" id="general-options-title"><?php _e('General Options', 'wplt') ?></a></li>
                <li><a href="#layout-options" id="layout-options-title"><?php _e('Layout Options', 'wplt') ?></a></li>
                <li><a href="#font-options" id="font-options-title"><?php _e('Font Options', 'wplt') ?></a></li>
                <li><a href="#color-options" id="color-options-title"><?php _e('Color Options', 'wplt') ?></a></li>
                <li><a href="#header-options" id="header-options-title"><?php _e('Header Options', 'wplt') ?></a></li>
                <li><a href="#footer-options" id="footer-options-title"><?php _e('Footer Options', 'wplt') ?></a></li>
                <li><a href="#social-icons-options" id="social-icons-options-title"><?php _e('Social Icons Options', 'wplt') ?></a></li>
                <li><a href="#flex-slider" id="flex-slider-title"><?php _e('Flex Slider', 'wplt') ?></a></li>
                <li><a href="#short-codes" id="short-codes-title"><?php _e('Short Codes', 'wplt') ?></a></li>
                <li><a href="#css-options" id="css-options-title"><?php _e('Custom CSS', 'wplt') ?></a></li>
            </ul>
        </div>

        <div class="right-panel tab-content col-md-10">

            <div class="panel-wp tab-pane fade in active" id="general-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('General Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <td style="width: 250px;">
                            <label for="wpl_theme_options_tel"><?php _e('Tel', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the text.', 'wplt'); ?></span>
                        </td>
                        <td><input type="text" id="wpl_theme_options_tel" name="wpl_theme_options[tel]" value="<?php echo $this->isset_variable('tel', $theme_options); ?>"/></td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_email"><?php _e('Email', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the text.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="email" id="wpl_theme_options_email" name="wpl_theme_options[email]"
                                   value="<?php echo $this->isset_variable('email', $theme_options); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_mailto"><?php _e('Mail To', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Render email address as a link with "emailto:email@address.com" format.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_mailto" name="wpl_theme_options[mailto]" <?php echo(($this->isset_variable('mailto', $theme_options) and $theme_options['mailto'] == 1) ? 'checked="checked"' : '') ?> >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_favicon_img"><?php _e('FavIcon', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Specified your FavIcon for your website.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <div class="wpl-28-config-uploader uploader">
                                <?php
                                if (isset($theme_options['favicon']) and $theme_options['favicon'] !== '') {
                                    echo '<i class="glyphicon glyphicon-remove"></i><img src="' . $theme_options['favicon'] . '" id="wpl_theme_options_favicon_img" />';
                                }
                                ?>
                                <input type="hidden" value="<?php echo $theme_options['favicon']; ?>"
                                       name="wpl_theme_options[favicon]" id="wpl_theme_options_favicon"/>
                                <input class="wpl-28-upload-btn button" name="favicon_button" id="wpl_theme_options_favicon_button"
                                       value="Upload"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Blog Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_show_navigation"><?php _e('Show Post Navigation', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Show next and previous post links after post content.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <select id="wpl_theme_options_show_navigation" name="wpl_theme_options[show_navigation]">
                                <option
                                    value="2" <?php echo($this->theme_options['show_navigation'] == 2 ? 'selected="selected"' : '') ?>><?php _e('Yes', 'wplt'); ?></option>
                                <option
                                    value="1" <?php echo($this->theme_options['show_navigation'] == 1 ? 'selected="selected"' : '') ?>><?php _e('No', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_show_author"><?php _e('Show Author', 'wplt'); ?></label>
                        </td>
                        <td>
                            <select id="wpl_theme_options_show_author" name="wpl_theme_options[show_author]">
                                <option
                                    value="2" <?php echo($this->theme_options['show_author'] == 2 ? 'selected="selected"' : '') ?>><?php _e('Yes', 'wplt'); ?></option>
                                <option
                                    value="1" <?php echo($this->theme_options['show_author'] == 1 ? 'selected="selected"' : '') ?>><?php _e('No', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_show_date"><?php _e('Show Date', 'wplt'); ?></label>
                        </td>
                        <td>
                            <select id="wpl_theme_options_show_date" name="wpl_theme_options[show_date]">
                                <option
                                    value="2" <?php echo($this->theme_options['show_date'] == 2 ? 'selected="selected"' : '') ?>><?php _e('Yes', 'wplt'); ?></option>
                                <option
                                    value="1" <?php echo($this->theme_options['show_date'] == 1 ? 'selected="selected"' : '') ?>><?php _e('No', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Tracking codes', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_tracking_code"><?php _e('Tracking codes', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Place your Google Analytics (and the others) here. This code will be located at the footer of template.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <textarea id="wpl_theme_options_tracking_code" name="wpl_theme_options[tracking_code]"
                                      style="width: 400px; height: 80px;"><?php echo stripslashes($this->isset_variable('tracking_code', $theme_options)); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_head_code"><?php _e('Before <code>&lt;/head&gt;</code>', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Add your code before the <code>&lt;/head&gt;</code>', 'wplt'); ?></span>
                        </td>
                        <td>
                            <textarea id="wpl_theme_options_head_code" name="wpl_theme_options[head_code]"
                                      style="width: 400px; height: 80px;"><?php echo stripslashes($this->isset_variable('head_code', $theme_options)); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_body_code"><?php _e('Before <code>&lt;/body&gt;</code>', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Add your code before the <code>&lt;/body&gt;</code>', 'wplt'); ?></span>
                        </td>
                        <td>
                            <textarea id="wpl_theme_options_body_code" name="wpl_theme_options[body_code]"
                                      style="width: 400px; height: 80px;"><?php echo stripslashes($this->isset_variable('body_code', $theme_options)); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="color-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Color Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td><label for="wpl_theme_options_theme"><?php _e('Theme', 'wplt'); ?></label></td>
                        <td>
                            <select id="wpl_theme_options_theme" name="wpl_theme_options[theme]">
                                <option
                                    value="light" <?php echo($this->theme_options['theme'] == 'light' ? 'selected="selected"' : '') ?>><?php _e('Light', 'wplt'); ?></option>
                                <option
                                    value="dark" <?php echo($this->theme_options['theme'] == 'dark' ? 'selected="selected"' : '') ?>><?php _e('Dark', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="wpl_theme_options_preset"><?php _e('Presets Style', 'wplt'); ?></label>
                        </td>
                        <td>
                            <div class="preset_select">
                                <input type="hidden" name="wpl_theme_options[preset]"
                                       value="<?php echo $theme_options['preset']; ?>"/>

                                <div id="preset_0"
                                     onclick="update_values('preset','preset_0')"><?php _e('No Preset', 'wplt'); ?></div>
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<div onclick="update_values(\'preset\',\'preset_' . $i . '\')" id="preset_' . $i . '"' . (($this->isset_variable('preset', $theme_options) and $theme_options['preset'] == "$i") ? ' class="selected"' : '') . '>' . $i . '</div>';
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><label
                                for="wpl_theme_options_body_pattern"><?php _e('Enable Pattern For background', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_body_pattern"
                                   name="wpl_theme_options[body_pattern]" <?php echo(($this->isset_variable('body_pattern', $theme_options) and $theme_options['body_pattern'] == 1) ? 'checked="checked"' : '') ?> />
                        </td>
                    </tr>
                    <tr class="pattern-selector">
                        <td><label
                                for="wpl_theme_options_body_pattern_select"><?php _e('Set Pattern For background', 'wplt'); ?></label>
                        </td>
                        <td>
                            <div class="pattern_select">
                                <input type="hidden" name="wpl_theme_options[body_pattern_value]"
                                       value="<?php echo $this->isset_variable('body_pattern_value', $theme_options); ?>"/>
                                <?php
                                for ($i = 1; $i <= 10; $i++) {
                                    echo '<div onclick="update_values(\'pattern\',\'pattern_' . $i . '\')" id="pattern_' . $i . '"' . (($this->isset_variable('body_pattern_value', $theme_options) and $theme_options['body_pattern_value'] == "$i") ? ' class="selected"' : '') . '></div>';
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><label
                                for="wpl_theme_options_custom_colors"><?php _e('Enable Custom Colors', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_custom_colors"
                                   name="wpl_theme_options[custom_colors]" <?php echo(($this->isset_variable('custom_colors', $theme_options) and $theme_options['custom_colors'] == 1) ? 'checked="checked"' : '') ?> />
                        </td>
                    </tr>
                    <tr class="bg-color-pickers">
                        <td><label for="wpl_theme_options_body"><?php _e('Body Background', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="text" name="wpl_theme_options[body]"
                                   value="<?php echo $theme_options['body']; ?>" class="my-color-field"
                                   data-default-color="#FFFFFF" id="wpl_theme_options_body"/>
                        </td>
                    </tr>
                    <tr class="bg-color-pickers">
                        <td><label
                                for="wpl_theme_options_main_color"><?php _e('Main Color', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="text" name="wpl_theme_options[main_color]"
                                   value="<?php echo $this->isset_variable('main_color', $theme_options); ?>"
                                   class="my-color-field" data-default-color="#29a9df"
                                   id="wpl_theme_options_main_color"/>
                        </td>
                    </tr>
                    <tr class="bg-color-pickers">
                        <td><label
                                for="wpl_theme_options_footer_bg_color"><?php _e('Footer Background Color', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="text" name="wpl_theme_options[footer_bg]"
                                   value="<?php echo $this->isset_variable('footer_bg', $theme_options); ?>"
                                   class="my-color-field" data-default-color="#191818"
                                   id="wpl_theme_options_footer_bg_color"/>
                        </td>
                    </tr>
                    <tr class="bg-color-pickers">
                        <td><label
                                for="wpl_theme_options_footertop_bg_color"><?php _e('Footer Top Background Color', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="text" name="wpl_theme_options[footertop_bg]"
                                   value="<?php echo $this->isset_variable('footertop_bg', $theme_options); ?>"
                                   class="my-color-field" data-default-color="#333131"
                                   id="wpl_theme_options_footertop_bg_color"/>
                        </td>
                    </tr>
                    <tr class="bg-color-pickers">
                        <td><label
                                for="wpl_theme_options_footer_fontc_color"><?php _e('Footer Font Color', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="text" name="wpl_theme_options[footer_fontc]"
                                   value="<?php echo $this->isset_variable('footer_fontc', $theme_options); ?>"
                                   class="my-color-field" data-default-color="#222222"
                                   id="wpl_theme_options_footer_fontc_color"/>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="wpl_theme_options_logo"><?php _e('Logo', 'wplt'); ?></label></td>
                        <td>
                            <div class="wpl-28-config-uploader uploader">
                                <?php
                                if (isset($theme_options['logo']) and $theme_options['logo'] !== '') {
                                    echo '<i class="glyphicon glyphicon-remove"></i><img src="' . $theme_options['logo'] . '" id="wpl_theme_options_logo_img" />';
                                }
                                ?>
                                <input type="hidden" value="<?php echo $theme_options['logo']; ?>"
                                       name="wpl_theme_options[logo]" id="wpl_theme_options_logo"/>
                                <input class="wpl-28-upload-btn button" name="logo_button" id="wpl_theme_options_logo_button"
                                       value="Upload"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_retina_logo"><?php _e('Logo For Retina', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('It must be exactly x2 the size of main Logo', 'wplt'); ?></span>
                        </td>
                        <td>
                            <div class="wpl-28-config-uploader uploader">
                                <?php
                                if (isset($theme_options['retina_logo']) and $theme_options['retina_logo'] !== '') {
                                    echo '<i class="glyphicon glyphicon-remove"></i><img src="' . $theme_options['retina_logo'] . '" id="wpl_theme_options_retina_logo_img" />';
                                }
                                ?>
                                <input type="hidden" value="<?php echo $theme_options['retina_logo']; ?>"
                                       name="wpl_theme_options[retina_logo]" id="wpl_theme_options_retina_logo"/>
                                <input class="wpl-28-upload-btn button" name="retina_logo_button"
                                       id="wpl_theme_options_retina_logo_button" value="Upload"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_logo2"><?php _e('Second Logo', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('You should select headers which supports second logo', 'wplt'); ?></span>
                        </td>
                        <td>
                            <div class="wpl-28-config-uploader uploader">
                                <?php
                                if (isset($theme_options['logo2']) and $theme_options['logo2'] !== '') {
                                    echo '<i class="glyphicon glyphicon-remove"></i><img src="' . $theme_options['logo2'] . '" id="wpl_theme_options_logo2_img" />';
                                }
                                ?>
                                <input type="hidden" value="<?php echo $theme_options['logo2']; ?>"
                                       name="wpl_theme_options[logo2]" id="wpl_theme_options_logo2"/>
                                <input class="wpl-28-upload-btn button" name="logo2_button" id="wpl_theme_options_logo2_button"
                                       value="Upload"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_logo2_link"><?php _e('Second Logo Link', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to link to homepage.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_logo2_link" name="wpl_theme_options[logo2_link]"
                                   value="<?php echo $this->isset_variable('logo2_link', $theme_options); ?>" placeholder="http://www.realtyna.com"/>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="header-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Header Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_sticky_header"><?php _e('Sticky Header', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('When this field is enabled, header will be fixed to top of the screen.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <select id="wpl_theme_options_sticky_header" name="wpl_theme_options[sticky_header]">
                                <option value="1" <?php echo($this->theme_options['sticky_header'] == 1 ? 'selected="selected"' : '') ?>><?php _e('Yes', 'wplt'); ?></option>
                                <option value="2" <?php echo($this->theme_options['sticky_header'] == 2 ? 'selected="selected"' : '') ?>><?php _e('No', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_h_transparent"><?php _e('Transparent Header', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_h_transparent" name="wpl_theme_options[h_transparent]"
                                <?php echo(($this->isset_variable('h_transparent', $theme_options) and $theme_options['h_transparent'] == 1) ? 'checked="checked"' : '') ?> >
                        </td>
                    </tr>
                    <tr>
                        <td><label for="wpl_theme_options_header"><?php _e('Header Type', 'wplt'); ?></label>
                        </td>
                        <td>
                            <div class="header_select">
                                <input type="hidden" name="wpl_theme_options[header]" value="<?php echo $theme_options['header']; ?>"/>
                                <?php
                                for ($i = 0; $i <= 10; $i++) {
                                    echo '<div onclick="update_values(\'header\',\'header_' . $i . '\')" id="header_' . $i . '"' . (($this->isset_variable('header', $theme_options) and $theme_options['header'] == "$i") ? ' class="selected"' : '') . '><span>' . __('Header Type ', 'wplt') . $i . '</span></div>';
                                }
                                ?>
                            </div>
                        </td>

                    </tr>
                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="footer-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Footer Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td><label for="wpl_theme_options_header"><?php _e('Footer Type', 'wplt'); ?></label>
                        </td>
                        <td>
                            <div class="footer_select">
                                <input type="hidden" name="wpl_theme_options[footer]"
                                       value="<?php echo $theme_options['footer']; ?>"/>
                                <?php
                                for ($i = 1; $i <= 3; $i++) {
                                    echo '<div onclick="update_values(\'footer\',\'footer_' . $i . '\')" id="footer_' . $i . '"' . (($this->isset_variable('footer', $theme_options) and $theme_options['footer'] == "$i") ? ' class="selected"' : '') . '><span>' . __('Footer Type ', 'wplt') . $i . '</span></div>';
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_footer_html"><?php _e('Footer text', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the text.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <textarea id="wpl_theme_options_footer_html" name="wpl_theme_options[footer_html]"
                                      style="width: 400px; height: 80px;"><?php echo $this->isset_variable('footer_html', $theme_options); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_footer_columns"><?php _e('Footer Columns', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Number of Footer columns - Maximum 4', 'wplt'); ?></span>
                        </td>
                        <td>
                            <select id="wpl_theme_options_footer_columns" name="wpl_theme_options[footer_columns]">
                                <option
                                    value="1" <?php echo((isset($this->theme_options['footer_columns']) and $this->theme_options['footer_columns'] == '1') ? 'selected="selected"' : '') ?>>1</option>
                                <option
                                    value="2" <?php echo((isset($this->theme_options['footer_columns']) and $this->theme_options['footer_columns'] == '2') ? 'selected="selected"' : '') ?>>2</option>
                                <option
                                    value="3" <?php echo((isset($this->theme_options['footer_columns']) and $this->theme_options['footer_columns'] == '3') ? 'selected="selected"' : '') ?>>3</option>
                                <option
                                    value="4" <?php echo((isset($this->theme_options['footer_columns']) and $this->theme_options['footer_columns'] == '4') ? 'selected="selected"' : '') ?>>4</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label
                                for="wpl_theme_options_footer_menu"><?php _e('Show footer menu', 'wplt'); ?></label>
                        </td>
                        <td>
                            <select id="wpl_theme_options_footer_menu" name="wpl_theme_options[footer_menu]">
                                <option
                                    value="1" <?php echo($this->theme_options['footer_menu'] == '1' ? 'selected="selected"' : '') ?>><?php _e('Yes', 'wplt'); ?></option>
                                <option
                                    value="0" <?php echo($this->theme_options['footer_menu'] == '0' ? 'selected="selected"' : '') ?>><?php _e('No', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label
                                for="wpl_theme_options_enable_footer_logo"><?php _e('Enable Footer Logo', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_enable_footer_logo"
                                   name="wpl_theme_options[enable_footer_logo]" <?php echo(($this->isset_variable('enable_footer_logo', $theme_options) and $theme_options['enable_footer_logo'] == 1) ? 'checked="checked"' : '') ?> />
                        </td>
                    </tr>
                    <tr>
                        <td><label
                                for="wpl_theme_options_footer_logo"><?php _e('Footer Logo', 'wplt'); ?></label>
                        </td>
                        <td>
                            <div class="wpl-28-config-uploader uploader">
                                <?php
                                if (isset($theme_options['footer_logo']) and $theme_options['footer_logo'] !== '') {
                                    echo '<i class="glyphicon glyphicon-remove"></i><img src="' . $theme_options['footer_logo'] . '" id="wpl_theme_options_footer_logo_img" />';
                                }
                                ?>
                                <input type="hidden" value="<?php echo $theme_options['footer_logo']; ?>"
                                       name="wpl_theme_options[footer_logo]" id="wpl_theme_options_footer_logo"/>
                                <input class="wpl-28-upload-btn button" name="footer_logo_button"
                                       id="wpl_theme_options_footer_logo_button" value="Upload"/>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="social-icons-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Social Icons Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_email"><?php _e('Facebook', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_email" name="wpl_theme_options[facebook]" value="<?php echo (isset($theme_options['facebook']) ? $theme_options['facebook'] : 'https://www.facebook.com/Realtyna'); ?>" class="long" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_email"><?php _e('Twitter', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_email" name="wpl_theme_options[twitter]"
                                   value="<?php echo $this->isset_variable('twitter', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_email"><?php _e('Google Plus', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_email" name="wpl_theme_options[gplus]"
                                   value="<?php echo $this->isset_variable('gplus', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
					<tr>
                        <td>
                            <label for="wpl_theme_options_instagram"><?php _e('Instagram', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_instagram" name="wpl_theme_options[instagram]" value="<?php echo $this->isset_variable('instagram', $theme_options); ?>" class="long" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_flickr"><?php _e('Flickr', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_flickr" name="wpl_theme_options[flickr]"
                                   value="<?php echo $this->isset_variable('flickr', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_rss"><?php _e('RSS', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_rss" name="wpl_theme_options[rss]"
                                   value="<?php echo $this->isset_variable('rss', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_vimeo"><?php _e('Vimeo', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_vimeo" name="wpl_theme_options[vimeo]"
                                   value="<?php echo $this->isset_variable('vimeo', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_youtube"><?php _e('Youtube', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_youtube" name="wpl_theme_options[youtube]"
                                   value="<?php echo $this->isset_variable('youtube', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_pinterest"><?php _e('Pinterest', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_pinterest" name="wpl_theme_options[pinterest]"
                                   value="<?php echo $this->isset_variable('pinterest', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_tumblr"><?php _e('Tumblr', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_tumblr" name="wpl_theme_options[tumblr]"
                                   value="<?php echo $this->isset_variable('tumblr', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_dribbble"><?php _e('Dribbble', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_dribbble" name="wpl_theme_options[dribbble]"
                                   value="<?php echo $this->isset_variable('dribbble', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_digg"><?php _e('Digg', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_digg" name="wpl_theme_options[digg]"
                                   value="<?php echo $this->isset_variable('digg', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_linkedin"><?php _e('LinkedIn', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_linkedin" name="wpl_theme_options[linkedin]"
                                   value="<?php echo $this->isset_variable('linkedin', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_blogger"><?php _e('Blogger', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_blogger" name="wpl_theme_options[blogger]"
                                   value="<?php echo $this->isset_variable('blogger', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
					<tr>
                        <td>
                            <label for="wpl_theme_options_telegram"><?php _e('Telegram', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_telegram" name="wpl_theme_options[telegram]" value="<?php echo $this->isset_variable('telegram', $theme_options); ?>" class="long" />
                        </td>
                    </tr>
                    <!--
        <tr>
            <td>
                <label for="wpl_theme_options_skype"><?php _e('Skype', 'wplt'); ?></label>
                <span class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
            </td>
            <td>
                <input type="text" id="wpl_theme_options_skype" name="wpl_theme_options[skype]" value="<?php echo $this->isset_variable('skype', $theme_options); ?>" class="long" />
            </td>
        </tr>
-->
                    <tr>
                        <td>
                            <label for="wpl_theme_options_forrst"><?php _e('Forrst', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_forrst" name="wpl_theme_options[forrst]"
                                   value="<?php echo $this->isset_variable('forrst', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_myspace"><?php _e('MySpace', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_skype" name="wpl_theme_options[myspace]"
                                   value="<?php echo $this->isset_variable('myspace', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_deviantart"><?php _e('Deviantart', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_deviantart" name="wpl_theme_options[deviantart]"
                                   value="<?php echo $this->isset_variable('deviantart', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_yahoo"><?php _e('Yahoo', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_yahoo" name="wpl_theme_options[yahoo]"
                                   value="<?php echo $this->isset_variable('yahoo', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_reddit"><?php _e('Reddit', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Leave it blank if you want to remove the icon', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_reddit" name="wpl_theme_options[reddit]"
                                   value="<?php echo $this->isset_variable('reddit', $theme_options); ?>" class="long"/>
                        </td>
                    </tr>
                </table>
            </div>


            <div class="panel-wp tab-pane fade" id="layout-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Page Layout Settings', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <td><label for="wpl_theme_options_layout"><?php _e('Layout', 'wplt'); ?></label></td>
                        <td>
                            <select id="wpl_theme_options_layout" name="wpl_theme_options[layout]">
                                <option
                                    value="1" <?php echo($this->theme_options['layout'] == 1 ? 'selected="selected"' : '') ?>><?php _e('Wide', 'wplt'); ?></option>
                                <option
                                    value="2" <?php echo($this->theme_options['layout'] == 2 ? 'selected="selected"' : '') ?>><?php _e('Boxed', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="width: 250px;">
                            <label for="wpl_theme_options_width"><?php _e('Page Width', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Change width of page in Boxed layout.', 'wplt'); ?></span>
                        </td>
                        <td><input type="text" id="wpl_theme_options_width" name="wpl_theme_options[width]"
                                   value="<?php echo $this->isset_variable('width', $theme_options); ?>"/> <?php _e('Pixel', 'wplt'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="wpl_theme_options_page_title"><?php _e('Show Page Title', 'wplt'); ?></label>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_page_title"
                                   name="wpl_theme_options[page_title]" <?php echo(($this->isset_variable('page_title', $theme_options) and $theme_options['page_title'] == 1) ? 'checked="checked"' : '') ?> />
                        </td>
                    </tr>

                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Listing Layouts', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <label for="wpl_theme_options_listing_boxes"><?php _e('Listing Boxes', 'wplt'); ?></label>
                            <span class="info_text"><?php echo __('Select your specific style for Grid Listing Boxes', 'wplt'); ?></span>
                        </td>
                        <td>
                            <?php
                                if(!isset($this->theme_options['layout_listing_list_boxes'])) {$this->theme_options['layout_listing_list_boxes']='outstanding';}
                            ?>
                            <select id="wpl_theme_options_listing_hover" name="wpl_theme_options[layout_listing_list_boxes]">
                                <option value="flat" <?php echo(( $this->theme_options['layout_listing_list_boxes'] == 'flat') ? 'selected="selected"' : '') ?>><?php _e('Flat Boxes', 'wplt'); ?></option>
                                <option value="outstanding" <?php echo(($this->theme_options['layout_listing_list_boxes'] == 'outstanding') ? 'selected="selected"' : '') ?>><?php _e('Outstanding Boxes', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="wpl_theme_options_listing_hover"><?php _e('Listing Hover', 'wplt'); ?></label>
                            <span class="info_text"><?php echo __('Select the listing hover effect in Listing Page', 'wplt'); ?></span>
                        </td>
                        <td>
                            <select id="wpl_theme_options_listing_hover" name="wpl_theme_options[layout_listing_hover]">
                                <option value="flip" <?php echo((isset($this->theme_options['layout_listing_hover']) and $this->theme_options['layout_listing_hover'] == 'flip') ? 'selected="selected"' : '') ?>><?php _e('Flip', 'wplt'); ?></option>
                                <option value="fade" <?php echo((isset($this->theme_options['layout_listing_hover']) and $this->theme_options['layout_listing_hover'] == 'fade') ? 'selected="selected"' : '') ?>><?php _e('Fade', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="wpl_theme_options_default_listing_layout"><?php _e('Default Listing Layout', 'wplt'); ?></label>
                            <span class="info_text"><?php echo __('Select your specific style for Grid Listing Boxes', 'wplt'); ?></span>
                        </td>
                        <td>
                            <select id="wpl_theme_options_default_listing_layout" name="wpl_theme_options[default_listing_layout]">
                                <option value="last" <?php echo((isset($this->theme_options['default_listing_layout']) and $this->theme_options['default_listing_layout'] == 'last') ? 'selected="selected"' : '') ?>><?php _e('Last Session', 'wplt'); ?></option>
                                <option value="grid" <?php echo((isset($this->theme_options['default_listing_layout']) and $this->theme_options['default_listing_layout'] == 'grid') ? 'selected="selected"' : '') ?>><?php _e('Grid', 'wplt'); ?></option>
                                <option value="list" <?php echo((isset($this->theme_options['default_listing_layout']) and $this->theme_options['default_listing_layout'] == 'list') ? 'selected="selected"' : '') ?>><?php _e('List', 'wplt'); ?></option>
                                <option value="map" <?php echo((isset($this->theme_options['default_listing_layout']) and $this->theme_options['default_listing_layout'] == 'map') ? 'selected="selected"' : '') ?>><?php _e('Map', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Widgets Layouts', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <label for="wpl_theme_options_m_carousel_text"><?php _e('Modern Carousel Text', 'wplt'); ?></label>
                            <span class="info_text"><?php echo __('Select where Carousel text show.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <select id="wpl_theme_options_m_carousel_text" name="wpl_theme_options[layout_m_carousel_text]">
                                <option value="default" <?php echo((isset($this->theme_options['layout_m_carousel_text']) and $this->theme_options['layout_m_carousel_text'] == 'default') ? 'selected="selected"' : '') ?>><?php _e('Default', 'wplt'); ?></option>
                                <option value="top-center" <?php echo((isset($this->theme_options['layout_m_carousel_text']) and $this->theme_options['layout_m_carousel_text'] == 'top-center') ? 'selected="selected"' : '') ?>><?php _e('Top Center', 'wplt'); ?></option>
                                <option value="center-center" <?php echo((isset($this->theme_options['layout_m_carousel_text']) and $this->theme_options['layout_m_carousel_text'] == 'center-center') ? 'selected="selected"' : '') ?>><?php _e('Center Center', 'wplt'); ?></option>
                                <option value="bottom-center" <?php echo((isset($this->theme_options['layout_m_carousel_text']) and $this->theme_options['layout_m_carousel_text'] == 'bottom-center') ? 'selected="selected"' : '') ?>><?php _e('Bottom Center', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="font-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Font Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_main_font"><?php _e('Main Font', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Google Webfont', 'wplt'); ?></span>
                        </td>
                        <td>
                            <?php
                            wpl_theme::wpl_google_font_select('main_font', $theme_options['main_font']);
                            wpl_theme::wpl_font_size('main_font_size', $theme_options['main_font_size']);
                            ?>
                            <span class="info_text"><?php _e('Default is "Lato" and "13px"', 'wplt'); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_header_font"><?php _e('Header Font', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Google Webfont', 'wplt'); ?></span>
                        </td>
                        <td>
                            <?php
                            wpl_theme::wpl_google_font_select('header_font', $theme_options['header_font']);
                            wpl_theme::wpl_font_size('header_font_size', $theme_options['header_font_size']);
                            ?>
                            <span class="info_text"><?php _e('Default is "Lato" and "13px"', 'wplt'); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_footer_font"><?php _e('Footer Font', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Google Webfont', 'wplt'); ?></span>
                        </td>
                        <td>
                            <?php
                            wpl_theme::wpl_google_font_select('footer_font', $theme_options['footer_font']);
                            wpl_theme::wpl_font_size('footer_font_size', $theme_options['footer_font_size']);
                            ?>
                            <span class="info_text"><?php _e('Default is "Lato" and "13px"', 'wplt'); ?></span>
                        </td>
                    </tr>

                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Internal Page Fonts', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_plisting_title_font"><?php _e('Property Listing - Title', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Google Webfont', 'wplt'); ?></span>
                        </td>
                        <td>
                            <?php
                            wpl_theme::wpl_google_font_select('plisting_font', trim($theme_options['plisting_font']) ? $theme_options['plisting_font'] : 'BenchNine');
                            wpl_theme::wpl_font_size('plisting_font_size', (trim($theme_options['plisting_font_size']) ? $theme_options['plisting_font_size'] : '3'), 'em', 20, 0.1);
                            ?>
                            <span
                                class="info_text"><?php _e('Default is "BenchNine" and "3em"', 'wplt'); ?></span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_pshow_price_font"><?php _e('Property Show - Price', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Google Webfont', 'wplt'); ?></span>
                        </td>
                        <td>
                            <?php
                            wpl_theme::wpl_google_font_select('pshow_price_font', trim($theme_options['pshow_price_font']) ? $theme_options['pshow_price_font'] : 'BenchNine');
                            wpl_theme::wpl_font_size('pshow_price_font_size', (trim($theme_options['pshow_price_font_size']) ? $theme_options['pshow_price_font_size'] : '3'), 'em', 20, 0.1);
                            ?>
                            <span
                                class="info_text"><?php _e('Default is "BenchNine" and "3em"', 'wplt'); ?></span>
                        </td>
                    </tr>

                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="flex-slider">
                <table class="wpl_theme_options_table flex_slider">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Flex Slider', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_fs_animation"><?php _e('Animation', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Select your animation type', 'wplt'); ?></span>
                        </td>
                        <td>
                            <select id="wpl_theme_options_fs_animation" name="wpl_theme_options[fs_animation]">
                                <option
                                    value="fade" <?php echo((isset($this->theme_options['fs_animation']) and $this->theme_options['fs_animation'] == 'fade') ? 'selected="selected"' : '') ?>><?php _e('Fade', 'wplt'); ?></option>
                                <option
                                    value="slide" <?php echo((isset($this->theme_options['fs_animation']) and $this->theme_options['fs_animation'] == 'slide') ? 'selected="selected"' : '') ?>><?php _e('Slide', 'wplt'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_fs_slideshow"><?php _e('Slideshow', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Animate slider automatically', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_fs_slideshow"
                                   name="wpl_theme_options[fs_slideshow]" <?php echo((isset($theme_options['fs_slideshow']) and $theme_options['fs_slideshow'] == 1) ? 'checked="checked"' : '') ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_fs_controlnav"><?php _e('Control Navigation', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Create navigation for paging control of each slide', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_fs_controlnav"
                                   name="wpl_theme_options[fs_controlnav]" <?php echo((isset($theme_options['fs_controlnav']) and $theme_options['fs_controlnav'] == 1) ? 'checked="checked"' : '') ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_fs_directionnav"><?php _e('Direction Navigation', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Create navigation for previous/next navigation', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_fs_directionnav"
                                   name="wpl_theme_options[fs_directionnav]" <?php echo((isset($theme_options['fs_directionnav']) and $theme_options['fs_directionnav'] == 1) ? 'checked="checked"' : '') ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_fs_randomize"><?php _e('Randomize', 'wplt'); ?></label>
                            <span class="info_text"><?php _e('Randomize slide order', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="checkbox" id="wpl_theme_options_fs_randomize"
                                   name="wpl_theme_options[fs_randomize]" <?php echo((isset($theme_options['fs_randomize']) and $theme_options['fs_randomize'] == 1) ? 'checked="checked"' : ''); ?> />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_fs_slideshowspeed"><?php _e('SlideShow Speed', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Set the speed of the slideshow cycling, in milliseconds', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_fs_slideshowspeed"
                                   name="wpl_theme_options[fs_slideshowspeed]"
                                   value="<?php echo $this->isset_variable('fs_slideshowspeed', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_fs_animationduration"><?php _e('Animation Duration', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Set the speed of animations, in milliseconds', 'wplt'); ?></span>
                        </td>
                        <td>
                            <input type="text" id="wpl_theme_options_fs_fs_animationduration"
                                   name="wpl_theme_options[fs_animationduration]"
                                   value="<?php echo $this->isset_variable('fs_animationduration', $theme_options); ?>"
                                   class="long"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="wpl-28-config-uploader uploader">
                                <input class="button" name="flex_slider_button"
                                       id="wpl_theme_options_flex_slider_button"
                                       value="<?php _e('Add images', 'wplt'); ?>"/>
                                <input type="hidden" value="<?php echo $theme_options['flex_slider']; ?>"
                                       name="wpl_theme_options[flex_slider]" id="wpl_theme_options_flex_slider"/>
                            </div>
                            <div class="img_container">
                                <?php
                                if (isset($theme_options['flex_slider'])) {
                                    $flex_slider_images = explode(';', trim($theme_options['flex_slider'], '; '));
                                    foreach ($flex_slider_images as $key => $value) {
                                        if (trim($value) == '') continue;

                                        echo '<div class="flex_slider_imgs"><i class="glyphicon glyphicon-remove"></i><img src="' . $value . '" class="img_flex_slider" /></div>';
                                    }
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="css-options">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('CSS Options', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label for="wpl_theme_options_css_code"><?php _e('CSS codes', 'wplt'); ?></label>
                            <span
                                class="info_text"><?php _e('Place your CSS codes here. Note that they do not include any HTML tags, these CSS codes override your styles.', 'wplt'); ?></span>
                        </td>
                        <td>
                            <textarea id="wpl_theme_options_css_code" name="wpl_theme_options[css_code]"
                                      style="width: 600px; height: 400px;"><?php echo(isset($theme_options['css_code']) ? $theme_options['css_code'] : ''); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="panel-wp tab-pane fade" id="short-codes">
                <table class="wpl_theme_options_table">
                    <tr>
                        <th colspan="2">
                            <h3>
                                <?php _e('Short Codes', 'wplt'); ?>
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <label
                                for="wpl_theme_options_css_code"><code><?php _e('[wpl_fs]', 'wplt'); ?></code></label>
                        </td>
                        <td><?php _e('This code will generate the Flex Slider.', 'wplt'); ?></td>
                    </tr>
                </table>
            </div>

        </div>

    </form>

    <footer>
        <div class="logo"></div>
    </footer>

</div>