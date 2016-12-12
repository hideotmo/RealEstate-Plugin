<?php
/**
* Theme Name: WPL TwentyEight
* Version: 3.2.0
* Author URI: https://realtyna.com/
* Text Domain: wplt
* Domain Path: /languages
**/

/*
* WPL execution
*/
if(!defined('_WPLTEXEC'))
{
	define('_WPLTEXEC', 1); /** this is WPL theme constant **/	
}

/** directory separator **/
if(!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

/** WPLT ABS PATH **/
if(!defined('WPLT_ABSPATH'))
{
	define('WPLT_ABSPATH', dirname(__FILE__) .DS);
}

/**
 * WPLT textdomain for language
 * @deprecated since version 3.0.0
 */
if(!defined('WPLT_TEXTDOMAIN'))
{
	define('WPLT_TEXTDOMAIN', 'wplt');
}

/** include dependencies file **/
if(file_exists(WPLT_ABSPATH. 'wpl' .DS. 'dependencies.php'))
{
	include_once WPLT_ABSPATH. 'wpl' .DS. 'dependencies.php';
}

/** Set content width value based on the theme's design **/
if(!isset($content_width))
{
	$content_width = 1000;
}

if(!class_exists('wpl_helper'))
{
	require_once WPLT_ABSPATH. 'wpl' .DS. 'helper.php';
}

if(!isset($wpl_helper))
{
    global $wpl_helper;
    
	$wpl_helper = new wpl_helper();
	$wpl_helper->init();
}