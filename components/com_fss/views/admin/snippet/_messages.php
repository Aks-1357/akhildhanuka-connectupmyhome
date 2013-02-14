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
<table class='fss_ticket_messages' width='100%' cellspacing=0 cellpadding=4>
<?php foreach ($this->messages as $message) : ?>
<?php if ($message['admin'] == 3) : ?>
<tr class="fss_support_msg_audit" style="display:none;">
		<td class='fss_ticket_messages_info_cont'>
		<table cellspacing=0 cellpadding=0 width='100%'>
			<tr class="fss_support_msg_audit_row">
				<td width="100" valign="top">
					<b><?php if ($message['admin'] == 0 && $this->ticket['unregname'] != "") : ?>
						<?php echo $this->ticket['unregname']; ?>
					<?php else: ?>
						<?php echo $message['name']; ?>
					<?php endif; ?>
					</b>
				</td>
				<td valign="top">
					<?php 
					$msg = $message['body'];
					$msg = str_replace("<","&lt;",$msg); 
					$msg = str_replace(">","&gt;",$msg); 
					$msg = str_replace("&amp;","&",$msg);
					$msg = str_replace("\n","<br />",$msg);
					echo $msg;
					?>
				</td>
				<td width="140" nowrap valign="top">
					<b><?php echo FSS_Helper::Date($message['posted'],FSS_DATETIME_MID); ?></b>
				</td>
			</tr>
		</table>
	</td>
</tr>
<?php else: ?>
<tr class="fss_support_msg_normal">
	<td class='fss_ticket_message_head
	 <?php if ($message['admin'] == 1) echo "fss_ticket_message_head_admin";?>
	 <?php if ($message['admin'] == 2) echo "fss_ticket_message_head_private";?>
	 <?php if ($message['admin'] == 0) echo "fss_ticket_message_head_user";?>
'>
<table cellspacing=0 cellpadding=0 width='100%'>
<tr>
<td>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/message.png'>&nbsp;<span id='subject_<?php echo $message['id']; ?>'><?php echo $message['subject']; ?></span>
				</td>
				<td width='60' align=right>	
					<?php if ($message['admin'] == 1){ ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/staff.png'>
					<?php } elseif ($message['admin'] == 2){ ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/staffonly.png'> 
					<?php } else { ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/user.png'>
					<?php } ?>
					<a class="editmessage" id="edit_<?php echo $message['id']; ?>" href="#">
						<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png" alt="Edit"/>
					</a>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr class="fss_support_msg_normal">
	<td class='fss_ticket_messages_info_cont'>
		<table class='fss_ticket_messages_info' width='100%'>
			<tr>
				<td class='fss_ticket_message_user' width='60%'>
				<?php if ($message['admin'] == 0 && $this->ticket['unregname'] != "") : ?>
					<?php echo $this->ticket['unregname']; ?>
				<?php else: ?>
					<?php echo $message['name']; ?>
				<?php endif; ?>
				</td>
				<td class='fss_ticket_message_date' width='40%' align=right>	
				<?php echo FSS_Helper::Date($message['posted'],FSS_DATETIME_MID); ?>
</td>
</tr>
</table>
</td>
</tr>
<tr class="fss_support_msg_normal">
<td class='fss_ticket_message_message'>
		<div id="message_<?php echo $message['id']; ?>">
		<?php 
		$msg = $message['body'];
		$msg = str_replace("<","&lt;",$msg); 
		$msg = str_replace(">","&gt;",$msg); 
		$msg = str_replace("&amp;","&",$msg);
		$msg = str_replace("\n","<br />",$msg);
		echo $msg;
		
		?>
</div>
		<div id="message_raw_<?php echo $message['id']; ?>" style='display:none'><?php echo superentities($msg); ?></div>
		<?php if (array_key_exists("attach", $message)) : ?>
			<?php foreach ($message['attach'] as &$attach): ?>
				<div class="fss_ticket_message_attach"><a href='<?php echo FSSRoute::x( '&fileid=' . $attach['id'] ); ?>'><img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/download-16x16.png'> <?php echo $attach['filename']; ?></a></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</table>
<div class="fss_spacer"></div><div class="fss_spacer"></div>

<table width="100%" cellpadding="0" cellspacing="0" class="fss_ticket_message_head_info">
	<tr>
		<td width="25%" class="fss_ticket_message_head_info"><?php echo JText::_('MESSAGE_KEY'); ?></td>
		<td width="25%" class="fss_ticket_message_head_info fss_ticket_message_head_user"><?php echo JText::_('MESSAGE_KEY_USER'); ?></td>
		<td width="25%" class="fss_ticket_message_head_info fss_ticket_message_head_admin"><?php echo JText::_('MESSAGE_KEY_HANDLER'); ?></td>
		<td width="25%" class="fss_ticket_message_head_info fss_ticket_message_head_private"><?php echo JText::_('MESSAGE_KEY_PRIVATE'); ?></td>
	</tr>
</table>
