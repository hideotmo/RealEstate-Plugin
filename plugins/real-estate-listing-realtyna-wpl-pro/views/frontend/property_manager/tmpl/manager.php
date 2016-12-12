<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.manager.js', true, true);
$this->_wpl_import($this->tpl_path.'.scripts.manager.css', true, true);
?>
<div class="wpl_view_container">
    <?php /** loading Property Manager page **/ echo wpl_html::load_listing_manager($this->instance); ?>
</div>