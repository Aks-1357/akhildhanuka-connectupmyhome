	<?php $ident = -1; $itemid = -1; $count = 0; ?>
	<?php if (is_array($this->_data))
			foreach ($this->_data as $ident => $articles): ?>
		<div class="fss_moderate_ident">
			<div class='fss_moderate_ident_title'><?php echo $this->handlers[$ident]->GetDesc(); ?></div>
			<div class="fss_moderate_ident_content">
			<?php foreach ($articles as $itemid => $comments): ?>
				<div class="fss_moderate_article">
					<div class='fss_moderate_article_title'><a href='<?php echo $this->handlers[$ident]->GetItemLink($itemid); ?>'><?php echo $this->handlers[$ident]->GetItemTitle($itemid); ?></a></div>
					<div class="fss_moderate_article_content">
					<?php if ($comments && count($comments) > 0) foreach ($comments as $this->comment): ?>
						<?php $count++; include $this->tmplpath . DS .'comment.php' ?>
					<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
	<?php if ($count == 0) : ?>
		<div class='fss_moderate_ident_title'><?php echo JText::_('NO_COMMENTS_FOUND'); ?></div>
	<?php endif; ?>