	<div class="fss_support_admin_section">
		<?php echo FSS_Helper::PageSubTitle("<a href='".FSSRoute::x( '&layout=content' )."'>
		<img class='fss_support_main_image' src='". JURI::root( true ) ."/components/com_fss/assets/images/support/content_24.png'>" . JText::_("CONTENT"). "</a>",false); ?>
		<?php echo FSS_Helper::PageSubTitle2("YOUR_ARTICLES"); ?>
		<div class="fss_moderate_status">
		<ul>
		<?php foreach ($this->artcounts as $type): ?>
			<li>
				<?php echo $type['desc']; ?>: <b><?php echo $type['counts']['user_total']; ?> </b> &nbsp;&nbsp;
				(<b><?php echo $type['counts']['user_pub']; ?></b> <?php echo JText::_('PUBLISHED'); ?>, 
				<b><?php echo $type['counts']['user_unpub']; ?></b> <?php echo JText::_('UNPUBLISHED'); ?>) - 
				<a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=content&type=' . $type['id'] ); ?>"><?php echo JText::_('VIEW_NOW'); ?></a>
			</li>
		<?php endforeach; ?>
		</ul>
		</div>
		
		<?php if ($this->permission['artperm'] > 1): ?>
		<?php echo FSS_Helper::PageSubTitle2("ALL_ARTICLES"); ?>
		<div class="fss_moderate_status">
		<ul>
		<?php foreach ($this->artcounts as $type): ?>
			<li>
				<?php echo $type['desc']; ?>: <b><?php echo $type['counts']['total']; ?></b> &nbsp;&nbsp;
				(<b><?php echo $type['counts']['pub']; ?></b> <?php echo JText::_('PUBLISHED'); ?>, 
				<b><?php echo $type['counts']['unpub']; ?></b> <?php echo JText::_('UNPUBLISHED'); ?>) - 
				<a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=content&type=' . $type['id'] ); ?>"><?php echo JText::_('VIEW_NOW'); ?></a>
			</li>
		<?php endforeach; ?>
		</ul>
		</div>
	
		<?php endif; ?>
	</div>