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

<form id='inlinereply' target="form_results" action="<?php echo FSSRoute::x( '&what=savereply', false );?>" method="post"  enctype="multipart/form-data">
	<table width="100%">
		<tr>
			<th width='100'><?php echo JText::_("SUBJECT"); ?></th>
			<td><input name='subject' id='subject' size='<?php echo FSS_Settings::get('support_subject_size'); ?>' value='Re: <?php echo FSS_Helper::encode($this->ticket['title']) ?>' ></td>
			<td><div class='fss_ticket_error' id='error_subject'><?php echo $this->errors['subject']; ?></div></td>
		</tr>
		<tr>
			<td colspan=3><div class='fss_ticket_error' id='error_subject'><?php echo FSS_Helper::encode($this->errors['body']); ?></div></td>
		</tr>
		<tr>
			<td colspan=3><textarea name='body' id='body' rows='<?php echo FSS_Settings::get('support_user_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_user_reply_width'); ?>' style='width:97%'></textarea></td>
		</tr>
	</table>
	<?php if ($this->support_user_attach): ?>
		<div class="fss_spacer contentheading"><?php echo JText::sprintf('UPLOAD_FILE',FSS_Helper::display_filesize(ini_get('upload_max_filesize'))); ?></div>
	<table>
	<tr>
	<td>
					<div id="file_1"><input type="file" size="60" id="filedata_1" name="filedata_1" /><button class='button' id="another_1" onclick='return addanother(1);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_2" style='display:none;'><input type="file" size="60" id="filedata_2" name="filedata_2" /><button class='button' id="another_2" onclick='return addanother(2);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_3" style='display:none;'><input type="file" size="60" id="filedata_3" name="filedata_3" /><button class='button' id="another_3" onclick='return addanother(3);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_4" style='display:none;'><input type="file" size="60" id="filedata_4" name="filedata_4" /><button class='button' id="another_4" onclick='return addanother(4);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_5" style='display:none;'><input type="file" size="60" id="filedata_5" name="filedata_5" /><button class='button' id="another_5" onclick='return addanother(5);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_6" style='display:none;'><input type="file" size="60" id="filedata_6" name="filedata_6" /><button class='button' id="another_6" onclick='return addanother(6);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_7" style='display:none;'><input type="file" size="60" id="filedata_7" name="filedata_7" /><button class='button' id="another_7" onclick='return addanother(7);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_8" style='display:none;'><input type="file" size="60" id="filedata_8" name="filedata_8" /><button class='button' id="another_8" onclick='return addanother(8);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
					<div id="file_9" style='display:none;'><input type="file" size="60" id="filedata_9" name="filedata_9" /></div>

				</td>
			</tr>
		</table>
	<?php endif; ?>
	<input type="hidden" name="ticketid" value="<?php echo $this->ticket['id']; ?>" />
	<input class='button' type='submit' value='<?php echo JText::_("POST_REPLY"); ?>' id='addcomment' /> 
	<input class='button' type='submit' id="replycancel" value='<?php echo JText::_("CANCEL"); ?>' />
</form>
<iframe name="form_results" id="form_results" style="display: none;"></iframe>
