<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.form.guest', true, ($this->wplraw ? false : true));
?>
<div class="wpl-save-search-addon" id="wpl_addon_save_searches_container">
    <div id="wpl_save_searches_form_container">

        <form id="wpl_save_searches_form" class="wpl-gen-form-wp" method="POST" onsubmit="wpl_save_search(); return false;">

            <div class="wpl-gen-form-row">
                <label for="wpl_ss_name"><?php echo __('Search Name', 'wpl'); ?>: </label>
                <input type="text" name="wplname" id="wpl_ss_name" value="<?php echo __('My Search Name', 'wpl'); ?>" autocomplete="off" required autofocus />
            </div>

            <div id="wpl_save_searches_form_register">

                <div class="wpl-gen-form-row">
                    <hr />
                    <p><span class="wpl-util-icon-lock"></span><?php echo __('Create an account to save searches and track listings (it only takes a minute).', 'wpl'); ?></p>
                </div>

                <div class="wpl-gen-form-row">
                    <label for="wpl_ss_email"><?php echo __('Email', 'wpl'); ?>: </label>
                    <input type="email" name="email" id="wpl_ss_email" autocomplete="off" />
                </div>

            </div>

            <div class="wpl-util-hidden" id="wpl_save_searches_form_login">

                <div class="wpl-gen-form-row">
                    <hr />
                    <p><span class="wpl-util-icon-key"></span><?php echo __('Log in to save your search. We will keep you up to date when new listings match your search.', 'wpl'); ?></p>
                </div>

                <div class="wpl-gen-form-row">
                    <label for="wpl_ss_username"><?php echo __('Username', 'wpl'); ?>: </label>
                    <input type="text" name="username" id="wpl_ss_username" autocomplete="off" />
                </div>

                <div class="wpl-gen-form-row">
                    <label for="wpl_ss_password"><?php echo __('Password', 'wpl'); ?>: </label>
                    <input type="password" name="password" id="wpl_ss_password" autocomplete="off" />
                </div>

            </div>

            <div class="wpl-gen-form-row wpl-util-right">
                <button type="submit" class="wpl-gen-btn-1" id="wpl_save_searches_register_save_submit"><?php echo __('Register & Save', 'wpl'); ?></button>
                <button type="submit" class="wpl-gen-btn-1 wpl-util-hidden" id="wpl_save_searches_login_save_submit"><?php echo __('Login & Save', 'wpl'); ?></button>
            </div>

            <input type="hidden" name="wpl_function" value="register" id="wpl_save_search_guest_method" />
            <input type="hidden" name="kind" value="<?php echo $this->kind; ?>" />
            <input type="hidden" name="token" id="wpl_save_searches_token" value="<?php echo $this->wpl_security->token(); ?>" />
            <input type="hidden" name="criteria" value="<?php echo base64_encode(json_encode($this->criteria)); ?>" />
            <input type="hidden" name="url" value="<?php echo urlencode($this->search_url); ?>" />

        </form>

        <div id="wpl_save_searches_toggle" class="wpl-gen-form-row wpl-addon-ss-toggle-btns">
            <p id="wpl_save_searches_toggle_register">
                <?php echo sprintf(__('Already a member? %s.', 'wpl'), '<a href="#" onclick="wpl_save_search_toggle(\'login\');return false;">'.__('Login', 'wpl').'</a>'); ?>
            </p>
            <p class="wpl-util-hidden" id="wpl_save_searches_toggle_login">
                <?php echo sprintf(__('Not a member? %s.', 'wpl'), '<a href="#" onclick="wpl_save_search_toggle(\'register\');return false;">'.__('Register', 'wpl').'</a>'); ?>
            </p>
        </div>

        <div id="wpl_save_searches_form_show_messages" class="wpl-addon-save-search-msg"></div>

    </div>
</div>