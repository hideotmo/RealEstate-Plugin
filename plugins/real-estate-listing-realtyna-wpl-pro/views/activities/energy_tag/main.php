<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.energy_tag');

/** activity class **/
class wpl_activity_main_energy_tag extends wpl_activity
{
    public $tpl_path = 'views.activities.energy_tag.tmpl';
	
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}