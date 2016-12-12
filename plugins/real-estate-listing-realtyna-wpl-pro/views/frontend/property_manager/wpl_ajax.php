<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_property_manager_controller extends wpl_controller
{
    public function display()
    {
        $function = wpl_request::getVar('wpl_function');
    }
    
    private function response($response)
    {
        echo json_encode($response);
        exit;
    }
}