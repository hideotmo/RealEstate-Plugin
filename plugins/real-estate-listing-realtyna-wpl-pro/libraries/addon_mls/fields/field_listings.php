<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** MLS field mapper **/
class wpl_mls_listings_map extends wpl_addon_mls_mapper
{
	/** map function **/
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
		$query = "SELECT `id` FROM `#__wpl_listing_types` WHERE LOWER(`name`)='".strtolower($mls_value)."'";
		$listing_id = wpl_db::select($query, 'loadResult');
		
		if($listing_id) $wpl_value = $listing_id;
		else
		{
			$listing_id = wpl_listing_types::insert_listing_type(1, ucfirst(strtolower($mls_value)));
			$query = "UPDATE `#__wpl_listing_types` SET `name`='".ucfirst(strtolower($mls_value))."' WHERE `id`='$listing_id'";
			wpl_db::q($query);
			
			$wpl_value = $listing_id;
		}
		
		return array('value'=>$wpl_value);
	}
}