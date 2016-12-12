<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.__('Log Manager', 'wpl').'</h3><p>'.__("Here you can see WPL logs here if WPL log is enabled on WPL settings. Logs help you sometimes to found what's happening on your website.", 'wpl').'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>__('Introduction', 'wpl'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/645/" target="_blank">'.__("What is the WPL Log Manager?", 'wpl').'</a></li>';

$content = '<h3>'.__('Related KB Articles', 'wpl').'</h3><p>'.__('Here you can find some of important KB articles that answer questions related to this page. You can check this section if you faced any question on certain pages.', 'wpl').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>__('KB Articles', 'wpl'));

// Hide Tour button
$tabs['sidebar'] = array('content'=>'');

return $tabs;