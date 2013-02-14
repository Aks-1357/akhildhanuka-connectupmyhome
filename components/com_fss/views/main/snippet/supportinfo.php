<!--<div class="fss_menu_support_cont">
<?php if ($this->permission['support']): ?>
	<div class="fss_menu_support">
		<img  class="fss_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/support_48.png'>
		<div class="fss_menu_support_title"><?php echo  JText::_("SUPPORT_TICKETS"); ?></div>
		<div class="fss_menu_support_items">
			<div class="fss_menu_support_item">
				<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=support&tickets=open' ); ?>">
					<?php echo JText::sprintf('SA_OPEN', $this->ticketopen); ?>
				</a>
			</div>
			<div class="fss_menu_support_item">
				<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=support&tickets=follow' ); ?>">
					<?php echo JText::sprintf('SA_FOLLOW', $this->ticketfollow); ?>
				</a>
			</div>
			<div class="fss_menu_support_item">
				<a href="<?php echo FSSRoute::_( 'index.php?option=com_fss&view=admin&layout=support&tickets=reply' ); ?>">
					<?php echo JText::sprintf('SA_WAITING', $this->ticketuser); ?>
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->permission['mod_kb']): ?>
	<div class="fss_menu_support">
		<img  class="fss_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/support/moderate_48.png'>
		<div class="fss_menu_support_title"><?php echo  JText::_("MODERATE"); ?></div>
		<div class="fss_menu_support_items">
		<?php $this->comments->DisplayModStatus("modstatus_menu.php"); ?>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->permission['mod_kb'] || $this->permission['support']): ?>

	<div class='fss_clear'></div>
	<div class='fss_spacer'></div>
<?php endif; ?>
</div>-->