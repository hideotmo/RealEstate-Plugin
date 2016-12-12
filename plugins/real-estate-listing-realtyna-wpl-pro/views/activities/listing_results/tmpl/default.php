<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : array();
$property_id = isset($wpl_properties['current']['data']['id']) ? $wpl_properties['current']['data']['id'] : NULL;

$query = "SELECT x.position FROM (SELECT p.id, @rownum := @rownum + 1 AS position FROM `#__wpl_properties` AS p JOIN (SELECT @rownum := 0) r WHERE 1 $where ORDER BY $orderby $order) AS x WHERE x.`id`='$property_id'";
$position = wpl_db::select($query, 'loadResult');

$query = "SELECT `id` FROM `#__wpl_properties` AS p WHERE 1 $where ORDER BY $orderby $order LIMIT ".(max($position-2, 0)).", 1";
$previous = wpl_db::select($query, 'loadResult');

$query = "SELECT `id` FROM `#__wpl_properties` AS p WHERE 1 $where ORDER BY $orderby $order LIMIT ".($position).", 1";
$next = wpl_db::select($query, 'loadResult');
?>
<div class="wpl-listing-results-links-cnt" id="wpl_listing_results_links_container<?php echo $property_id; ?>">
    <?php if(trim($search_url)): ?><div class="wpl-back-to-search-results"><a href="<?php echo $search_url; ?>"><?php echo __('Back to search results', 'wpl'); ?></a></div><?php endif; ?>
	<ul class="wpl_listing_results_links_list_container clearfix">
        <?php if($previous and $previous != $property_id): ?><li class="wpl-previous-listing"><a href="<?php echo wpl_property::get_property_link(NULL, $previous); ?>"><?php echo __('Prev', 'wpl'); ?></a></li><?php endif; ?>
        <li class="wpl-listing-result-pagination"><span><?php echo __('Result ', 'wpl'); ?><?php echo $position; ?></span><?php echo __(' of ', 'wpl'); ?><span><?php echo $total; ?></span></li>
        <?php if($next and $next != $property_id): ?><li class="wpl-next-listing"><a href="<?php echo wpl_property::get_property_link(NULL, $next); ?>"><?php echo __('Next', 'wpl'); ?></a></li><?php endif; ?>
    </ul>
</div>