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
<?php echo FSS_Helper::PageStylePopup(); ?>
<form action="<?php echo FSSRoute::x('index.php?option=com_fss&view=admin&layout=content&tmpl=component&type=' . $this->id . '&what=author'); ?>" method="post" name="adminForm">
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("SEARCH"); ?>:
				<input type="text" name="search" value="<?php echo JViewLegacy::escape($this->search);?>" onchange="document.adminForm.submit();"/><br>
				<button onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();this.form.getElementById('faq_cat_id').value='0';"><?php echo JText::_("RESET"); ?></button>
			</td>
		</tr>
	</table>

    <table class="fss_table" cellpadding="0" cellspacing="0" width="100%">
    <thead>

        <tr>
			<th width="5">#</th>
            <th nowrap="nowrap" style="text-align:left;">
                <?php echo JHTML::_('grid.sort',   'User_ID', 'question', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th nowrap="nowrap" style="text-align:left;">
				<?php echo JHTML::_('grid.sort',   'User_Name', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th nowrap="nowrap" style="text-align:left;">
				<?php echo JHTML::_('grid.sort',   'EMail', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
			<th nowrap="nowrap" style="text-align:left;">
				<?php echo JText::_("PICK"); ?>
			</th>
		</tr>
    </thead>
    <?php

    $k = 0;
    foreach ($this->users as $user)
    {
        //$link = FSSRoute::x( 'index.php?option=com_fss&controller=faq&task=edit&cid[]='. $row->id );

        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $user->id; ?>
            </td>
            <td>
                <?php echo $user->username; ?>
            </td>
            <td>
                <?php echo $user->name; ?>
            </td>
            <td>
                <?php echo $user->email; ?>
			</td>
			<td>
                <a href="#" onclick="window.parent.PickUser('<?php echo $user->id; ?>','<?php echo FSS_Helper::escapeJavaScriptText($user->username); ?>','<?php echo FSS_Helper::escapeJavaScriptText($user->name); ?>');parent.SqueezeBox.close();return false;"><?php echo JText::_("PICK"); ?></a>
            </td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>
	<tfoot>
		<tr>
			<td colspan="9"><?php echo $this->pagination->getListFooter(); ?></td>
		</tr>
	</tfoot>

    </table>
</div>
<input type="hidden" name="view" value="admin" />
<input type="hidden" name="layout" value="support" />
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="what" value="pickuser" />
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php';
//include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStylePopupEnd(); ?>