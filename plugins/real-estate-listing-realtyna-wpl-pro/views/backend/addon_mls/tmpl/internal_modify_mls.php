<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js');
$edit_mode = $this->id ? true : false;
?>
<div class="fanc-content size-width-2">
    <h2><?php echo __('MLS Server', 'wpl'); ?></h2>
    <div class="wpl_show_message"></div>
    <div class="fanc-body">
        <div class="fanc-row  fanc-button-row-2">
            <input type="button" class="wpl-button button-1" value="<?php echo __('Save', 'wpl'); ?>" onclick="wpl_save_mls();" />
        </div>
        <div class="col-wp">
            <div class="col-fanc-left fanc-tabs-wp">
                <ul>
                    <li class="active"><a href="#basic" class="tab-basic" id="wpl_slide_label_id_basic" onclick="rta.internal.slides.open('_basic','.fanc-tabs-wp','.fanc-content-body');"><?php echo __('Basic', 'wpl'); ?></a></li>
                    <li><a href="#advanced" class="tab-advanced" id="wpl_slide_label_id_advanced" onclick="rta.internal.slides.open('_advanced','.fanc-tabs-wp','.fanc-content-body');"><?php echo __('Advanced', 'wpl'); ?></a></li>
                </ul>
            </div>
            <div class="col-fanc-right fanc-content-wp" id="wpl_modify_mls">
                <div class="fanc-content-body" id="wpl_slide_container_id_basic">
                    <div class="fanc-row">
                        <label for="mls_name"><?php echo __('Name', 'wpl'); ?> :</label>
                        <input type="text" name="mls_name" id="mls_name" value="<?php echo (isset($this->mls_server->mls_name) ? $this->mls_server->mls_name : ''); ?>" />
                        <?php if($edit_mode): ?>
						<input type="hidden" id="id" name="id" value="<?php echo $this->id; ?>" />
                        <?php endif ?>
                    </div>
                    <div class="fanc-row">
                        <label for="url"><?php echo __('URL', 'wpl'); ?> :</label>
                        <input type="text" name="url" id="url" value="<?php echo (isset($this->mls_server->url) ? $this->mls_server->url : ''); ?>" class="long" />
                    </div>
                    <div class="fanc-row">
                        <label for="username"><?php echo __('Username', 'wpl'); ?> :</label>
                        <input type="text" name="username" id="username" value="<?php echo (isset($this->mls_server->username) ? $this->mls_server->username : ''); ?>" />
                    </div>
                    <div class="fanc-row">
                        <label for="password"><?php echo __('Password', 'wpl'); ?> :</label>
                        <input type="password" name="password" id="password" value="<?php echo (isset($this->mls_server->password) ? $this->mls_server->password : ''); ?>" />
                    </div>
                    
                    <div class="fanc-row">
                        <label for="agent-username"><?php echo __('Agent Username', 'wpl'); ?> :</label>
                        <input type="text" name="agent_username" id="agent_username" value="<?php echo (isset($this->mls_server->agent_username) ? $this->mls_server->agent_username : ''); ?>" />
                    </div>
                     <div class="fanc-row">
                        <label for="agent-password"><?php echo __('Agent Password', 'wpl'); ?> :</label>
                        <input type="text" name="agent_password" id="agent_password" value="<?php echo (isset($this->mls_server->agent_password) ? $this->mls_server->agent_password : ''); ?>" />
                    </div>
                    
                    <div class="fanc-row">
                        <label for="mls_unique_field"><?php echo __('Unique Field', 'wpl'); ?> :</label>
                        <input type="text" name="mls_unique_field" id="mls_unique_field" value="<?php echo (isset($this->mls_server->mls_unique_field) ? $this->mls_server->mls_unique_field : ''); ?>" />
                        <span>&nbsp;<?php echo __('Required for import!', 'wpl'); ?></span>
                    </div>
                    <div class="fanc-row">
                        <label for="mls_sample_query"><?php echo __('Sample Query', 'wpl'); ?> :</label>
                        <input type="text" name="mls_sample_query" id="mls_sample_query" value="<?php echo (isset($this->mls_server->mls_sample_query) ? $this->mls_server->mls_sample_query : ''); ?>" />
                        <span>&nbsp;<?php echo __('Required for sample data!', 'wpl'); ?></span>
                    </div>
                </div>
                <div class="fanc-content-body" id="wpl_slide_container_id_advanced" style="display: none">
                    <div id="tab_setting_advance">
                        <?php $this->generate_advanced_tab(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>