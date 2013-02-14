		<?php if (count($this->tags) == 0): ?>
			<?php echo JText::_('NONE_') ?>
		<?php else: ?>
			<?php foreach($this->tags as $tag): ?>
				<div class="fss_ticket_tag" id="tag_<?php echo $tag['tag']; ?>"><span class="fss_ticket_tag_text"><?php echo $tag['tag']; ?></span><a href="#" onclick="removetag('<?php echo $tag['tag']; ?>');return false;"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_delete.png"></a></div>
			<?php endforeach; ?>
		<?php endif; ?>

