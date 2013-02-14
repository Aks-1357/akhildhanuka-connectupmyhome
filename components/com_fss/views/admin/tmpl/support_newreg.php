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
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"NEW_SUPPORT_TICKET"); ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php';
//include "components/com_fss/views/admin/snippet/_tabbar.php" ?>
<?php if ($this->permission['support']): ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_supportbar.php';
//include "components/com_fss/views/admin/snippet/_supportbar.php" ?>

<div class="contentheading"><?php echo JText::_("CREATE_TICKET_FOR_REGISTERED_USER"); ?></div>
<div class="fss_spacer"></div>
<script>
function PickUser(userid, username, name)
{
	//alert(userid + "\n" + username + "\n" + name);
	$('user_id').value = userid;
	$('username_display').innerHTML = name + " (" + username + ")";
}

window.addEvent( 'domready', function( e )
{
	$('new_ticket').addEvent( 'click', function( ce )
    {
		if ($('user_id').value == 0)
		{
			alert("Please select a user");
			new Event( ce ).stop();
		}
    });
});

</script>

<form action="<?php echo FSSRoute::x('&view=ticket&layout=open&admincreate=1'); ?>" method="post" >
<table class="fss_table" cellpadding="0" cellspacing="0"> 
	<tr>
		<th><?php echo JText::_("SELECT_USER"); ?></th>
		<td>
			<span class="" id="username_display"><?php echo JText::_("NONE_"); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
			<a id="pick_user" class="modal" href="<?php echo FSSRoute::x("&what=pickuser&tmpl=component"); ?>" rel="{handler: 'iframe', size: {x: 650, y: 410}}"><?php echo JText::_("CHANGE"); ?></a>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input class='button' type="submit" id="new_ticket" value="<?php echo JText::_("CREATE_NEW_TICKET"); ?>">
		</td>
	</tr>
</table>
<input name="user_id" id="user_id" type="hidden" value="0">
</form>
<?php endif; ?>
<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
