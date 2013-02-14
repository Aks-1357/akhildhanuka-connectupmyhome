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

<?php $text = ""; ?>
<?php if ($this->forward == 0): ?>
	<?php $text = "REPLY_TO_SUPORT_TICKET"; ?>
<?php elseif ($this->forward == 1): ?>
	<?php $text = "FORWARD_TICKET_TO_A_DIFFERENT_HANDLER"; ?>
<?php elseif ($this->forward == 2): ?>
	<?php $text = "FORWARD_TICKET_TO_A_DIFFERENT_DEPARTMENT"; ?>
<?php elseif ($this->forward == 3): ?>
	<?php $text = "ADD_HANDLER_COMMENT_TO_TICKET"; ?>
<?php elseif ($this->forward == 4): ?>
	<?php $text = "FORWARD_TICKET_TO_A_DIFFERENT_USER"; ?>
<?php endif; ?>
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',$text); ?>


<?php /* include "components/com_fss/views/admin/snippet/_tabbar.php"*/ ?>
<?php if ($this->permission['support']): ?>
<?php /*include "components/com_fss/views/admin/snippet/_supportbar.php"*/ ?>


<form id='newticket' action="<?php echo str_replace("&amp;","&",FSSRoute::x( '&what=&forward=' ));?>" method="post"  enctype="multipart/form-data">
<input type=hidden name='ticketid' id='ticketid' value='<?php echo $this->ticketid; ?>'>
<input type=hidden name='what' id='what' value='savereply'>
<input type=hidden name='forward' id='forward' value='<?php echo $this->forward; ?>'>

<?php echo FSS_Helper::PageSubTitle("MESSAGE_DETAILS"); ?>
<table width="100%">
<tr>
<th width='100'><?php echo JText::_("SUBJECT"); ?></th>
				<td><input name='subject' id='subject' size='<?php echo FSS_Settings::get('support_subject_size'); ?>' value="Re: <?php echo JViewLegacy::escape($this->ticket['title']); ?>"></td>
</tr>
<?php if ($this->forward == 0): ?>
	<tr>
		<th width='100'><?php echo JText::_("NEW_STATUS"); ?></th>
		<td>
			<select name="reply_status">
				<?php
				FSS_Ticket_Helper::GetStatusList();
				$def_admin = FSS_Ticket_Helper::GetStatusID('def_admin');
				foreach (FSS_Ticket_Helper::$status_list as $status)
				{
					if ($status->def_archive) continue;
					$sel = $status->id == $def_admin ? "SELECTED" : "";
					echo "<option value='{$status->id}' style='color:{$status->color};' {$sel}>{$status->title}</option>";	
				}
				?>
			</select>
		</td>
	</tr>
	<?php if ($this->support_assign_reply) : ?>
	<tr>
			<th width='100'><?php echo JText::_("ASSIGN_TICKET"); ?></th>
			<td><input type=checkbox value='1' id='dontassign' name="dontassign"><?php echo JText::_("DONT_ASSIGN_THIS_SUPPORT_TICKET_TO_ME"); ?></td>
		</tr>
	<?php endif; ?>
	
<?php elseif ($this->forward == 1) : ?>
<tr>
		<th width='100'><?php echo JText::_("NEW_HANDLER"); ?></th>
		<td>
			<?php echo $this->handlers; ?>
		</td>
	</tr>
	
<?php elseif ($this->forward == 2) : ?>
<tr>
		<th width='130' nowrap><?php echo JText::_("NEW_DEPARTMENT"); ?></th>
		<td>
			<?php echo $this->departments; ?>
		</td>
	</tr>
	<?php if ($this->products): ?>
	<tr>
		<th width='100' nowrap><?php echo JText::_("NEW_PRODUCT"); ?></th>
		<td>
			<?php echo $this->products; ?>
		</td>
	</tr>
	<?php endif; ?>
<?php elseif ($this->forward == 4) : ?>
<script>
function PickUser(userid, username, name)
{
//alert(userid + "\n" + username + "\n" + name);
$('user_id').value = userid;
$('username_display').innerHTML = name + " (" + username + ")";
}
</script>
<tr>
		<th width='100'><?php echo JText::_("NEW_USER"); ?></th>
		<td>
			<span class="" id="username_display"><?php echo $this->user['name']; ?> (<?php echo $this->user['username']; ?>)</span>&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="modal" href="<?php echo FSSRoute::x("&what=pickuser&tmpl=component"); ?>" rel="{handler: 'iframe', size: {x: 650, y: 410}}"><?php echo JText::_("CHANGE"); ?></a>
		</td>
	</tr>
	<input name="user_id" id="user_id" type="hidden" value="<?php echo $this->ticket['user_id']; ?>">
<?php endif; ?>


<?php if ($this->forward == 0): ?>

	<tr><th colspan="3"><?php echo JText::_("MESSAGE"); ?></th></tr>
<tr>
		<td colspan=3><textarea style='width:95%' name='body' id='body' rows='<?php echo FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_admin_reply_width'); ?>'></textarea></td>
	</tr>
		
<?php elseif ($this->forward == 1): ?>

	<tr><th colspan="3"><?php echo JText::_("MESSAGE_TO_HANDLER"); ?></th></tr>
<tr>
<td colspan=3><textarea style='width:95%' name='body2' id='body2' rows='<?php echo FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_admin_reply_width'); ?>'></textarea></td>
</tr>

	<tr><th colspan="3"><?php echo JText::_("MESSAGE_TO_USER"); ?></th></tr>
<tr>
		<td colspan=3><textarea style='width:95%' name='body' id='body' rows='<?php echo FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_admin_reply_width'); ?>'></textarea></td>
	</tr>
		
<?php elseif ($this->forward == 2): ?>

	<tr><th colspan="3"><?php echo JText::_("MESSAGE_TO_DEPARTMENT"); ?></th></tr>
<tr>
<td colspan=3><textarea style='width:95%' name='body2' id='body2' rows='<?php echo FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_admin_reply_width'); ?>'></textarea></td>
</tr>

	<tr><th colspan="3"><?php echo JText::_("MESSAGE_TO_USER"); ?></th></tr>
<tr>
		<td colspan=3><textarea style='width:95%' name='body' id='body' rows='<?php echo FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_admin_reply_width'); ?>'></textarea></td>
	</tr>
		
<?php elseif ($this->forward == 3): ?>

	<tr><th colspan="3"><?php echo JText::_("COMMENT"); ?></th></tr>
<tr>
	<td colspan=3><textarea style='width:95%' name='body2' id='body2' rows='<?php echo FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_admin_reply_width'); ?>'></textarea></td>
	</tr>
	<input name="hidefromuser" value="1" type="hidden" />
	
<?php elseif ($this->forward == 4): ?>

	<tr><th colspan="3"><?php echo JText::_("MESSAGE_TO_USER"); ?></th></tr>
<tr>
		<td colspan=3><textarea name='body' id='body' rows='<?php echo FSS_Settings::get('support_admin_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_admin_reply_width'); ?>'></textarea></td>
	</tr>
	
<?php endif; ?>

</table>
<div class="fss_spacer"></div>
<?php 
switch ($this->forward)
{
	case 0:
		$label = JText::_("POST_REPLY");
		break;
	case 1:
	case 2:
	case 4:
		$label = JText::_("FORWARD_TICKET");
		break;
	case 3:
		$label = JText::_("POST_COMMENT");
		break;
	default:
		$label = JText::_("POST_MESSAGE");
		break;
}
?>

<input  class='button' type='submit' value='<?php echo $label; ?>' id='addcomment'>

<table width="100%">
	<tr>
		<td width="170" valign="top">
			<?php echo FSS_Helper::PageSubTitle(JText::_('TAGS'),false); ?>
			<div id="tags">
				<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tags.php';
				//include "components/com_fss/views/admin/snippet/_tags.php" ?>
			</div>
			<div class="fss_clear"></div>
			<table class="fss_table" border="0" cellpadding="0" cellspacing="0" width="130">
				<tr>
					<th style="text-align: center;"><?php echo JText::_("ADD_TAGS"); ?></th>
				</tr>
				<?php foreach ($this->alltags as $tag): ?>
					<tr>
						<td>
							<A href='#' onclick="addtag('<?php echo $tag['tag']; ?>');return false;"><?php echo $tag['tag']; ?></a>
						</td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td>
						<div style="float:left;">
							<input name="tag" id="new_tag" class="tag_add_input" value="" size="9">
						</div>
						<div style="float:right;">
							<a href="#" onclick="addtag($('new_tag').value);return false;">
								<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_add.png" border="0" alt="Tooltip"/>
							</a>
						</div>
					</td>
				</tr>
			</table>
		</td>
<td valign="top">
			<?php echo FSS_Helper::PageSubTitle(JText::sprintf('UPLOAD_FILE',FSS_Helper::display_filesize(ini_get('upload_max_filesize'))),false); ?>
<table>
<tr>
<td>
								<div id="file_1"><input type="file" size="40" id="filedata_1" name="filedata_1" /><button id="another_1" onclick='return addanother(1);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_2" style='display:none;'><input type="file" size="40" id="filedata_2" name="filedata_2" /><button id="another_2" onclick='return addanother(2);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_3" style='display:none;'><input type="file" size="40" id="filedata_3" name="filedata_3" /><button id="another_3" onclick='return addanother(3);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_4" style='display:none;'><input type="file" size="40" id="filedata_4" name="filedata_4" /><button id="another_4" onclick='return addanother(4);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_5" style='display:none;'><input type="file" size="40" id="filedata_5" name="filedata_5" /><button id="another_5" onclick='return addanother(5);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_6" style='display:none;'><input type="file" size="40" id="filedata_6" name="filedata_6" /><button id="another_6" onclick='return addanother(6);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_7" style='display:none;'><input type="file" size="40" id="filedata_7" name="filedata_7" /><button id="another_7" onclick='return addanother(7);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_8" style='display:none;'><input type="file" size="40" id="filedata_8" name="filedata_8" /><button id="another_8" onclick='return addanother(8);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
								<div id="file_9" style='display:none;'><input type="file" size="40" id="filedata_9" name="filedata_9" /></div>
							</td>
						</tr>
					</table>
				<br />
			<?php echo FSS_Helper::PageSubTitle('Signature:'); ?>
			<a class="modal" href="<?php echo FSSRoute::x("&tmpl=component&what=changesig" ); ?>" rel="{handler: 'iframe', size: {x: 420, y: 330}}"><img src='<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png'></a>
			<input type="checkbox" value="1" checked name="append_sig"><label for="append_sig">&nbsp;<?php echo JText::_('USE_SIGNATURE');?></label>

			<div class="fss_clear"></div>
			<div class="fss_signature" id="signature">
			<?php echo str_replace("\n","<br />",$this->GetUserSig()); ?>
			</div>
		
		</td>
	</tr>
</table>
<?php 
switch ($this->forward)
{
case 0:
	$label = JText::_("POST_REPLY");
	break;
case 1:
case 2:
case 4:
	$label = JText::_("FORWARD_TICKET");
	break;
case 3:
	$label = JText::_("POST_COMMENT");
	break;
default:
	$label = JText::_("POST_MESSAGE");
	break;
}
?>

<input  class='button' type='submit' value='<?php echo $label; ?>' id='addcomment'>

</form>


<?php echo FSS_Helper::PageSubTitle('MESSAGES'); ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_messages.php';
//include "components/com_fss/views/admin/snippet/_messages.php" ?>

<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
<script>

function removetag(tagname)
{
	$('tags').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	var url = '<?php echo FSSRoute::x("&what=removetag&tmpl=component"); ?>&tag=' + tagname;
<?php if (FSS_Helper::Is16()): ?>
	$('tags').load(url);
<?php else: ?>
	new Ajax(url, {
	method: 'get',
	update: $('tags')
	}).request();
<?php endif; ?>
	return false;
}

function addtag(tagname)
{
	if (tagname == "")
		return;
	$('tags').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	var url = '<?php echo FSSRoute::x("&what=addtag&tmpl=component"); ?>&tag=' + tagname;
<?php if (FSS_Helper::Is16()): ?>
	$('tags').load(url);
<?php else: ?>
	new Ajax(url, {
	method: 'get',
	update: $('tags')
	}).request();
<?php endif; ?>
	return false;
}

function reloadSig()
{
	SqueezeBox.close();
	$('signature').innerHTML = "<div style='font-size:130%;'><?php echo JText::_('PLEASE_WAIT'); ?></div>";
	var url = "<?php echo FSSRoute::x("&tmpl=component&what=getsig" ); ?>";
<?php if (FSS_Helper::Is16()): ?>
	$('signature').load(url);
<?php else: ?>
	new Ajax(url, {
		method: 'get',
		update: $('signature')
	}).request();
<?php endif; ?>
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
