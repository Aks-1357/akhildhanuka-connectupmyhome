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
<?php echo FSS_Helper::PageTitle("SUPPORT","VIEW_SUPPORT_TICKET"); ?>
<div class="fss_spacer"></div>
	<?php if ($this->userid > 0): ?>
	<div class='ffs_tabs'>
	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open' ); ?>'><?php echo JText::_("OPEN_NEW_TICKET"); ?></a>

	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&tickets=open' ); ?>'><?php echo JText::sprintf("VIEW_OPEN",$this->count['all']-$this->count['closed']); ?></a>

	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&tickets=closed' ); ?>'><?php echo JText::sprintf("VIEW_CLOSED",$this->count['closed']); ?></a>

	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&tickets=all' ); ?>'><?php echo JText::sprintf("VIEW_ALL",$this->count['all']); ?></a>

	<a class='ffs_tab fss_tab_selected' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=view&ticketid=' . JRequest::getVar('ticketid') ); ?>'><?php echo JText::_("VIEW_TICKET"); ?></a>
	</div>
<?php else: ?>
<div class='ffs_tabs'>
	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open' ); ?>'><?php echo JText::_("OPEN_NEW_TICKET"); ?></a>
	<a class='ffs_tab' href='<?php echo FSSRoute::_( 'index.php?option=com_fss&view=ticket&what=find' ); ?>'><?php echo JText::_("VIEW_DIFFERENT_TICKET"); ?></a>
	<a class='ffs_tab fss_tab_selected' href='<?php echo FSSRoute::x( '&layout=view' ); ?>'><?php echo JText::_("VIEW_TICKET"); ?></a>
	</div>
<?php endif; ?>

<?php echo FSS_Helper::PageSubTitle("TICKET_DETAILS"); ?>
<table class='fss_table' cellspacing=0 cellpadding=0>
<tr>
	<th><?php echo JText::_("TICKET_ID"); ?></th>
	<td colspan="2"><?php echo $this->ticket['reference']; ?></td>
</tr>
<?php if ($this->multiuser) : ?>
<tr>
	<th><?php echo JText::_("USER"); ?></th>
	<td colspan="2"><?php echo $this->user['name']; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->show_cc) : ?>
<tr>
	<th><?php echo JText::_("CC_USERS"); ?></th>
<td style="border-right:none;">
<div id="ccusers">
			<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php';
			//include "components/com_fss/views/ticket/snippet/_ccusers.php" ?>
		</div>
	</td>
	<td style="position: relative">
			<?php if (true /* can add tags */) : ?>
		<a href="#" id="fss_show_userlist">
			<img class="fsj_tip" src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_add.png" border="0" alt="Tooltip" title="<?php echo JText::_('CC_USER'); ?>"/>
		</a>
	<?php endif;?>
	</td>
</tr>
<?php endif; ?>
<?php if ($this->ticket['password']): ?>
	<tr>
	<th><?php echo JText::_("PASSWORD"); ?></th>
	<td colspan="2"><?php echo $this->ticket['password']; ?></td>
</tr>
<?php endif; ?>
<?php if ($this->ticket['product']): ?>
<tr>
	<th><?php echo JText::_("PRODUCT"); ?></th>
	<td colspan="2"><?php echo FSS_Helper::TrF('title', $this->ticket['product'], $this->ticket['prtr']); ?></td>
</tr>
<?php endif; ?>
<?php if ($this->ticket['dept']): ?>
<tr>
<th><?php echo JText::_("DEPARTMENT"); ?></th>
	<td colspan="2"><?php echo FSS_Helper::TrF('title', $this->ticket['dept'], $this->ticket['dtr']); ?></td>
</tr>
<?php endif; ?>
<?php if ($this->ticket['cat']): ?>
<tr>
<th><?php echo JText::_("CATEGORY"); ?></th>
	<td colspan="2"><?php echo FSS_Helper::TrF('title', $this->ticket['cat'], $this->ticket['ctr']); ?></td>
</tr>
<?php endif; ?>
<tr>
	<th><?php echo JText::_("LAST_UPDATE"); ?></th>
	<td colspan="2">
		<?php echo FSS_Helper::Date($this->ticket['lastupdate'], FSS_DATETIME_MID); ?>
	</td>
</tr>

<?php $st = FSS_Ticket_Helper::GetStatusByID($this->ticket['ticket_status_id']); ?>

<?php if ($st->is_closed) : ?>
<tr>
	<th><?php echo JText::_("CLOSED"); ?></th>
	<td colspan="2">
		<?php echo FSS_Helper::Date($this->ticket['closed'], FSS_DATETIME_MID); ?>
	</td>
</tr>
<?php endif; ?>

<?php //print_p($this->ticket); ?>
<tr>
	<th><?php echo JText::_("STATUS"); ?></th>
	<?php $userstatus = FSS_Helper::TrF('userdisp', $this->ticket['userdisp'], $this->ticket['str']); ?>
	<?php $status = FSS_Helper::TrF('title', $this->ticket['status'], $this->ticket['str']); ?>
	
	<td colspan="2"><span style='color: <?php echo $this->ticket['scolor']; ?>'><?php echo $userstatus ? $userstatus : $status; ?></span></td>
</tr>
<?php if (!FSS_Settings::get('support_hide_priority')) : ?>
<tr>
	<th><?php echo JText::_("PRIORITY"); ?></th>
	<td colspan="2"><span style='color:<?php echo $this->ticket['pcolor']; ?>'><?php echo FSS_Helper::TrF('title', $this->ticket['pri'], $this->ticket['ptr']); ?></span></td>
</tr>
<?php endif; ?>
<?php if (!FSS_Settings::get('support_hide_handler')) : ?>
<th><?php echo JText::_("HANDLER"); ?></th>
	<td colspan="2"><?php if ($this->ticket['assigned']) {echo $this->ticket['assigned'];} else {echo JText::_("UNASSIGNED");} ?></td>
</tr>
<?php endif; ?>
<?php
foreach ($this->fields as $field)
{
	if ($field['grouping'] != "")
		continue;
		
	if ($field['permissions'] > 1)
		continue;
?>
	<Tr>
		<th width='100'><?php echo FSSCF::FieldHeader($field); ?></th>
<td style="border-right:none;">
			<?php echo FSSCF::FieldOutput($field, $this->fieldvalues, array('ticketid' => $this->ticket['id'], 'userid' => $this->userid, 'ticket' => $this->ticket)); ?>
</td>
<td align="right" width="20">
<?php if ($field['permissions'] == 0 && !$st->is_closed && $this->CanEditField($field)): ?>
	<a class='modal' rel="{handler: 'iframe', size: {x: 400, y: 400}}" href="<?php echo FSSRoute::x("&tmpl=component&what=editfield&ticketid=".$this->ticket['id']. "&editfield=" . $field['id'] ); ?>"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png" alt="Edit"></a>
<?php endif; ?>
</td>
</tr>	
	<?php
}
?>	


</table>

<?php 
$grouping = "";
$open = false;
foreach ($this->fields as $field)
{
	if ($field['grouping'] == "")
		continue;	
		
	if ($field['permissions'] > 1)
		continue;
		
	if ($field['grouping'] != $grouping)
	{
		if ($open)
		{
?>
			</table>
			<?php
		}
		
			?>
			<?php echo FSS_Helper::PageSubTitle($field['grouping']); ?>
<table class='fss_table' cellspacing=0 cellpadding=0>
		<?php	
		$open = true;	
		$grouping = $field['grouping'];
	}
	
	?>
<Tr>
		<th width='100'><?php echo FSSCF::FieldHeader($field); ?></th>
<td style="border-right:none;">
			<?php echo FSSCF::FieldOutput($field,$this->fieldvalues, array('ticketid' => $this->ticket['id'], 'userid' => $this->userid, 'ticket' => $this->ticket)); ?>
</td>
<td align="right" width="20">
<?php if ($field['permissions'] == 0 && !$st->is_closed && $this->CanEditField($field)): ?>
	<a class='modal' rel="{handler: 'iframe', size: {x: 400, y: 400}}" href="<?php echo FSSRoute::x("&tmpl=component&what=editfield&ticketid=".$this->ticket['id']. "&editfield=" . $field['id'] ); ?>"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png" alt="Edit"></a>
<?php endif; ?>
</td>
</tr>	
	<?php
}

if ($open)
{
?>
	</table>
	<?php
}

?>

<?php if (!$st->is_closed && $this->ticket['can_edit']): ?>
<?php echo FSS_Helper::PageSubTitle("EDIT_PROPERTIES"); ?>
<form id='newticket' action="<?php echo FSSRoute::x( '' ); ?>" method="post">
<table class='fss_table' cellspacing=0 cellpadding=0>
<tr>
		<?php if (!$st->is_closed && FSS_Settings::get('support_user_can_close') && $this->ticket['can_close']) : ?>
			<th width=25%><?php echo JText::_("STATUS"); ?></th>
			<td width=25%>
			<?php FSS_Helper::Tr($this->statuss); ?>
			<select id='new_status' name='new_status'>
				<?php foreach ($this->statuss as $status): ?>
					<?php if ($status['def_closed'] > 0 || $status['id'] == $this->ticket['sid']): ?>
					<option value='<?php echo $status['id']; ?>' style='color: <?php echo $status['color']; ?>' <?php if ($status['id'] == $this->ticket['sid']) echo "selected='selected'"; ?>><?php echo $status['userdisp'] ? $status['userdisp'] : $status['title']; ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
			</td>
		<?php else: ?>
			<input type="hidden" name="new_status" value="<?php echo $this->ticket['sid']; ?>">
		<?php endif; ?>
		<?php if (!FSS_Settings::get('support_hide_priority')) : ?>
		<th width=25%><?php echo JText::_("PRIORITY"); ?></th>
		<td width=25%>
		<select id='new_pri' name='new_pri'>
			<?php FSS_Helper::Tr($this->pris); ?>
			<?php foreach ($this->pris as $pri): ?>
				<option value='<?php echo $pri['id']; ?>' style='color: <?php echo $pri['color']; ?>' <?php if ($pri['id'] == $this->ticket['pid']) echo "selected='selected'"; ?>><?php echo $pri['title']; ?></option>
			<?php endforeach; ?>
		</select>
		</td>
		<?php endif; ?>
	</tr>
	<tr>
		<td colspan=4 align=center>
			<input class='button' type='submit' value='<?php echo JText::_("UPDATE"); ?>'>
		</td>
	</tr>
</table>
<input type="hidden" name="what" value="statuschange">
</form>
<?php endif; ?>

<?php echo FSS_Helper::PageSubTitle("MESSAGES"); ?>

<?php if (($this->ticket['sid'] != 3 || FSS_Settings::get('support_user_can_reopen')) && $this->ticket['can_edit']): ?>
<table width=100% class='fss_ticket_msgsheader'>
<tr>

<?php if (FSS_Settings::get('support_actions_as_buttons')) :?>

	<td valign="middle" nowrap>
		<button class="button post_reply">
			<?php echo JText::_("POST_REPLY"); ?>
		</button>
	</td>
	<td width=100%>

	</td>
	<td valign="middle" nowrap>
		<button class="button ticketrefresh" href='<?php echo FSSRoute::x( '&view=ticket&what=messages&ticketid=' . $this->ticket['id'] ); ?>'>
			<?php echo JText::_("REFRESH"); ?>
		</button>
	</td>
	
<?php else: ?>

	<td valign="middle" nowrap>
		<a class="post_reply" href='#'>
			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/reply.png'>
		</a>
	</td>
	<td valign="middle" nowrap>
		<a href='<?php echo FSSRoute::x( '&option=com_fss&view=ticket&layout=reply&ticketid=' . $this->ticket['id'] ); ?>' class="post_reply"><?php echo JText::_("POST_REPLY"); ?></a>
	</td>
	<td width=100%>

	</td>
	<td valign="middle" nowrap>
		<a class="ticketrefresh" href='<?php echo FSSRoute::x( '&view=ticket&what=messages&ticketid=' . $this->ticket['id'] ); ?>'>
			<img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/refresh.png'>
		</a>
	</td>
	<td valign="middle" nowrap>
		<a class="ticketrefresh" href='<?php echo FSSRoute::x( '&view=ticket&what=messages&ticketid=' . $this->ticket['id'] ); ?>'><?php echo JText::_("REFRESH"); ?></a>
	</td>
	
<?php endif; ?>

</tr>
</table>
<?php endif; ?>

<div id="ticket_messages">
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php';
//include "components/com_fss/views/ticket/snippet/_messages.php" ?>
</div>
<?php if (count($this->attach) > 0) : ?>

<?php echo FSS_Helper::PageSubTitle("ATTACHEMNTS"); ?>

<table class='fss_ticket_attach' width='100%' cellspacing=0 cellpadding=4>

<?php foreach ($this->attach as $attach) : ?>
<?php
	$info = pathinfo($attach['filename']);
	$image = false;
	$images = array('jpg','jpeg','png','gif');
	if (in_array(strtolower($info['extension']), $images))
	{
		$image = true;	
	}
?>
	<tr>
	<td rowspan="2" class="fss_ticket_attach_size">
<?php if ($image): ?>
		<a class="modal" rel="{handler: 'image'}" href="<?php echo JRoute::_('index.php?option=com_fss&view=ticket&fileid=' . $attach['id']); ?>">
			<img src="<?php echo JRoute::_('index.php?option=com_fss&view=ticket&what=attach_thumb&fileid=' . $attach['id']); ?>" width="48" height="48">
		</a>
<?php endif; ?>
	</td>
	<td class='fss_ticket_attach_file' valign="middle" width=26>
		<a href='<?php echo FSSRoute::x( '&fileid=' . $attach['id'] ); ?>'><img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/download-24x24.png'></a>
	</td>
	<td class='fss_ticket_attach_filename' valign="middle" width=60%>
		<a href='<?php echo FSSRoute::x( '&fileid=' . $attach['id'] ); ?>'><?php echo $attach['filename']; ?></a>
	</td>
	<td class='fss_ticket_attach_size' align=right valign="middle">	
		<?php echo FSS_Helper::display_filesize($attach['size']); ?>
	</td>
</tr>
<tr>
	<td colspan=2 class='fss_ticket_attach_user' width='60%'>
		Uploaded by <?php echo $attach['name']; ?>
	</td>
	<td class='fss_ticket_attach_date' width='40%' align=right>	
		<?php echo FSS_Helper::Date($attach['added'], FSS_DATETIME_MID); ?>
	</td>
</tr>

<?php endforeach; ?>
</table>
<?php endif; ?>

<script>

var procform = false;
function FromDone()
{
	if (procform)
		return;
		
	procform = true;
	var result = jQuery('#form_results').contents();
	jQuery('.post_reply').css('display','inline');
	var html = result[0].body.innerHTML;
	jQuery('#ticket_messages').html(html);
	
	CreateEvents();
	
	procform = false;
}

function CreateEvents()
{
	jQuery('#addcomment').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('#messagepleasewait').show();
		
		jQuery('#inlinereply').submit();
	});	
	
	jQuery('#replycancel').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('.post_reply').show();
		jQuery('#body').val("");
	});
}

jQuery(document).ready(function () {
	jQuery('.post_reply').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').show();
		jQuery('.post_reply').hide();
	});

	jQuery('.ticketrefresh').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		//alert(jQuery(this).attr('href'));
		jQuery('#ticket_messages').html("<div class='fss_please_wait'><?php echo JText::_('PLEASE_WAIT'); ?></div>");
		
		jQuery('#ticket_messages').load(jQuery(this).attr('href'));
	});	

	jQuery('#fss_show_userlist').click(function (ev) {
		ev.preventDefault();
		
		var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=ticket&tmpl=component&what=pickccuser&ticketid=' . $this->ticket['id']); ?>';
		
		TINY.box.show({iframe:url, width:800,height:500});
	});
	CreateEvents();	
});

function AddCCUser(userid)
{
	TINY.box.hide();
	
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=ticket&tmpl=component&what=addccuser&userid=XXUIDXX&ticketid=' . $this->ticket['id']); ?>';
	url = url.replace('XXUIDXX', userid);
	
	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

function removecc(userid)
{
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = '<?php echo FSSRoute::x('index.php?option=com_fss&view=ticket&tmpl=component&what=removeccuser&userid=XXUIDXX&ticketid=' . $this->ticket['id']); ?>';
	url = url.replace('XXUIDXX',userid);

	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

function addanother(no)
{
	var oldbtn = "another_" + no;
	no ++;
	var newfile = "file_" + no;

	if ($(newfile))
		$(oldbtn).style.display = 'none';

	if ($(newfile))
		$(newfile).style.display = 'block';

	/*if ($(newbtn))
		$(newbtn).style.display = 'inline';*/

	return false;
}

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
