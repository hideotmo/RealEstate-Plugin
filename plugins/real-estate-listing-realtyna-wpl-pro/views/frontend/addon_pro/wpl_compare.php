<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.addon_pro');
_wpl_import('libraries.activities');
_wpl_import('views.frontend.addon_pro.wpl_compare_input');

class wpl_compare_controller extends wpl_controller
{
	public $tpl_path = 'views.frontend.addon_pro.tmpl';
	public $tpl;


	public function display()
	{
		$this->tpl = 'compare';
		$this->image_activity_position = 'wpl_property_compare_image';
		$this->property_title = 'field_313';

        $pids = !empty($_GET['pids']) ? $_GET['pids'] : null;
		wpl_compare_input::set_pids($pids);

		$properties = wpl_addon_pro::compare_get_properties(wpl_compare_input::get_pids());
		$minimal_fields = wpl_addon_pro::compare_get_minimal_fields($properties);
		$categorized_fields = wpl_addon_pro::compare_get_fields(true, $minimal_fields);

		$compare_rendered = wpl_addon_pro::compare_render($properties, $minimal_fields);

		$this->fields = $categorized_fields;
		$this->properties = $properties;
		$this->values = $compare_rendered->values;
		$this->compare_row = $compare_rendered->compare_row;

		$this->fields_list = array_map(array($this, '_map_table_column'), $minimal_fields);

		/** Setting activity params TODO: should moved to a new gallery layout for compare **/
		$this->image_height = 0;
		$this->image_width = 0;
		$this->image_class = '';

		$property_image_activity = wpl_activity::get_activities($this->image_activity_position, 1, '', 'loadObject');

		if($property_image_activity)
		{
			$activity_params = json_decode($property_image_activity->params);
			if($activity_params)
			{
				$this->image_height = $activity_params->image_height;
				$this->image_width = $activity_params->image_width;
				$this->image_class = $activity_params->image_class;
			}
		}
		/** End **/

		if(!sizeof($properties))
		{
			$this->frontend_url = wpl_global::get_wp_url('frontend');
			$this->tpl = 'compare_empty';
		}

		/** Loads js.dragscroll library **/
		$url = wpl_global::get_wpl_asset_url('js/libraries/wpl.dragscroll.min.js');
		wp_enqueue_script('wpl.dragscroll', $url, array(), false, true);

		return parent::render($this->tpl_path, $this->tpl, false, true);
	}

	/**
	 * @TODO make anonymous
	 * Mapps table_column of a field list to an array
	 */
	private function _map_table_column($element)
	{
		if(isset($element->table_column)) return $element->table_column;
	}

}