<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_bingmap extends wpl_activity
{
    public $tpl_path = 'views.activities.bingmap.tmpl';
	
	public function start($layout, $params)
	{
        // Include Bing Map API
        wpl_extensions::import_javascript((object) array('param1'=>'wpl-jqplot', 'param2'=>'https://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&s=1', 'external'=>true));
        
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}