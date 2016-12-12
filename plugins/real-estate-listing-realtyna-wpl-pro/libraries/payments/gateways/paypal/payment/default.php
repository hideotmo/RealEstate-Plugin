<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_gateway<?php echo $this->get_gateway_id(); ?>_payment_container" class="wpl_gateway_payment_container wpl-util-clearfix">

    <?php
        $express_path = _wpl_import($this->wplpath('express', 'payment'), true, true);
        $direct_path = _wpl_import($this->wplpath('direct', 'payment'), true, true);

        $has_express = in_array($this->settings['method'], array(1, 3));
        $has_direct = in_array($this->settings['method'], array(2, 3));

        $grid_size = ($has_express and $has_direct) ? '2' : '1';
    ?>

    <?php if($has_direct): ?>
    <div class="wpl-util-grid-1-<?php echo $grid_size; ?>">
        <?php include $direct_path; ?>
    </div>
    <?php endif; ?>

    <?php if($has_express): ?>
    <div class="wpl-util-grid-1-<?php echo $grid_size; ?>">
        <?php include $express_path; ?>
    </div>
    <?php endif; ?>

</div>