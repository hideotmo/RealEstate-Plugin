<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.__('MLS Addon / Servers', 'wpl').'</h3><p>'.__("You can insert RETS/MLS servers here for importing their listings. You're able to insert as many RETS/MLS server as you like.", 'wpl').'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>__('Introduction', 'wpl'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/559/" target="_blank">'.__("Where can I find help with the WPL MLS Add-on?", 'wpl').'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/614/" target="_blank">'.__("How to include MLS/Importer addon fields into the search widget?", 'wpl').'</a></li>';

$content = '<h3>'.__('Related KB Articles', 'wpl').'</h3><p>'.__('Here you can find some of important KB articles that answer questions related to this page. You can check this section if you faced any question on certain pages.', 'wpl').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>__('KB Articles', 'wpl'));

// Hide Tour button
$tabs['sidebar'] = array('content'=>'');

return $tabs;