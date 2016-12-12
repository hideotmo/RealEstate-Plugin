<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content  = '<h3>'.__('MLS Addon / Mapping', 'wpl').'</h3><p>'.__("You should map MLS/RETS fields to WPL fields here. Please note, this step is very important. Please note to following items during mapping: ", 'wpl').'</p>';
$content .= '<ul>';
$content .= '<li>'.__('Map the source fields to existing WPL fields.', 'wpl').'</li>';
$content .= '<li>'.__('Avoid creating new fields using "Auto Create" button unless really there is no related field on WPL for those fields.', 'wpl').'</li>';
$content .= '<li>'.__("It's better if you don't map the fields which don't have any data on sample data. It can make your website messy with unnecessary information.", 'wpl').'</li>';
$content .= '<li>'.__("WPL's most important fields are: listing type, property type, price, price unit, price type (for rental properties), listing ID, bedrooms, bathrooms, rooms, view, property description, built up area, lot area, images, location information, property title (if exists). Map these fields with carefully.", 'wpl').'</li>';
$content .= '<li>'.__("Mapping some fields may need to be manually tailored based on customizations you may have on your WPL, other Addons.", 'wpl').'</li>';
$content .= '<li>'.__("It's highly recommended to don't map the fields that you don't need.", 'wpl').'</li>';
$content .= '</ul>';

$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>__('Introduction', 'wpl'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/559/" target="_blank">'.__("Where can I find help with the WPL MLS Add-on?", 'wpl').'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/614/" target="_blank">'.__("How to include MLS/Importer addon fields into the search widget?", 'wpl').'</a></li>';

$content = '<h3>'.__('Related KB Articles', 'wpl').'</h3><p>'.__('Here you can find some of important KB articles that answer questions related to this page. You can check this section if you faced any question on certain pages.', 'wpl').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>__('KB Articles', 'wpl'));

// Hide Tour button
$tabs['sidebar'] = array('content'=>'');

return $tabs;