<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path . '.scripts.js');
$this->_wpl_import($this->tpl_path . '.scripts.css');
?>
<div class="wrap wpl-wp wpl-payments-wp">
    <header>
        <div id="icon-payments" class="icon48"></div>
        <h2><?php echo __('Payments', 'wpl'); ?></h2>
    </header>
    <?php $this->include_tabs(); ?>
    <div class="wpl_payments_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="side-2 side-tabs-wp">
            <ul>
                <li class="finilized">
                    <a href="#10000" class="tab-finalize wpl_slide_label_id10000" id="wpl_slide_label_id10000" onclick="wpl_payments_save_settings();">
                        <span><?php echo __('Save', 'wpl'); ?></span>
                        <i class="icon-finalize"></i>
                    </a>
                </li>
                <?php foreach($this->gateways as $gateway): ?>
                <li id="wpl-payments-settings-tab<?php echo $this->objects[$gateway]->get_gateway_id(); ?>">
                    <a href="#<?php echo $this->objects[$gateway]->get_gateway_id(); ?>" class="wpl_slide_label" id="wpl_slide_label_id<?php echo $this->objects[$gateway]->get_gateway_id(); ?>" onclick="rta.internal.slides.open('<?php echo $this->objects[$gateway]->get_gateway_id(); ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');">
                        <?php echo __($this->objects[$gateway]->get_gateway_label(), 'wpl'); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="side-12 side-content-wp">
            <form onsubmit="wpl_payments_save_settings(); return false;" id="wpl_payments_settings_form">
                <?php foreach($this->gateways as $gateway): ?>
                <div class="pwizard-panel wpl_slide_container wpl_slide_container<?php echo $this->objects[$gateway]->get_gateway_id(); ?>" id="wpl_slide_container_id<?php echo $this->objects[$gateway]->get_gateway_id(); ?>">
                    <?php $this->objects[$gateway]->settings_form(); ?>
                </div>
                <?php endforeach; ?>
            </form>
        </div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>