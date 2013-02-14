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
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"CURRENT_SUPPORT_TICKETS"); ?>

<form id='adminForm' action="<?php echo FSSRoute::x( 'index.php?option=com_fss&view=admin&layout=support&what=settings' );?>" name="adminForm" method="post">
<input type='hidden' name='action' id='action' value='' />
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>

<?php if ($this->permission['support']): ?>

	<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_supportbar.php';
	//include "components/com_fss/views/admin/snippet/_supportbar.php" ?>
<?php endif; ?>
	<div class="fss_content_toolbar">
		<div class="fss_content_toolbar_title">
			<?php echo  FSS_Helper::PageSubTitle("MY_SETTINGS"); ?>
		</div>
		<div class="fss_content_toolbar_item" id='fss_form_cancel'>
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/cancel.png" /><br />
				<span><?php echo JText::_('CANCEL');?></span>
		</div>
		<div class="fss_content_toolbar_item" id="fss_form_save">
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/save.png" /><br />
				<span><?php echo JText::_('SAVE'); ?></span>
		</div>
		<div class="fss_content_toolbar_item" id="fss_form_apply">
				<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/content/publish.png" /><br />
				<span><?php echo JText::_('APPLY'); ?></span>
		</div>
	</div>
<div class="fss_clear"></div>
<div id="tab_general">

	<fieldset class="adminform">
		<legend><?php echo JText::_("TICKET_LIST"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("DEFAULT_PER_PAGE"); ?>:
				</td>
				<td style="width:100px;">
					<input name='per_page' value='<?php echo FSS_Helper::getUserSetting('per_page'); ?>' size="5">
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_PER_PAGE'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("TICKET_VIEW"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("REVERSE_ORDER_MESSAGES"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='reverse_order' value='1' <?php if (FSS_Helper::getUserSetting('reverse_order') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_REVERSE_ORDER'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>


	<fieldset class="adminform">
		<legend><?php echo JText::_("REPLY_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("RETURN_TO_OPEN_ON_REPLY"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='return_on_reply' value='1' <?php if (FSS_Helper::getUserSetting('return_on_reply') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_RETURN_TO_OPEN_ON_REPLY'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("RETURN_TO_OPEN_ON_CLOSE"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='return_on_close' value='1' <?php if (FSS_Helper::getUserSetting('return_on_close') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_RETURN_TO_OPEN_ON_CLOSE'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("TICKET_LIST_GROUPING"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("GROUP_BY_PRODUCT"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='group_products' value='1' <?php if (FSS_Helper::getUserSetting('group_products') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_GROUP_PRODUCTS'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("GROUP_BY_DEPARTMENT"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='group_departments' value='1' <?php if (FSS_Helper::getUserSetting('group_departments') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_GROUP_DEPARTMENTS'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("GROUP_BY_CATEGORY"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='group_cats' value='1' <?php if (FSS_Helper::getUserSetting('group_cats') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_GROUP_CATS'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("GROUP_BY_TICKET_GROUP"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='group_group' value='1' <?php if (FSS_Helper::getUserSetting('group_group') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_GROUP_GROUP'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("GROUP_BY_PRIORITY"); ?>:
				</td>
				<td style="width:100px;">
					<input type='checkbox' name='group_pri' value='1' <?php if (FSS_Helper::getUserSetting('group_pri') == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('MYSETHELP_GROUP_PRI'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

</form>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>

<script>

function FormButton(task)
{
	jQuery('#adminForm').find('input[name="action"]').val(task);
	jQuery('#adminForm').submit();
}

jQuery(document).ready(function () {
	jQuery('#fss_form_cancel').click(function (ev) {
		ev.preventDefault();
		FormButton("cancel")
	});
	jQuery('#fss_form_save').click(function (ev) {
		ev.preventDefault();
		FormButton("save")
	});
	jQuery('#fss_form_apply').click(function (ev) {
		ev.preventDefault();
		FormButton("apply")
	});
});
</script>