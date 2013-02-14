	<div class="fss_content_toolbar">
		<div class="fss_content_toolbar_title">
			<?php echo  FSS_Helper::PageSubTitle($this->descs); ?>
		</div>
<?php /*if ($this->permission['artperm'] > 1): ?>
		<div class="fss_content_toolbar_item">
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/publish.png" /><br />
			<span>Publish</span>
		</div>
		<div class="fss_content_toolbar_item">
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/unpublish.png" /><br />
			<span>Unpublish</span>
		</div>
<?php endif;*/ ?>
		<div class="fss_content_toolbar_item" id="fss_content_new">
			<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/new.png" /><br />
			<span><?php echo JText::_('NEW');?></span>
		</div>
	</div>
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
	<input name="order" type="hidden" id="fss_order" value="<?php echo $this->filter_values['order']; ?>">
	<input name="order_dir" type="hidden" id="fss_order_dir" value="<?php echo $this->filter_values['order_dir']; ?>">
	
	<input name="limit_start" type="hidden" id="limitstart" value="<?php echo $this->filter_values['limitstart']; ?>">
	
	<div class="fss_clear"></div>
	<div class="fss_spacer"></div>
	<?php if (count($this->data) > 0): ?>
	<table width="100%" class="fss_content_list">
			<thead>
				<tr>
					<?php foreach ($this->list as $list) :
						$field = $this->fields[$list]; ?>
						<th><a href='#' class="filter_field" order="<?php echo $field->field; ?>"><?php echo $field->desc; ?></a></th>
					<?php endforeach; ?>
					<?php if ($this->list_added): ?><th><a href='#' class="filter_field" order="added"><?php echo JText::_('ADDED'); ?></a></th><?php endif; ?>
					<?php if ($this->list_published): ?><th><a href='#' class="filter_field" order="published"><?php echo JText::_('PUB'); ?></a></th><?php endif; ?>
					<?php if ($this->permission['artperm'] > 1): ?>
					<?php if ($this->list_user): ?><th><a href='#' class="filter_field" order="u.name"><?php echo JText::_('AUTHOR'); ?></a></th><?php endif; ?>
					<?php endif; ?>
				</tr>
			</thead>
		
			<tbody>
			<?php foreach ($this->data as &$item) :?>
				<tr>
					<?php foreach ($this->list as $list):
						$field = $this->fields[$list]; ?>
						<td>
							<?php if ($field->link): ?>
								<a href='<?php echo FSSRoute::x('index.php?option=com_fss&view=admin&layout=content&type=' . $this->id . '&what=edit&id=' . $item['id']); ?>'>
							<?php endif; ?>
							<?php if ($field->type == "lookup"): ?>
								<?php echo $this->GetLookupText($field, $item[$field->field]); ?>
							<?php else: ?>
								<?php echo $item[$field->field]; ?>
							<?php endif; ?>
							<?php if ($field->link): ?></a><?php endif; ?>
						</td>
					<?php endforeach; ?>
					<?php if ($this->list_added): ?>
						<td nowrap><?php echo FSS_Helper::Date($item['added'],FSS_DATE_SHORT); ?></td>
					<?php endif; ?>

					<?php if ($this->list_published): ?>
						<td nowrap>
							<a href="#" id="publish_<?php echo $item['id']; ?>" class="fss_publish_button" state="<?php echo $item['published']; ?>">
								<?php echo FSS_Helper::GetPublishedText($item['published']); ?>
							</a>
						</td>
					<?php endif; ?>

					<?php if ($this->permission['artperm'] > 1): ?>
					<?php if ($this->list_user): ?>
						<td><?php echo $item['name']; ?></td>
					<?php endif; ?>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>	
		</table>
		<div class='fss_content_pagination'><?php echo $this->_pagination->getListFooter(); ?></div>
	<?php else: ?>
		<div><?php echo JText::sprintf("YOU_HAVE_NO",$this->descs); ?></div>
	<?php endif; ?>
</form>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'assets'.DS.'js'.DS.'content.js';
	//include 'components/com_fss/assets/js/content.js'; ?>
</script>