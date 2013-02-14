
<table class='fss_ticket_messages' width='100%' cellspacing=0 cellpadding=4>
<tr id="messagereply" style="display: none;">
	<td colspan="3" class="fss_ticket_message_message" style="padding:4px;">
		<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_reply.php';
		//include "components/com_fss/views/ticket/snippet/_reply.php" ?>
	</td>
</tr>
<tr id="messagepleasewait" style="display: none;">
	<td colspan="3" class="fss_ticket_message_message" style="padding:4px;align:center;color:#AAAAAA;font-size:150%;">
		<?php echo JText::_('PLEASE_WAIT'); ?>
	</td>
</tr>
<?php foreach ($this->messages as $message) : ?>
<?php if ($message['admin'] == 2 || $message['admin'] == 3) continue; ?>
<tr>
	<td class='fss_ticket_message_head
	<?php if ($message['admin'] == 1) echo "fss_ticket_message_head_admin";?>
	 <?php if ($message['admin'] == 2) echo "fss_ticket_message_head_private";?>
	 <?php if ($message['admin'] == 0) echo "fss_ticket_message_head_user";?>
'>
<table cellspacing=0 cellpadding=0 width='100%' class='fss_ticket_msgheader'>
<tr>
<td>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/message.png'>&nbsp;<?php echo $message['subject']; ?>
				</td>
				<td width='22' align=right>	
					<?php if ($message['admin'] == 1){ ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/staff.png'>
					<?php } else { ?>
					<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/user.png'>
					<?php } ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class='fss_ticket_messages_info_cont'>
<table class='fss_ticket_messages_info fss_ticket_msgheader' width='100%'>
			<tr>
				<td class='fss_ticket_message_user' width='60%'>
				<?php if ($message['admin'] == 0 && $this->ticket['unregname'] != "") : ?>
					<?php echo $this->ticket['unregname']; ?>
				<?php else: ?>
					<?php echo $message['name']; ?>
				<?php endif; ?>
				</td>
				<td class='fss_ticket_message_date' width='40%' align=right>	
					<?php echo FSS_Helper::Date($message['posted'], FSS_DATETIME_MID); ?>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
<td class='fss_ticket_message_message'>
		<?php 
			$msg = $message['body'];
			$msg = str_replace("<","&lt;",$msg); 
			$msg = str_replace(">","&gt;",$msg); 
			$msg = str_replace("&amp;","&",$msg);
			$msg = str_replace("\n","<br />",$msg);
			echo $msg;
		?>
		<?php if (array_key_exists("attach", $message)) : ?>
			<?php foreach ($message['attach'] as &$attach): ?>
				<div class="fss_ticket_message_attach"><a href='<?php echo FSSRoute::x( 'index.php?option=com_fss&view=ticket&fileid=' . $attach['id'] ); ?>'><img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/download-16x16.png'> <?php echo $attach['filename']; ?></a></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>
</table>
<div class="fss_spacer"></div><div class="fss_spacer"></div>

<table width="100%" cellpadding="0" cellspacing="0" class="fss_ticket_message_head_info fss_ticket_messages">
	<tr>
		<td width="25%" class="fss_ticket_message_head_info"><?php echo JText::_('MESSAGE_KEY'); ?></td>
		<td width="25%" class="fss_ticket_message_head_info fss_ticket_message_head_user"><?php echo JText::_('MESSAGE_KEY_USER'); ?></td>
		<td width="25%" class="fss_ticket_message_head_info fss_ticket_message_head_admin"><?php echo JText::_('MESSAGE_KEY_HANDLER'); ?></td>
	</tr>
</table>
