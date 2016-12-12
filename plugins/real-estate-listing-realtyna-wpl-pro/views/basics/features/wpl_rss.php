<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.settings');
_wpl_import('libraries.addon_pro');

class wpl_features_controller extends wpl_controller
{
	public $tpl_path = 'views.basics.features.tmpl';
	public $tpl;
	
	public function display()
	{
		if(!wpl_global::check_addon('pro'))
        {
            echo __('PRO Addon must be installed for this!', 'wpl');
            exit;
        }

		$function = wpl_request::getVar('wpl_function', 'show');
		if($function == 'show') $this->show();
	}
	
	private function show()
	{
		$this->tpl = 'rss';
		$this->settings = wpl_settings::get_settings(1);

		/** RSS Information **/
		$this->rss = array();
		$this->rss['title'] = __($this->settings['listings_rss_title'], 'wpl');
		$this->rss['link'] = wpl_global::get_wp_site_url();
		$this->rss['desc'] = __($this->settings['listings_rss_description'], 'wpl');

		/** Get Properties **/
		wpl_request::setVar('wplmethod', 'get_listings');
        
        _wpl_import('views.frontend.property_listing.wpl_get');
        $model = new wpl_property_listing_controller();
        $this->properties = $model->display();

        if(!is_array($this->properties))
        {
        	echo __('Error loading RSS feed!', 'wpl');
        	exit;
        }
        
        /** import tpl **/
		echo parent::render($this->tpl_path, $this->tpl, false, true);
		exit;
	}
}