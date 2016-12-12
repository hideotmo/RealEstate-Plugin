<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

/** Kind **/
$this->kind = isset($wpl_properties['current']['data']['kind']) ? $wpl_properties['current']['data']['kind'] : 0;
$kind_data = wpl_flex::get_kind($this->kind);

/** Parameters **/
$this->params = $params;

/** get params **/
$this->authentication_key = isset($params['authentication_key']) ? $params['authentication_key'] : '';
$this->map_height = isset($params['map_height']) ? $params['map_height'] : 385;
$this->default_zoom = isset($params['default_zoom']) ? $params['default_zoom'] : '20';
$this->show_marker = (isset($kind_data['map']) and $kind_data['map'] != 'marker') ? 0 : 1;

$this->markers = wpl_property::render_markers($wpl_properties);

/** load js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.pshow', true, true);
?>
<div class="wpl_bingmap_container wpl_bingmap_pshow" id="wpl_bingmap_container<?php echo $this->activity_id; ?>">
	<div class="wpl_map_canvas" id="wpl_map_canvas<?php echo $this->activity_id; ?>" style="position: relative; top: 0; left: 0; height: <?php echo $this->map_height ?>px;"></div>
</div>