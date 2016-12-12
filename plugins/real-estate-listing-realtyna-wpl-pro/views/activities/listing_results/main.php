<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.addon_aps');

/** activity class **/
class wpl_activity_main_listing_results extends wpl_activity
{
    public $tpl_path = 'views.activities.listing_results.tmpl';
	
	public function start($layout, $params)
	{
        // Results Links
        $search_url = wpl_session::get('wpl_last_search_url');
        if(!trim($search_url)) return;
        
        $where = (array) wpl_session::get('wpl_listing_criteria');
        $orderby = wpl_session::get('wpl_listing_orderby');
        $order = wpl_session::get('wpl_listing_order');
        $total = wpl_session::get('wpl_listing_total');
        
        // generate where condition
		$where = wpl_db::create_query($where);
        
		/** include layout **/
		include _wpl_import($layout, true, true);
	}
}