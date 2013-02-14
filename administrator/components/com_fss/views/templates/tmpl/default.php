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
<script>
function ResetElement(tabid)
{
	document.getElementById('tab_' + tabid).style.display = 'none';
	document.getElementById('link_' + tabid).style.backgroundColor = '';

	document.getElementById('link_' + tabid).onmouseover = function() {
		this.style.backgroundColor='<?php echo FSS_Settings::get('css_hl'); ?>';
	}
	document.getElementById('link_' + tabid).onmouseout = function() {
		this.style.backgroundColor='';
	}

}
function ShowTab(tabid)
{
	ResetElement('visual');
//##NOT_FAQS_START##
//##NOT_TEST_START##
	ResetElement('support');
	ResetElement('announce');
//##NOT_TEST_END##
	ResetElement('comments');
//##NOT_FAQS_END##

	location.hash = tabid;
	jQuery('#tab').val(tabid);
	document.getElementById('tab_' + tabid).style.display = 'inline';
	document.getElementById('link_' + tabid).style.backgroundColor = '#f0f0ff';
	
	document.getElementById('link_' + tabid).onmouseover = function() {
	}
	document.getElementById('link_' + tabid).onmouseout = function() {
	}
}

window.addEvent('domready', function(){
	if (location.hash)
	{
		ShowTab(location.hash.replace('#',''));
	}
	else
	{
		ShowTab('visual');
//##NOT_FAQS_START##
		ShowTab('comments');
//##NOT_FAQS_END##
	}

//##NOT_FAQS_START##
//##NOT_TEST_START##
	showhide_customize();
//##NOT_TEST_END##
	setup_comments('comments_general');
//##NOT_TEST_START##
	setup_comments('comments_announce');
	setup_comments('comments_kb');
	setup_comments('announce');
	setup_comments('announcemod');
	setup_comments('announcesingle');
//##NOT_TEST_END##
	setup_comments('comments_test');
	setup_comments('comments_testmod');
//##NOT_FAQS_END##
});

function setup_comments(cset)
{
	jQuery('#' + cset + '_reset').click(function (ev) { 
		ev.stopPropagation();
		ev.preventDefault();
		if (confirm("Are you sure you wish to reset this custom template"))
		{
			jQuery('#' + cset).val(jQuery('#' + cset + '_default').val());
		}
	});
	
	jQuery('#' + cset + '_use_custom').change(function (ev) {
		showhide_comments(cset);
	});
	showhide_comments(cset);
}

function showhide_comments(cset)
{
	var value = jQuery('#' + cset + '_use_custom').attr('checked');
	if (value == "checked")
	{
		jQuery('#' + cset + '_row').css('display','table-row');
		
		if (jQuery('#' + cset).val() == "")
			jQuery('#' + cset).val(jQuery('#' + cset + '_default').val());
	} else {
		jQuery('#' + cset + '_row').css('display','none');
	}
}
</script>

<style>
.fss_custom_warn
{
	color: red;
}
.fss_help
{
	border: 1px solid #CCC;
	float: left;
	padding: 3px;
	background-color: #F8F8FF;
}
.admintable td
{
	border-bottom: 1px solid #CCC;
	padding-bottom: 4px;
	padding-top: 2px;
}</style>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="what" value="save">
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="view" value="templates" />
<input type="hidden" name="tab" id='tab' value="<?php echo $this->tab; ?>" />
<div class='ffs_tabs'>

<!--<a id='link_general' class='ffs_tab' href='#' onclick="ShowTab('general');return false;">General</a>-->

<?php //##NOT_FAQS_START## ?>
<a id='link_comments' class='ffs_tab' href='#' onclick="ShowTab('comments');return false;"><?php echo JText::_("COMMENTS"); ?></a>

<?php //##NOT_TEST_START## ?>
<a id='link_support' class='ffs_tab' href='#' onclick="ShowTab('support');return false;"><?php echo JText::_("SUPPORT"); ?></a>
<a id='link_announce' class='ffs_tab' href='#' onclick="ShowTab('announce');return false;"><?php echo JText::_("ANNOUNCEMENTS"); ?></a>
<?php //##NOT_TEST_END## ?>
<?php //##NOT_FAQS_END## ?>

<a id='link_visual' class='ffs_tab' href='#' onclick="ShowTab('visual');return false;"><?php echo JText::_("VISUAL"); ?></a>

</div>

<?php //##NOT_FAQS_START## ?>
<div id="tab_comments" style="display:none;">

	<fieldset class="adminform">
		<legend><?php echo JText::_("MODERATION_COMMENTS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='comments_general_use_custom' id='comments_general_use_custom' value='1' <?php if ($this->settings['comments_general_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_general_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_general_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_general_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="comments_general_row">
					<textarea name='comments_general' id="comments_general" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_general']; ?></textarea><br>
					<textarea id="comments_general_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_general_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>
	
<?php //##NOT_TEST_START## ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_("ANNOUNCEMENT_COMMENTS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='comments_announce_use_custom' id='comments_announce_use_custom' value='1' <?php if ($this->settings['comments_announce_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_announce_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_announce_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_announce_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="comments_announce_row">
					<textarea name='comments_announce' id="comments_announce" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_announce']; ?></textarea><br>
					<textarea id="comments_announce_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_announce_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_COMMENTS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='comments_kb_use_custom' id='comments_kb_use_custom' value='1' <?php if ($this->settings['comments_kb_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_kb_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_kb_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_kb_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="comments_kb_row">
					<textarea name='comments_kb' id="comments_kb" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_kb']; ?></textarea><br>
					<textarea id="comments_kb_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_kb_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>
<?php //##NOT_TEST_END## ?>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("TESTIMONIAL_TEMPLATES"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='comments_test_use_custom' id='comments_test_use_custom' value='1' <?php if ($this->settings['comments_test_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_test_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_test_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_test_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="comments_test_row">
					<textarea name='comments_test' id="comments_test" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_test']; ?></textarea><br>
					<textarea id="comments_test_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_test_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Module_Template"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='comments_testmod_use_custom' id='comments_testmod_use_custom' value='1' <?php if ($this->settings['comments_testmod_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_comments_testmod_use_custom'); ?></div></td>
			</tr>

			<tr id="comments_testmod_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='comments_testmod_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="comments_testmod_row">
					<textarea name='comments_testmod' id="comments_testmod" rows="12" cols="80" style="float:none;"><?php echo $this->settings['comments_testmod']; ?></textarea><br>
					<textarea id="comments_testmod_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['comments_testmod_default']; ?></textarea><br>
				</td>
				<td><div class="fss_help"><?php echo FSSAdminHelper::IncludeHelp("comment.htm"); ?></div></td>
			</tr>
		</table>
	</fieldset>
</div>


<?php //##NOT_TEST_START## ?>
<div id="tab_support" style="display:none;">

	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_LIST_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:120px;">
						<?php echo JText::_("LIST_TEMPLATE"); ?>:
				</td>
				<td>
					<select name='support_list_template' id="support_list_template" onchange="showhide_customize()">
						<option value="classic" <?php if ($this->settings['support_list_template'] == "classic") echo " SELECTED"; ?> ><?php echo JText::_('CLASSIC'); ?></option>
						<option value="custom" <?php if ($this->settings['support_list_template'] == "custom") echo " SELECTED"; ?> ><?php echo JText::_('Custom'); ?></option>
						<option value="withpriority" <?php if ($this->settings['support_list_template'] == "withpriority") echo " SELECTED"; ?> ><?php echo JText::_('CLASSIC_WITH_PRIORITY'); ?></option>
						<option value="withcustomfields" <?php if ($this->settings['support_list_template'] == "withcustomfields") echo " SELECTED"; ?> ><?php echo JText::_('WITH_CUSTOM_FIELDS_LISTED'); ?></option>
						<option value="withproddept" <?php if ($this->settings['support_list_template'] == "withproddept") echo " SELECTED"; ?> ><?php echo JText::_('WITH_DEPARTMENT_PRODUCT_CATEGORY_LISTED'); ?></option>
						<option value="withall" <?php if ($this->settings['support_list_template'] == "withall") echo " SELECTED"; ?> ><?php echo JText::_('WITH_ALL_DETAILS_LISTED'); ?></option>
					</select>
				</td>
				<td><button id="customize_button"><?php echo JText::_('CUSTOMIZE_THIS_TEMPLATE'); ?></button><button onclick="slt_preview(); return false;"><?php echo JText::_('PREVIEW_TEMPLATE'); ?></button></td>
			</tr>
			<tr id="customtemplaterow">
				<td valign="top" align="right" class="key" style="width:120px;">
						<?php echo JText::_("LIST_CUSTOM"); ?>:
				</td>
				<td valign="top">
					<span class='fss_custom_warn'><?php echo JText::_('TMPLHELP_WARN1'); ?></span><br>
					<strong><?php echo JText::_("HEADER"); ?></strong><br>
					<textarea name='support_list_head' id="support_list_head" rows="20" cols="80" style="float:none;"><?php echo $this->settings['support_list_head']; ?></textarea><br>
					<strong><?php echo JText::_("TICKET_ROW"); ?></strong><br>
					<textarea name='support_list_row' id="support_list_row" rows="20" cols="80" style="float:none;"><?php echo $this->settings['support_list_row']; ?></textarea><br>
				</td>
				<td valign="top">
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("support_template.htm"); ?>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div id="tab_announce" style="display:none;">

	<fieldset class="adminform">
		<legend><?php echo JText::_("ANNOUNCEMENTS_TEMPLATES"); ?></legend>
		<table class="admintable">
				<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Custom_Template_Announce_List"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='announce_use_custom' id='announce_use_custom' value='1' <?php if ($this->settings['announce_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_announce_use_custom'); ?></div></td>
			</tr>

			<tr id="announce_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='announce_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="announce_row">
					<textarea name='announce' id="announce" rows="20" cols="80" style="float:none;"><?php echo $this->settings['announce']; ?></textarea><br>
					<textarea id="announce_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['announce_default']; ?></textarea><br>
				</td>
				<td>
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("announce_list.htm"); ?>
					</div>
				</td>
			</tr>
		
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("Use_Custom_Template_Announce_Single"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='announcesingle_use_custom' id='announcesingle_use_custom' value='1' <?php if ($this->settings['announcesingle_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_announcesingle_use_custom'); ?></div></td>
			</tr>

			<tr id="announcesingle_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='announcesingle_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="announcesingle_row">
					<textarea name='announcesingle' id="announcesingle" rows="12" cols="80" style="float:none;"><?php echo $this->settings['announcesingle']; ?></textarea><br>
					<textarea id="announcesingle_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['announcesingle_default']; ?></textarea><br>
				</td>
				<td>
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("announce_single.htm"); ?>
					</div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:150px;">
						<?php echo JText::_("USE_CUSTOM_MODULE_TEMPLATE_ANNOUNCE"); ?>:
				</td>
				<td width="370">
					<input type='checkbox' name='announcemod_use_custom' id='announcemod_use_custom' value='1' <?php if ($this->settings['announcemod_use_custom'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td><div class="fss_help"><?php echo JText::_('TMPLHELP_announcemod_use_custom'); ?></div></td>
			</tr>

			<tr id="announcemod_row">
				<td valign="top" align="right" class="key" style="width:150px;">
					<?php echo JText::_("Custom_Template"); ?>:<br />
					<button id='announcemod_reset' style='float:none;'><?php echo JText::_('Reset');?></button><br />
					<span class='fss_custom_warn'>
						<?php echo JText::_('TMPLHELP_WARN1'); ?>
					</span>
				</td>
				<td valign="top" width="370" id="announcemod_row">
					<textarea name='announcemod' id="announcemod" rows="12" cols="80" style="float:none;"><?php echo $this->settings['announcemod']; ?></textarea><br>
					<textarea id="announcemod_default" rows="12" cols="80" style="display:none;"><?php echo $this->settings['announcemod_default']; ?></textarea><br>
				</td>
				<td>
					<div class="fss_help">
						<?php echo FSSAdminHelper::IncludeHelp("announce_module.htm"); ?>
					</div>
				</td>
			</tr>
			
		</table>
	</fieldset>
</div>

<?php //##NOT_FAQS_END## ?>
<?php //##NOT_TEST_END## ?>

<div id="tab_visual" style="display:none;">


	<fieldset class="adminform">
		<legend><?php echo JText::_("CSS_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">	
						<?php echo JText::_("MAIN_CSS"); ?>:
				</td>
				<td>
					<textarea name="display_style" rows="10" cols="60"><?php echo $this->settings['display_style'] ?></textarea>
				</td>
				<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_style'); ?>
					

					</div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">	
						<?php echo JText::_("POPUP_CSS"); ?>:
				</td>
				<td>
					<textarea name="display_popup_style" rows="10" cols="60"><?php echo $this->settings['display_popup_style'] ?></textarea>
				</td>
				<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_popup_style'); ?>
					
					</div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">	
						<?php echo JText::_("PAGE_HEADER"); ?>:
				</td>
				<td>
					<textarea name="display_head" rows="10" cols="60"><?php echo $this->settings['display_head'] ?></textarea>
				</td>
				<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_head'); ?>
						
					</div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">	
						<?php echo JText::_("PAGE_FOOTER"); ?>:
				</td>
				<td>
					<textarea name="display_foot" rows="10" cols="60"><?php echo $this->settings['display_foot'] ?></textarea>
				</td>
				<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_foot'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("PAGE_TITLE_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("H1_STYLE"); ?>:
					
				</td>
				<td>
					<textarea name="display_h1" rows="5" cols="60"><?php echo $this->settings['display_h1'] ?></textarea>
				</td>
				<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_h1'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("H2_STYLE"); ?>:
					
				</td>
				<td>
					<textarea name="display_h2" rows="5" cols="60"><?php echo $this->settings['display_h2'] ?></textarea>
				</td>
				<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_h2'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("H3_STYLE"); ?>:
					
				</td>
				<td>
					<textarea name="display_h3" rows="5" cols="60"><?php echo $this->settings['display_h3'] ?></textarea>
				</td>
					<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_h3'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">	
						<?php echo JText::_("POPUP_STYLE"); ?>:	
				</td>
				<td>
					<textarea name="display_popup" rows="5" cols="60"><?php echo $this->settings['display_popup'] ?></textarea>
				</td>
				<td>
					<div class="fss_help"><?php echo JText::_('TMPLHELP_display_popup'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

</form>

<form action="<?php echo JURI::root(); ?>/index.php?view=admin&layout=support&option=com_fss&preview=1" method="post" name="adminForm2" id="adminForm2" target="_blank">
<input type="hidden" name="list_template" id="list_template" value="" />
<textarea style='display:none;' name="list_head" id="list_head"></textarea>
<textarea style='display:none;' name="list_row" id="list_row"></textarea>
</form>

<script>

//##NOT_EXT_START##
function showhide_customize()
{
	var current = $('support_list_template').value;
	if (current == "custom")
	{
		$('customize_button').style.display = 'none';
		$('customtemplaterow').style.display = 'table-row';
	} else {
		$('customize_button').style.display = 'inline';
		$('customtemplaterow').style.display = 'none';
	}
}

jQuery(document).ready(function () {
	jQuery('#customize_button').click(function(e){
		e.preventDefault();
		slt_customize();
	});
});
function slt_customize()
{
	var current = $('support_list_template').value;
	if (current == "custom")
		return;

	if (!confirm("This will over write any existing custom template! Are you sure?")) 
		return;

	var url = '<?php echo FSSRoute::x("index.php?option=com_fss&view=settings&what=customtemplate",false); ?>&name=' + current;
	<?php if (FSS_Helper::Is16()): ?>
	var req = new Request({
		method: 'get',
		url: url,
		data: { 'do' : '1' },
		onComplete: function(response) { 
			jsonObj = JSON.decode(response); 
			$('support_list_head').value = jsonObj.head;
			$('support_list_row').value = jsonObj.row;
			$('support_list_template').value = "custom";
			showhide_customize();
		}
	}).send();
	<?php else : ?>
	var request = new Json.Remote(url, {
		onComplete: function(jsonObj) {
			$('support_list_head').value = jsonObj.head;
			$('support_list_row').value = jsonObj.row;
			$('support_list_template').value = "custom";
			showhide_customize();
		}
	}).send();
	<?php endif; ?>
	
	return false;
}

function slt_preview()
{
	$('list_template').value = $('support_list_template').value;
	$('list_head').value = $('support_list_head').value;
	$('list_row').value = $('support_list_row').value;
	$('adminForm2').submit();
}
//##NOT_EXT_END##

</script>
