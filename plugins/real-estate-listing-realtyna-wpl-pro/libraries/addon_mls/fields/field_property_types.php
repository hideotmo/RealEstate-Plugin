<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** MLS field mapper **/
class wpl_mls_property_types_map extends wpl_addon_mls_mapper
{
	/** map function **/
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
		$query = "SELECT `id` FROM `#__wpl_property_types` WHERE `parent` <> '0' AND LOWER(`name`)='".strtolower($mls_value)."'";
		$property_type_id = wpl_db::select($query, 'loadResult');
		
		if($property_type_id) $wpl_value = $property_type_id;
		else
		{
			$property_type_id = wpl_property_types::insert_property_type('1',ucfirst(strtolower($mls_value)));
			
			$wpl_value = $property_type_id;
		}
		
		return array('value'=>$wpl_value);
	}
}