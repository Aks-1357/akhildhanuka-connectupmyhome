<?php
/**
* @Copyright Freestyle Joomla (C) 2010
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*     
* This file is part of Freestyle Support Portal
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
?>
<?php if ($this->admin_create == 1): ?>

	<div class="fss_unreg_msg">
		<?php echo JText::sprintf("YOU_ARE_CREATING_A_NEW_SUPPORT_TICKET_FOR_EMAIL",$this->user['name'],$this->user['username'],$this->user['email']); ?>
	</div>
	<div class="fss_spacer"></div>
	<div class="fss_spacer"></div>
<?php elseif ($this->admin_create == 2): ?>

	<div class="fss_unreg_msg">
		<?php echo JText::sprintf("YOU_ARE_CREATING_A_NEW_SUPPORT_TICKET_FOR_UNREGISTERED_USER_EMAIL",$this->unreg_name,$this->unreg_email); ?>
	</div>
<div class="fss_spacer"></div>
<div class="fss_spacer"></div>

<?php endif; ?>

<?php if ($this->userid > 0): ?>

	<div class='ffs_tabs'>
	<a class='ffs_tab fss_tab_selected' href='<?php echo FSSRoute::_('index.php?option=com_fss&view=ticket&layout=open'); ?>'><?php echo JText::_("OPEN_NEW_TICKET"); ?></a>

	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&tickets=open&layout=' ); ?>'><?php echo JText::sprintf("VIEW_OPEN",$this->count['all']-$this->count['closed']); ?></a>
	
	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&tickets=closed&layout=' ); ?>'><?php echo JText::sprintf("VIEW_CLOSED",$this->count['closed']); ?></a>
	
	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&tickets=all&layout=' ); ?>'><?php echo JText::sprintf("VIEW_ALL",$this->count['all']); ?></a>
	
</div>

<?php else: ?>

	<div class="fss_unreg_msg">
		<?php echo JText::sprintf("CREATE_UNREG",$this->email,FSSRoute::_( '&layout=open&what=reset' )); ?>
	</div>
	<div class="fss_spacer"></div>
	<div class="fss_spacer"></div>

	<div class='ffs_tabs'>
		<a class='ffs_tab fss_tab_selected' href='<?php echo FSSRoute::_( '&layout=open&limitstart=0' ); ?>'><?php echo JText::_('OPEN_NEW_TICKET'); ?></a>
		<a class='ffs_tab' href='<?php echo FSSRoute::_( '&what=find&layout=view' ); ?>'><?php echo JText::_('VIEW_TICKET'); ?></a>
	</div>

<?php endif; ?>

