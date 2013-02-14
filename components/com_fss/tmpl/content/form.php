	<form id="fss_form" action="<?php echo FSSRoute::x('index.php?option=com_fss&view=admin&layout=content&type=' . $this->id); ?>" method='post'>
	<input type="hidden" name="return" value="<?php echo JRequest::getVar("return",""); ?>" />
	<div class="fss_content_toolbar">
		<div class="fss_content_toolbar_title">
			<?php echo  FSS_Helper::PageSubTitle($this->descs . ': ' . ($this->item['id'] > 0 ? JText::_('EDIT') : JText::_('CREATE'))); ?>
		</div>
		<div class="fss_content_toolbar_item" id='fss_form_cancel'>
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/cancel.png" /><br />
				<span><?php echo JText::_('CANCEL');?></span>
		</div>
		<div class="fss_content_toolbar_item" id="fss_form_savenew">
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/savenew.png" /><br />
				<span><?php echo JText::_('SAVE_A_ADD'); ?></span>
		</div>
		<div class="fss_content_toolbar_item" id="fss_form_save">
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/save.png" /><br />
				<span><?php echo JText::_('SAVE'); ?></span>
		</div>
		<div class="fss_content_toolbar_item" id="fss_form_apply">
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/publish.png" /><br />
				<span><?php echo JText::_('APPLY'); ?></span>
		</div>
		<?php if ($this->item['id'] > 0) : ?>
		<div class="fss_content_toolbar_item" id="fss_form_view">
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/view.png" /><br />
				<span><?php echo JText::_('VIEW'); ?></span>
		</div>
		<?php endif; ?>
	</div>

		<table width="100%">
			<?php if ($this->permission['artperm'] > 2) : ?>
			<tr>
				<td>
					<span class="fss_content_form_title"><?php echo JText::_('PUBLISHED'); ?>:</span>&nbsp;&nbsp;
					<input type='checkbox' name='published' value='1' <?php if ($this->item['published'] == 1) { echo " checked='yes' "; } ?>>&nbsp;&nbsp;&nbsp;&nbsp;
					<span class="fss_content_form_title"><?php echo JText::_('AUTHOR'); ?>:</span>&nbsp;&nbsp;
					<?php echo $this->authorselect; ?>
				</td>
			</tr>
			<?php endif; ?>
			
			<?php foreach ($this->edit as $edit):
			$field = $this->GetField($edit);
				//print_p($field);
			 ?>
			<tr>
				<th><div class="fss_content_form_title"><?php echo $field->desc; ?></div> <?php echo FSS_Helper::ShowError($this->errors,$field->field); ?></th>
			</tr>
			<tr>
				<td>
				<?php if ($field->type == "string") : ?>
					<input name="<?php echo $field->field; ?>" size="32" value="<?php echo JViewLegacy::escape($this->item[$field->field]); ?>" />
				<?php elseif ($field->type == "text"):?>
					<?php
					$text = $this->item[$field->field];
					if ($field->more)
					{
						if ($this->item[$field->more])
						{
							$text .= '<hr id="system-readmore" />';
							$text .= $this->item[$field->more];                     
						}
					}
					$editor =& JFactory::getEditor();
					echo $editor->display($field->field, $text, '550', '400', '60', '20', true);
					
					?>
				<?php elseif ($field->type == "long"):?>
					<textarea name="<?php echo $field->field; ?>" id="<?php echo $field->field; ?>" cols="80" rows="4" style="width:544px;"><?php echo JViewLegacy::escape($this->item[$field->field]); ?></textarea>
				<?php elseif ($field->type == "products"):?>
					<div>
						<?php echo JText::_("SHOW_FOR_ALL_PRODUCTS"); ?>
						<?php echo $field->products_yesno; ?>
					</div>
					<div id="prodlist_<?php echo $field->field; ?>" <?php if ($this->item[$field->field]) echo 'style="display:none;"'; ?>>
						<?php echo $field->products_check; ?>
					</div>

				<?php elseif ($field->type == "related"):?>
					<button class="button fss_content_related_button" id="relbtn_<?php echo $field->field; ?>"><?php echo $field->rel_button_txt; ?></button>
					<div id="related_items">
					<?php foreach ($field->rel_ids as $id => $title): ?>
						<div class="fss_content_related_item" id="relitem_<?php echo $field->field; ?>_<?php echo $id; ?>">
							<a href='#'>
								<img  class="fss_tab_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/remove_related.png'>
								<?php echo JText::_('Remove'); ?>
							</a> - <?php echo $title; ?>
						</div>
					<?php endforeach; ?>
					</div>
					<input type="hidden" name="<?php echo $field->field; ?>" value="<?php echo $field->rel_id_list; ?>">
				<?php elseif ($field->type == "lookup"):?>
					<?php echo $this->LookupInput($field, $this->item);	?>
				<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>		
		</table>

		<input type="hidden" name="id" value="<?php echo $this->item['id']; ?>" />
		<input type="hidden" name="what" value="" />
	</form>


<script>
function DoAllProdChange(field)
{
	var field_input = jQuery('input[name="' + field+ '"]');
	var pldiv = jQuery('#prodlist_' + field);

	if (field_input.attr('checked') == "checked")
	{
		pldiv.css('display','block');
	} else {
		pldiv.css('display','none');
	}						
}

function FormButton(task)
{
	jQuery('#fss_form').find('input[name="what"]').val(task);
	jQuery('#fss_form').submit();
}

jQuery(document).ready(function () {
	jQuery('#fss_form_cancel').click(function (ev) {
		ev.preventDefault();
		FormButton("cancel")
	});
	jQuery('#fss_form_save').click(function (ev) {
		ev.preventDefault();
		FormButton("save")
	});
	jQuery('#fss_form_apply').click(function (ev) {
		ev.preventDefault();
		FormButton("apply")
	});
	jQuery('#fss_form_savenew').click(function (ev) {
		ev.preventDefault();
		FormButton("savenew")
	});
	
	jQuery('#fss_form_view').click(function (ev) {
		ev.preventDefault();
		window.open('<?php echo $this->viewurl; ?>','article');
	});
	
	jQuery('.fss_content_toolbar_item').mouseenter(function () {
		jQuery(this).css('background-color', '<?php echo FSS_Settings::get('css_hl'); ?>');
	});
	jQuery('.fss_content_toolbar_item').mouseleave(function () {
		jQuery(this).css('background-color' ,'white');
	});

	jQuery('.fss_content_related_item a').click(function (ev) {
		ev.preventDefault();
		
		// remove link clicked
		var id = jQuery(this).parent().attr('id');
		var parts = id.split("_");
		var field = parts[1];
		var id = parts[2];
		RemoveRelatedItem(field, id);
	});
	
	jQuery('.fss_content_related_button').click(function (ev) {
		ev.preventDefault();
		var id = jQuery(this).attr('id').split("_")[1];
		
		// add related item
		TINY.box.show({iframe:'<?php echo FSSRoute::x('index.php?option=com_fss&view=admin&layout=content&tmpl=component&type=' . $this->id . '&what=pick&field='); ?>&field=' + id,width:800,height:500})
		// show popup for id
	});
	
	jQuery('#change_author').click(function(ev) {
		ev.preventDefault();
		TINY.box.show({iframe:'<?php echo FSSRoute::x('index.php?option=com_fss&view=admin&layout=content&tmpl=component&type=' . $this->id . '&what=author'); ?>',width:800,height:500})
	});
});

function RemoveRelatedItem(field, id)
{
	var values = jQuery('input[name="' + field + '"]').val();
	values = values.split(':');
	RemoveFromArray(values, id);
	values = values.join(":");
	jQuery('input[name="' + field + '"]').val(values);
	
	jQuery('#relitem_' + field + '_' + id).remove();
}

function RemoveFromArray(originalArray, itemToRemove)
{
	var j = 0;
	while (j < originalArray.length) 
	{
		// alert(originalArray[j]);
		if (originalArray[j] == itemToRemove) {
			originalArray.splice(j, 1);
		} else { 
			j++; 
		}
	}				
}

function PickUser(id, username, name)
{
	TINY.box.hide();
	jQuery('#content_authname').text(name);
	jQuery('#content_author').val(id);
}

function AddRelatedItem(field, id, title)
{
	TINY.box.hide();
	
	var values = jQuery('input[name="' + field + '"]').val();
	if (values == "")
	{
		values = new Array();
	} else {
		values = values.split(':');
	}
	var added = AddToArray(values, id);
	values = values.join(":");
	jQuery('input[name="' + field + '"]').val(values);
	
	if (added)
	{
		var html = "<div class='fss_content_related_item' id='relitem_" + field + "_" + id  + "'>";
		html += "	<a href='#'>";
		html += "		<img  class='fss_tab_image' src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/remove_related.png'>";
		html += "			<?php echo JText::_('Remove'); ?>";
		html += "		</a> - " + title;
		html += "</div>";	
		jQuery('#related_items').append(html);
	}
	
}

function AddToArray(ar, id)
{
	for (var i = 0 ; i < ar.length ; i++)
	{
		if (ar[i] == id)
			return false;
	}				
	
	// store new value
	ar[ar.length] = id;
	return true;
}
</script>
