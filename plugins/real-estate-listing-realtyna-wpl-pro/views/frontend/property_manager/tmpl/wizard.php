<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.wizard.js', true, true);
$this->_wpl_import($this->tpl_path.'.scripts.wizard.css', true, true);
?>
<div class="wpl_view_container">
    <?php /** loading Property Wizard page **/ echo wpl_html::load_add_edit_listing($this->instance); ?>
</div>