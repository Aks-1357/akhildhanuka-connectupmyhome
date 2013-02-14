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
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"GROUP_ADMINISTRATION"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>

<?php if ($this->permission['groups'] == 1): ?>
<div style="font-size:120%;"><a href="<?php echo FSSRoute::x('index.php?option=com_fss&view=groups&what=create'); ?>"><img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/tag_add.png" width="16" height="16" style="position:relative;top:3px;"><?php echo JText::_('CREATE_NEW_GROUP'); ?></a></div>
<?php endif; ?>
<div class="fss_clear"></div>
<?php if (count($this->groups) == 0): ?>
	<div class="fss_ticket_nonefound">
		<?php echo JText::_("THERE_ARE_CURRENTLY_NO_GROUP"); ?>
	</div>	
<?php endif; ?>
<?php foreach ($this->groups as &$group): ?>
<?php
	$allemail = FSS_Helper::GetYesNoText($group->allemail);
	$ccexclude = FSS_Helper::GetYesNoText($group->ccexclude);
	if ($group->allsee == 0)
	{
		$allsee = JText::_('VIEW_NONE');//"None";	
	} elseif ($group->allsee == 1)
	{
		$allsee = JText::_('VIEW');//"See all tickets";	
	} elseif ($group->allsee == 2)
	{
		$allsee = JText::_('VIEW_REPLY');//"Reply to all tickets";	
	} elseif ($group->allsee == 3)
	{
		$allsee = JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
	}

?>
	<div class='fss_group'>
		<div class='fss_group_title'><?php echo FSS_Helper::PageSubTitle2($group->groupname); ?></div>
		<div class="fss_group_inner">
			<div class='fss_group_desc'><?php echo $group->description; ?></div>
<div class='fss_group_perms'>
<table width="100%">
<tr>
						<th style="width:150px"><?php echo JText::_('GMEMBERS');?></th>
<td>
							<a class="fsj_tip" style="position:relative;top:-1px;" href="<?php echo FSSRoute::x("index.php?option=com_fss&view=groups&groupid={$group->id}"); ?>" style="position:relative;top:-3px;" title="<?php echo JText::_('EDIT_MEMBERS');?>">
								<img src="<?php echo JURI::root( true ); ?>/administrator/components/com_fss/assets/members.png" width="16" height="16" style="position:relative;top:3px;">	
								<?php 
								if ($group->cnt == 0)
								{
									echo JText::_("NO_MEMBERS"); 
								} else if ($group->cnt == 1) {
									echo JText::sprintf("X_MEMBER",$group->cnt); 
								} else {
									echo JText::sprintf("X_MEMBERS",$group->cnt); 
								}				
								?>
</a>

</td>
						<th style="width:150px"><?php echo JText::_('PRODUCTS'); ?>:</th>
						<td>					
							<?php if ($group->allprods) { ?>
								<?php echo JText::_("ALL_PROD"); ?>
							<?php } else { ?>
							<?php $link = FSSRoute::x("index.php?option=com_fss&view=groups&tmpl=component&groupid={$group->id}&what=productlist"); ?>
								<a class="popup_link fsj_tip" title="<?php echo JText::_("VIEW_PROD_INFO"); ?>"  href="<?php echo $link; ?>"><?php echo JText::_("VIEW_PROD"); ?></a>
							<?php } ?>
						</td>
						<th rowspan="2" style="width:40px;text-align: center;">
							<a href='<?php echo FSSRoute::x("index.php?option=com_fss&view=groups&groupid={$group->id}"); ?>'>
								<img src="<?php echo JURI::root( true ); ?>/components/com_fss/assets/images/edit.png" width="18" height="18"><br />
<?php echo JText::_('EDIT'); ?>
</a>
</th>
</tr>
<tr>
<th style="width:150px" colspan="2" class="fss_groups_exclude">
							<?php echo JText::_("CC_ALL_USERS"); ?>&nbsp;<?php echo $allemail; ?>&nbsp;&nbsp;
							<?php echo JText::_("CC_EXCLUDE"); ?>&nbsp;<?php echo $ccexclude; ?>
</th>
						<th style="width:150px"><?php echo JText::_('DEFAULT_PERMISSIONS');?>:</th>
						<td><?php echo $allsee; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
<?php endforeach; ?>

<script>

jQuery(document).ready(function () {
	jQuery('.popup_link').click(function (ev) {
		ev.preventDefault();
	
		var url = jQuery(this).attr('href');
		TINY.box.show({iframe:url, width:500,height:300});
	});
});

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
