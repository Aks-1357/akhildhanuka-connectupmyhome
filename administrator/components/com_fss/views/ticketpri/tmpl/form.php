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

<style>
label {
	width: auto !important;
	float: none !important;
	clear: none !important;
	display: inline !important;
}
input {
	float: none !important;
	clear: none !important;
	display: inline !important;
}
</style>

<?php if (FSS_Helper::Is16()): ?>

<script language="javascript" type="text/javascript">

var to_translate = {
	title: {
		title: "<?php echo JText::_("PRIORITY"); ?>",
		type: 'input'
		}
}

jQuery(document).ready( function () {
	displayTranslations();
});

<?php 

$langs = array();
$jl = JLanguage::getKnownLanguages();
foreach ($jl as $key => $language)
{
	$langs[] = str_replace("-", "", $language['tag']) . ": '" . $language['name'] . "'";
}

?>

var tr_langs = { <?php echo implode(", ", $langs); ?> };

function doTranslate()
{
	var url = '<?php echo JRoute::_('index.php?option=com_fss&view=translate&tmpl=component', false); ?>&data=' + encodeURIComponent(JSON.stringify(to_translate));
	TINY.box.show({url:url, width:630,height:440,openjs:function () {doTranlsateLoaded();}});
}

Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == "translate") {
		return doTranslate();
	}
	Joomla.submitform(pressbutton);
}

</script>
<?php endif; ?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("PRIORITY"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->ticketpri->title);?>" />
			</td>
			<td>
				<div id="trprev_title"></div>
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->ticketpri, true); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("COLOR"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="color" id="color" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->ticketpri->color);?>" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->ticketpri->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->ticketpri->ordering; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketpri" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->ticketpri->translation); ?>" />
</form>

