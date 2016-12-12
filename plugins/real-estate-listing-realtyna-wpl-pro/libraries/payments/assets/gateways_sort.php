<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$sorted = array();
$i = 0;
foreach($gateways as $gateway)
{
    $i++;
    
    $settings = $this->get_settings($gateway);
    $index = isset($settings['index']) ? $settings['index'] : $i;
    $i = max($index, $i);
    $sorted[$index] = $gateway;
}

ksort($sorted);