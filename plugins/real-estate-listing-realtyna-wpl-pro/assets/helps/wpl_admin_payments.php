<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.__('Payments', 'wpl').'</h3><p>'.__("Using this menu you can configure your website payment gateways. Please note that WPL payment option is useful for websites that have membership addon, Booking addon etc so if you don't have anything for sell on your website, you don't need to configure anything and you can simply ignore this menu.", 'wpl').'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>__('Introduction', 'wpl'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/669/" target="_blank">'.__("How to start with WPL Payment system?", 'wpl').'</a></li>';

$content = '<h3>'.__('Related KB Articles', 'wpl').'</h3><p>'.__('Here you can find some of important KB articles that answer questions related to this page. You can check this section if you faced any question on certain pages.', 'wpl').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>__('KB Articles', 'wpl'));

// Hide Tour button
$tabs['sidebar'] = array('content'=>'');

return $tabs;