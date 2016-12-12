<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.profile.js', true, true);
$this->_wpl_import($this->tpl_path.'.scripts.profile.css', true, true);
?>
<div class="wpl_addon_membership_container wpl_view_container wpl_membership_wrap" id="wpl_addon_membership_container">
    <div class="wpl_dashboard_header">
        <?php echo __('Saved Searches', 'wpl'); ?>
        <?php if(wpl_users::is_administrator()): ?><a class="administrator_link" href="<?php echo wpl_global::get_wp_admin_url(); ?>"><?php echo __('Admin', 'wpl'); ?></a><?php endif; ?>
    </div>
    
    <?php if(count(wpl_activity::get_activities('dashboard_side', 1))): ?>
    <div class="wpl_dashboard_side2">
        <div id="wpl_dashboard_side2_container">
            <?php
            $activities = wpl_activity::get_activities('dashboard_side', 1);
            foreach($activities as $activity)
            {
                $content = wpl_activity::render_activity($activity, array('user_data'=>$this->user_data));
                if(trim($content) == '') continue;
                ?>
                <div>
                    <?php if($activity->show_title and trim($activity->title) != ''): ?>
                    <h3><?php echo __($activity->title, 'wpl'); ?></h3>
                    <?php endif; ?>
                    
                    <div><?php echo $content; ?></div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="wpl_dashboard_side1">
        <div id="wpl_dashboard_main_content"><?php /** Loading WPL Save Searches Page **/ echo wpl_global::load('addon_save_searches', '', array('wplmethod'=>'listing', 'wpl_dashboard'=>1), NULL, true); ?></div>
    </div>
</div>