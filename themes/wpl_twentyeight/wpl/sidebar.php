<?php
/** no direct access **/
defined('_WPLTEXEC') or die('Restricted access');

?>
<?php if($this->is_active_sidebar($sidebar_name)): ?>
	<div class="sidebar-container" id="sidebar_container<?php echo $sidebar_name; ?>">
		<div class="sidebar-inner">
			<div class="widget-area <?php echo ($sidebar_name == 'sidebar-4' ? 'row' : ''); ?>">
				<?php $this->load_sidebar($sidebar_name); ?>
			</div>
		</div>
	</div>
<?php endif; ?>