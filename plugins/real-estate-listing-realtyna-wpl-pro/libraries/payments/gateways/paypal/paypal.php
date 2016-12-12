<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Paypal Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.9.0
 * @date 25/10/2014
 */
class wpl_payments_paypal extends wpl_payments implements wpl_payments_interface
{
    protected $gateway_label = 'Paypal';
    protected $gateway_id = 1;
    protected $gateway_key = 'paypal';
    
    public function __construct()
    {
        parent::__construct();
        $this->settings = $this->get_settings($this->gateway_key);
    }
    
    public function get_paypal_api_url($method = 1)
    {
        if($method == 1) /** Express Payment **/
        {
            $live = 'https://www.paypal.com/cgi-bin/webscr';
            $sandbox = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        elseif($method == 2) /** Direct Payment **/
        {
            $live = 'https://api-3t.paypal.com/nvp';
            $sandbox = 'https://api-3t.sandbox.paypal.com/nvp';
        }
        
        if($this->settings['mode'] == 0) $url = $sandbox;
        elseif($this->settings['mode'] == 1) $url = $live;
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_paypal_get_api_url', array('url'=>$url, 'mode'=>$this->settings['mode'], 'settings'=>$this->settings)));
        
        return $url;
    }
    
    public function get_paypal_cancel_url()
    {
        $url = $this->frontend('cancel');
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_paypal_cancel_url', array('url'=>$url)));
        
        return $url;
    }
    
    public function get_paypal_notify_url()
    {
        $url = $this->url('ipn');
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_paypal_notify_url', array('url'=>$url)));
        
        return $url;
    }
    
    public function get_paypal_return_url()
    {
        $url = $this->frontend('return');
        
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('payment_paypal_return_url', array('url'=>$url)));
        
        return $url;
    }
    
    public function do_direct_payment($transaction_id, $vars)
    {
        /** Check if Paypal is disabled **/
        if(!$this->settings['status']) return array('success'=>0, 'message'=>__('Paypal is disabled!', 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
        
        $card_type = (isset($vars['card_type']) ? $vars['card_type'] : NULL);
        $cc_number = (isset($vars['cc_number']) ? $vars['cc_number'] : NULL);
        $cvv2 = (isset($vars['cvv2']) ? $vars['cvv2'] : NULL);
        $first_name = (isset($vars['first_name']) ? $vars['first_name'] : NULL);
        $last_name = (isset($vars['last_name']) ? $vars['last_name'] : NULL);
        
        /** Check Card details **/
        if(!$card_type or !$cc_number or !$cvv2) return array('success'=>0, 'message'=>__('Card data is invalid.', 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
        
        $this->transactions = new wpl_transactions($transaction_id);
        $this->transaction = $this->transactions->get();
        
        /** Set Gateway **/
        $this->transactions->gateway($this->get_gateway_key());
        
        $expdate = $vars['expiration_date_month'].$vars['expiration_date_year'];
        $params = array
        (
            'METHOD' => 'DoDirectPayment',
            'USER' => $this->getter('username'),
            'PWD' => $this->getter('password'),
            'SIGNATURE' => $this->getter('signature'),
            'VERSION' => 90.0,
            'CREDITCARDTYPE' => $card_type,
            'ACCT' => $cc_number,
            'EXPDATE' => $expdate,
            'CVV2' => $cvv2,
            'FIRSTNAME' => $first_name,
            'LASTNAME' => $last_name,
            'AMT' => $this->getter('amount'),
            'CURRENCYCODE' => $this->getter('currency_code'),
            'DESC' => $this->getter('title')
        );
        
        $request_str = $this->get_request_string($params);
        $response_str = $this->get_paypal_response($request_str, 2);
        
        $results = $this->normalize_NVP($response_str);
        
        /** Store Gateway Response **/
        $this->response($results, $transaction_id);
        
        $status = strpos(strtolower($results['ACK']), 'success') !== false ? 1 : 0;
        $amount = $results['AMT'];
        $unit_id = wpl_units::id($results['CURRENCYCODE'], 'extra', 4);
        
        /** Compare paid amount with transaction amount **/
        $valid = $this->validate($transaction_id, $amount, $status, $unit_id);
        
        $this->transactions->done(((int) $valid));
        
        if($valid) return array('success'=>1, 'message'=>__('Payment successfully done.', 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
        elseif($status) return array('success'=>0, 'message'=>__('Payment done but not valid!', 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
        else return array('success'=>0, 'message'=>__($results['L_LONGMESSAGE0'], 'wpl'), 'data'=>array('gateway'=>$this->get_gateway_key()));
    }
    
    public function validate_express_payment($vars)
    {
        /** Check if Paypal is disabled **/
        if(!$this->settings['status']) return false;
        
        $custom = $this->decode_custom($vars['custom']);
        $transaction_id = $custom['transaction_id'];
        
        $this->transactions = new wpl_transactions($transaction_id);
        
        /** Set Gateway **/
        $this->transactions->gateway($this->get_gateway_key());
        
        $request_str = $this->get_request_string($vars).'&cmd=_notify-validate';
        $response_str = $this->get_paypal_response($request_str, 1);
        
        if(strpos($response_str, 'VERIFIED') !== false) $status = 1;
        else $status = 0;
        
        /** Store Gateway Response **/
        $this->response($vars, $transaction_id);
        
        $amount = $vars['mc_gross'];
        if(isset($vars['tax'])) $amount = ($vars['mc_gross']-$vars['tax']);
        
        $unit_id = wpl_units::id($vars['mc_currency'], 'extra', 4);
        
        /** Compare paid amount with transaction amount **/
        $valid = $this->validate($transaction_id, $amount, $status, $unit_id);
        
        $this->transactions->done(((int) $valid));
        
        if($valid) return true;
        elseif($status) return false;
        else return false;
    }
    
    public function get_paypal_response($request_str, $url_type = 2, $method = 'socket')
    {
        $method = strtolower($method);
        if(!in_array($method, array('socket', 'curl'))) $method = 'socket';
        
        if($method == 'curl')
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_URL, $this->get_paypal_api_url($url_type));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request_str);

            $results = curl_exec($curl);
            curl_close($curl);
        }
        elseif($method == 'socket')
        {
            $api_url = $this->get_paypal_api_url($url_type);
            $parsed_url = parse_url($api_url);
            $fp = fsockopen("ssl://".$parsed_url['host'], '443', $errNum, $errStr, 30);
            
            if(!$fp)
            {
                /** @TODO log error here **/
                return '';
            }
            else
            {
                fputs($fp, "POST ".$parsed_url['path']." HTTP/1.1\r\n");
                fputs($fp, "Host: ".$parsed_url['host']."\r\n");
                fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
                fputs($fp, "Content-length: ".strlen($request_str)."\r\n");
                fputs($fp, "Connection: close\r\n\r\n");
                fputs($fp, $request_str."\r\n\r\n");

                $results = '';
                while(!feof($fp)) $results .= fgets($fp, 1024);
                fclose($fp);
                
                list($header, $body) = preg_split("/\R\R/", $results, 2);
                $results = $body;
            }
        }
        
        return $results;
    }
    
    public function normalize_NVP($nvp_string)
    {
        $Array = array();
        while(strlen($nvp_string))
        {
            /** name **/
            $keypos = strpos($nvp_string, '=');
            $keyval = substr($nvp_string, 0, $keypos);
            
            /** value **/
            $valuepos = strpos($nvp_string, '&') ? strpos($nvp_string, '&') : strlen($nvp_string);
            $valval = substr($nvp_string, $keypos+1, $valuepos-$keypos-1);
            
            /** decoding the respose **/
            $Array[$keyval] = urldecode($valval);
            $nvp_string = substr($nvp_string, $valuepos+1, strlen($nvp_string));
        }
        
        return $Array;
    }
    
    /**
     * Decodes custom parameter
     * @author Howard <howard@realtyna.com>
     * @param string $encoded
     * @return array
     */
    public function decode_custom($encoded)
    {
        $base64 = urldecode($encoded);
        $json = base64_decode($base64);
        return json_decode($json, true);
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
        
        $gtid = isset($response['TRANSACTIONID']) ? $response['TRANSACTIONID'] : NULL;
        if(is_null($gtid)) $gtid = isset($response['txn_id']) ? $response['txn_id'] : NULL;
        
        return $transactions->update(array('response'=>json_encode($response), 'gtid'=>$gtid));
    }
}