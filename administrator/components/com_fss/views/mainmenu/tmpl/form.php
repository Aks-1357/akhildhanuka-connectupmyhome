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

        <?php
                $editor =& JFactory::getEditor();
        echo $editor->save( 'description' );
        ?>
        submitform(pressbutton);
}

function changeMenuItem()
{
	var value = $('menuitem').value;
	var itemid = value.substr(0,value.indexOf('|'));
	var link = value.substr(value.indexOf('|')+1);
	
	$('itemid').value = itemid;
	$('link').value = link;
}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("TITLE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->mainmenu->title);?>" />
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->mainmenu); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor =& JFactory::getEditor();
				echo $editor->display('description', $this->mainmenu->description, '550', '200', '60', '20', array('pagebreak', 'readmore'));
				?>
            </td>

		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="image">
					<?php echo JText::_("IMAGE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['images']; ?>
				<?php echo JText::_("FOUND_IN_IMAGES_FSS_MENU"); ?>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="image">
					<?php echo JText::_("TYPE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['types']; ?>
			</td>
		</tr>
		<?php //if ($this->mainmenu->itemtype != 7): ?>
				
		<?php if (array_key_exists('menuitems', $this->lists)): ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("MENU_ITEM"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['menuitems']; ?><br />If you have multiple menu items of this type, you can choose here which one you would like the main menu to link to. If there are none displayed here then Freestyle will use the menu item for the Support Main Menu.
			</td>
		</tr>
		<?php endif; ?>
		<input type="hidden" name="itemid" id="itemid" value="<?php echo JViewLegacy::escape($this->mainmenu->itemid);?>" />
		<!--<input type="hidden" name="link" id="link" value="<?php echo JViewLegacy::escape($this->mainmenu->link);?>" />-->
		
		<?php //else: ?>
		
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("LINK"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="link" id="link" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->mainmenu->link);?>" />
			</td>
		</tr>
		<?php //endif; ?>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->mainmenu->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->mainmenu->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->mainmenu->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="mainmenu" />
</form>

