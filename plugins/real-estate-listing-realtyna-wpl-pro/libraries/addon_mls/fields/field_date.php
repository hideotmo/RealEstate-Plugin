<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.render');

/** MLS field mapper **/
class wpl_mls_date_map extends wpl_addon_mls_mapper
{
	/** map function **/
	public function map($wpl_field, $mls_value, $mls_listing, $mls_field)
	{
        $wpl_value = wpl_render::render_date(wpl_render::derender_date($mls_value));
        
        if($wpl_field['table_column'] == 'add_date') $wpl_value = date("Y-m-d H:i:s", strtotime($wpl_value));
        
        return array('value'=>$wpl_value);
	}
}