<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'openhouse_dates' and !$done_this)
{
    _wpl_import('libraries.render');
	wp_enqueue_script('jquery-ui-datepicker');

    $date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
    $jqdate_format = $date_format_arr[1];

    $mindate = explode('-', date('Y-m-d', strtotime('-1 year')));
    $maxdate = explode('-', date('Y-m-d', strtotime('+3 years')));
    $mindate[1] = intval($mindate[1]);
    $mindate[2] = intval($mindate[2]);
    $maxdate[1] = intval($maxdate[1]);
    $maxdate[2] = intval($maxdate[2]);

    $raw_items = wpl_items::get_items($item_id, 'opendates', $this->kind);
    $opendates = array();
    foreach($raw_items as $raw_item) $opendates[$raw_item->item_cat] = array('value'=>$raw_item->item_name, 'desc'=>$raw_item->item_extra2);
?>
<div class="openhouse-dates-wp" id="openhouse-dates-wp<?php echo $field->id; ?>" <?php if(isset($values['sp_openhouse']) and !$values['sp_openhouse']) echo 'style="display: none;"'; ?>>
    <label for="wpl_c_<?php echo $field->id; ?>_1"><?php echo __($label, 'wpl'); ?><?php if (in_array($mandatory, array(1, 2))): ?><span class="required-star">*</span><?php endif; ?></label>
    <div class="openhouse-dates-comment"><?php echo __('Add Open House dates and any comments for each in the next textbox such as 4-10 p.m., call prior etc. If any of these dates are not filled in,  they are automatically deleted.'); ?></div>

    <?php for($i=1; $i<=10; $i++): ?>
    <div id="wpl_c_<?php echo $field->id; ?>_<?php echo $i; ?>_container">
        <input type="text" id="wpl_c_<?php echo $field->id; ?>_<?php echo $i; ?>" value="<?php echo (isset($opendates[$i]) ? $opendates[$i]['value'] : ''); ?>" onchange="item_save(this.value, '<?php echo $item_id; ?>', '<?php echo $field->id; ?>', 'opendates', '<?php echo $i; ?>', '<?php echo $i; ?>', wplj('#wpl_c_<?php echo $field->id; ?>_<?php echo $i; ?>_desc').val(), '<?php echo $jqdate_format ?>');" placeholder="<?php echo __('Open Date', 'wpl'); ?>" />
        <input type="text" id="wpl_c_<?php echo $field->id; ?>_<?php echo $i; ?>_desc" value="<?php echo (isset($opendates[$i]) ? $opendates[$i]['desc'] : ''); ?>" onchange="item_save(wplj('#wpl_c_<?php echo $field->id; ?>_<?php echo $i; ?>').val(), '<?php echo $item_id; ?>', '<?php echo $field->id; ?>', 'opendates', '<?php echo $i; ?>', '<?php echo $i; ?>', this.value, '<?php echo $jqdate_format ?>');" placeholder="<?php echo __('Comment', 'wpl'); ?>" />
        <span id="wpl_listing_saved_span_<?php echo $field->id; ?>_<?php echo $i; ?>" class="ajax-inline-save"></span>
    </div>
    <?php endfor; ?>
</div>

<script type="text/javascript">
    wplj(function () {

        wplj(".wpl_c_sp_openhouse").on("change", function (event) {
            if (this.checked) {
                wplj("#openhouse-dates-wp<?php echo $field->id; ?>").slideDown();
            }
            else if (!this.checked) {
                wplj("#openhouse-dates-wp<?php echo $field->id; ?>").slideUp();
            }
        });

        <?php
            for($i=1; $i<=10; $i++)
            {
                echo 'wplj("#wpl_c_' . $field->id . '_'.$i.'").datepicker(
                    {
                        dayNamesMin: ["' . addslashes(__('SU', 'wpl')) . '", "' . addslashes(__('MO', 'wpl')) . '", "' . addslashes(__('TU', 'wpl')) . '", "' . addslashes(__('WE', 'wpl')) . '", "' . addslashes(__('TH', 'wpl')) . '", "' . addslashes(__('FR', 'wpl')) . '", "' . addslashes(__('SA', 'wpl')) . '"],
                        dayNames: 	 ["' . addslashes(__('Sunday', 'wpl')) . '", "' . addslashes(__('Monday', 'wpl')) . '", "' . addslashes(__('Tuesday', 'wpl')) . '", "' . addslashes(__('Wednesday', 'wpl')) . '", "' . addslashes(__('Thursday', 'wpl')) . '", "' . addslashes(__('Friday', 'wpl')) . '", "' . addslashes(__('Saturday', 'wpl')) . '"],
                        monthNames:  ["' . addslashes(__('January', 'wpl')) . '", "' . addslashes(__('February', 'wpl')) . '", "' . addslashes(__('March', 'wpl')) . '", "' . addslashes(__('April', 'wpl')) . '", "' . addslashes(__('May', 'wpl')) . '", "' . addslashes(__('June', 'wpl')) . '", "' . addslashes(__('July', 'wpl')) . '", "' . addslashes(__('August', 'wpl')) . '", "' . addslashes(__('September', 'wpl')) . '", "' . addslashes(__('October', 'wpl')) . '", "' . addslashes(__('November', 'wpl')) . '", "' . addslashes(__('December', 'wpl')) . '"],
                        dateFormat: "' . $jqdate_format . '",
                        gotoCurrent: true,
                        minDate: new Date(' . $mindate[0] . ', ' . $mindate[1] . '-1, ' . $mindate[2] . '),
                        maxDate: new Date(' . $maxdate[0] . ', ' . $maxdate[1] . '-1, ' . $maxdate[2] . '),
                        changeYear: true,
                        yearRange: "' . $mindate[0] . ':' . $maxdate[0] . '",
                        showOn: "both",
                        buttonImage: "' . wpl_global::get_wpl_asset_url('img/system/calendar3.png') . '",
                        buttonImageOnly: false,
                        buttonImageOnly: true,
                        firstDay: 1,
                        onSelect: function(dateText, inst)
                        {
                            item_save(dateText, '.$item_id.', '.$field->id.', "opendates", "'.$i.'", "'.$i.'", wplj("#wpl_c_'.$field->id.'_'.$i.'_desc").val(), "'.$jqdate_format.'");
                        }
                    });';
            }
        ?>
    });
</script>

<?php
$done_this = true;
}