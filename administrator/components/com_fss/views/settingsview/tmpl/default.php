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
//##NOT_FAQS_START##	
	ResetElement('test');
//##NOT_FAQS_END##	

//##NOT_TEST_START##	
	ResetElement('faqs');
	ResetElement('glossary');
//##NOT_TEST_END##
	
//##NOT_EXT_START##		
	ResetElement('kb');
//##NOT_EXT_END##

	location.hash = tabid;
	jQuery('#tab').val(tabid);
	
	document.getElementById('tab_' + tabid).style.display = 'inline';
	document.getElementById('link_' + tabid).style.backgroundColor = '#f0f0ff';
	
	document.getElementById('link_' + tabid).onmouseover = function() {
	}
	
	document.getElementById('link_' + tabid).onmouseout = function() {
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
}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="what" value="save">
<input type="hidden" name="option" value="com_fss" />
<input type="hidden" name="task" value="save" />
<input type="hidden" name="view" value="settingsview" />
<input type="hidden" name="tab" id='tab' value="<?php echo $this->tab; ?>" />
<div class='ffs_tabs'>


<?php //##NOT_EXT_START## ?>
<a id='link_kb' class='ffs_tab' href='#' onclick="ShowTab('kb');return false;"><?php echo JText::_("KB_ARTICLES_LIST"); ?></a> 
<?php //##NOT_EXT_END## ?>

<?php //##NOT_TEST_START## ?>
<a id='link_faqs' class='ffs_tab' href='#' onclick="ShowTab('faqs');return false;"><?php echo JText::_("FAQS_LIST"); ?></a> 
<a id='link_glossary' class='ffs_tab' href='#' onclick="ShowTab('glossary');return false;"><?php echo JText::_("Glossary"); ?></a> 
<?php //##NOT_TEST_END## ?>

<?php //##NOT_FAQS_START## ?>
<a id='link_test' class='ffs_tab' href='#' onclick="ShowTab('test');return false;"><?php echo JText::_("TESTIMONIALS_LIST"); ?></a>
<?php //##NOT_FAQS_END## ?>

</div>

<?php //##NOT_TEST_START## ?>
<div id="tab_faqs">
	<fieldset class="adminform">
		<legend><?php echo JText::_("FAQS_WHEN_SHOWING_LIST_OF_CATEGORIES"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_ALWAYS_SHOW_FAQS"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_always_show_faqs' value='1' <?php if ($this->settings['faqs_always_show_faqs'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_ALWAYS_SHOW_FAQS'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_HIDE_ALLFAQS"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_hide_allfaqs' value='1' <?php if ($this->settings['faqs_hide_allfaqs'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_HIDE_ALLFAQS'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_HIDE_TAGS"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_hide_tags' value='1' <?php if ($this->settings['faqs_hide_tags'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_hide_tags'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_HIDE_SEARCH"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_hide_search' value='1' <?php if ($this->settings['faqs_hide_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_hide_search'); ?></div>
				</td>
			</tr>

			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_SHOW_FEATURED"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_show_featured' value='1' <?php if ($this->settings['faqs_show_featured'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_show_featured'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_NUM_CAT_COLUMS"); ?>:
					
				</td>
				<td>
					<select name="faqs_num_cat_colums">
						<option value="1" <?php if ($this->settings['faqs_num_cat_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['faqs_num_cat_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['faqs_num_cat_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['faqs_num_cat_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_NUM_CAT_COLUMS'); ?></div>
				</td>
			</tr>

			
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_view_mode_cat"); ?>:
					
				</td>
				<td>
					<select name="faqs_view_mode_cat">
						<option value="list" <?php if ($this->settings['faqs_view_mode_cat'] == 'list') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_LIST'); ?></option>
						<option value="inline" <?php if ($this->settings['faqs_view_mode_cat'] == 'inline') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_INLINE'); ?></option>
						<option value="accordian" <?php if ($this->settings['faqs_view_mode_cat'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_ACCORDIAN'); ?></option>
						<option value="popup" <?php if ($this->settings['faqs_view_mode_cat'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('FAQS_VIEW_MODE_CAT_POPUP'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_view_mode_cat'); ?></div>
				</td>
			</tr>

			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_view_mode_incat"); ?>:
					
				</td>
				<td>
					<select name="faqs_view_mode_incat">
						<option value="allononepage" <?php if ($this->settings['faqs_view_mode_incat'] == 'allononepage') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_allononepage'); ?></option>
						<option value="accordian" <?php if ($this->settings['faqs_view_mode_incat'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_accordian'); ?></option>
						<option value="questionwithtooltip" <?php if ($this->settings['faqs_view_mode_incat'] == 'questionwithtooltip') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithtooltip'); ?></option>
						<option value="questionwithlink" <?php if ($this->settings['faqs_view_mode_incat'] == 'questionwithlink') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithlink'); ?></option>
						<option value="questionwithpopup" <?php if ($this->settings['faqs_view_mode_incat'] == 'questionwithpopup') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithpopup'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_faqs_view_mode_incat'); ?></div>
				</td>
			</tr>


		</table>
	</fieldset>


	<fieldset class="adminform">
		<legend><?php echo JText::_("FAQS_WHEN_SHOWING_LIST_OF_FAQS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_always_show_cats"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_always_show_cats' value='1' <?php if ($this->settings['faqs_always_show_cats'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_always_show_cats'); ?></div>
				</td>
			</tr>
		
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQS_view_mode"); ?>:
					
				</td>
				<td>
					<select name="faqs_view_mode">
						<option value="allononepage" <?php if ($this->settings['faqs_view_mode'] == 'allononepage') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_allononepage'); ?></option>
						<option value="accordian" <?php if ($this->settings['faqs_view_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_accordian'); ?></option>
						<option value="questionwithtooltip" <?php if ($this->settings['faqs_view_mode'] == 'questionwithtooltip') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithtooltip'); ?></option>
						<option value="questionwithlink" <?php if ($this->settings['faqs_view_mode'] == 'questionwithlink') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithlink'); ?></option>
						<option value="questionwithpopup" <?php if ($this->settings['faqs_view_mode'] == 'questionwithpopup') echo " SELECTED"; ?> ><?php echo JText::_('faqs_view_mode_incat_questionwithpopup'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_faqs_view_mode'); ?></div>
				</td>
			</tr>

			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_enable_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='faqs_enable_pages' value='1' <?php if ($this->settings['faqs_enable_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_FAQS_enable_pages'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

</div>

<div id="tab_glossary">

	<fieldset class="adminform">
		<legend><?php echo JText::_("GLOSSARY_VIEW_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("glossary_use_letter_bar"); ?>:
				</td>
				<td style="width:250px;">
					<select name="glossary_use_letter_bar">
						<option value="0" <?php if ($this->settings['glossary_use_letter_bar'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('GLOSSARY_USE_LETTER_BAR_0'); ?></option>
						<option value="1" <?php if ($this->settings['glossary_use_letter_bar'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('GLOSSARY_USE_LETTER_BAR_1'); ?></option>
						<option value="2" <?php if ($this->settings['glossary_use_letter_bar'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('GLOSSARY_USE_LETTER_BAR_2'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('view_help_glossary_use_letter_bar'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

</div>

<?php //##NOT_TEST_END## ?>

<?php //##NOT_EXT_START## ?>

<div id="tab_kb">


	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_MAIN_PAGE"); ?></legend>

		<table class="admintable">
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_prod"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_prod' value='1' <?php if ($this->settings['kb_main_show_prod'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_prod'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_cat"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_cat' value='1' <?php if ($this->settings['kb_main_show_cat'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_cat'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_sidebyside"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_sidebyside' value='1' <?php if ($this->settings['kb_main_show_sidebyside'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_sidebyside'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_show_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_show_search' value='1' <?php if ($this->settings['kb_main_show_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_show_search'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_MAIN_PAGE_PROD"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("KB_main_prod_colums"); ?>:
					
				</td>
				<td>
					<select name="kb_main_prod_colums">
						<option value="1" <?php if ($this->settings['kb_main_prod_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['kb_main_prod_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['kb_main_prod_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['kb_main_prod_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_prod_colums'); ?></div>
				</td>
			</tr>
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_prod_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_prod_search' value='1' <?php if ($this->settings['kb_main_prod_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_prod_search'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_prod_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_main_prod_pages' value='1' <?php if ($this->settings['kb_main_prod_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_prod_pages'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_MAIN_PAGE_CAT"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("kb_main_cat_mode"); ?>:
					
				</td>
				<td>
					<select name="kb_main_cat_mode">
						<option value="normal" <?php if ($this->settings['kb_main_cat_mode'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_normal'); ?></option>
						<option value="accordian" <?php if ($this->settings['kb_main_cat_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_accordian'); ?></option>
						<option value="links" <?php if ($this->settings['kb_main_cat_mode'] == 'links') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_links'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_cat_mode'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_main_cat_arts"); ?>:
				</td>
				<td>
					<select name="kb_main_cat_arts">
						<option value="normal" <?php if ($this->settings['kb_main_cat_arts'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_normal'); ?></option>
						<option value="popup" <?php if ($this->settings['kb_main_cat_arts'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_popup'); ?></option>
					</select>
				</td>
			</tr>


			<tr>
				<td align="right" class="key">
					<?php echo JText::_("KB_main_prod_colums"); ?>:
					
				</td>
				<td>
					<select name="kb_main_cat_colums">
						<option value="1" <?php if ($this->settings['kb_main_prod_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['kb_main_prod_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['kb_main_prod_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['kb_main_prod_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_main_cat_colums'); ?></div>
				</td>
			</tr>
		

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_PROD_SEL"); ?></legend>

		<table class="admintable">
		
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("kb_prod_cat_mode"); ?>:
					
				</td>
				<td>
					<select name="kb_prod_cat_mode">
						<option value="normal" <?php if ($this->settings['kb_prod_cat_mode'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_normal'); ?></option>
						<option value="accordian" <?php if ($this->settings['kb_prod_cat_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_accordian'); ?></option>
						<option value="links" <?php if ($this->settings['kb_prod_cat_mode'] == 'links') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_mode_links'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_cat_mode'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_prod_cat_arts"); ?>:
				</td>
				<td>
					<select name="kb_prod_cat_arts">
						<option value="normal" <?php if ($this->settings['kb_prod_cat_arts'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_normal'); ?></option>
						<option value="popup" <?php if ($this->settings['kb_prod_cat_arts'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('kb_main_cat_arts_popup'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_cat_arts'); ?></div>
				</td>
			</tr>


			<tr>
				<td align="right" class="key">
					<?php echo JText::_("KB_prod_cat_colums"); ?>:
					
				</td>
				<td>
					<select name="KB_prod_cat_colums">
						<option value="1" <?php if ($this->settings['kb_prod_cat_colums'] == 1) echo " SELECTED"; ?> >1</option>
						<option value="2" <?php if ($this->settings['kb_prod_cat_colums'] == 2) echo " SELECTED"; ?> >2</option>
						<option value="3" <?php if ($this->settings['kb_prod_cat_colums'] == 3) echo " SELECTED"; ?> >3</option>
						<option value="4" <?php if ($this->settings['kb_prod_cat_colums'] == 4) echo " SELECTED"; ?> >4</option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_cat_colums'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_prod_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_prod_search' value='1' <?php if ($this->settings['kb_prod_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_prod_search'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("KB_PROD_CAT_SEL"); ?></legend>

		<table class="admintable">
		
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_cat_mode"); ?>:
				</td>
				<td>
					<select name="kb_cat_cat_mode">
						<option value="normal" <?php if ($this->settings['kb_cat_cat_mode'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_mode_normal'); ?></option>
						<option value="accordian" <?php if ($this->settings['kb_cat_cat_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_mode_accordian'); ?></option>
						<option value="links" <?php if ($this->settings['kb_cat_cat_mode'] == 'links') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_mode_links'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_cat_mode'); ?></div>
				</td>
			</tr>
		
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_cat_arts"); ?>:
				</td>
				<td>
					<select name="kb_cat_cat_arts">
						<option value="normal" <?php if ($this->settings['kb_cat_cat_arts'] == 'normal') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_arts_normal'); ?></option>
						<option value="popup" <?php if ($this->settings['kb_cat_cat_arts'] == 'popup') echo " SELECTED"; ?> ><?php echo JText::_('kb_cat_cat_arts_popup'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_cat_arts'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_art_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_cat_art_pages' value='1' <?php if ($this->settings['kb_cat_art_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_art_pages'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("kb_cat_search"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_cat_search' value='1' <?php if ($this->settings['kb_cat_search'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_kb_cat_search'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

</div>
<?php //##NOT_EXT_END## ?>

<?php //##NOT_FAQS_START## ?>
<div id="tab_test">

	<fieldset class="adminform">
		<legend><?php echo JText::_("TEST_WHEN_SHOWING_PRODUCT_LIST"); ?></legend>

		<table class="admintable">
		
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("test_show_prod_mode"); ?>:
					
				</td>
				<td>
					<select name="test_test_show_prod_mode">
						<option value="list" <?php if ($this->settings['test_test_show_prod_mode'] == 'list') echo " SELECTED"; ?> ><?php echo JText::_('test_show_prod_mode_list'); ?></option>
						<option value="inline" <?php if ($this->settings['test_test_show_prod_mode'] == 'inline') echo " SELECTED"; ?> ><?php echo JText::_('test_show_prod_mode_inline'); ?></option>
						<option value="accordian" <?php if ($this->settings['test_test_show_prod_mode'] == 'accordian') echo " SELECTED"; ?> ><?php echo JText::_('test_show_prod_mode_accordian'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_test_show_prod_mode'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("test_pages"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='test_test_pages' value='1' <?php if ($this->settings['test_test_pages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_test_pages'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("test_always_prod_select"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='test_test_always_prod_select' value='1' <?php if ($this->settings['test_test_always_prod_select'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('VIEWHELP_test_always_prod_select'); ?></div>
				</td>
			</tr>

		</table>
	</fieldset>

</div>
<?php //##NOT_FAQS_END## ?>

</form>

<script>

window.addEvent('domready', function(){
	if (location.hash)
	{
		ShowTab(location.hash.replace('#',''));
	}
	else
	{
		var els = jQuery('a.ffs_tab');
		var el = jQuery(els[0]);
		var firsttab = el.attr('id').replace("link_","");
		ShowTab(firsttab);
	}
});
 
</script>
