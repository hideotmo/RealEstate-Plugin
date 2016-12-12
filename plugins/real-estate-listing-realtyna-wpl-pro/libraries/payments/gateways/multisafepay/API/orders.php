<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_payments_multisafepay_object_orders extends wpl_payments_multisafepay_object_core
{
    public $success;
    public $data;

    public function patch($body, $endpoint = '')
    {
        $result = parent::patch(json_encode($body), $endpoint);
        $this->success = $result->success;
        $this->data = $result->data;
        
        return $result;
    }

    public function get($type = 'orders', $id, $body = array(), $query_string = false)
    {
        $result = parent::get($type, $id, $body, $query_string);
        $this->success = $result->success;
        $this->data = $result->data;
        
        return $this->data;
    }

    public function post($body, $endpoint = 'orders')
    {
        $result = parent::post(json_encode($body), $endpoint);
        $this->success = $result->success;
        $this->data = $result->data;
        
        return $this->data;
    }

    public function getPaymentLink()
    {
        return $this->data->payment_url;
    }
}