<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.notifications.notifications');
_wpl_import('libraries.addon_save_searches');

/**
 * WPL Save Searches event handler functions
 * @author Howard <howard@realtyna.com>
 * @package Save Searches
 */
class wpl_events_addon_save_searches
{
    public static function notify($params)
    {
        $replacements = $params[0];
        
        $notification = new wpl_notifications('email');
        $notification->prepare(301, $replacements);
        
        /** Disabled **/
        if(!$notification->notification_data['enabled']) return false;
        
        $search_id = $params[0]['id'];
        $new_results = $params[0]['new_results'];
        
        /** No new listings **/
        if(!count($new_results)) return false;
        
        /** Save Search Object **/
        $save_searches = new wpl_addon_save_searches();

        $save_search = $save_searches->get($search_id);
                    
        $user = wpl_users::get_user($save_search['user_id']);
        $replacements['name'] = isset($user->data->wpl_data) ? $user->data->wpl_data->first_name : $user->data->display_name;
        $replacements['listing_count'] = count($new_results);
        $replacements['search_name'] = trim($save_search['name']) ? $save_search['name'] : '';
        
        $property_ids = array();
        foreach($new_results as $key=>$new_result)
        {
            $property_id = isset($new_result->id) ? $new_result->id : $key;
            $property_ids[] = $property_id;
        }
        
        $replacements['listings'] = $save_searches->render_listings($property_ids);
        
        $notification->replacements = $notification->set_replacements($replacements);
        $notification->rendered_content = $notification->render_notification_content();
        $notification->recipients = $notification->set_recipients(array($user->data->user_email));
        $notification->send();
        
        return true;
    }
}