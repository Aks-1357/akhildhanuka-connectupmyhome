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
<?php if ($this->log) : ?>
<h1>Your upgrade has been completed.</h1>
<h4>The log of this process is below.</h4>
<?php $logno = 1; ?>
<?php foreach ($this->log as &$log): ?>
	<div>
	<div style="margin:4px;font-size:115%;"><a href="#" onclick="ToggleLog('log<?php echo $logno; ?>')">+<?php echo $log['name']; ?></a></div>
	<div id="log<?php echo $logno; ?>" style="display:none;">
	<pre style="margin-left: 20px;border: 1px solid black;padding: 2px;background-color: ghostWhite;"><?php echo $log['log']; ?></pre>
	</div>
</div>
	<?php $logno++; ?>
<?php endforeach; ?>

<script>
function ToggleLog(log)
{
	if (document.getElementById(log).style.display == "inline")
	{
		document.getElementById(log).style.display = 'none';
	} else {
		document.getElementById(log).style.display = 'inline';
	}
}
</script>
<?php else: ?>

<!--##NOT_EXT_START##-->
<?php if (FSSAdminHelper::Is16()): ?>
<h1><?php echo JText::_("API_KEY"); ?></h1>
To use the automatic Joomla updater, you need to enter your username and API key for freestyle-joomla.com here. To find your API get, please goto <a href='http://freestyle-joomla.com/my-account' target="_blank">http://freestyle-joomla.com/my-account</a> and log in.<br/><br/>
<form action="<?php echo FSSRoute::x("index.php?option=com_fss&view=backup&task=saveapi"); ?>" method="post" name="adminForm3" id="adminForm3"></::>
<table>
	<tr>
		<th>Username:</th>
		<td><input id="username" name="username" value="<?php echo FSS_Settings::get('fsj_username'); ?>"></td>
	</tr>
	<tr>
		<th>API Key:</th>
		<td><input id="apikey" name="apikey" size="60" value="<?php echo FSS_Settings::get('fsj_apikey'); ?>"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="Save" value="<?php echo JText::_("SAVE"); ?>"></td>
	</tr>
</table>
<br />
<h3>Checking API Key:</h3>
<iframe src="http://www.freestyle-joomla.com/api/validateapi.php?username=<?php echo FSS_Settings::get('fsj_username'); ?>&apikey=<?php echo FSS_Settings::get('fsj_apikey'); ?>" width="600" height="50" frameborder="0" border="0"></iframe>
</form>
<?php endif; ?>
<!--##NOT_EXT_END##-->

<h1><?php echo JText::_("UPDATE"); ?></h1>
<a href='<?php echo FSSRoute::x("index.php?option=com_fss&view=backup&task=update"); ?>'><?php echo JText::_("PROCESS_FREESTYLE_JOOMLA_INSTALL_UPDATE"); ?></a><br />&nbsp;<br />

<h1><?php echo JText::_("BACKUP_DATABASE"); ?></h1>
<a href='<?php echo FSSRoute::x("index.php?option=com_fss&view=backup&task=backup"); ?>'><?php echo JText::_("DOWNLOAD_BACKUP_NOW"); ?></a><br />&nbsp;<br />

<h1><?php echo JText::_("RESTORE_DATABASE"); ?></h1>
<div style="color:red; font-size:150%"><?php echo JText::_("PLEASE_NOTE_THE_WILL_OVERWRITE_AND_EXISTING_DATA_FOR_FREESTYLE_SUPPORT_PORTAL"); ?></div>

<?php //##NOT_FAQS_START## ?>
<?php echo JText::_("YOU_CAN_ALSO_RESTORE_BACKUPS_FROM_FREESTYLE_TESTIMONIALS_LITE_AND_FREESTYLE_FAQS_LITE_HERE"); ?><br>
<?php //##NOT_FAQS_END## ?>

<form action="<?php echo FSSRoute::x("index.php?option=com_fss&view=backup&task=restore"); ?>"  method="post" name="adminForm2" id="adminForm2" enctype="multipart/form-data"></::>
<input type="file" id="filedata" name="filedata" /><input type="submit" name="Restore" value="<?php echo JText::_("RESTORE"); ?>">
</form>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="view" value="backup" />
</form>
<?php endif; ?>