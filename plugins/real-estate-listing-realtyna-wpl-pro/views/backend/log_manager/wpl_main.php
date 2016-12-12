<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.logs');
_wpl_import('libraries.pagination');
_wpl_import('libraries.settings');

class wpl_log_manager_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.log_manager.tmpl';
	public $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
        
        // Create Nonce
        $this->nonce = wpl_security::create_nonce('wpl_log_manager');
        
		/* Select datemin and datemax */
		$this->select_log_datemin = trim(wpl_request::getVar('sf_datemin_log_date')) != '' ? trim(wpl_request::getVar('sf_datemin_log_date')) : '';
		$this->select_log_datemax = trim(wpl_request::getVar('sf_datemax_log_date')) != '' ? trim(wpl_request::getVar('sf_datemax_log_date')) : '';

		$this->addon = trim(wpl_request::getVar('sf_select_addon_id')) != '' ? wpl_request::getVar('sf_select_addon_id') : "";
		$this->text_search = trim(wpl_request::getVar('sf_text_section')) != '' ? wpl_request::getVar('sf_text_section') : '';

		/* Check and select priority for seach */
		if(trim(wpl_request::getVar('sf_select_priority')))
		{
			$this->priority = wpl_request::getVar('sf_select_priority');
			if($this->priority == 10)
			{
				wpl_request::setVar('sf_select_priority', '');
				wpl_request::setVar('sf_tmin_priority', 10);
				wpl_request::setVar('sf_tmax_priority', 1000);
				$this->priority = 10;
			}
		}
		else $this->priority = '';
		
		$this->admin_url = wpl_global::get_wp_admin_url();
		
		$possible_orders = array('log_date','addon_id','section','log_text','id','ip','priority');
		
		$orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$order = in_array(strtoupper(wpl_request::getVar('order')), array('ASC','DESC')) ? wpl_request::getVar('order') : 'DESC';
		
		$page_size = trim(wpl_request::getVar('page_size')) != '' ? wpl_request::getVar('page_size') : NULL;
		
		/** create where **/
		$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$where_query = wpl_db::create_query($vars);

		$num_result = wpl_db::num("SELECT COUNT(id) FROM `#__wpl_logs` WHERE 1 ".$where_query);
		
		$this->pagination = wpl_pagination::get_pagination($num_result, $page_size);
		$where_query .= " ORDER BY $orderby $order ".$this->pagination->limit_query;
		$this->wp_logs = wpl_logs::get_logs($where_query);
		
		/* Get all addons are installed */
		$this->addons = $this->get_addons();

		/* load library jquery ui datepicker from wordpress*/
		wp_enqueue_script('jquery-ui-datepicker');

		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}

	/**
	 * Get all addons
	 * @author Matthew N. <matthew@realtyna.com>
	 * @return return an array as object.
	 */
	public function get_addons()
	{
		return wpl_db::select('SELECT `id`, `name` FROM `#__wpl_addons`');
	}
}