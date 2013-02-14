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

<script language="javascript" type="text/javascript">
<!--
function submitbutton(pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
                submitform( pressbutton );
                return;
        }
        submitform(pressbutton);
}
//-->

function DoAllProdChange()
{
	var form = document.adminForm;
	var prodlist = document.getElementById('prodlist');
		
	if (form.allprods[1].checked)
    {
		jQuery('#prodlist').hide();
	} else {
		jQuery('#prodlist').show();
	}
}

function DoAllDeptChange()
{
	var form = document.adminForm;
		
	if (form.alldepts[1].checked)
    {
		jQuery('#deptlist').hide();
	} else {
		jQuery('#deptlist').show();
	}
}

function HideAllTypeSettings()
{
	jQuery('#no_settings').hide();
	jQuery('#checkbox_settings').hide();
	jQuery('#text_settings').hide();
	jQuery('#radio_settings').hide();
	jQuery('#combo_settings').hide();
	jQuery('#area_settings').hide();
	jQuery('#plugin_settings').hide();
	
	jQuery('#checkbox_default').hide();
	jQuery('#text_default').hide();
	jQuery('#radio_default').hide();
	jQuery('#combo_default').hide();
	jQuery('#area_default').hide();
	jQuery('#plugin_default').hide();
}

function ShowType(atype)
{
	if (atype == '') atype = 'no';
	
	jQuery('#' + atype + '_settings').show();
	
	if (atype != 'no')
		jQuery('#' +atype + '_default').show();

	if (atype == "text" || atype == "combo" || atype == "area")
	{
		jQuery('#basicsearch').show();
	} else {
		jQuery('#basicsearch').hide();
	}

	if (atype == "plugin")
	{
		jQuery('#advsearch').hide();
	} else {
		jQuery('#advsearch').show();
	}

	if (atype != "area")
	{
		jQuery('#inlist').show();
	} else {
		jQuery('#inlist').hide();
	}
}

function DoTypeChange(control)
{
	HideAllTypeSettings();
	ShowType(control.value);
}

function plugin_changed()
{
	var plugin = jQuery('#plugin').val();
	if (plugin == "")
	{
		jQuery('#plugin_sub_settings').html("Please select a plugin");
		return;
	}
	jQuery('#plugin_sub_settings').html("<?php echo JText::_('PLEASE_WAIT'); ?>");
	
	var url = '<?php echo FSSRoute::x('index.php?option=com_fss&controller=field&task=plugin_form', false); ?>&plugin=' + jQuery('#plugin').val();
	jQuery.get(url, function (data) {
		jQuery('#plugin_sub_settings').html(data);
	});
}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<div>
	<fieldset class="adminform">
		<legend><?php echo JText::_("DETAILS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("DESCRIPTION"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="description" id="description" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->field->description);?>" />
			</td>
		</tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("WHERE_USED"); ?>:
			    </label>
		    </td>
		    <td>
				<?php echo $this->ident; ?>
		    </td>
	    </tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("TYPE"); ?>:
				</label>
			</td>
			<td>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td width="135">
							<input type="radio" name="type" value="checkbox" onchange="DoTypeChange(this);" <?php if ($this->field->type == "checkbox") echo "checked"; ?>><?php echo JText::_("CHECKBOX"); ?>
						</td>
						<td width="135">
							<input type="radio" name="type" value="text" onchange="DoTypeChange(this);" <?php if ($this->field->type == "text") echo "checked"; ?>><?php echo JText::_("TEXT_ENTRY"); ?>
						</td>
						<td width="135">
							<input type="radio" name="type" value="radio" onchange="DoTypeChange(this);" <?php if ($this->field->type == "radio") echo "checked"; ?>><?php echo JText::_("RADIO_GROUP"); ?>
						</td>
						</tr>
						<tr>
						<td width="135">
							<input type="radio" name="type" value="combo" onchange="DoTypeChange(this);" <?php if ($this->field->type == "combo") echo "checked"; ?>><?php echo JText::_("COMBO_BOX"); ?>
						</td>
						<td width="135">
							<input type="radio" name="type" value="area" onchange="DoTypeChange(this);" <?php if ($this->field->type == "area") echo "checked"; ?>><?php echo JText::_("TEXT_AREA"); ?>
						</td>
						<td width="135" id='plugin_opt'>
							<input type="radio" name="type" value="plugin" onchange="DoTypeChange(this);" <?php if ($this->field->type == "plugin") echo "checked"; ?>><?php echo JText::_("PLUGIN"); ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("FIELD_SETTINGS"); ?>:
				</label>
			</td>
			<td>
				<div id='no_settings'>
					<?php echo JText::_("PLEASE_SELECT_A_FIELD_TYPE"); ?>
				</div>
				<!-- Settings for checkbox -->
				<div id="checkbox_settings">
					<?php echo JText::_("NO_SETTINGS_NEEDED_FOR_A_CHECKBOX_FIELD"); ?>
				</div>
				<!-- Settings for text -->
				<div id="text_settings">
					<table>
						<tr>
							<th><?php echo JText::_("MINIMUM_CHARACTERS"); ?></th>
							<td><input name="text_min" value="<?php echo $this->text_min; ?>"></td>
						</tr>
						<tr>
							<th><?php echo JText::_("MAXIMUM_CHARACTERS"); ?></th>
							<td><input name="text_max" value="<?php echo $this->text_max; ?>"></td>
						</tr>
						<tr>
							<th><?php echo JText::_("INPUT_SIZE"); ?></th>
							<td><input name="text_size" value="<?php echo $this->text_size; ?>"></td>
						</tr>
					</table>
				</div>
				<!-- Settings for radio -->
				<div id="radio_settings">
					<?php echo JText::_("RADIO_GROUP_VALUES_PLEASE_ENTER_ONE_VALUE_PER_ROW"); ?><br>
					<textarea cols="40" rows="5" name="radio_values"><?php echo $this->values; ?></textarea>
				</div>
				<!-- Settings for combo -->
				<div id="combo_settings">
					<?php echo JText::_("COMBO_BOX_VALUES_PLEASE_ENTER_ONE_VALUE_PER_ROW"); ?><br>
					<textarea cols="40" rows="10" name="combo_values"><?php echo $this->values; ?></textarea>
				</div>
				<!-- Settings for area -->
				<div id="area_settings">
					<table>
						<tr>
							<th><?php echo JText::_("AREA_WIDTH"); ?></th>
							<td><input name="area_width" value="<?php echo $this->area_width; ?>"></td>
						</tr>
						<tr>
							<th><?php echo JText::_("AREA_HEIGHT"); ?></th>
							<td><input name="area_height" value="<?php echo $this->area_height; ?>"></td>
						</tr>
					</table>
				</div>
				<!-- Settings for plugin -->
				<div id="plugin_settings">
					<table>
						<tr>
							<th><?php echo JText::_("PLUGIN"); ?></th>
							<td><?php echo $this->pllist; ?></td>
							<td><a href='http://freestyle-joomla.com/help/knowledge-base?kbartid=91' target="_blank">Creating Plugins Help</a></td>
						</tr>
					</table>
					<div id='plugin_sub_settings'>
						<?php echo $this->plugin_form; ?>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="question">
					<?php echo JText::_("DEFAULT_VALUE"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="default" id="default" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->field->default);?>" /><br>
				<div id='checkbox_default'><?php echo JText::_("ENTER_ON_TO_HAVE_THE_CHECKBOX_CHECKED_BY_DEFAULT"); ?></div>
				<div id='text_default'><?php echo JText::_("ENTER_THE_DEFAULT_TEXT"); ?></div>
				<div id='radio_default'><?php echo JText::_("ENTER_ONE_OF_THE_VALUES_TO_HAVE_IT_SELECTED_BY_DEFAULT_LEAVE_THIS_BLANK_TO_HAVE_NOTHING_SELECTED_BY_DEFAULT"); ?></div>
				<div id='combo_default'><?php echo JText::_("ENTER_ONE_OF_THE_VALUES_TO_HAVE_IT_SELECTED_BY_DEFAULT_LEAVE_THIS_BLANK_TO_HAVE_NOTHING_SELECTED_BY_DEFAULT"); ?></div>
				<div id='area_default'><?php echo JText::_("ENTER_THE_DEFAULT_TEXT"); ?></div>
			</td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("REQUIRED_FIELD"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='required' value='1' <?php if ($this->field->required) { echo " checked='yes' "; } ?>><br>
					<?php echo JText::_("NOTE_THIS_HAS_NO_EFFECT_ON_CHECKBOX_AND_TEXT_AREA_FIELDS"); ?>
            </td>
		</tr>
	</table>
	</fieldset>
</div>

<div id="ticket_field_settings">
	<fieldset class="adminform">
		<legend><?php echo JText::_("TICKET_FIELD_SETTINGS"); ?></legend>

		<table class="admintable">
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("GROUPING"); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="grouping" id="grouping" size="32" maxlength="250" value="<?php echo JViewLegacy::escape($this->field->grouping);?>" />
				<br><?php echo JText::_("USE_THIS_TO_SEPARATE_A_SET_OF_OPTIONS_INTO_A_DIFFERENT_GROUPING_WHEN_USER_CREATES_A_SUPPORT_TICKET"); ?>
            </td>
		</tr>
		<tr>
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("PER_USER_FIELD"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='peruser' value='1' <?php if ($this->field->peruser) { echo " checked='yes' "; } ?>><br>
					<?php echo JText::_("PER_USER_FIELD_HELP"); ?>
            </td>
		</tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("SUPPORT_FOR_WHICH_PRODUCTS"); ?>:
			    </label>
		    </td>
		    <td>
				<div>
					<?php echo JText::_("ALL_PRODUCTS"); ?>
					<?php echo $this->allprod; ?>
				</div>
				<div id="prodlist" <?php if ($this->allprods) echo 'style="display:none;"'; ?>>
					<?php echo $this->products; ?>
				</div>
		    </td>
	    </tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("SUPPORT_FOR_WHICH_DEPARTMENTS"); ?>:
			    </label>
		    </td>
		    <td>
				<div>
					<?php echo JText::_("ALL_DEPARTMENTS"); ?>
					<?php echo $this->alldept; ?>
				</div>
				<div id="deptlist" <?php if ($this->alldepts) echo 'style="display:none;"'; ?>>
					<?php echo $this->departments; ?>
				</div>
		    </td>
	    </tr>
		<tr>
		    <td width="135" align="right" class="key">
			    <label for="eh">
					<?php echo JText::_("FIELD_PERMISSIONS"); ?>:
			    </label>
		    </td>
		    <td>
				<?php echo $this->fieldperm; ?>
		    </td>
	    </tr>
		<tr id="basicsearch">
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SEARCH_IN_BASIC"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='basicsearch' value='1' <?php if ($this->field->basicsearch) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
		<tr id="advsearch">
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SEARCH_IN_ADAVANCE"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='advancedsearch' value='1' <?php if ($this->field->advancedsearch) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
		<tr id="inlist">
			<td width="135" align="right" class="key">
				<label for="description">
					<?php echo JText::_("SHOW_ON_TICKET_LIST"); ?>:
				</label>
			</td>
			<td>
				<input type='checkbox' name='inlist' value='1' <?php if ($this->field->inlist) { echo " checked='yes' "; } ?>><br>
            </td>
		</tr>
	</table>
	</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="id" value="<?php echo $this->field->id; ?>" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="controller" value="field" />
<input type="hidden" name="ordering" value="<?php echo $this->field->ordering; ?>" />
<input type="hidden" name="published" value="<?php echo $this->field->published; ?>" />
</form>

<script>
HideAllTypeSettings();
ShowType('<?php echo $this->field->type; ?>');

function ident_changed()
{
	var value = jQuery('#ident').val();
	
	if (value == 0)
	{
		jQuery('#ticket_field_settings').show();
		jQuery('#plugin_opt').show();
	} else {
		jQuery('#ticket_field_settings').hide();
		jQuery('#plugin_opt').hide();
	}
}

ident_changed();
</script>
