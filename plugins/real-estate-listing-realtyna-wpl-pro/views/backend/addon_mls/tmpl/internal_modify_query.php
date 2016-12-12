<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js');
$edit_mode = $this->id ? true : false;
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	<?php if($this->mls_class_id): ?>
	wpl_mls_class_select('<?php echo $this->mls_class_id; ?>');
	<?php endif; ?>
});
</script>
<div class="fanc-content size-width-2">
    <h2><?php echo __('Query Wizard', 'wpl'); ?></h2>
    <div class="wpl_show_message"></div>
    <div class="fanc-body">
        <div class="fanc-row  fanc-button-row-2">
            <input type="button" class="wpl-button button-1" value="<?php echo __('Save', 'wpl'); ?>" onclick="wpl_save_query();" />
        </div>
        <div class="col-wp">
            <div class="col-fanc-left fanc-tabs-wp">
                <ul>
                    <li class="active"><a href="#basic" class="tab-basic" id="wpl_slide_label_id_basic" onclick="rta.internal.slides.open('_basic','.fanc-tabs-wp','.fanc-content-body');"><?php echo __('Basic', 'wpl'); ?></a></li>
                    <li><a href="#advanced" class="tab-advanced" id="wpl_slide_label_id_advanced" onclick="rta.internal.slides.open('_advanced','.fanc-tabs-wp','.fanc-content-body');"><?php echo __('Advanced', 'wpl'); ?></a></li>
                </ul>
            </div>
            <div class="col-fanc-right fanc-content-wp" id="wpl_modify_query">
                <div class="fanc-content-body" id="wpl_slide_container_id_basic">
                    <div class="fanc-row">
                    	<label for="mls_server_id"><?php echo __('Server', 'wpl'); ?> :</label>
                        <select name="mls_server_id" id="mls_server_id" onchange="wpl_mls_server_select(this.value);">
                            <option value="">----</option>
                            <?php foreach($this->mls_servers as $mls_server): ?>
                            <option value="<?php echo $mls_server->id; ?>" <?php if($mls_server->id == $this->server_id) echo 'selected="selected"'; ?>><?php echo $mls_server->mls_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if($edit_mode): ?><input type="hidden" name="id" id="id" value="<?php echo $this->id; ?>" /><?php endif; ?>
                        <span id="wpl_ajax_loader_select_mls_server"></span>
                    </div>
                    <div class="fanc-row">
                    	<label for="mls_class_id"><?php echo __('Server Class', 'wpl'); ?> :</label>
                        <span id="wpl_addon_mls_class_container">
                            <select name="mls_class_id" id="mls_class_id">
                                <option value="">----</option>
                                <?php foreach($this->classes as $key=>$class): ?>
                                <option value="<?php echo $key; ?>" <?php if($key == $this->mls_class_id) echo 'selected="selected"'; ?> onclick="wpl_mls_class_select(this.value);"><?php echo ($class ? $class : $key); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span id="wpl_ajax_loader_select_mls_class_id"></span>
                        </span>
                    </div>
                    <div class="fanc-row">
                        <label for="default_user_id"><?php echo __('Default User', 'wpl'); ?> :</label>
                        <select name="default_user_id" id="default_user_id">
                        	<?php foreach($this->users as $wp_user): ?>
                            <option value="<?php echo $wp_user->ID; ?>" <?php if(isset($this->mls_query->default_user_id) and $wp_user->ID == $this->mls_query->default_user_id) echo 'selected="selected"'; ?>><?php echo $wp_user->user_login; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="fanc-row">
                        <label for="images"><?php echo __('Images', 'wpl'); ?> :</label>
                        <input type="text" name="images" id="images" value="<?php echo (isset($this->mls_query->images) ? $this->mls_query->images : '5'); ?>" />
                        <span>&nbsp;<?php echo __('Insert -1 for unlimited', 'wpl'); ?></span>
                    </div>
                    <div class="fanc-row">
                        <label for="limit"><?php echo __('Limit', 'wpl'); ?> :</label>
                        <input type="text" name="limit" id="limit" value="<?php echo (isset($this->mls_query->limit) ? $this->mls_query->limit : 50); ?>" />
                    </div>
                    <div class="fanc-row">
                        <label for="sync_period"><?php echo __('Sync Period', 'wpl'); ?> :</label>
                        <input type="text" name="sync_period" id="sync_period" value="<?php echo (isset($this->mls_query->sync_period) ? $this->mls_query->sync_period : 1); ?>" />
                        <span>&nbsp;<?php echo __('(Days)', 'wpl'); ?></span>
                    </div>
                </div>
                <div class="fanc-content-body" id="wpl_slide_container_id_advanced" style="display: none">
                    <div id="tab_setting_advance">
                        <div class="fanc-row">
                            <label for="query"><?php echo __('Query', 'wpl'); ?> :</label>
                            <textarea name="query" id="query" style="width: 350px; height: 100px;"><?php echo (isset($this->mls_query->query) ? $this->mls_query->query : ''); ?></textarea>
                        </div>
                        <div id="wpl_mls_query_wizard_container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>