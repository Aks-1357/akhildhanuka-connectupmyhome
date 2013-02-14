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
<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php echo JHTML::_( 'form.token' ); ?>

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }
		
		if (!checkFormOK())
        {
			return;
		}
		
        submitform(pressbutton);
}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="question">
					<?php echo JText::_("MOD_STATUS"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['published']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="question">
					<?php echo JText::_("SECTION"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['sections']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="question" id="type_label">
					<?php if ($this->test->ident): ?>
						<?php echo $this->lists['comments']->handler->email_article_type ?>:
					<?php endif;?>
				</label>
			</td>
			<td id="tr_items">
				<?php echo $this->lists['itemid']; ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_("CREATED"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="created" id="created" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->test->created);?>" /> 
				<div style="float:right;position: relative;top: 6px;">Please use MySQL date format (YYYY-MM-DD HH:MM:SS)</div>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_("NAME"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->test->name);?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="email">
					<?php echo JText::_("EMAIL"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->test->email);?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="website">
					<?php echo JText::_("WEBSITE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="website" id="website" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->test->website);?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="body">
					<?php echo JText::_("BODY"); ?>:
				</label>
			</td>
			<td>
				<textarea id="body" name="body" rows="20" cols="60"><?php echo htmlentities($this->test->body); ?></textarea>
            </td>

		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->test->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="test" />
</form>

<script>

jQuery(document).ready( function () {
	jQuery('#toolbar-save a').unbind('click');
	jQuery('#toolbar-save a').attr('onclick','');
	jQuery('#toolbar-save a').click(function (ev) {
		ev.preventDefault();
		ev.stopPropagation();
		if (checkFormOK())
			submitbutton('save');
	});
});

function change_section()
{
	var ident = jQuery('#ident');
	var newval = ident.val();
	
	var url = '<?php echo FSSRoute::x('index.php?option=com_fss&controller=test&task=ident&ident=XXX', false); ?>';
	url = url.replace("XXX",newval);
	jQuery('#type_label').html("");
	jQuery('#tr_items').html("<?php echo JText::_('PLEASE_WAIT');?>");
	jQuery.get(url, function (data) {
		jsonObj = JSON.decode(data); 		
		jQuery('#type_label').html(jsonObj.title + ":");
		jQuery('#tr_items').html(jsonObj.select);
		
	});
}


function checkFormOK()
{
	var ident = jQuery('#ident');
	var newval = ident.val();
	if (newval < 1)
	{
		alert("You must select a section before saving");
		return false;			
	}
				
	var itemid = jQuery('#itemid');
	if (!itemid)
	{
		alert("You must select a section before saving");
		return false;	
	}

	return true;
}

</script>
