<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.logs');

class wpl_log_manager_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.log_manager.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
        // Check Nonce
        if(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_log_manager')) $this->response(array('success'=>0, 'message'=>__('The security nonce is not valid!', 'wpl')));
        
		$function = wpl_request::getVar('wpl_function');
        if($function == 'delete') $this->delete();
	}
    
    private function delete()
    {
        $id = wpl_request::getVar('id', 0);
        
        if($id) wpl_logs::delete($id);
        else wpl_logs::delete_all_logs();
        
        $this->response(array('success'=>1));
    }
    
    private function response($response = array())
    {
        echo json_encode($response);
        exit;
    }
}