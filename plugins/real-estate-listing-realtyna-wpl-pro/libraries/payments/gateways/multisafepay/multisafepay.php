<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

// Import MultiSafePay PHP client
_wpl_import('libraries.payments.gateways.multisafepay.API.client');

/**
 * MultiSafePay Library
 * @author Howard <howard@realtyna.com>
 * @since WPL3.0.1
 * @date 13/05/2016
 */
class wpl_payments_multisafepay extends wpl_payments implements wpl_payments_interface
{
    protected $gateway_label = 'MultiSafePay';
    protected $gateway_id = 4;
    protected $gateway_key = 'multisafepay';
    
    public function __construct()
    {
        parent::__construct();
        $this->settings = $this->get_settings($this->gateway_key);
    }
    
    public function get_multisafepay_api_url()
    {
        $live = 'https://api.multisafepay.com/v1/json/';
        $sandbox = 'https://testapi.multisafepay.com/v1/json/';
        
        if($this->settings['mode'] == 0) $url = $sandbox;
        elseif($this->settings['mode'] == 1) $url = $live;
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_multisafepay_get_api_url', array('url'=>$url, 'mode'=>$this->settings['mode'], 'settings'=>$this->settings)));
        
        return $url;
    }
    
    public function get_multisafepay_cancel_url()
    {
        $url = $this->frontend('cancel');
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_multisafepay_cancel_url', array('url'=>$url)));
        
        return $url;
    }
    
    public function get_multisafepay_notify_url()
    {
        $url = $this->url('ipn');
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_multisafepay_notify_url', array('url'=>$url)));
        
        return $url;
    }
    
    public function get_multisafepay_return_url()
    {
        $url = $this->frontend('return');
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_multisafepay_return_url', array('url'=>$url)));
        
        return $url;
    }
    
    public function get_api_key()
    {
        return isset($this->settings['api_key']) ? $this->settings['api_key'] : '';
    }
    
    public function create_transaction()
    {
        $user_id = isset($this->transaction['user_id']) ? $this->transaction['user_id'] : wpl_users::get_cur_user_id();
        $user_data = wpl_users::get_user($user_id);

        $msp = $this->getMSP();
        $msp->orders->post(array
        (
            "type"=>"redirect",
            "order_id"=>$this->transaction_id,
            "currency"=>$this->getter('currency_code'),
            "amount"=>$this->getter('amount')*100,
            "description"=>$this->getter('item_name'),
            "var"=>$this->getter('custom'),
            "manual"=>false,
            "gateway"=>"",
            "days_active"=>"30",
            "payment_options"=>array
            (
                "notification_url"=>$this->get_multisafepay_notify_url(),
                "redirect_url"=>$this->get_multisafepay_return_url(),
                "cancel_url"=>$this->get_multisafepay_cancel_url(),
                "close_window"=>true
            ),
            "customer"=>array
            (
                "ip_address"=>wpl_users::get_current_ip(),
                "first_name"=>(isset($user_data->wpl_data) ? $user_data->wpl_data->first_name : ''),
                "last_name"=>(isset($user_data->wpl_data) ? $user_data->wpl_data->last_name : ''),
                "email"=>$user_data->data->user_email,
            ),
            "custom_info"=>$this->getter('custom')
        ));

        return $msp->orders->getPaymentLink();
    }
    
    public function validate_express_payment($transaction_id)
    {
        /** Check if Paypal is disabled **/
        if(!$this->settings['status']) return false;
        
        $msp = $this->getMSP();
        $order = $msp->orders->get('orders', $transaction_id);
        
        $this->transactions = new wpl_transactions($transaction_id);
        
        /** Set Gateway **/
        $this->transactions->gateway($this->get_gateway_key());
        
        $status = (strtolower($order->status) == 'completed' ? 1 : 0);
        
        /** Store Gateway Response **/
        $this->response((array) $order, $transaction_id);
        
        $amount = ($order->amount/100);
        $unit_id = wpl_units::id($order->currency, 'extra', 4);
        
        /** Compare paid amount with transaction amount **/
        $valid = $this->validate($transaction_id, $amount, $status, $unit_id);
        
        $this->transactions->done(((int) $valid));
        
        if($valid) return true;
        elseif($status) return false;
        else return false;
    }
    
    public function getMSP()
    {
        $msp = new wpl_payments_multisafepay_client();
        $msp->setApiKey($this->get_api_key());
        $msp->setApiUrl($this->get_multisafepay_api_url());
        
        return $msp;
    }
    
    /**
     * Stores Gateway response and Gateway Transaction ID (GTID)
     * @author Howard <howard@realtyna.com>
     * @param array $response
     * @param int $transaction_id
     * @return mixed
     */
    public function response($response, $transaction_id)
    {
        /** response must be array **/
        if(!is_array($response) or !trim($transaction_id)) return false;
        
        $transactions = new wpl_transactions($transaction_id);
        $gtid = isset($response['transaction_id']) ? $response['transaction_id'] : NULL;
        
        return $transactions->update(array('response'=>json_encode($response), 'gtid'=>$gtid));
    }
}