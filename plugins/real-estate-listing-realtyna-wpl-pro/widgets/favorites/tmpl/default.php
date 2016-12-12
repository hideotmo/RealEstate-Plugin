<?php
defined('_WPLEXEC') or die('Restricted access');

$this->image_width = isset($this->data['image_width']) ? $this->data['image_width'] : 32;
$this->image_height = isset($this->data['image_height']) ? $this->data['image_height'] : 32;
$this->max_char_title = isset($this->data['max_char_title']) ? $this->data['max_char_title'] : 15;
$this->show_contact_form = isset($this->data['show_contact_form']) ? $this->data['show_contact_form'] : 1;
$this->show_compare = isset($this->data['show_compare']) ? $this->data['show_compare'] : 0;

include _wpl_import('widgets.favorites.scripts.js', true, true);
?>
<script id="wpl_favorites_tmpl<?php echo $this->widget_id; ?>" type="text/x-handlebars-template">
    <div class="wpl_favorite_widget_title">
        <?php echo $args['before_title']; ?>
            <?php echo $this->title; ?> <div id="wpl_favorites_count<?php echo $this->widget_id; ?>" class="badge">{{count}}</div>
        <?php echo $args['after_title']; ?>
    </div>
    <ul id="wpl_favorites_items<?php echo $this->widget_id; ?>" class="wpl_favorites_items">
        {{#each listings}}
        <li id="wpl_favorites_item<?php echo $this->widget_id; ?>_{{id}}">
            <a href="{{link}}" target="_blank">
                {{#if image}}
                <img class="wpl_favorite_item_image" src="{{image}}" width="<?php echo $this->image_width; ?>" height="<?php echo $this->image_height; ?>" />
                {{else}}
                <div class="no_image_box"></div>
                {{/if}}
                <span class="wpl_favorite_item_title">{{title}}</span>
            </a>
            <span class="wpl_favorite_item_remove" onclick="wpl_remove_favorite({{id}})">x</span>
        </li>
        {{/each}}
    </ul>
</script>
<div id="wpl_favorite_listings_cnt<?php echo $this->widget_id; ?>" class="wpl_favorite_listings <?php echo $this->css_class; ?>"></div>
<?php if($this->show_contact_form): ?>
    <div class="wpl_favorite_contact_form">
        <form method="post" action="#" id="wpl_favorite_form<?php echo $this->widget_id; ?>" onsubmit="return wpl_send_favorite(<?php echo $this->widget_id; ?>);">
            <div class="form-field">
                <input class="text-box" type="text" id="wpl_favorite_fullname" name="fullname" placeholder="<?php echo __('Full Name', 'wpl'); ?>" />
            </div>

            <div class="form-field">
                <input class="text-box" type="text" id="wpl_favorite_phone" name="phone" placeholder="<?php echo __('Phone', 'wpl'); ?>" />
            </div>

            <div class="form-field">
                <input class="text-box" type="text" id="wpl_favorite_email" name="email" placeholder="<?php echo __('Email', 'wpl'); ?>" />
            </div>

            <div class="form-field">
                <textarea class="text-box" id="wpl_favorite_message" name="message" placeholder="<?php echo __('Message', 'wpl'); ?>"></textarea>
            </div>
            
            <?php wpl_security::nonce_field('wpl_favorites_contact_form'); ?>
            
            <div class="form-field">
                <input class="btn btn-primary" id="wpl-favorite-contact-agent" type="submit" value="<?php echo __('Contact Agent', 'wpl'); ?>" />
            </div>
        </form>
        <div id="wpl_favorite_ajax_loader_<?php echo $this->widget_id; ?>"></div>
        <div id="wpl_favorite_message_<?php echo $this->widget_id; ?>"></div>
    </div>
<?php endif; ?>
<?php if($this->show_compare): ?>
    <div class="form-field">
        <button style="margin: -5px 0px 0px 10px;border-radius: 0px;padding-left: 8px;padding-right: 8px;" onclick="location='<?php echo $this->compare_url ?>';" class="btn btn-primary" id="wpl-favorite-compare"><?php echo __('Compare favorites', 'wpl'); ?></button>
    </div>
<?php endif; ?>