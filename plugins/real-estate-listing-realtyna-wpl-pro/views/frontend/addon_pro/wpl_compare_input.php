<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Maintain getting pids directly from user
 * @author Edward <edward@realtyna.com>
 * @since WPL 3.0
 */
class wpl_compare_input
{
	/**
	 * pids to compare
	 * @var array
	 */
	private static $pids = null;

	/**
	 * To set pids from querystring, (RAW input can be passed to method)
	 * @author Edward <edward@realtyna.com>
	 * @since WPL 3.0
	 * @param string $qs
	 * @return void
	 */
	public static function set_pids($qs)
	{
		if(!trim($qs)) return;

		preg_match_all('/\d+|{\d+(,\d+)*}/', $qs, $matches);

		if(!isset($matches[0]) or !sizeof($matches[0])) return;

		foreach($matches[0] as $match)
		{
			$match = (int) $match;
			if($match > 0) self::$pids[] = $match;
		}
	}

	/**
	 * Get class's pids
	 * @author Edward <edward@realtyna.com>
	 * @since WPL 3.0
	 * @return array
	 */
	public static function get_pids()
	{
		return self::$pids;
	}
}
