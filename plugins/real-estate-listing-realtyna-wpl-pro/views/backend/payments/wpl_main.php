<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.payments.payments');
_wpl_import('libraries.pagination');

class wpl_payments_controller extends wpl_controller
{
    public $tpl_path = 'views.backend.payments.tmpl';
    public $tpl;

    public function home()
    {
        /** check permission **/
        wpl_global::min_access('administrator');
        $this->tpl = wpl_request::getVar('tpl', 'default');
        
        // Create Nonce
        $this->nonce = wpl_security::create_nonce('wpl_payments');
        
        $this->model = new wpl_payments();
        $this->gateways = $this->model->gateways;
        
        $this->objects = array();
        foreach($this->gateways as $gateway)
        {
            $this->objects[$gateway] = $this->model->get($gateway);
        }
        
        /** Transactions **/
        if($this->tpl == 'transactions') $this->transactions();
        
        parent::render($this->tpl_path, $this->tpl);
    }
    
    protected function include_tabs()
    {
        /** include the layout **/
		parent::render($this->tpl_path, 'internal_tabs');
    }
    
    protected function transactions()
    {
        $this->page_number = wpl_request::getVar('wplpage', 1);
        
        $limit = wpl_request::getVar('limit', 100);
        $start = wpl_request::getVar('start', (($this->page_number-1)*$limit));

        $possible_orders = array('creation_date','user_id','amount','status','id','paid_date','gtid');

        $orderby = in_array(wpl_request::getVar('orderby', 'id'), $possible_orders) ? wpl_request::getVar('orderby', 'id') : $possible_orders[0];
        $order = in_array(strtoupper(wpl_request::getVar('order', 'DESC')), array('ASC','DESC')) ? wpl_request::getVar('order', 'DESC') : 'DESC';

        /** create where **/
        $this->vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
        
        /** Set gateway **/
        $this->gateway = wpl_request::getVar('sf_select_gateway', 'ewallet');
        if(!isset($this->vars['sf_select_gateway']) or (isset($this->vars['sf_select_gateway']) and !trim($this->vars['sf_select_gateway']))) $this->vars['sf_select_gateway'] = $this->gateway;
        
        /** Set Filter **/
        $filter_query = '';
        $this->filter = wpl_request::getVar('filter', NULL);
        
        if(trim($this->filter)) $filter_query = " AND (`gtid` LIKE '%".$this->filter."%' OR `title` LIKE '%".$this->filter."%' OR `description` LIKE '%".$this->filter."%')";
        $where_query = wpl_db::create_query($this->vars).$filter_query;

        $num_result = wpl_db::num("SELECT COUNT(id) FROM `#__wpl_transactions` WHERE 1 ".$where_query);

        $this->pagination = wpl_pagination::get_pagination($num_result, $limit);
        $where_query .= " ORDER BY $orderby $order ".$this->pagination->limit_query;

        $this->transactions = wpl_db::select("SELECT * FROM `#__wpl_transactions` WHERE 1 ".$where_query, 'loadObjectList');
        
        $transaction_tmpl = $this->tpl_path.'.transactions.'.$this->gateway;
        $this->transaction_path = _wpl_import($transaction_tmpl, true, true);
        if(!wpl_file::exists($this->transaction_path)) $this->transaction_path = _wpl_import($this->tpl_path.'.transactions.default', true, true);
        
        $this->status_labels = array('0'=>'Failed', '1'=>'Succeed', '2'=>'Waiting');
    }
}