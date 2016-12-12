<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$values = $this->values;
$this->_wpl_import($this->tpl_path.'.scripts.compare_js', true, true);

$properties_count = sizeof($this->properties);

$custom_items = array('gallery', 'property_title', 'property_address');
?>

<style type="text/css">
/** Same as top line, remove this after adding new layout too **/
.<?php echo $this->image_class ?>{
	width: <?php echo $this->image_width; ?>px;
	height: <?php echo $this->image_height; ?>px;
}
.wpl-compare-table > thead th:first-of-type
{
	height: <?php echo $this->image_height; ?>px;
}
.wpl-compare-table > tbody td {

	width: <?php echo $this->image_width ?>px;
}
</style>
<div>
<table class="wpl-compare-table">
	<thead>
		<tr>
			<!-- Dummy element for corresponding property image -->
			<th></th>
			<!-- Dummy element for corresponding Property title & Property address -->
			<th><span class="wpl-compare-label"></span></th>
			<th><span class="wpl-compare-label"></span></th>

			<?php foreach($this->fields as $category => $_fields): ?>
				<th class="wpl-compare-category">
					<span class="wpl-compare-text"><?php echo __($category, 'wpl') ?></span>
				</th>
				<?php
				foreach($_fields as $field):
					if($field->table_column == $this->property_title) continue;
				?>
					<th class="wpl-compare-label">
						<span class="wpl-compare-text"><?php echo __($field->name, 'wpl'); ?></span>
					</th>
				<?php endforeach; ?>	
			<?php endforeach; ?>

			<?php foreach($this->properties as $property): ?>

			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody data-wpl-dragscroll>
		<?php foreach ($custom_items as $_item): ?>
			<tr class="<?php echo 'wpl_'.$_item.'_row'; ?>">
				<?php foreach($this->properties as $pid => $property): ?>
					<?php if($_item == 'gallery'): ?>
						<td data-wpl-property="<?php echo isset($values[$pid][$this->property_title]) ? $values[$pid][$this->property_title] : '' ?>" data-wpl-is-gallery>
							<!-- Property link: <?php echo $property['property_link'] ?> -->
							<?php wpl_activity::load_position($this->image_activity_position, array('wpl_properties'=>array('current'=>$property))); ?>
						</td>
					<?php elseif($_item == 'property_title'): ?>
						<td data-wpl-property="<?php echo isset($values[$pid][$this->property_title]) ? $values[$pid][$this->property_title] : '' ?>">
							<a href="<?php echo $property['property_link'] ?>" class="wpl-compare-text wpl-compare-property-title"><?php echo in_array($this->property_title, $this->fields_list) ? $values[$pid][$this->property_title] : '' ?></a>
						</td>
					<?php elseif($_item == 'property_address'): ?>
						<td data-wpl-property="<?php echo isset($values[$pid][$this->property_title]) ? $values[$pid][$this->property_title] : '' ?>">
							<span class="wpl-compare-text wpl-compare-property-address"></span>
						</td>
					<?php endif; ?>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>

		<tr>
		</tr>

		<?php foreach($this->fields as $category => $_fields): ?>

			<tr class="wpl-compare-empty-row">
				<?php
				// echo '<td colspan="' . $properties_count . '"></td>';
				for($i=0;$i<$properties_count;$i++){ ?>
					<td>
						<span class="wpl-compare-text">&nbsp;</span>
					</td>
				<?php } ?>
			</tr>

			<?php
			foreach($_fields as $field):
				if($field->table_column == $this->property_title) continue;
				if(isset($this->compare_row[$field->table_column])) $current_compare = $this->compare_row[$field->table_column];
			?>

				<tr data-wpl-label="<?php echo $field->name ?>">
					<?php foreach($this->properties as $pid => $property): ?>
						<td data-wpl-property="<?php echo isset($values[$pid][$this->property_title]) ? $values[$pid][$this->property_title] : '' ?>">
							<span class="wpl-compare-text <?php echo isset($current_compare) ? ((isset($current_compare['best']) and $current_compare['best'] == $pid) ? 'wpl-compare-text-best' : ((isset($current_compare['worst']) and $current_compare['worst'] == $pid) ? 'wpl-compare-text-worst' : '')) : '' ?>">
								<?php echo isset($this->values[$pid][$field->table_column]) ? $this->values[$pid][$field->table_column] : '' ?>
							</span>
						</td>
					<?php endforeach; ?>
				</tr>

			<?php
				unset($current_compare);
			endforeach;
			?>

		<?php
		endforeach;
		?>

	</tbody>
</table>
</div>

<?php
/** Import JS file **/
$this->_wpl_import($this->tpl_path.'.scripts.compare_js', true, true);