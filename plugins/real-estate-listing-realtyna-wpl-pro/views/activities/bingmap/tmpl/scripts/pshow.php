<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
/** default values in case of no marker to showing **/
var default_zoom<?php echo $this->activity_id; ?> = <?php echo $this->default_zoom; ?>;
var wpl_map_initialized<?php echo $this->activity_id; ?> = false;

wplj(document).ready(function()
{
    if(wplj('#wpl_map_canvas<?php echo $this->activity_id; ?>').is(':visible')) wpl_pshow_map_init<?php echo $this->activity_id; ?>();
});

function wpl_pshow_map_init<?php echo $this->activity_id; ?>()
{
	if(wpl_map_initialized<?php echo $this->activity_id; ?>) return;
	
	var marker = new Microsoft.Maps.Location(<?php echo $this->markers[0]['googlemap_lt']; ?>, <?php echo $this->markers[0]['googlemap_ln']; ?>);
	var map = new Microsoft.Maps.Map(document.getElementById("wpl_map_canvas<?php echo $this->activity_id; ?>"),
	{
        credentials: "<?php echo $this->authentication_key; ?>",
        center: marker,
        zoom: default_zoom<?php echo $this->activity_id; ?>,
        mapTypeId: Microsoft.Maps.MapTypeId.birdseye
	});
    
    var pin = new Microsoft.Maps.Pushpin(marker);
    map.entities.push(pin);

	/** set true **/
	wpl_map_initialized<?php echo $this->activity_id; ?> = true;
}
</script>