<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** MLS field mapper **/
class wpl_mls_locations_map extends wpl_addon_mls_mapper
{
	/** map function **/
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
		$location_level = $mls_field['custom1'];
		
		if($location_level != 'zip') 
		{
			$table_name = '#__wpl_location'.$location_level;
			$query = "SELECT `name` FROM `$table_name` WHERE `abbr`='".wpl_db::escape($mls_value)."'";
            $result = wpl_db::select($query, 'loadAssoc');
            
            if($result) $mls_value = $result['name'];
			
			$location_level_name = 'location'.$location_level.'_name'; 
		}
		
		else $location_level_name = 'zip_name';
		
		return array('value'=>$mls_value, 'table_column'=>$location_level_name);
	}
}