<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * MLS Mapper
 * @author Howard <howard@realtyna.com>
 * @date 12/16/2013
 */
class wpl_addon_mls_mapper
{
    /**
     * Map function
     * @author Howard <howard@realtyna.com>
     * @param type $wpl_field
     * @param type $mls_value
     * @param type $mls_listing
     * @param type $mls_field
     * @return type
     */
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
		return array('value'=>$mls_value, 'table_column'=>$wpl_field['table_column']);
	}
	
	/**
     * Get string and return array of items
     * @author Natan <natan@realtyna.com>
     * @param String $string
     * @param String $seprator
     * @return array
     */
	public function toArray($string = '', $seprator = ',')
    {
        if(!trim($string)) return array();
        
        $ex = explode($seprator, $string);
        
        $return = array();
        foreach($ex as $value)
        {
            if(!trim($value)) continue;
            
            $return[] = $value;
        }
        
        return $return;
    }
	
    /**
     * Sets default values like user id and mls_server_id etc. based on query id and current listing values
     * @author Howard <howard@realtyna.com>
     * @global array $mls_query
     * @global array $mls_server
     * @param array $mls_listing
     * @param int $mls_query_id
     * @param array $mls_listings
     * @return array
     */
	public function before_mapping($mls_listing, $mls_query_id, $mls_listings)
	{
		/** global vars **/
		global $mls_query;
		global $mls_server;
		
		$path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'before_mapping.php';
		$overrode_path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'overrides'.DS.'before_mapping.php';
		
		/** define result **/
		$defaults = array();
		
		if(wpl_file::exists($overrode_path)) include $overrode_path;
		elseif(wpl_file::exists($path)) include $path;

		return $defaults;
	}
	
    /**
     * Edits current values of single listing if needed after mapping
     * @author Howard <howard@realtyna.com>
     * @global array $mls_query
     * @global array $mls_server
     * @global array $mls_listings
     * @param array $wpl_listing
     * @param int $mls_query_id
     * @param array $mls_listing
     * @return array
     */
	public function after_mapping($wpl_listing, $mls_query_id, $mls_listing)
	{
		/** global vars **/
		global $mls_query;
		global $mls_server;
		global $mls_listings;
		
		$path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'after_mapping.php';
		$override_path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'overrides'.DS.'after_mapping.php';
		
		if(wpl_file::exists($override_path)) include $override_path;
		elseif(wpl_file::exists($path)) include $path;
        
        // Automatically assign listings the their agents in WPL
		if(wpl_global::check_addon('mls_agents'))
		{
			$agent_path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'agent_mapping.php';
			$agent_override_path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'overrides'.DS.'agent_mapping.php';
            
			if(wpl_file::exists($agent_override_path)) include $agent_override_path;
			elseif(wpl_file::exists($agent_path)) include $agent_path;
		}

		return $wpl_listing;
	}
	
    /**
     * Runs before finalize of property
     * @author Howard <howard@realtyna.com>
     * @global array $mls_query
     * @global array $mls_server
     * @param int $pid
     * @param string $mode
     * @param int $user_id
     */
	public function before_finalize($pid, $mode, $user_id)
	{
		/** global vars **/
		global $mls_query;
		global $mls_server;
		
		$path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'before_finalize.php';
		$overrode_path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'overrides'.DS.'before_finalize.php';
		
		if(wpl_file::exists($overrode_path)) include $overrode_path;
		elseif(wpl_file::exists($path)) include $path;
	}
	
    /**
     * Runs after finalize of property
     * @author Howard <howard@realtyna.com>
     * @global array $mls_query
     * @global array $mls_server
     * @param int $pid
     * @param string $mode
     * @param int $user_id
     */
	public function after_finalize($pid, $mode, $user_id)
	{
		/** global vars **/
		global $mls_query;
		global $mls_server;
		
		$path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'after_finalize.php';
		$overrode_path = WPL_ABSPATH.'libraries'.DS.'addon_mls'.DS.'files'.DS.'overrides'.DS.'after_finalize.php';
		
		if(wpl_file::exists($overrode_path)) include $overrode_path;
		elseif(wpl_file::exists($path)) include $path;
	}
}