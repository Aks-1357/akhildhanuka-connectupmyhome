<div class="fss_moderate_status">
	<ul>
<?php if (is_array($this->_moderatecounts)) foreach ($this->_moderatecounts as $ident => $count) : ?>
<li><?php echo $this->handlers[$ident]->GetDesc(); ?>: <b><?php echo $count['count']; ?></b> - <a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=moderate&ident=' . $ident ); ?>"><?php echo JText::_('VIEW_NOW'); ?></a></li>
<?php endforeach; ?>
	</ul>
</div>

