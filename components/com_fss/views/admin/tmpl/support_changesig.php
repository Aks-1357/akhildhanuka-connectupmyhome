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
<?php echo FSS_Helper::PageStylePopup(); ?>

<?php echo FSS_Helper::PageTitlePopup('SUPPORT_ADMIN',"EDIT_SIGNATURE"); ?>

<form action="<?php echo FSSRoute::x("&editfield=&tmpl="); ?>" method="post">
<table class="fss_table" cellpadding="0" cellspacing="0">
	<tr>
		<td><textarea name="signature" style="width:350px;height:200px;"><?php echo $this->GetUserSig(); ?></textarea></td>
	</tr>
</table>
<br />
<input type="hidden" name="what" value="savesig">
<input class='button' type="submit" value="<?php echo JText::_("SAVE"); ?>" name="store">&nbsp;
<input class='button' type="submit" name="store" value="<?php echo JText::_("CANCEL"); ?>" onclick="parent.SqueezeBox.close();return false;">
</form>

<?php echo FSS_Helper::PageStylePopupEnd(); ?>
