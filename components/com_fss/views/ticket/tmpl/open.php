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
<?php echo FSS_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>
<div class="fss_spacer"></div>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php';
//include "components/com_fss/views/ticket/snippet/_openheader.php" ?>

<form id='newticket' action="<?php echo str_replace("&amp;","&",FSSRoute::x( '&layout=open' ));?>" method="post"  enctype="multipart/form-data">
<input type='hidden' name='prodid' id='prodid' value='<?php echo $this->prodid; ?>'>
<input type='hidden' name='deptid' id='deptid' value='<?php echo $this->deptid; ?>'>
<input type='hidden' name='what' id='what' value='add'>

<?php if ($this->product['title'] || $this->dept['title']): ?>
	
<?php if ($this->product['title'] && $this->dept['title']): ?>
	<?php echo FSS_Helper::PageSubTitle("PRODUCT_AND_DEPARTMENT_INFORMATION"); ?>
<?php elseif ($this->product['title']): ?>
	<?php echo FSS_Helper::PageSubTitle("PRODUCT_INFORMATION"); ?>
<?php elseif ($this->dept['title']): ?>
	<?php echo FSS_Helper::PageSubTitle("DEPARTMENT_INFORMATION"); ?>
<?php endif; ?>

		<table class='fss_table' cellspacing=0 cellpadding=0>
			<?php if ($this->product['title']): ?>
			<tr>
				<th width='100'><?php echo JText::_("PRODUCT"); ?></th>
				<td>
					<div class='fss_ticket_dept_prod'>
					<?php if ($this->product['image']): ?>
						<img src='<?php echo JURI::root( true ); ?>/images/fss/products/<?php echo $this->product['image']; ?>' width=24 height=24 style='position:relative;top:7px;'>
					&nbsp;
					<?php endif; ?><?php echo $this->product['title']; ?>
					</div>
				</td>
			</tr>
			<?php endif; ?>
			<?php if ($this->dept['title']): ?>
			<tr>
				<th><?php echo JText::_("DEPARTMENT"); ?></th>
				<td>
					<div class='fss_ticket_dept_prod'>
						<?php FSS_Helper::TrSingle($this->dept); ?>
						<?php echo $this->dept['title']; ?>
					</div>
				</td>
			</tr>
			<?php endif; ?>
		</table>
<?php endif; ?>

<?php 
$grouping = "";
$open = false;

foreach ($this->fields as $field)
{
	if ($field['grouping'] == "")
		continue;
		
	
	if ($this->admin_create == 0 && ($field['permissions'] > 0 && $field['permissions'] != 4))
		continue;
	
	if ($field['grouping'] != $grouping)
	{
		if ($open)
		{
			?>
				</table>
			<?php
		}
		echo FSS_Helper::PageSubTitle($field['grouping']);
		?>
			<table class='fss_table' cellspacing=0 cellpadding=0>
		<?php	
		$open = true;	
		$grouping = $field['grouping'];
	}
	
	?>
	<Tr>
		<th width='100'><?php echo FSSCF::FieldHeader($field,true); ?></th>
<td>
			<?php echo FSSCF::FieldInput($field,$this->errors,'ticket',array('ticketid' => 0, 'userid' => $this->userid)); ?>
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
<?php echo FSS_Helper::PageSubTitle("MESSAGE_DETAILS"); ?>
		<table class='fss_table' cellspacing=0 cellpadding=0 width="100%">
<?php
foreach ($this->fields as $field)
{
	if ($field['grouping'] != "")
		continue;
		
	if ($this->admin_create == 0 && ($field['permissions'] > 0 && $field['permissions'] != 4))
		continue;
?>
	<tr>
		<th width='100'><?php echo FSSCF::FieldHeader($field,true); ?></th>
<td>
			<?php echo FSSCF::FieldInput($field,$this->errors,'ticket',array('ticketid' => 0, 'userid' => $this->userid)); ?>
		</td>
	</tr>	
	<?php
}
?>		
<?php if (count($this->cats) > 0): ?>
			<tr>
				<th width='100'><?php echo JText::_("CATEGORY"); ?></th>
				<td>
					<select id='catid' name='catid'>
						<?php $sect = ""; $open = false; ?>
						<?php FSS_Helper::Tr($this->cats); ?>
						<?php foreach ($this->cats as $cat): ?>
							<?php 
								if ($cat['section'] != $sect) {
									if ($open)
										echo "</optgroup>";
									$open = true;
									echo "<optgroup label='" . $cat['section'] . "'>";
									$sect = $cat['section'];	
								}								
							?>
							<option value='<?php echo $cat['id']; ?>'><?php echo $cat['title']; ?></option>
						<?php endforeach; ?>
						<?php if ($open) echo "</optgroup>"; ?>
					</select>
				</td>
			</tr>
<?php endif; ?>
<?php if (!FSS_Settings::get('support_hide_priority')) : ?>
			<tr>
				<th width='100'><?php echo JText::_("PRIORITY"); ?></th>
				<td>
					<select id='priid' name='priid'>
						<?php FSS_Helper::Tr($this->pris); ?>
						<?php foreach ($this->pris as $pri): ?>
							<option value='<?php echo $pri['id']; ?>'
							 style='color: <?php echo $pri['color']; ?>'
							 <?php if ($pri['id'] == $this->ticket['priid']) echo "selected='selected'"; ?>>
							<?php echo $pri['title']; ?>
						</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
<?php endif; ?>			
<?php if (FSS_Settings::get('support_choose_handler') == "user" || ($this->admin_create > 0 && FSS_Settings::get('support_choose_handler') == "admin")) : ?>
	<?php if (count($this->handlers) > 1): ?>
			<tr>
				<th width='100'><?php echo JText::_("HANDLER"); ?></th>
				<td>
					<select id='handler' name='handler'>
						<?php foreach ($this->handlers as $handler): ?>
							<option value='<?php echo $handler['id']; ?>'
							<?php if ($handler['id'] == $this->ticket['handler']) echo "selected='selected'"; ?>>
							<?php echo $handler['name']; ?>
						</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
	<?php else: ?>
		<input type='hidden' name="handler" id="handler" value="0" />
	<?php endif; ?>
<?php endif; ?>	
<?php if (FSS_Settings::get('support_subject_message_hide') != "subject"): ?>
			<tr>
				<th width='100'><?php echo JText::_("SUBJECT"); ?> <span class="fss_must_have_field">*</span></th>
<td>
					<input name='subject' id='subject' size='<?php echo FSS_Settings::get('support_subject_size'); ?>' value="<?php echo JViewLegacy::escape($this->ticket['subject']) ?>" >
					<div class='fss_ticket_error' id='error_subject'><?php echo $this->errors['subject']; ?></div>
				</td>
			</tr>
<?php endif; ?>
<?php if (FSS_Settings::get('support_subject_message_hide') != "message"): ?>
			<tr>
				<td colspan=3>
					<div class='fss_ticket_error' id='error_subject'><?php echo $this->errors['body']; ?></div>
					<textarea name='body' id='body' rows='<?php echo FSS_Settings::get('support_user_reply_height'); ?>' cols='<?php echo FSS_Settings::get('support_user_reply_width'); ?>' style='width:95%;'><?php echo JViewLegacy::escape($this->ticket['body']) ?></textarea>
				</td>
			</tr>
<?php endif; ?>
		</table>
<?php if ($this->support_user_attach): ?>
	<?php echo FSS_Helper::PageSubTitle(JText::sprintf("UPLOAD_FILE",FSS_Helper::display_filesize(ini_get('upload_max_filesize'))),false); ?>
	<table>
	<tr>
	<td>
				<div id="file_1"><input type="file" size="60" id="filedata_1" name="filedata_1" /><button id="another_1" onclick='return addanother(1);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_2" style='display:none;'><input type="file" size="60" id="filedata_2" name="filedata_2" /><button id="another_2" onclick='return addanother(2);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_3" style='display:none;'><input type="file" size="60" id="filedata_3" name="filedata_3" /><button id="another_3" onclick='return addanother(3);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_4" style='display:none;'><input type="file" size="60" id="filedata_4" name="filedata_4" /><button id="another_4" onclick='return addanother(4);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_5" style='display:none;'><input type="file" size="60" id="filedata_5" name="filedata_5" /><button id="another_5" onclick='return addanother(5);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_6" style='display:none;'><input type="file" size="60" id="filedata_6" name="filedata_6" /><button id="another_6" onclick='return addanother(6);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_7" style='display:none;'><input type="file" size="60" id="filedata_7" name="filedata_7" /><button id="another_7" onclick='return addanother(7);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_8" style='display:none;'><input type="file" size="60" id="filedata_8" name="filedata_8" /><button id="another_8" onclick='return addanother(8);'><?php echo JText::_('ADD_ANOTHER_FILE'); ?></button></div>
				<div id="file_9" style='display:none;'><input type="file" size="60" id="filedata_9" name="filedata_9" /></div>
			</td>
		</tr>
	</table>
<?php endif; ?>

<div class='fss_ticket_foot'></div>
<?php if ($this->prodid > 0 || $this->deptid > 0): ?>
<input class='button' type='submit' id='backprod' value='<?php echo JText::_("BACK"); ?>'>
<?php endif; ?>
<input class='button' type='submit' value='<?php echo JText::_("CREATE_NEW_TICKET"); ?>' id='addcomment'>
</form>

<script>
window.addEvent('domready', function(){
<?php if ($this->prodid > 0 || $this->deptid > 0): ?>
	$('backprod').addEvent( 'click', function(evt){
		// Stops the submission of the form.
		if ($('deptid').value == '' || $('deptid').value == 0)
			$('prodid').value = '';		
			
		$('deptid').value = '';		
	} );
<?php endif; ?>
});

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