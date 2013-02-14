<table class="fss_table fss_taglist" id="fss_taglist" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<th style="text-align: center;"><?php echo JText::_("ADD_TAGS"); ?></th>
	</tr>
	<tr>
		<td style="padding: 0px;">
			<div class="fss_taglist_scroll">
			<table class="fss_taglist_inner" width="100%" border="0" cellpadding="0" cellspacing="0">
			<?php foreach ($this->alltags as $tag): ?>
				<tr>
					<td>
						<A href='#' onclick="addtag('<?php echo $tag['tag']; ?>');return false;"><?php echo $tag['tag']; ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
			</div>
		</td>	
	</tr>
	<tr>
		<td>
			<div style="float:left;">
				<input name="tag" id="new_tag" class="tag_add_input" value="" size="9">
			</div>
			<div style="float:right;">
				<a href="#" onclick="addtag($('new_tag').value);return false;">
					<img class="fsj_tip" src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_add.png" border="0" alt="Tooltip" title="<?php echo JText::_('ADD_TAG'); ?>"/>
				</a>
			</div>
		</td>
	</tr>
</table>