<?php
/**
 *  WordPress initializing
 */
function wpl_find_wordpress_base_path()
{
    $dir = dirname(__FILE__);
    
    do
    {
        if(file_exists($dir.'/wp-config.php')) return $dir;
    }
    while($dir = realpath($dir.'/..'));
    
    return NULL;
}

define('BASE_PATH', wpl_find_wordpress_base_path().'/');
define('WP_USE_THEMES', false);

global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require(BASE_PATH.'wp-load.php');

/** exit if request method is POST **/
if(wpl_request::getVar('REQUEST_METHOD', 'POST', 'SERVER') == 'POST') exit;

/** import payments library **/
_wpl_import('libraries.payments.payments');

$model = new wpl_payments();
$multisafepay = $model->get('multisafepay');

$order_id = wpl_request::getVar('transactionid', 0, 'GET');
$verified = $multisafepay->validate_express_payment($order_id);