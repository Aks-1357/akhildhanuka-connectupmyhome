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

<?php if (FSS_Helper::Is16()): ?>

<script language="javascript" type="text/javascript">

var to_translate = {
	title: {
		title: "<?php echo JText::_("TITLE"); ?>",
		type: 'input'
		},
	description: {
		title: "<?php echo JText::_("DESCRIPTION"); ?>",
		type: 'html'
		},
}

jQuery(document).ready( function () {
	jQuery('[name=body]').attr('id','body');
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
    <?php
    $editor =& JFactory::getEditor();
    echo $editor->save( 'description' );
    ?>
	
	var url = '<?php echo JRoute::_('index.php?option=com_fss&view=translate&tmpl=component', false); ?>&data=' + encodeURIComponent(JSON.stringify(to_translate));
	TINY.box.show({url:url, width:630,height:440,openjs:function () {doTranlsateLoaded();}});
}

Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == "translate") {
		return doTranslate();
	}
	Joomla.submitform(pressbutton);
}

//-->
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
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->prod->title);?>" />
			</td>
			<td>
				<div id="trprev_title"></div>
			</td>
		</tr>
		<?php FSSAdminHelper::LA_Form($this->prod, true); ?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="image">
					<?php echo JText::_("IMAGE"); ?>:
				</label>
			</td>
			<td>
				<?php echo $this->lists['images']; ?>
				<?php echo JText::_("FOUND_IN_IMAGES_FSS_PRODUCTS"); ?>
			</td>
		</tr>		
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor =& JFactory::getEditor();
				echo $editor->display('description', $this->prod->description, '550', '200', '60', '20', array('pagebreak', 'readmore'));
				?>
            </td>
			<td>
				<div id="trprev_description"></div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("EXTRA_PANEL_TEXT"); ?>:
				</label>
			</td>
			<td>
				<?php
				$editor =& JFactory::getEditor();
				echo $editor->display('extratext', $this->prod->extratext, '550', '200', '60', '20', array('pagebreak', 'readmore'));
				?>
            </td>

		</tr>
<!--##NOT_TEST_START##-->
		<tr>
			<td width="150" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SHOW_IN_KB"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='inkb' value='1' <?php if ($this->prod->inkb) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
		<tr>
			<td width="150" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SHOW_IN_SUPPORT"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='insupport' value='1' <?php if ($this->prod->insupport) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
<!--##NOT_TEST_END##-->
		<tr <?php if (JRequest::getVar('option') == "com_fst") { echo "style='display:none;'"; } ?>>
			<td width="150" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SHOW_IN_TEST"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='intest' value='1' <?php if ($this->prod->intest) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->prod->id; ?>" />
<input type="hidden" name="ordering" value="<?php echo $this->prod->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->prod->published; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="prod" />
<input size=100 name="translation" id="translation" value="<?php echo htmlEntities($this->prod->translation); ?>" />

</form>

