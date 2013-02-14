<?php if (empty($this->_moderatecounts)) $this->_moderatecounts = array(); 
	if (count($this->_moderatecounts) > 0)
		foreach ($this->_moderatecounts as $ident => $count) : ?>
			<div class="fss_module_support_item">
				<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=moderate&ident=' . $ident ); ?>">
					<?php echo $this->handlers[$ident]->GetDesc(); ?> (<?php echo $count['count']; ?>)
				</a>
			</div>
<?php endforeach; ?>

<?php if (count($this->_moderatecounts) == 0): ?>
	<div class="fss_module_support_item">
		<?php echo JText::_("NO_COMMENTS_FOR_MOD"); ?>
	</div>
<?php endif; ?>
