<?php   
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_save_searches_widget_cnt<?php echo $this->widget_id; ?>" class="wpl-save-searches-widget <?php echo $this->css_class; ?>">

    <?php echo $args['before_title'].__($this->title, 'wpl').$args['after_title']; ?>
    <?php /** Loading WPL Save Searches Page **/ echo wpl_global::load('addon_save_searches', '', array('wplmethod'=>'listing', 'wpl_dashboard'=>1), NULL, true); ?>
    
</div>