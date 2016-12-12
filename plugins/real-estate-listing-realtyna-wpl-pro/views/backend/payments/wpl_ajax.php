<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.payments.payments');
_wpl_import('libraries.settings');

class wpl_payments_controller extends wpl_controller
{
    public $tpl_path = 'views.backend.payments.tmpl';

    public function display()
    {
        /** check permission **/
        wpl_global::min_access('administrator');
        $function = wpl_request::getVar('wpl_function');
        
        // Check Nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_payments')) $this->response(array('success'=>0, 'message'=>__('The security nonce is not valid!', 'wpl')));
        
        if($function == 'save_settings') $this->save_settings();
        elseif($function == 'confirm_bank_receipt') $this->confirm_bank_receipt();
    }
    
    private function save_settings()
    {
        $settings = wpl_request::getVar('settings', array());
        $encoded = json_encode($settings);
        
        $res = wpl_settings::save_setting('wpl_payments', $encoded);
        
        $res = 1;
		$message = $res ? __('Saved.', 'wpl') : __('Error Occured.', 'wpl');
		$data = NULL;
        
        $this->response(array('success'=>$res, 'message'=>$message, 'data'=>$data));
    }
    
    private function confirm_bank_receipt()
    {
        $transaction_id = wpl_request::getVar('transaction_id', 0);
        $value = wpl_request::getVar('value', 0);
        
        $payments = new wpl_payments();
        
        $bank = $payments->get('bank');
        $bank->confirm_bank_receipt($transaction_id, $value);
        
        $this->response(array('success'=>1));
    }
    
    private function response($response)
    {
        echo json_encode($response);
        exit;
    }
}