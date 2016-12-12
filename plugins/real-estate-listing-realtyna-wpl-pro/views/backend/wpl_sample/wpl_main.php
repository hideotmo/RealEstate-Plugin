<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.settings');
_wpl_import('libraries.flex');

class wpl_wpl_sample_controller extends wpl_controller
{
	var $tpl_path = 'views.backend.wpl_sample.tmpl';
	var $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$this->listings = wpl_global::get_listings();
		$this->property_types = wpl_global::get_property_types();
		$this->users = wpl_users::get_wpl_users();
        $this->kinds = wpl_flex::get_kinds();
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
}