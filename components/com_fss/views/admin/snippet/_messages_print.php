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
<?php if ($this->clean && $message['admin'] == 2) continue; ?>
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
	<td class='fss_ticket_message_head' style='padding:0px;margin:0px;'>
<table cellspacing=0 cellpadding=0 width='100%' style='width:100%;padding:0px;margin:0px;border:none;'>
			<tr>
				<td style="border: none;">
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/message.png'>&nbsp;<span id='subject_<?php echo $message['id']; ?>'><?php echo $message['subject']; ?></span>
				</td>
				<td width='60' align=right style="border: none;">	
					<?php if ($message['admin'] == 1){ ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/staff.png'>
					<?php } elseif ($message['admin'] == 2){ ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/staffonly.png'> 
					<?php } else { ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/user.png'>
					<?php } ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr class="fss_support_msg_normal">
	<td class='fss_ticket_messages_info_cont' style='padding:0px;margin:0px;'>
		<table class='fss_ticket_messages_info' width='100%'style='width:100%;padding:0px;margin:0px;border:none;'>
			<tr>
				<td class='fss_ticket_message_user' width='60%' style="border: none;">
				<?php if ($message['admin'] == 0 && $this->ticket['unregname'] != "") : ?>
					<?php echo $this->ticket['unregname']; ?>
				<?php else: ?>
					<?php echo $message['name']; ?>
				<?php endif; ?>
				</td>
				<td class='fss_ticket_message_date' width='40%' align=right style="border: none;">	
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
		<div id="message_raw_<?php echo $message['id']; ?>" style='display:none'><?php echo htmlentities($message['body']); ?></div>
		<?php if (array_key_exists("attach", $message)) : ?>
			<?php foreach ($message['attach'] as &$attach): ?>
				<div class="fss_ticket_message_attach"><img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/download-16x16.png'> <?php echo $attach['filename']; ?></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</table>
<div class="fss_spacer"></div><div class="fss_spacer"></div>
