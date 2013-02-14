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
		title: "<?php echo JText::_("TITLE"); ?>",
		type: 'input'
		},
	section: {
		title: "<?php echo JText::_("LIST_HEADING"); ?>",
		type: 'textarea'
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

function DoAllProdChange()
{
	var form = document.adminForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		prodlist.style.display = 'none';
	} else {
		prodlist.style.display = 'inline';
	}
}

function DoAllDeptChange()
{
	var form = document.adminForm;
	var deptlist = document.getElementById('deptlist');
		
	if (form.alldepts[1].checked)
    {
		deptlist.style.display = 'none';
	} else {
		deptlist.style.display = 'inline';
	}
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
					<?php echo JText::_("TITLE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->ticketcat->title);?>" />
			</td>
			<td>
				<div id="trprev_title"></div>
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->ticketcat, true); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="section">
					<?php echo JText::_("LIST_HEADING"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="section" id="section" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->ticketcat->section);?>" />
			</td>
			<td>
				<div id="trprev_section"></div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="eh">
					<?php echo JText::_("PRODUCTS"); ?>:
				</label>
			</td>
			<td>
				<div>
					<?php echo JText::_("SHOW_FOR_ALL_PRODUCTS"); ?>
					<?php echo $this->lists['allprod']; ?>
				</div>
				<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
					<?php echo $this->lists['products']; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="eh">
					<?php echo JText::_("DEPARTMENTS"); ?>:
				</label>
			</td>
			<td>
				<div>
					<?php echo JText::_("SHOW_FOR_ALL_DEPARTMENTS"); ?>
					<?php echo $this->lists['alldept']; ?>
				</div>
				<div id="deptlist" <?php if ($this->alldepts) echo 'style="display:none;"'; ?>>
					<?php echo $this->lists['departments']; ?>
				</div>
			</td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->ticketcat->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="ticketcat" />
<input type="hidden" name="translation" id="translation" value="<?php echo htmlEntities($this->ticketcat->translation); ?>" />
</form>

