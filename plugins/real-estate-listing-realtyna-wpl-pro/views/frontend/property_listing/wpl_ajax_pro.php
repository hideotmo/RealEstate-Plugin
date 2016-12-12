<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.locations');
_wpl_import('libraries.addon_pro');

class wpl_property_listing_controller extends wpl_controller
{
    public function display()
    {
        $function = wpl_request::getVar('wpl_function');

        if($function == 'favorites_control') $this->favorites_control();
        elseif($function == 'favorites_load') $this->favorites_load();
        elseif($function == 'favorites_send') $this->favorites_send();
    }

    private function favorites_control()
    {
        $property_id = wpl_request::getVar('pid');
        $mode = wpl_request::getVar('mode');
        $mode = $mode ? 'add' : 'remove';
            
        $results = wpl_addon_pro::favorite_add_remove($property_id, $mode);
        $pids = wpl_addon_pro::favorite_get_pids();
        
        $response = array('success'=>(int) $results, 'pids'=>$pids);
        echo json_encode($response);
        exit;
    }

    private function favorites_load()
    {
        $image_width = wpl_request::getVar('image_width', 32);
        $image_height = wpl_request::getVar('image_height', 32);
        $wpltarget = wpl_request::getVar('wpltarget', 0);
        
        $results = wpl_addon_pro::favorites_load('', $image_width, $image_height, $wpltarget);
        
        echo json_encode($results);
        exit;
    }

    private function favorites_send()
    {
        $fullname = wpl_request::getVar('fullname', '');
        $phone = wpl_request::getVar('phone', '');
        $email = wpl_request::getVar('email', '');
        $message = wpl_request::getVar('message', '');
        
        $parameters = array(
            'fullname' => $fullname,
            'phone' => $phone,
            'email' => $email,
            'message' => $message
        );
        
        $returnData = array();
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Your email is not a valid email!', 'wpl');
        }
        elseif(!wpl_security::verify_nonce(wpl_request::getVar('_wpnonce', ''), 'wpl_favorites_contact_form'))
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('The security nonce is not valid!', 'wpl');
        }
        else
        {
            if(wpl_addon_pro::favorites_send($parameters))
            {
                $returnData['success'] = 1;
                $returnData['message'] = __('Information sent to agents.', 'wpl');
            }
            else
            {
                $returnData['success'] = 0;
                $returnData['message'] = __('Error sending!', 'wpl');
            }
        }
        
        echo json_encode($returnData);
        exit;
    }
}