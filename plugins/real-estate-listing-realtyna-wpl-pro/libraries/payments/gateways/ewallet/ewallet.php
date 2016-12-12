<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * e-Wallet Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.9.0
 * @date 26/10/2014
 */
class wpl_payments_ewallet extends wpl_payments implements wpl_payments_interface
{
    protected $gateway_label = 'e-Wallet';
    protected $gateway_id = 3;
    protected $gateway_key = 'ewallet';
    public $user_id = 0;
    
    public function __construct()
    {
        parent::__construct();
        $this->settings = $this->get_settings($this->gateway_key);
    }
    
    public function balance($user_id = NULL, $currency_id = 260)
    {
        if(is_null($user_id)) $user_id = $this->user_id;
        if(!$user_id) $user_id = wpl_users::get_cur_user_id();
        
        $plus = wpl_db::select("SELECT SUM(`amount_si`) AS sum FROM `#__wpl_transactions` WHERE `user_id`='$user_id' AND `mode`='1' AND `status`='1' AND `type`='ewallet'", 'loadResult');
        $minus = wpl_db::select("SELECT SUM(`amount_si`) AS sum FROM `#__wpl_transactions` WHERE `user_id`='$user_id' AND `mode`='2' AND `status`='1' AND `type`='ewallet'", 'loadResult');
        
        $balance = $plus-$minus;
        return wpl_units::convert($balance, 260, $currency_id);
    }
    
    public function charge($transaction_id)
    {
        $transactions = new wpl_transactions($transaction_id);
        $transaction = $transactions->get();
        
        /** trigger event **/
        wpl_global::event_handler('ewallet_charged', array('transaction_id'=>$transaction_id, 'user_id'=>$transaction['user_id'], 'amount'=>$transaction['amount'], 'unit_id'=>$transaction['unit_id']));
    }
    
    public function plus($transaction_id)
    {
        $transactions = new wpl_transactions($transaction_id);
        $transaction = $transactions->get();
        
        /** Create a new transaction **/
        $values = array
        (
            'user_id'=>$transaction['user_id'], 'mode'=>1, 'type'=>'ewallet', 'amount'=>$transaction['amount'],
            'unit_id'=>$transaction['unit_id'], 'title'=>$transaction['title'], 'description'=>$transaction['description'],
            'params'=>array('ref_transaction_id'=>$transaction_id)
        );
        
        $id = $transactions->create($values);
        
        $new_transaction = new wpl_transactions($id);
        $new_transaction->gateway($this->get_gateway_key());
        $new_transaction->update(array('gtid'=>$transaction['gtid'], 'response'=>$transaction['response']));
        $new_transaction->done(1);
        
        /** trigger event **/
        wpl_global::event_handler('ewallet_plus', array('transaction_id'=>$id, 'user_id'=>$transaction['user_id'], 'amount'=>$transaction['amount'], 'unit_id'=>$transaction['unit_id']));
        
        return $id;
    }
    
    public function minus($transaction_id)
    {
        $transactions = new wpl_transactions($transaction_id);
        $transaction = $transactions->get();
        
        /** Create a new transaction **/
        $values = array
        (
            'user_id'=>$transaction['user_id'], 'mode'=>2, 'type'=>'ewallet', 'amount'=>$transaction['amount'],
            'unit_id'=>$transaction['unit_id'], 'title'=>$transaction['title'], 'description'=>$transaction['description'],
            'params'=>array('ref_transaction_id'=>$transaction_id)
        );
        
        $id = $transactions->create($values);
        
        $new_transaction = new wpl_transactions($id);
        $new_transaction->gateway($this->get_gateway_key());
        $new_transaction->update(array('gtid'=>$transaction['gtid'], 'response'=>$transaction['response']));
        $new_transaction->done(1);
        
        /** trigger event **/
        wpl_global::event_handler('ewallet_minus', array('transaction_id'=>$id, 'user_id'=>$transaction['user_id'], 'amount'=>$transaction['amount'], 'unit_id'=>$transaction['unit_id']));
        
        return $id;
    }
    
    public function pay($transaction_id)
    {
        $transactions = new wpl_transactions($transaction_id);
        $transaction = $transactions->get();
        
        $balance = $this->balance($transaction['user_id'], $transaction['unit_id']);
        $enough = ($balance >= $transaction['amount']) ? true : false;
        
        /** Is balance is not enough for paying this transaction **/
        if(!$enough) return array('success'=>0, 'message'=>__('e-Wallet balance is low.', 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
                
        /** Set Gateway **/
        $transactions->gateway($this->get_gateway_key());
        
        $status = 1;
        $amount = $transaction['amount'];
        $unit_id = $transaction['unit_id'];
        
        /** Compare paid amount with transaction amount **/
        $valid = $this->validate($transaction_id, $amount, $status, $unit_id);
        
        $transactions->done(((int) $valid));
        
        if($valid) return array('success'=>1, 'message'=>__('Payment successfully done.', 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
        else return array('success'=>0, 'message'=>__('Payment is not valid!', 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
    }
}