<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$defaults['user_id'] = array('wpl_value'=>$mls_query->default_user_id, 'wpl_table_column'=>'user_id');
$defaults['confirmed'] = array('wpl_value'=>1, 'wpl_table_column'=>'confirmed');
$defaults['mls_server_id'] = array('wpl_value'=>$mls_query->mls_server_id, 'wpl_table_column'=>'mls_server_id');
$defaults['mls_class_id'] = array('wpl_value'=>$mls_query->mls_class_id, 'wpl_table_column'=>'mls_class_id');
$defaults['mls_query_id'] = array('wpl_value'=>$mls_query_id, 'wpl_table_column'=>'mls_query_id');