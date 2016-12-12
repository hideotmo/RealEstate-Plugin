<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_payments_multisafepay_object_issuers extends wpl_payments_multisafepay_object_core
{
    public $success;
    public $data;

    public function get($endpoint = 'issuers', $type = 'ideal', $body = array(), $query_string = false)
    {
        $result = parent::get($endpoint, $type, $body, $query_string);
        $this->success = $result->success;
        $this->data = $result->data;

        return $this->data;
    }
}
