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
<?php // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","ADMIN_OVERVIEW"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>

<?php if ($this->permission['support']): ?>
	<div class="fss_support_admin_section">
		<?php echo FSS_Helper::PageSubTitle("<a href='".FSSRoute::x( '&layout=support' )."'>
		<img class='fss_support_main_image' src='". JURI::root( true ) ."/components/com_fss/assets/images/support/support_24.png'>" . JText::_("SUPPORT_TICKETS"). "</a>",false); ?>
<div class="fss_moderate_status">
		<ul>
		<?php
		 //echo JText::sprintf("TICKET_STATUS",$this->ticketopen,$this->ticketfollow,$this->ticketuser,FSSRoute::x( '&layout=support' )); 
		FSS_Ticket_Helper::GetStatusList();
		$counts = $this->get('TicketCount');
		FSS_Helper::Tr(FSS_Ticket_Helper::$status_list);
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($status->def_archive) continue;
			if ($status->is_closed) continue;
			if (!array_key_exists($status->id, $counts)) continue;
			if ($counts[$status->id] < 1) continue;
			echo "<li>" . $status->title . ": <b>" . $counts[$status->id] . "</b> - <a href='".FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&tickets=' . $status->id ) . "'>" . JText::_("VIEW_NOW") . "</a></li>";	
		}
		?>
		</ul>
</div>
		<br>
		<div class="fss_spacer"></div>
		<div class="fss_admin_create_cont">
			<div class="fss_admin_create_130"><?php echo JText::_("CREATE_TICKET_FOR"); ?></div>
			<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=newregistered' ); ?>"><?php echo JText::_("REGISTERED_USER"); ?></a></span>
			<span class="fss_admin_create_sub"><a href="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=newunregistered' ); ?>"><?php echo JText::_("UNREGISTERED_USER"); ?></a></span>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->permission['mod_kb']): ?>
	<div class="fss_support_admin_section">
		<?php echo FSS_Helper::PageSubTitle("<a href='".FSSRoute::x( '&layout=moderate&ident=' )."'>
		<img class='fss_support_main_image' src='". JURI::root( true ) ."/components/com_fss/assets/images/support/moderate_24.png'>" . JText::_("MODERATE"). "</a>",false); ?>
		<?php echo JText::sprintf("MOD_STATUS",$this->moderatecount,FSSRoute::x( '&layout=moderate&ident=' )); ?>
		<?php $this->comments->DisplayModStatus(); ?>
	</div>
<?php endif; ?>

<?php if ($this->permission['artperm'] > 0): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_contentinfo.php';
//include "components/com_fss/views/admin/snippet/_contentinfo.php" ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>