<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.addon_pro');

/**
 * PRO service
 * @author Howard <howard@realtyna.com>
 * @date 12/21/2013
 * @package WPL PRO
 */
class wpl_service_addon_pro
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
		/** WPL sitemap **/
        $wpl_sitemap = wpl_global::get_setting('wpl_sitemap');
        if($wpl_sitemap == 'google-sitemap-generator')
        {
            add_action('sm_build_index', array('wpl_addon_pro', 'add_sitemaps'), 10, 1);
            add_action('sm_build_content', array('wpl_addon_pro', 'add_sitemaps_links'), 10, 1);
        }
        elseif($wpl_sitemap == 'yoast-seo')
        {
            $yoast_options = get_option('wpseo_xml');
            
            /** Check if xml sitemap module is activated **/
            if($yoast_options && $yoast_options['enablexmlsitemap'])
            {
                add_filter('wpseo_sitemap_index', array('wpl_addon_pro', 'add_sitemaps'), 10, 1);
                
                /* Must prevent Yoast transinet caching in order to add new sitemap(s) */
                add_filter('wpseo_enable_xml_sitemap_transient_caching', '__return_false');
                wpl_addon_pro::register_sitemaps_yoast();
            }
        }
        elseif($wpl_sitemap == 'all-in-one-seo' && function_exists('aioseop_init_class'))
        {
            $aioseop_options = get_option('aioseop_options');

            /** Check if xml sitemap module is activated **/
            if($aioseop_options && trim($aioseop_options['modules']['aiosp_feature_manager_options']['aiosp_feature_manager_enable_sitemap']) == 'on')
            {
                $aioseop_sitemap_options = $aioseop_options['modules']['aiosp_sitemap_options'];

                if(trim($aioseop_sitemap_options['aiosp_sitemap_indexes']) == 'on')
                {
                    add_filter('aiosp_sitemap_data', array('wpl_addon_pro', 'add_sitemaps'), 10, 1);
                }

                add_filter('aiosp_sitemap_data', array('wpl_addon_pro', 'add_sitemaps_links'), 10, 1);
            }
        }

        /** WPML Language Switcher **/
        if(wpl_global::check_multilingual_status()) add_filter('icl_ls_languages', array('wpl_addon_pro', 'wpml_language_switcher'));
        
        /** Load WPL Customizer Style on frontend **/
        if(wpl_global::get_setting('wpl_ui_customizer_status', 11) and !wpl_global::get_client()) add_action('wp_enqueue_scripts', array($this, 'import_wpl_customizer_assets'), 9);
        
        /** Load WPL Custom Style on frontend **/
        if(trim(wpl_global::get_setting('wpl_ui_customizer_styles', 11)) and !wpl_global::get_client()) add_action('wp_head', array($this, 'import_wpl_custom_styles'), 10);
        
        // Run Theme compatibility option
        if(wpl_global::get_setting('wpl_theme_compatibility', 11)) add_action('wp_enqueue_scripts', array($this, 'theme_compatibility'), 8);
        
        // Add User favorites from COOKIE to Database after login/register
        add_action('user_register', array('wpl_addon_pro', 'favorites_sync'), 10, 1);
        add_action('wp_login', array('wpl_addon_pro', 'favorites_sync'), 10, 1);
        add_action('delete_user', array('wpl_addon_pro', 'favorites_user_delete'), 10, 1);
        
        // Unit Switcher
        if(wpl_request::getVar('wpl_unit_switcher', 0))
        {
            $this->unit_switcher();
        }
	}
    
    public function import_wpl_customizer_assets()
    {
        $css_file = 'css/ui_customizer/wpl.css';
        
        // Make WPL UI Customizer multisite support
        $current_blog_id = wpl_global::get_current_blog_id();
        if($current_blog_id and $current_blog_id != 1) $css_file = 'css/ui_customizer/wpl'.$current_blog_id.'.css';
        
        $JSON = wpl_global::get_setting('wpl_ui_customizer');
        $ui_customizer = json_decode($JSON, true);
        
        wpl_extensions::import_style((object) array('param1'=>'wpl_ui_customizer_fonts', 'param2'=>'//fonts.googleapis.com/css?family='.$ui_customizer['main_font'].'|'.$ui_customizer['sec_font'], 'external'=>true));
        wpl_extensions::import_style((object) array('param1'=>'wpl_ui_customizer_style', 'param2'=>$css_file));
    }
    
    public function import_wpl_custom_styles()
    {
        $custom_styles = wpl_global::get_setting('wpl_ui_customizer_styles', 11);
        
        echo '<style type="text/css">';
        echo strip_tags($custom_styles);
		echo '</style>';
    }
    
    public function theme_compatibility()
    {
        $style = NULL;
        $js = NULL;
        $current_theme = get_option('template');
        
        if($current_theme == 'bridge')
        {
            $style = 'styles/bridge/main.css';
            $js = 'styles/bridge/main.min.js';
        }
        elseif($current_theme == 'avada') $style = NULL;
        elseif($current_theme == 'enfold') $style = NULL;
        elseif($current_theme == 'betheme') $style = NULL;
        elseif($current_theme == 'x') $style = NULL;
        
        if(!is_null($style)) wpl_extensions::import_style((object) array('param1'=>'wpl_theme_compatibility_style', 'param2'=>$style));
        if(!is_null($js)) wpl_extensions::import_javascript((object) array('param1'=>'wpl_theme_compatibility_js', 'param2'=>$js));
    }
    
    public function unit_switcher()
    {
        $unit_id = wpl_request::getVar('wpl_unit_switcher');
        $unit = wpl_units::get_unit($unit_id);
        
        // Unit is not exists or it's disabled
        if(!isset($unit['id']) or (isset($unit['enabled']) and !$unit['enabled'])) return false;
        $type = $unit['type'];
        
        setcookie('wpl_unit'.$type, $unit_id, time()+(86400*30), '/');
        
        $url = wpl_global::remove_qs_var('wpl_unit_switcher', wpl_global::add_qs_var('c', mt_rand(1000, 9999)));
        wpl_global::redirect($url);
    }
}