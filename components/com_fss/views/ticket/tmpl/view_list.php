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
<?php
 
// No direct access

defined('_JEXEC') or die('Restricted access'); ?>
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","CURRENT_SUPPORT_TICKETS"); ?>
<div class="fss_spacer"></div>
<div class='ffs_tabs'>
<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open&limitstart=0' ); ?>'><?php echo JText::_("OPEN_NEW_TICKET"); ?></a>
<a class='ffs_tab <?php if ($this->ticket_view == "open") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=&tickets=open&ticketid=&limitstart=0' ); ?>'>
<?php echo JText::sprintf("VIEW_OPEN",$this->count['all']-$this->count['closed']); ?>
</a>
<a class='ffs_tab <?php if ($this->ticket_view == "closed") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=&tickets=closed&ticketid=&limitstart=0' ); ?>'>
<?php echo JText::sprintf("VIEW_CLOSED",$this->count['closed']); ?>
</a>
<a class='ffs_tab <?php if ($this->ticket_view == "all") echo "fss_tab_selected";?>' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=&tickets=all&ticketid=&limitstart=0' ); ?>'>
<?php echo JText::sprintf("VIEW_ALL",$this->count['all']); ?>
</a>
</div>

<?php if (count($this->tickets) < 1) { ?>

<?php if ($this->ticket_view == "open") echo JText::_("YOU_CURRENTLY_HAVE_NO_OPEN_SUPPORT_TICKETS"); ?>
<?php if ($this->ticket_view == "closed") echo JText::_("YOU_CURRENTLY_HAVE_NO_CLOSED_SUPPORT_TICKETS"); ?>

<?php } else { ?>

<table class='fss_ticket_list' cellspacing=0 cellpadding=4 width=100%>
<tr>
<th><?php echo JText::_("TICKET_REF"); ?></th>
<th><?php echo JText::_("STATUS"); ?></th>
<th><?php echo JText::_("LAST_UPDATE"); ?></th>
<?php if (!FSS_Settings::get('support_hide_handler')) : ?>
<th><?php echo JText::_("HANDLER"); ?></th>
<?php endif; ?>
<?php if ($this->multiuser) : ?>
<th><?php echo JText::_("USER"); ?></th>
<?php endif; ?>
</tr>
<?php foreach ($this->tickets as $ticket): ?>
<tr id='ticket_<?php echo $ticket['id'];?>'
	 onmouseover="$('ticket_<?php echo $ticket['id'];?>').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';$('ticket_<?php echo $ticket['id'];?>_2').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';"
	 onmouseout="$('ticket_<?php echo $ticket['id'];?>').style.background = '';$('ticket_<?php echo $ticket['id'];?>_2').style.background = '';"
	 onclick="window.location.href = '<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticket&ticketid=' . $ticket['id'] );?>';"
>
	<td colspan=6 class='fss_ticket_title'><a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticket&ticketid=' . $ticket['id'] );?>'><?php echo $ticket['title']; ?></a></td>
</tr>
<tr id='ticket_<?php echo $ticket['id'];?>_2'
	onmouseover="$('ticket_<?php echo $ticket['id'];?>').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';$('ticket_<?php echo $ticket['id'];?>_2').style.background = '<?php echo FSS_Settings::get('css_hl'); ?>';"
	onmouseout="$('ticket_<?php echo $ticket['id'];?>').style.background = '';$('ticket_<?php echo $ticket['id'];?>_2').style.background = '';"
	 onclick="window.location.href = '<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticket&ticketid=' . $ticket['id'] );?>';"
	>
	<td class='fss_ticket_row'><?php echo $ticket['reference']; ?></td>
	<td class='fss_ticket_row'><span style='color: <?php echo $ticket['color']; ?>'>
		<?php
		$userstatus = FSS_Helper::TrF("userdisp", $ticket['userdisp'], $ticket['str']);
		$status = FSS_Helper::TrF("title", $ticket['status'], $ticket['str']);
		echo $userstatus ? $userstatus : $status;		
		?>	
	</span></td>
	<td class='fss_ticket_row'>
		<?php echo FSS_Helper::Date($ticket['lastupdate'], FSS_DATETIME_MID); ?>
	</td>
<?php if (!FSS_Settings::get('support_hide_handler')) : ?>
	<td class='fss_ticket_row'><?php if ($ticket['assigned']) {echo $ticket['assigned'];} else {echo JText::_("UNASSIGNED");} ?></td>
<?php endif; ?>
<?php if ($this->multiuser) : ?>
	<td class='fss_ticket_row'><?php echo $ticket['user']; ?></td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</table>
<div class="fss_spacer"></div>
<form id="adminForm" action="<?php echo FSSRoute::x('&limitstart=0');?>" method="post" name="adminForm">
	<?php echo $this->pagination->getListFooter(); ?>
</form>
<?php } ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>