<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Set Country if not mapped **/
if(!isset($wpl_listing['location1_id']['wpl_value']))
{
    $arr = array();
    $arr['wpl_value'] = 254;
    $wpl_table_column = 'location1_id';
    $arr['wpl_table_column'] = $wpl_table_column;
    $wpl_listing[$wpl_table_column] = $arr;
	
	$arr = array();
    $arr['wpl_value'] = 'United States';
    $wpl_table_column = 'location1_name';
    $arr['wpl_table_column'] = $wpl_table_column;
    $wpl_listing[$wpl_table_column] = $arr;
}

/** location integration with wpl **/
for($location_level=1; $location_level<=7; $location_level++)
{
	if(!isset($wpl_listing['location'.$location_level.'_id']) and isset($wpl_listing['location'.$location_level.'_name']))
	{
		$query = "SELECT `id` FROM `#__wpl_location".$location_level."` WHERE (LOWER(`name`)='".strtolower($wpl_listing['location'.$location_level.'_name']['wpl_value'])."' OR LOWER(`abbr`)='".strtolower($wpl_listing['location'.$location_level.'_name']['wpl_value'])."') and `parent`='".$wpl_listing['location'.($location_level-1).'_id']['wpl_value']."'";
		$location_id = wpl_db::select($query, 'loadResult');
		
		if($location_id) $wpl_value = $location_id;
		else
		{
			$query = "INSERT INTO `#__wpl_location".$location_level."`(`parent`, `name`) VALUES ('".$wpl_listing['location'.($location_level-1).'_id']['wpl_value']."','".$wpl_listing['location'.$location_level.'_name']['wpl_value']."')";
			$wpl_value = wpl_db::q($query, 'insert');
		}
		
        $arr = array();
        $arr['wpl_value'] = $wpl_value;
        $wpl_table_column = 'location'.$location_level.'_id';
        $arr['wpl_table_column'] = $wpl_table_column;

        $wpl_listing[$wpl_table_column] = $arr;
	}
}

/** zip id integration with wpl **/
if(!isset($wpl_listing['zip_id']) and isset($wpl_listing['zip_name']))
{
    $query = "SELECT `id` FROM `#__wpl_locationzips` WHERE LOWER(`name`)='".strtolower($wpl_listing['zip_name']['wpl_value'])."' and `parent`='".$wpl_value."'";
    $zip_id = wpl_db::select($query, 'loadResult');

    if($zip_id) $wpl_zipid = $zip_id;
    else
    {
        $query = "INSERT INTO `#__wpl_locationzips`(`parent`, `name`) VALUES ('".$wpl_value."','".$wpl_listing['zip_name']['wpl_value']."')";
        $wpl_zipid = wpl_db::q($query, 'insert');
    }

    $arr = array();
    $arr['wpl_value'] = $wpl_zipid;
    $wpl_table_column = 'zip_id';
    $arr['wpl_table_column'] = $wpl_table_column;

    $wpl_listing[$wpl_table_column] = $arr;
}

/** Set googlemap co-ordinates **/
$address = '';
for($location_level=7; $location_level>0; $location_level--)
{
    if(isset($wpl_listing['location'.$location_level.'_name']['wpl_value']))
    $address .= ', ' . $wpl_listing['location'.$location_level.'_name']['wpl_value'];
}

if(isset($wpl_listing['field_42']['wpl_value']))
{
    $address = $wpl_listing['field_42']['wpl_value'] . $address;

    if(isset($wpl_listing['street_no']['wpl_value']))
       $address = $wpl_listing['street_no']['wpl_value'] . ' ' . $address;	
}
else
{
    $address = substr($address, 1);
}

$geo_coordinates = wpl_locations::get_LatLng($address);

$arr = array();
$arr['wpl_value'] = $geo_coordinates[0];
$wpl_table_column = 'googlemap_lt';
$arr['wpl_table_column'] = $wpl_table_column;

$wpl_listing[$wpl_table_column] = $arr;

$arr = array();
$arr['wpl_value'] = $geo_coordinates[1];
$wpl_table_column = 'googlemap_ln';
$arr['wpl_table_column'] = $wpl_table_column;

$wpl_listing[$wpl_table_column] = $arr;
		
/** Set listing type if not mapped **/
if(!isset($wpl_listing['listing']['wpl_value']))
{
    $arr = array();
    $arr['wpl_value'] = 9;
    $wpl_table_column = 'listing';
    $arr['wpl_table_column'] = $wpl_table_column;

    $wpl_listing[$wpl_table_column] = $arr;
}

// Automatically create neighborhoods and assign listings to the related neighborhood
if(trim($mls_server->neighborhood_field) != '' and wpl_global::check_addon('neighborhoods'))
{
    $neighborhood_name = isset($mls_listing[$mls_server->neighborhood_field]) ? $mls_listing[$mls_server->neighborhood_field] : NULL;
    
    if($neighborhood_name != '')
    {
        _wpl_import('libraries.addon_neighborhoods');

        $neighborhood = new wpl_addon_neighborhoods();
        $neighborhood_id = $neighborhood->add($neighborhood_name, $mls_query->default_user_id);

        $arr = array();
        $arr['wpl_value'] = $neighborhood_id;
        $wpl_table_column = 'neighborhood_id';
        $arr['wpl_table_column'] = $wpl_table_column;

        $wpl_listing[$wpl_table_column] = $arr;
    }
}

// Automatically create complexes and assign listings to the related complex
if(trim($mls_server->complex_field) != '' and wpl_global::check_addon('complex'))
{
    $complex_name = isset($mls_listing[$mls_server->complex_field]) ? $mls_listing[$mls_server->complex_field] : NULL;
    
    if($complex_name != '')
    {
        _wpl_import('libraries.addon_complex');

        $complex = new wpl_addon_complex();
        $complex_id = $complex->add($complex_name, $mls_query->default_user_id);

        $arr = array();
        $arr['wpl_value'] = $complex_id;
        $wpl_table_column = 'parent';
        $arr['wpl_table_column'] = $wpl_table_column;

        $wpl_listing[$wpl_table_column] = $arr;
    }
}