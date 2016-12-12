<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.payments.payments');

abstract class wpl_payments_controller_abstract extends wpl_controller
{
	public $tpl_path = 'views.frontend.payments.tmpl';
	public $tpl;
	public $model;
	public $kind;
	
	public function display($instance = array())
	{
        /** Set tpl to NULL **/
        if(wpl_request::getVar('tpl') == 'default') wpl_request::setVar('tpl', NULL); 
        
        $this->method = wpl_request::getVar('wplmethod', 'cancel');
        
        /** global settings **/
		$this->settings = wpl_settings::get_settings();
        
		/** membership model **/
		$this->model = new wpl_users();
        
        if($this->method == 'return') $output = $this->comeback();
        elseif($this->method == 'cancel') $output = $this->cancel();
        elseif($this->method == 'checkout') $output = $this->checkout();
        
        if($this->wplraw)
        {
            echo $output;
            exit;
        }
        else
        {
            /** Return **/
            return $output;
        }
	}
    
    private function comeback()
    {
        $this->tpl = wpl_request::getVar('tpl', 'return');
        
        /** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
    }
    
    private function cancel()
    {
        $this->tpl = wpl_request::getVar('tpl', 'cancel');
        
        /** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
    }
    
    private function checkout()
    {
        $this->tpl = wpl_request::getVar('tpl', 'checkout');
        
        $this->transaction_id = wpl_request::getVar('transaction_id', 0);
        if(!$this->transaction_id or ($this->transaction_id and !wpl_db::exists($this->transaction_id, 'wpl_transactions')))
        {
            /** import message tpl **/
			$this->message = __("Transaction is invalid!", 'wpl');
			return parent::render($this->tpl_path, 'message', false, true);
        }
        
        $this->transactions = new wpl_transactions($this->transaction_id);
        $this->transaction = $this->transactions->get();
        
        if(isset($this->transaction['status']) and $this->transaction['status'] != 2)
        {
            /** import message tpl **/
			$this->message = __("The Transaction is paid or failed before!", 'wpl');
			return parent::render($this->tpl_path, 'message', false, true);
        }
        
        $amount = wpl_render::render_price($this->transaction['amount'], $this->transaction['unit_id']);
        
        // Apply filters
		_wpl_import('libraries.filters');
		extract(wpl_filters::apply('wpl_checkout_amount', array('amount'=>$amount, 'transaction'=>$this->transaction)));
        
        $this->amount = $amount;
        $this->system = json_decode($this->transaction['system'], true);
            
        $disabled_gateways = wpl_request::getVar('disabled_gateways', '');
        
        $this->disabled_gateways = array();
        if(trim($disabled_gateways)) $this->disabled_gateways = explode(',', $disabled_gateways);
        
        $this->payments = new wpl_payments();
        $this->settings = $this->payments->get_settings();
        $gateways = $this->payments->gateways;
        
        foreach($gateways as $gateway)
        {
            $obj = $this->payments->get($gateway);
            
            /** Skip disabled gateways **/
            if(!isset($obj->settings['status']) or (isset($obj->settings['status']) and !$obj->settings['status'])) continue;
            if(in_array($gateway, $this->disabled_gateways)) continue;
            
            $this->gateways[$gateway] = $obj;
        }
        
        /** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
    }
}