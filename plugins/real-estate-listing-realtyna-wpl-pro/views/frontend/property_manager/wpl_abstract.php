<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.activities');

abstract class wpl_property_manager_controller_abstract extends wpl_controller
{
	public $tpl_path = 'views.frontend.property_manager.tmpl';
	public $tpl;
	public $model;
	
	public function display($instance = array())
	{
        /** Set tpl to NULL **/
        if(wpl_request::getVar('tpl') == 'default') wpl_request::setVar('tpl', NULL);
        
        $this->target = wpl_request::getVar('wpltarget', NULL);
        $this->method = wpl_request::getVar('wplmethod', 'manager');
        $this->instance = $instance;
        
        /** global settings **/
		$this->settings = wpl_global::get_settings();
        
        if($this->method == 'manager') $output = $this->manager();
        elseif($this->method == 'wizard') $output = $this->wizard();
        else $output = $this->undefined();
        
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
    
    private function manager()
    {
        $this->tpl = wpl_request::getVar('tpl', 'manager');
        
        /** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
    }
    
    private function wizard()
    {
        $this->tpl = wpl_request::getVar('tpl', 'wizard');
        
        /** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
    }
    
    private function undefined()
    {
        /** import message tpl **/
        $this->message = __("Undefined Action!", 'wpl');
        return parent::render($this->tpl_path, 'message', false, true);
    }
}