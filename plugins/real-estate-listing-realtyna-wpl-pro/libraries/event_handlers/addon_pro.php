<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.notifications.notifications');

/**
 * WPL PRO event handler functions
 * @author Howard <howard@realtyna.com>
 */
class wpl_events_pro
{
    /**
     * Sends email to agent for contacting
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $params
     * @return boolean
     */
    public static function send_favorite_contact_form($params)
    {
        $replacements = $params[0];
        $notification_data = wpl_notifications::get_notification(1);

        /** Email notification is enabled **/
        if($notification_data['enabled'])
        {
            $notification = new wpl_notifications('email');
            $notification->prepare(1, $replacements);

            $pids = wpl_addon_pro::favorite_get_pids();

            foreach($pids as $property_id)
            {
                $property = wpl_property::get_property_raw_data($property_id);
                $user = wpl_users::get_user($property['user_id']);

                $property_title = wpl_property::update_property_title($property);
                $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'].' ('.$property_title.')</a>';

                $notification->replacements = $notification->set_replacements($replacements);
                $notification->rendered_content = $notification->render_notification_content();
                $notification->recipients = $notification->set_recipients(array($user->data->user_email));

                $notification->send();
            }
        }
        
        /** SMS notification is enabled **/
        if(wpl_global::check_addon('sms') and $notification_data['sms_enabled'])
        {
            $notification = new wpl_notifications('sms');
            $notification->prepare(1, $replacements);
            
            $pids = wpl_addon_pro::favorite_get_pids();
            
            foreach($pids as $property_id)
            {
                $property = wpl_property::get_property_raw_data($property_id);
                $user = $notification->sms->wpl_get_user_data('wpl.`mobile`', "AND wpl.`id`='".$property['user_id']."'", 'loadObject');

                $property_title = wpl_property::update_property_title($property);
                $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'].' ('.$property_title.')</a>';

                $notification->replacements = $notification->set_replacements($replacements);
                $notification->rendered_content = $notification->render_notification_content();
                $notification->recipients = $notification->set_recipients(array($user->mobile));

                $notification->send();
            }
        }
        
        return true;
    }
    
    /**
     * Sends abuse report to website admin
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $params
     * @return boolean
     */
    public static function send_abuse_report($params)
    {
        $replacements = $params[0];
        $notification_data = wpl_notifications::get_notification(4);

        /** Email notification is enabled **/
        if($notification_data['enabled'])
        {
            $notification = new wpl_notifications('email');
            $notification->prepare(4, $replacements);

            $property_id = $replacements['property_id'];
            $property = wpl_property::get_property_raw_data($property_id);
            $user = wpl_users::get_user($property['user_id']);

            $property_title = wpl_property::update_property_title($property);
            $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'] .' ('.$property_title.')</a>';

            $details = '';
            foreach($replacements as $key=>$value)
            {
                if(in_array($key, array('property_id', 'listing_id')) or trim($value) == '') continue;
                $details .= '<strong>'.$key.': </strong><span>'.$value.'</span><br />';
            }

            $replacements['report_details'] = $details;

            $notification->replacements = $notification->set_replacements($replacements);
            $notification->rendered_content = $notification->render_notification_content();
            $notification->recipients = $notification->set_recipients(array($user->data->user_email, wpl_global::get_admin_id()));

            $notification->send();
        }
        
        /** SMS notification is enabled **/
        if(wpl_global::check_addon('sms') and $notification_data['sms_enabled'])
        {
            $notification = new wpl_notifications('sms');
            $notification->prepare(4, $replacements);
            
            $property_title = wpl_property::update_property_title($property);
            $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'].' ('.$property_title.')</a>';
            
            $property_id = $replacements['property_id'];
            $property = wpl_property::get_property_raw_data($property_id);
            
            $user = $notification->sms->wpl_get_user_data('wpl.`mobile`', "AND wpl.`id`='".$property['user_id']."'", 'loadObject');

            $property_title = wpl_property::update_property_title($property);
            $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'] .' ('.$property_title.')</a>';

            $details = '';
            foreach($replacements as $key=>$value)
            {
                if(in_array($key, array('property_id', 'listing_id')) or trim($value) == '') continue;
                $details .= '<strong>'.$key.': </strong><span>'.$value.'</span><br />';
            }

            $replacements['report_details'] = $details;

            $notification->replacements = $notification->set_replacements($replacements);
            $notification->rendered_content = $notification->render_notification_content();
            $notification->recipients = $notification->set_recipients(array($user->mobile, wpl_global::get_admin_id()));

            $notification->send();
            
        }
        
        return true;
    }
}