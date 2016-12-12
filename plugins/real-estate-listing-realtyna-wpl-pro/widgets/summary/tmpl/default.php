<?php   
defined('_WPLEXEC') or die('Restricted access');

/** Currency Unit **/
$this->unit_id = isset($this->data['unit_id']) ? $this->data['unit_id'] : 0;
if(!$this->unit_id)
{
    $default_unit = wpl_units::get_default_unit();
    $this->unit_id = isset($default_unit['id']) ? $default_unit['id'] : 260;
}

$this->skip_zero = isset($this->data['skip_zero']) ? $this->data['skip_zero'] : 0;

/** Filter **/
$filter = wpl_flex::get_field($this->filter);
$options = json_decode($filter->options, true);

/** Listing **/
$listing = wpl_global::get_listings($this->listing);

/** Cache File **/
$cache_file = $this->wplcache->path('widgets'.DS.$this->widget_uq_name.'.json');

/** Check if cache file is valid **/
if($this->wplcache->valid($cache_file))
{
    $JSON = $this->wplcache->read($cache_file);
    $stats = json_decode($JSON, true);
}
else
{
    $conditions = array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_expired'=>0, 'sf_select_kind'=>$this->kind, 'sf_select_listing'=>$this->listing);
    $where = wpl_db::create_query($conditions);
    
    $query = "SELECT `".$filter->table_column."`, COUNT(`id`) as count, MIN(`price_si`) as min, MAX(`price_si`) as max, AVG(`price_si`) as avg FROM `#__wpl_properties` WHERE 1 ".$where." GROUP BY `".$filter->table_column."`";
    $stats = wpl_db::select($query, 'LoadAssocList');
    
    $JSON = json_encode($stats);
    $stats = json_decode($JSON, true);
    
    /** Write to Cache File **/
    $this->wplcache->write($cache_file, $JSON);
}
?>
<div id="wpl_summary_cnt<?php echo $this->widget_id; ?>" class="wpl-summary-widget <?php echo $this->css_class; ?>">

    <?php echo $args['before_title'].__($this->title, 'wpl').$args['after_title']; ?>

    <ul class="wpl-widget-summary-wp">
        <?php
            foreach($options['params'] as $option)
            {
                if(trim($option['value']) == '') continue;
                
                $key = $option['key'];
                if((!isset($stats[$key]) or (isset($stats[$key]) and $stats[$key]['count'] == 0)) and $this->skip_zero) continue;
        ?>
        <li>
            <div class="wpl-widget-summary-info-wp">

                <div class="wpl-widget-summary-label">
                    <?php echo __($filter->name.': '.$option['value'], 'wpl'); ?>
                </div>

                <div class="wpl-widget-summary-number">
                    <span><?php echo __('No of properties:', 'wpl'); ?></span>
                    <?php echo (isset($stats[$key]) ? wpl_render::render_number($stats[$key]['count']) : 0); ?>
                </div>

                <div class="wpl-widget-summary-price">
                    <span><?php echo __('Price range:', 'wpl'); ?></span>
                    <?php echo sprintf(__('%s to %s', 'wpl'), (isset($stats[$key]) ? wpl_render::render_price($stats[$key]['min'], $this->unit_id) : wpl_render::render_price(0, $this->unit_id)), (isset($stats[$key]) ? wpl_render::render_price($stats[$key]['max'], $this->unit_id) : wpl_render::render_price(0, $this->unit_id))); ?>
                </div>

                <div class="wpl-widget-summary-average">
                    <span><?php echo __('Average price:', 'wpl'); ?></span>
                    <?php echo (isset($stats[$key]) ? wpl_render::render_price($stats[$key]['avg'], $this->unit_id) : wpl_render::render_price(0, $this->unit_id)); ?>
                </div>
            </div>

            <a class="wpl-widget-summary-hover" href="<?php echo $this->get_link($filter->table_column, $option['key']); ?>">
                <span><?php echo __('See Properties', 'wpl'); ?></span>
            </a>

        </li>
        <?php } ?>
    </ul>

</div>