<?php
/** No direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Set params **/
$this->license_key_checker = false;
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();

/** skip if address is hide **/
if(!$wpl_properties['current']['data']['show_address']) return;

$this->property_address = isset($wpl_properties['current']['location_text']) ? $wpl_properties['current']['location_text'] : NULL;
$this->walkscore_googlemap_lt = isset($wpl_properties['current']['data']['googlemap_lt']) ? $wpl_properties['current']['data']['googlemap_lt'] : NULL;
$this->walkscore_googlemap_ln = isset($wpl_properties['current']['data']['googlemap_ln']) ? $wpl_properties['current']['data']['googlemap_ln'] : NULL;

/** Get Activity options */
$this->walkscore_license_key = isset($params['walkscore_license_key']) ? $params['walkscore_license_key'] : NULL;
$this->walkscore_width = isset($params['walkscore_width']) ? $params['walkscore_width'] : '317';
$this->walkscore_height = isset($params['walkscore_height']) ? $params['walkscore_height'] : '460';
$this->walkscore_layout = isset($params['walkscore_layout']) ? $params['walkscore_layout'] : 'vertical';

/** load js codes **/
$this->_wpl_import($this->tpl_path.'.scripts.default', true, true, true);
?>
<div id="ws-walkscore-tile"></div>