<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Bank Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.9.0
 * @date 25/10/2014
 */
class wpl_payments_bank extends wpl_payments implements wpl_payments_interface
{
    protected $gateway_label = 'Bank';
    protected $gateway_id = 2;
    protected $gateway_key = 'bank';
    
    public function __construct()
    {
        parent::__construct();
        $this->settings = $this->get_settings($this->gateway_key);
    }
    
    public function add_receipt($transaction_id, $vars)
    {
        /** Check if Paypal is disabled **/
        if(!$this->settings['status']) return array('success'=>0, 'message'=>__('Bank is disabled!', 'wpl'), 'data'=>NULL);
        
        $receipt = (isset($vars['receipt']) ? $vars['receipt'] : NULL);
        
        $this->transactions = new wpl_transactions($transaction_id);
        $this->transaction = $this->transactions->get();
        
        /** Set Gateway **/
        $this->transactions->gateway($this->get_gateway_key());
        
        /** Save Bank Receipt **/
        $this->transactions->update(array('gtid'=>$receipt, 'params'=>json_encode(array('submit_date'=>date('Y-m-d H:i:s')))));
        
        /** trigger event **/
        wpl_global::event_handler('bank_receipt_received', array('id'=>$transaction_id));
            
        /** Auto Confirm **/
        if(isset($this->settings['auto_confirm']) and $this->settings['auto_confirm'])
        {
            $this->transactions->done(1);
            
            /** trigger event **/
            wpl_global::event_handler('bank_receipt_confirmed', array('id'=>$transaction_id));
        
            return array('success'=>1, 'message'=>__('Your bank receipt received and confirmed automatically!', 'wpl'), 'data'=>array('waiting'=>0, 'gateway'=>$this->get_gateway_key()));
        }
        
        return array('success'=>1, 'message'=>__('Your bank receipt received successfully. We will check it soon.', 'wpl'), 'data'=>array('waiting'=>1, 'gateway'=>$this->get_gateway_key()));
    }
    
    public function confirm_bank_receipt($transaction_id, $value)
    {
        $transactions = new wpl_transactions($transaction_id);
        $response = $transactions->done($value);
        
        /** trigger event **/
        wpl_global::event_handler('bank_receipt_checked', array('transaction_id'=>$transaction_id, 'confirmed'=>$value));
        
        return $response;
    }
}