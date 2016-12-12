<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.addon_mls');
_wpl_import('libraries.pagination');
_wpl_import('libraries.flex');

class wpl_addon_mls_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.addon_mls.tmpl';
	public $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$this->mls_servers = wpl_addon_mls::get_servers();
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function mapping()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$this->tpl = 'mapping';
		$this->server_id = wpl_request::getVar('server_id', '0');
		$this->mls_class_id = wpl_request::getVar('mls_class_id', '0');
		$this->mls_field_filter = wpl_request::getVar('mls_field_filter', '');
		$this->mls_servers = wpl_addon_mls::get_servers();
		$this->mls_server = wpl_addon_mls::get_servers($this->server_id);
		$this->classes = wpl_addon_mls::get_classes($this->server_id);
        $this->dbst_types = wpl_flex::get_dbst_types();
		
		$possible_orders = array('id', 'field_name', 'field_type', 'wpl_field_id', 'field_sample_data');
		
		$orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$order = in_array(strtoupper(wpl_request::getVar('order')), array('ASC','DESC')) ? wpl_request::getVar('order') : 'ASC';
		
		$page_size = trim(wpl_request::getVar('page_size')) != '' ? wpl_request::getVar('page_size') : 30;
		
		/** create where **/
		$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$vars = array_merge($vars, array('sf_select_mls_server_id'=>$this->server_id, 'sf_select_mls_class_id'=>$this->mls_class_id));
		$where_query = wpl_db::create_query($vars);
		
		if(trim($this->mls_field_filter) != '') $where_query .= " AND (`field_name` LIKE '%".$this->mls_field_filter."%' OR `field_sample_data` LIKE '%".$this->mls_field_filter."%')";
		$num_result = wpl_db::num("SELECT COUNT(id) FROM `#__wpl_addon_mls_mappings` WHERE 1 ".$where_query);
		
		$this->pagination = wpl_pagination::get_pagination($num_result, $page_size);
		$where_query .= " ORDER BY $orderby $order ".$this->pagination->limit_query;
		
		$this->fields = wpl_addon_mls::get_fields(NULL, $where_query);
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function query()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$this->tpl = wpl_request::getVar('tpl', 'query');
		$this->mls_queries = wpl_addon_mls::get_mls_queries();
		
		if($this->tpl == 'import')
		{
			$this->id = wpl_request::getVar('id', 0);
			$this->query_data = wpl_addon_mls::get_mls_queries($this->id);
			$this->mls_server_data = wpl_addon_mls::get_servers($this->query_data->mls_server_id);
			
			/** update select text of mls query **/
			wpl_addon_mls::generate_selects_query($this->id);
		}
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
}