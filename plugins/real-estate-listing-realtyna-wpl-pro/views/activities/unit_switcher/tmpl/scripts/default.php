<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function()
{
	wplj('#wpl_unit_switcher_activity<?php echo $this->activity_id; ?>_select').wpl_unit_switcher(
    {
        type: 'select',
        url: '<?php echo wpl_global::get_full_url(); ?>',
        unit_type: <?php echo $this->unit_type; ?>,
        selector: '#wpl_unit_switcher_activity<?php echo $this->activity_id; ?>_select',
    });
});
</script>