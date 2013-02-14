<?php
/**
* @Copyright Freestyle Joomla (C) 2010
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*     
* This file is part of Freestyle Support Portal
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
<form method="post" action="<?php echo FSSRoute::x('&'); ?>" id="adminForm" name="adminForm">
	<div class="fss_content_filter">
		<div class="fss_content_filter_search">
			<input name="search" size="12" id="fss_search" value="<?php echo $this->filter_values['search']; ?>">
			<button class="button"><?php echo JText::_('GO'); ?></button>
			<button class="button" id="fss_content_reset"><?php echo JText::_('RESET'); ?></button>
		</div>
		<div class="fss_content_filter_item">
			<?php echo $this->filter_html['published']; ?>
		</div>
		<?php if ($this->permission['artperm'] > 1): ?>
	<div class="fss_content_filter_item">
				<?php echo $this->filter_html['userid']; ?>
	</div>
			<?php endif; ?>
			<?php foreach ($this->filters as $filter): ?>
	<div class="fss_content_filter_item">
					<?php echo $this->filter_html[$filter->field]; ?>
	</div>
		<?php endforeach; ?>

	</div>
	<div class="fss_clear"></div>
	<input name="order" type="hidden" id="fss_order" value="<?php echo $this->filter_values['order']; ?>">
	<input name="order_dir" type="hidden" id="fss_order_dir" value="<?php echo $this->filter_values['order_dir']; ?>">
	
	<input name="limit_start" type="hidden" type="hidden" id="limitstart" value="<?php echo $this->filter_values['limitstart']; ?>">
	<table width="100%" class="fss_content_list">
		<thead>
			<tr>
				<td width="30">ID</td>
				<?php foreach($field->rel_lookup_display as $fieldname => $finfo): ?>
				<td><a href='#' class="filter_field" order="<?php echo $fieldname; ?>"><?php echo $finfo['desc']; ?></a></td>	
				<?php endforeach; ?>			
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->pick_data as $item) : ?>
				<tr>
					<td><?php echo $item[$field->rel_lookup_id]; ?></td>
					<?php foreach($field->rel_lookup_display as $fieldname => $finfo): ?>
					<td>
						<?php if ($field->rel_lookup_pick_field == $fieldname) : ?>
							<a href="#" id="pick_<?php echo $item[$field->rel_lookup_id]; ?>" class='pick_link'>
						<?php endif; ?>
						<?php echo $item[$finfo['alias']]; ?>
						<?php if ($field->rel_lookup_pick_field == $fieldname) : ?>
							</a>
						<?php endif; ?>
					</td>	
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class='fss_content_pagination'><?php echo $this->_pagination->getListFooter(); ?></div>
</form>

<script>
jQuery(document).ready(function () {
	jQuery('.pick_link').click(function (ev) {
		ev.preventDefault();
		var id = jQuery(this).attr('id').split('_')[1];
		title = jQuery(this).text();
		parent.AddRelatedItem('<?php echo $this->pick_field; ?>', id, title);
	});
});
</script>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content.js';
//include 'components/com_fss/assets/js/content.js'; ?>
</script>