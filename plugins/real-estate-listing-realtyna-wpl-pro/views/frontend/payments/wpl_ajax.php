<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.payments.payments');
_wpl_import('libraries.settings');

class wpl_payments_controller extends wpl_controller
{
    public $tpl_path = 'views.frontend.payments.tmpl';

    public function display()
    {
        $function = wpl_request::getVar('wpl_function');
        
        if($function == 'ewallet_charge') $this->ewallet_charge();
        elseif($function == 'paypal_direct') $this->paypal_direct();
        elseif($function == 'bank_receipt') $this->bank_receipt();
        elseif($function == 'payment_express_check') $this->payment_express_check();
        elseif($function == 'ewallet_pay') $this->ewallet_pay();
    }
    
    private function ewallet_charge()
    {
        // WPL Security nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_payments_ewallet_charge')) $this->response(array('success'=>0, 'message'=>__('The security nonce is not valid!', 'wpl'), 'data'=>array()));
        
        $payments = new wpl_payments();
        $ewallet = $payments->get('ewallet');
        
        $transaction_id = $this->insert_transaction(true);
        $transactions = new wpl_transactions($transaction_id);
        
        $system = array
        (
            'callbacks' => array
            (
                0 => array
                (
                    'class_path'=>$ewallet->wplpath($ewallet->get_gateway_key()),
                    'class_name'=>$ewallet->get_gateway_class_name(),
                    'function_name'=>'charge',
                    'function_params'=>array($transaction_id)
                )
            ),
            'javascript' => array
            (
                'success'=>array('wpl_ewallet_payment_success(response);'),
                'error'=>array('wpl_ewallet_payment_error(response);')
            )
        );
        
        $values = array('system'=>json_encode($system));
        $transactions->update($values);
        
        $res = 1;
		$message = $res ? __('Saved.', 'wpl') : __('Error Occured.', 'wpl');
		$data = array('transaction_id'=>$transaction_id);
        
        $this->response(array('success'=>$res, 'message'=>$message, 'data'=>$data));
    }
    
    private function insert_transaction($return = false)
    {
        $transactions = new wpl_transactions(0);
        
        $values = array();
        $values['user_id'] = wpl_request::getVar('user_id', wpl_users::get_cur_user_id());
        $values['mode'] = wpl_request::getVar('mode', 0);
        $values['type'] = wpl_request::getVar('type', 'ewallet');
        
        $amount = wpl_request::getVar('amount', 0);
        $unit_id = wpl_request::getVar('unit_id', 260);
        
        $values['amount'] = $amount;
        $values['unit_id'] = $unit_id;
        $values['amount_si'] = wpl_units::convert($amount, $unit_id, 260);
        
        $values['status'] = 2;
        $values['creation_date'] = date('Y-m-d H:i:s');
        $values['title'] = wpl_request::getVar('title', NULL);
        $values['description'] = wpl_request::getVar('description', NULL);
        $values['params'] = wpl_request::getVar('params', array());
        
        $id = $transactions->create($values);
        
        /** return **/
        if($return) return $id;
        
        $res = 1;
		$message = $res ? __('Saved.', 'wpl') : __('Error Occured.', 'wpl');
		$data = array('transaction_id'=>$id);
        
        $this->response(array('success'=>$res, 'message'=>$message, 'data'=>$data));
    }
    
    private function paypal_direct()
    {
        // WPL Security nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_payments_paypal_direct')) $this->response(array('success'=>0, 'message'=>__('The security nonce is not valid!', 'wpl'), 'data'=>array()));
        
        $transaction_id = wpl_request::getVar('transaction_id', 0);
        $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
        
        $payments = new wpl_payments();
        $paypal = $payments->get('paypal');
        
        $response = $paypal->do_direct_payment($transaction_id, $vars);
        $this->response($response);
    }
    
    private function bank_receipt()
    {
        // WPL Security nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_payments_bank_receipt')) $this->response(array('success'=>0, 'message'=>__('The security nonce is not valid!', 'wpl'), 'data'=>array()));
        
        $transaction_id = wpl_request::getVar('transaction_id', 0);
        $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
        
        $payments = new wpl_payments();
        $bank = $payments->get('bank');
        
        $response = $bank->add_receipt($transaction_id, $vars);
        $this->response($response);
    }
    
    private function payment_express_check()
    {
        // WPL Security nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_payments_payment_express_check')) $this->response(array('success'=>0, 'message'=>__('The security nonce is not valid!', 'wpl'), 'data'=>array()));
        
        $transaction_id = wpl_request::getVar('transaction_id', 0);
        
        $transactions = new wpl_transactions($transaction_id);
        $transaction = $transactions->get();
        
        if($transaction['status'] == 1) $message = __('Transaction paid.', 'wpl');
        elseif($transaction['status'] == 2) $message = __('Waiting for response from gateway.', 'wpl');
        elseif($transaction['status'] == 0) $message = __('Transaction failed.', 'wpl');
        
        $this->response(array('success'=>$transaction['status'], 'message'=>$message, 'data'=>array()));
    }
    
    private function ewallet_pay()
    {
        // WPL Security nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_payments_ewallet_pay')) $this->response(array('success'=>0, 'message'=>__('The security nonce is not valid!', 'wpl'), 'data'=>array()));
        
        $payments = new wpl_payments();
        $ewallet = $payments->get('ewallet');
        
        $transaction_id = wpl_request::getVar('transaction_id', 0);
        $response = $ewallet->pay($transaction_id);
        
        $this->response($response);
    }
    
    private function response($response)
    {
        echo json_encode($response);
        exit;
    }
}