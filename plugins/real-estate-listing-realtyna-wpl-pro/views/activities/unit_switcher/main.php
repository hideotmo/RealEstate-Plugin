<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.units');

/** activity class **/
class wpl_activity_main_unit_switcher extends wpl_activity
{
    public $tpl_path = 'views.activities.unit_switcher.tmpl';
	
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}