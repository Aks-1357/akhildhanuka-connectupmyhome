		<?php if (count($this->ticket['cc']) == 0): ?>
			<?php echo JText::_('NONE_') ?>
			<?php else: ?>
			<?php foreach($this->ticket['cc'] as $cc): ?>
				<div class="fss_ticket_tag" id="tag_<?php echo $cc['id']; ?>"><span class="fss_ticket_tag_text"><?php echo $cc['name']; ?></span><a href="#" onclick="removecc('<?php echo $cc['id']; ?>');return false;"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_delete.png"></a></div>
			<?php endforeach; ?>
		<?php endif; ?>

