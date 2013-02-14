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
	ResetElement('general');
//##NOT_FAQS_START##
//##NOT_TEST_START##
	ResetElement('announce');
	ResetElement('kb');
//##NOT_TEST_END##
	ResetElement('test');
//##NOT_TEST_START##
	ResetElement('support');
//##NOT_FAQS_END##
//##NOT_TEST_END##
	ResetElement('visual');
//##NOT_TEST_START##
	ResetElement('glossary');
	ResetElement('faq');
//##NOT_TEST_END##
	
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
<input type="hidden" name="view" value="settings" />
<input type="hidden" name="tab" id='tab' value="<?php echo $this->tab; ?>" />
<input type="hidden" name="version" value="<?php echo $this->settings['version']; ?>" />
<input type="hidden" name="fsj_username" value="<?php echo $this->settings['fsj_username']; ?>" />
<input type="hidden" name="fsj_apikey" value="<?php echo $this->settings['fsj_apikey']; ?>" />
<input type="hidden" name="content_unpublished_color" value="<?php echo $this->settings['content_unpublished_color']; ?>" />
<div class='ffs_tabs'>

<!--<a id='link_general' class='ffs_tab' href='#' onclick="ShowTab('general');return false;">General</a>-->

<a id='link_general' class='ffs_tab' href='#' onclick="ShowTab('general');return false;"><?php echo JText::_("GENERAL_SETTINGS"); ?></a> 
<?php //##NOT_FAQS_START## ?>
<?php //##NOT_TEST_START## ?>
<a id='link_announce' class='ffs_tab' href='#' onclick="ShowTab('announce');return false;"><?php echo JText::_("ANNOUNCEMENTS"); ?></a> 
<a id='link_kb' class='ffs_tab' href='#' onclick="ShowTab('kb');return false;"><?php echo JText::_("KNOWLEDGE_BASE"); ?></a> 
<?php //##NOT_TEST_END## ?>
<a id='link_test' class='ffs_tab' href='#' onclick="ShowTab('test');return false;"><?php echo JText::_("TESTIMONIALS"); ?></a>
<?php //##NOT_TEST_START## ?>
<a id='link_support' class='ffs_tab' href='#' onclick="ShowTab('support');return false;"><?php echo JText::_("SUPPORT"); ?></a>
<?php //##NOT_TEST_END## ?>
<?php //##NOT_FAQS_END## ?>
<a id='link_visual' class='ffs_tab' href='#' onclick="ShowTab('visual');return false;"><?php echo JText::_("VISUAL"); ?></a>
<?php //##NOT_TEST_START## ?>
<a id='link_glossary' class='ffs_tab' href='#' onclick="ShowTab('glossary');return false;"><?php echo JText::_("GLOSSARY"); ?></a>
<a id='link_faq' class='ffs_tab' href='#' onclick="ShowTab('faq');return false;"><?php echo JText::_("FAQS"); ?></a> 
<?php //##NOT_TEST_END## ?>

</div>

<div id="tab_general">

	<fieldset class="adminform">
		<legend><?php echo JText::_("GENERAL_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("HIDE_POWERED"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='hide_powered' value='1' <?php if ($this->settings['hide_powered'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_hide_powered'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
<?php //##NOT_FAQS_START## ?>

	<fieldset class="adminform">
		<legend><?php echo JText::_("PERMISSIONS_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("USE_JOOMLA_PERM_COMMENT"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='perm_mod_joomla' value='1' <?php if ($this->settings['perm_mod_joomla'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_perm_mod_joomla'); ?></div>
				</td>
			</tr>
<?php //##NOT_TEST_START## ?>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("USE_JOOMLA_PERM_CONTENT"); ?>:
				</td>
				<td>
					<input type='checkbox' name='perm_article_joomla' value='1' <?php if ($this->settings['perm_article_joomla'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_perm_article_joomla'); ?></div>
				</td>
			</tr>
<?php //##NOT_TEST_END## ?>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("COMMENTS_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("CAPTCHA_TYPE"); ?>:
				</td>
				<td style="width:250px;">
					<select name="captcha_type">
						<option value="none" <?php if ($this->settings['captcha_type'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('FNONE'); ?></option>
						<option value="fsj" <?php if ($this->settings['captcha_type'] == "fsj") echo " SELECTED"; ?> ><?php echo JText::_('BUILT_IN'); ?></option>
						<option value="recaptcha" <?php if ($this->settings['captcha_type'] == "recaptcha") echo " SELECTED"; ?> ><?php echo JText::_('RECAPTCHA'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_captcha_type'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("HIDE_ADD_COMMENT"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='comments_hide_add' value='1' <?php if ($this->settings['comments_hide_add'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_comments_hide_add'); ?></div>
				</td>
			</tr>
<?php //##NOT_TEST_START## ?>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("COMMENTS_ARE_MODERATED_BEFORE_DISPLAY"); ?>:
					
				</td>
				<td>
					<select name="comments_moderate">
						<option value="all" <?php if ($this->settings['comments_moderate'] == "all") echo " SELECTED"; ?> ><?php echo JText::_('ALL_COMMENTS_MODERATED'); ?></option>
						<option value="guests" <?php if ($this->settings['comments_moderate'] == "guests") echo " SELECTED"; ?> ><?php echo JText::_('GUEST_COMMENTS_MODERATED'); ?></option>
						<option value="registered" <?php if ($this->settings['comments_moderate'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_AND_GUEST_COMMENTS_MODERATED'); ?></option>
						<option value="none" <?php if ($this->settings['comments_moderate'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('NO_COMMENTS_ARE_MODERATED'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_comments_moderate'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("WHO_CAN_ADD_COMMENTS"); ?>:
				</td>
				<td>
					<select name="comments_who_can_add">
						<option value="anyone" <?php if ($this->settings['comments_who_can_add'] == "anyone") echo " SELECTED"; ?> ><?php echo JText::_('ANYONE'); ?></option>
						<option value="registered" <?php if ($this->settings['comments_who_can_add'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_USERS_ONLY'); ?></option>
						<!--<option value="moderators" <?php if ($this->settings['comments_who_can_add'] == "moderators") echo " SELECTED"; ?> >Moderators Only</option>-->
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_comments_who_can_add'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("KB_EMAIL_ON_COMMENT"); ?>:
					
				</td>
				<td>
					<input name='email_on_comment' size="40" value='<?php echo $this->settings['email_on_comment']; ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_email_on_comment'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("COMMENT_USE_EMAIL"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='commnents_use_email' value='1' <?php if ($this->settings['commnents_use_email'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_commnents_use_email'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("COMMENT_USE_WEBSITE"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='commnents_use_website' value='1' <?php if ($this->settings['commnents_use_website'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_commnents_use_website'); ?></div>
				</td>
			</tr>
<?php //##NOT_TEST_END## ?>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("RECAPTCHA_PUBLIC_KEY"); ?>:
				</td>
				<td>
					<input name='recaptcha_public' size="40" value='<?php echo $this->settings['recaptcha_public'] ?>'>
				</td>
				<td rowspan="2">
					<div class='fss_help'><?php echo JText::_('SETHELP_recaptcha_public'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("RECAPTCHA_PRIVATE_KEY"); ?>:
				</td>
				<td>
					<input name='recaptcha_private' size="40" value='<?php echo $this->settings['recaptcha_private'] ?>'>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("RECAPTCHA_THEME"); ?>:
				</td>
				<td>
					<select name="recaptcha_theme">
						<option value="red" <?php if ($this->settings['recaptcha_theme'] == "red") echo " SELECTED"; ?> ><?php echo JText::_('RED'); ?></option>
						<option value="white" <?php if ($this->settings['recaptcha_theme'] == "white") echo " SELECTED"; ?> ><?php echo JText::_('WHITE'); ?></option>
						<option value="blackglass" <?php if ($this->settings['recaptcha_theme'] == "blackglass") echo " SELECTED"; ?> ><?php echo JText::_('BLACK_GLASS'); ?></option>
						<option value="clean" <?php if ($this->settings['recaptcha_theme'] == "clean") echo " SELECTED"; ?> ><?php echo JText::_('CLEAN'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_recaptcha_theme'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
<?php //##NOT_FAQS_END## ?>

<?php if (FSS_Helper::Is16()): ?>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("DATE_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("SHORT_DATETIME"); ?>:
				</td>
				<td style="width:350px;">
					<input name='date_dt_short' id='date_dt_short' size="40" value='<?php echo $this->settings['date_dt_short'] ?>'>
					<div class="fss_clear"></div>
					<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC4') . ', H:i'; ?></b></div>
					<div id="test_date_dt_short"></div>
				</td>
				<td rowspan="4" valign="top">
					<div class='fss_help'>
					<?php echo JText::_('SETHELP_DATE_FORMATS'); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("LONG_DATETIME"); ?>:
				</td>
				<td style="width:350px;">
					<input name='date_dt_long' id='date_dt_long' size="40" value='<?php echo $this->settings['date_dt_long'] ?>'>
					<div class="fss_clear"></div>
					<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC3') . ', H:i'; ?></b></div>
					<div id="test_date_dt_long"></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("SHORT_DATE"); ?>:
				</td>
				<td style="width:350px;">
					<input name='date_d_short' id='date_d_short' size="40" value='<?php echo $this->settings['date_d_short'] ?>'>
					<div class="fss_clear"></div>
					<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC4'); ?></b></div>
					<div id="test_date_d_short"></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("LONG_DATE"); ?>:
				</td>
				<td style="width:350px;">
					<input name='date_d_long' id='date_d_long' size="40" value='<?php echo $this->settings['date_d_long'] ?>'>
					<div class="fss_clear"></div>
					<div>Joomla : <b><?php echo JText::_('DATE_FORMAT_LC3'); ?></b></div>
					<div id="test_date_d_long"></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("TIMEZONE_OFFSET"); ?>:
				</td>
				<td>
					<input name='timezone_offset' size="40" value='<?php echo $this->settings['timezone_offset'] ?>'>
					<div class="fss_clear"></div>
					<div id="test_timezone_offset"></div>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_timezone_offset'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("TEST_DATE_FORMATS"); ?>:
				</td>
				<td style="width:250px;">
					<button id="test_date_formats"><?php echo JText::_('TEST_DATE_FORMATS_BUTTON'); ?></button>
				</td>
				<td valign="top">
					<div class='fss_help'>
					<?php echo JText::_('SETHELP_DATE_TEST'); ?>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>

<?php endif; ?>
</div>

<?php //##NOT_FAQS_START## ?>
<?php //##NOT_TEST_START## ?>
<div id="tab_announce">

	<fieldset class="adminform">
		<legend><?php echo JText::_("ANNOUNCEMENTS_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("ALLOW_ARTICLE_COMMENTS"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='announce_comments_allow' value='1' <?php if ($this->settings['announce_comments_allow'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_announce_comments_allow'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("ANNOUNCE_USE_CONTENT_PLUGINS"); ?>:
				</td>
				<td>
					<input type='checkbox' name='announce_use_content_plugins' value='1' <?php if ($this->settings['announce_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_announce_use_content_plugins'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("ANNOUNCE_USE_CONTENT_PLUGINS_LIST"); ?>:
				</td>
				<td>
					<input type='checkbox' name='announce_use_content_plugins_list' value='1' <?php if ($this->settings['announce_use_content_plugins_list'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_announce_use_content_plugins_list'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("ANNOUNCE_COMMENTS_PER_PAGE"); ?>:
				</td>
				<td>
					<?php $this->PerPage('announce_comments_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_announce_comments_per_page'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("ANNOUNCE_PER_PAGE"); ?>:
				</td>
				<td>
					<?php $this->PerPage('announce_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_announce_per_page'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div id="tab_kb">

	<fieldset class="adminform">
		<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_COMMENTS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("USE_RATING_SYSTEM"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_rate' value='1' <?php if ($this->settings['kb_rate'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_rate'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("ALLOW_ARTICLE_COMMENTS"); ?>:
				</td>
				<td>
					<input type='checkbox' name='kb_comments' value='1' <?php if ($this->settings['kb_comments'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_comments'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("KB_USE_CONTENT_PLUGINS"); ?>:					
				</td>
				<td>
					<input type='checkbox' name='kb_use_content_plugins' value='1' <?php if ($this->settings['kb_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_use_content_plugins'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_VIEWS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("SHOW_VIEWS"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_show_views' value='1' <?php if ($this->settings['kb_show_views'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_views'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("SHOW_VIEWS_TOP"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_view_top' value='1' <?php if ($this->settings['kb_view_top'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_view_top'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("SHOW_MOST_RECENT_VIEW"); ?>:
				</td>
				<td>
					<input type='checkbox' name='kb_show_recent' value='1' <?php if ($this->settings['kb_show_recent'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_recent'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("SHOW_MOST_RECENT_STATISTICS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='kb_show_recent_stats' value='1' <?php if ($this->settings['kb_show_recent_stats'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_recent_stats'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("SHOW_HIGHEST_RATED_VIEW"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='kb_show_rated' value='1' <?php if ($this->settings['kb_show_rated'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_rated'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("SHOW_HIGHEST_RATED_STATISTICS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='kb_show_rated_stats' value='1' <?php if ($this->settings['kb_show_rated_stats'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_rated_stats'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("SHOW_MOST_VIEWED_VIEW"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='kb_show_viewed' value='1' <?php if ($this->settings['kb_show_viewed'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_viewed'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("SHOW_MOST_VIEWED_STATISTICS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='kb_show_viewed_stats' value='1' <?php if ($this->settings['kb_show_viewed_stats'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_viewed_stats'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_VIEWING_ARTICLE"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_CREATED_AND_MODIFIED_DATES"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_show_dates' value='1' <?php if ($this->settings['kb_show_dates'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_dates'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_KB_ART_RELATED"); ?>:
				</td>
				<td>
					<input type='checkbox' name='kb_show_art_related' value='1' <?php if ($this->settings['kb_show_art_related'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_art_related'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_KB_ART_APPLYS"); ?>:
				</td>
				<td>
					<input type='checkbox' name='kb_show_art_products' value='1' <?php if ($this->settings['kb_show_art_products'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_art_products'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_KB_ART_ATTACH"); ?>:
				</td>
				<td>
					<input type='checkbox' name='kb_show_art_attach' value='1' <?php if ($this->settings['kb_show_art_attach'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_show_art_attach'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("KB_COMMENTS_PER_PAGE"); ?>:
				</td>
				<td>
					<?php $this->PerPage('kb_comments_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_comments_per_page'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_("KNOWLEDGE_BASE_SETTINGS_GENERAL"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SMALLER_IMAGES_ON_SUBCATEGORIES"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='kb_smaller_subcat_images' value='1' <?php if ($this->settings['kb_smaller_subcat_images'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_smaller_subcat_images'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("KBS_PER_PAGE"); ?>:
				</td>
				<td>
					<?php $this->PerPage('kb_art_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_art_per_page'); ?></div>
				</td>
			</tr>	
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("KB_PRODS_PER_PAGE"); ?>:
				</td>
				<td>
					<?php $this->PerPage('kb_prod_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_kb_prod_per_page'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

</div>
<?php //##NOT_TEST_END## ?>

<div id="tab_test" style="display:none;">

	<fieldset class="adminform">
		<legend><?php echo JText::_("TESTIMONIAL_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("TESTIMONIALS_ARE_MODERATED_BEFORE_DISPLAY"); ?>:
					
				</td>
				<td style="width:250px;">
					<select name="test_moderate">
						<option value="all" <?php if ($this->settings['test_moderate'] == "all") echo " SELECTED"; ?> ><?php echo JText::_('ALL_TESTIMONIALS_MODERATED'); ?></option>
						<option value="guests" <?php if ($this->settings['test_moderate'] == "guests") echo " SELECTED"; ?> ><?php echo JText::_('GUEST_TESTIMONIALS_MODERATED'); ?></option>
						<option value="registered" <?php if ($this->settings['test_moderate'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_AND_GUEST_TESTIMONIALS_MODERATED'); ?></option>
						<option value="none" <?php if ($this->settings['test_moderate'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('NO_TESTIMONIALS_ARE_MODERATED'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_moderate'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("ALLOW_NO_PRODUCT_TESTS"); ?>:
				</td>
				<td>
					<input type='checkbox' name='test_allow_no_product' value='1' <?php if ($this->settings['test_allow_no_product'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_allow_no_product'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("HIDE_EMPTY_PROD_WHEN_LISTING"); ?>:
				</td>
				<td>
					<input type='checkbox' name='test_hide_empty_prod' value='1' <?php if ($this->settings['test_hide_empty_prod'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_hide_empty_prod'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("WHO_CAN_ADD_TESTIMONIALS"); ?>:
				</td>
				<td>
					<select name="test_who_can_add">
						<option value="anyone" <?php if ($this->settings['test_who_can_add'] == "anyone") echo " SELECTED"; ?> ><?php echo JText::_('ANYONE'); ?></option>
						<option value="registered" <?php if ($this->settings['test_who_can_add'] == "registered") echo " SELECTED"; ?> ><?php echo JText::_('REGISTERED_USERS_ONLY'); ?></option>
						<option value="moderators" <?php if ($this->settings['test_who_can_add'] == "moderators") echo " SELECTED"; ?> ><?php echo JText::_('MODERATORS_ONLY'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_who_can_add'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("EMAIL_ON_SUBMITTED"); ?>:
					
				</td>
				<td>
					<input name='test_email_on_submit' size="40" value='<?php echo $this->settings['test_email_on_submit']; ?>'>
					</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_email_on_submit'); ?></div>
			</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("TEST_USE_EMAIL"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='test_use_email' value='1' <?php if ($this->settings['test_use_email'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_use_email'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("TEST_USE_WEBSITE"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='test_use_website' value='1' <?php if ($this->settings['test_use_website'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_use_website'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("TEST_COMMENTS_PER_PAGE"); ?>:
				</td>
				<td>
					<?php $this->PerPage('test_comments_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_test_comments_per_page'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

</div>
<?php //##NOT_TEST_START## ?>

<div id="tab_support" style="display:none;">

	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("USE_ADVANCED_PRODUCT_SELECTION"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='support_advanced' value='1' <?php if ($this->settings['support_advanced'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("ticket_prod_per_page"); ?>:
				</td>
				<td>
					<?php $this->PerPage('ticket_prod_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_ticket_prod_per_page'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("OPEN_NEXT_ON_CLICK"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='support_next_prod_click' value='1' <?php if ($this->settings['support_next_prod_click'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_next_prod_click'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("ALLOW_TICKETS_BY_UNREGISTERED_USERS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_allow_unreg' value='1' <?php if ($this->settings['support_allow_unreg'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_allow_unreg'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("NO_LOGIN_ON_OPEN"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_no_logon' value='1' <?php if ($this->settings['support_no_logon'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_no_logon'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("NO_REGISTER_ON_OPEN"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_no_register' value='1' <?php if ($this->settings['support_no_register'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_no_register'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("ALLOW_DELETING_OF_TICKETS_BY_HANDLERS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_delete' value='1' <?php if ($this->settings['support_delete'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_delete'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_ADVANCED_SEARCH_BY_DEFAULT"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_advanced_default' value='1' <?php if ($this->settings['support_advanced_default'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_advanced_default'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("ENTIRE_ROW_TICKET"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_entire_row' value='1' <?php if ($this->settings['support_entire_row'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_entire_row'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("CUSTOM_REGISTER_LINK"); ?>:
					
				</td>
				<td>
					<input type='text' name='support_custom_register' value='<?php echo $this->settings['support_custom_register']; ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_custom_register'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("RESTRICT_TO_GROUPS_PRODUCTS"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_restrict_prod' value='1' <?php if ($this->settings['support_restrict_prod'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_restrict_prod'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("USER_REPLY_WIDTH"); ?>:
				</td>
				<td>
					<input type='text' name='support_user_reply_width' value='<?php echo $this->settings['support_user_reply_width']; ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reply_width'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("USER_REPLY_HEIGHT"); ?>:
				</td>
				<td>
					<input type='text' name='support_user_reply_height' value='<?php echo $this->settings['support_user_reply_height']; ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_user_reply_height'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("ADMIN_REPLY_WIDTH"); ?>:
				</td>
				<td>
					<input type='text' name='support_admin_reply_width' value='<?php echo $this->settings['support_admin_reply_width']; ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_admin_reply_width'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("ADMIN_REPLY_HEIGHT"); ?>:
				</td>
				<td>
					<input type='text' name='support_admin_reply_height' value='<?php echo $this->settings['support_admin_reply_height']; ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_admin_reply_height'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("SUBJECT_INPUT_SIZE"); ?>:
				</td>
				<td>
					<input type='text' name='support_subject_size' value='<?php echo $this->settings['support_subject_size']; ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_subject_size'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("ACTION_LINKS_AS_BUTTONS"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_actions_as_buttons' value='1' <?php if ($this->settings['support_actions_as_buttons'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_actions_as_buttons'); ?></div>
				</td>
			</tr>
						<tr>
				<td align="right" class="key">
					<?php echo JText::_("DONT_CHECK_EMAIL_ON_UNREG"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_dont_check_dupe' value='1' <?php if ($this->settings['support_dont_check_dupe'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_dont_check_dupe'); ?></div>
				</td>
			</tr>
			
		</table>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_TICKET_OWNERSHIP_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("AUTO_ASSIGN_TICKETS_TO_HANDLER"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='support_autoassign' value='1' <?php if ($this->settings['support_autoassign'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_autoassign'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("ASSIGN_TICKET_ON_HANDLER_OPEN"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_assign_open' value='1' <?php if ($this->settings['support_assign_open'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_assign_open'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("TAKE_OWNERSHIP_ON_HANDLER_REPLY"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_assign_reply' value='1' <?php if ($this->settings['support_assign_reply'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_assign_reply'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">				
						<?php echo JText::_("ALLOW_HANDLER_TO_BE_CHOSEN"); ?>:	
				</td>
				<td>
					<select name="support_choose_handler">
						<option value="none" <?php if ($this->settings['support_choose_handler'] == "none") echo " SELECTED"; ?> ><?php echo JText::_('DISABLED'); ?></option>
						<option value="admin" <?php if ($this->settings['support_choose_handler'] == "admin") echo " SELECTED"; ?> ><?php echo JText::_('ADMINS_ONLY'); ?></option>
						<option value="user" <?php if ($this->settings['support_choose_handler'] == "user") echo " SELECTED"; ?> ><?php echo JText::_('ALL_USERS'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_SUPPOER_CHOOSE_HANDLER'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_AUTOCLOSE_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("AUTOMATICALLY_CLOSE"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='support_autoclose' value='1' <?php if ($this->settings['support_autoclose'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td rowspan="3">
					<div class='fss_help'>
					<?php echo JText::printf('AUTOCLOSE_MSG', JURI::root() . 'index.php?option=com_fss&view=cron'); ?><br />
					<a href="<?php echo FSSRoute::x('index.php?option=com_fss&view=cronlog'); ?>">
						<img style="float:none;margin:0px;" src='<?php echo JURI::base(); ?>/components/com_fss/assets/log.png'>
						<span style="position:relative;top:-2px;"><?php echo JText::_('VIEW_LOG'); ?></span>
					</a>
					</div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("AUTOCLOSE_DURATION"); ?>:
				</td>
				<td>
					<input type='text' name='support_autoclose_duration' value='<?php echo $this->settings['support_autoclose_duration']; ?>'>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("AUTOCLOSE_AUDITLOG"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_autoclose_audit' value='1' <?php if ($this->settings['support_autoclose_audit'] == 1) { echo " checked='yes' "; } ?>>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("AUTOCLOSE_EMAIL_USER"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_autoclose_email' value='1' <?php if ($this->settings['support_autoclose_email'] == 1) { echo " checked='yes' "; } ?>>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("KEEP_LOG_FOR"); ?>:
				</td>
				<td>
					<input type='text' name='support_cronlog_keep' value='<?php echo $this->settings['support_cronlog_keep']; ?>'>
				</td>
			</tr>

		</table>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_USER_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("USER_CAN_CHANGE_CLOSE_OPEN_TICKETS"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='support_user_can_close' value='1' <?php if ($this->settings['support_user_can_close'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_close'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("USER_CAN_REOPEN_CLOSED_TICKETS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_user_can_reopen' value='1' <?php if ($this->settings['support_user_can_reopen'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_user_can_reopen'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("USER_CAN_ATTACH_FILES"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_user_attach' value='1' <?php if ($this->settings['support_user_attach'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_user_attach'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_EMAIL_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("EMAIL_ADDRESS_TO_EMAIL_FOR_UNASSIGNED_TICKETS_LEAVE_BLANK_FOR_NO_EMAIL"); ?>:
					
				</td>
				<td style="width:250px;">
					<input name='support_email_unassigned' size="40" value='<?php echo $this->settings['support_email_unassigned']; ?>' >
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_unassigned'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("EMAIL_FROM_ADDRESS_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
				</td>
				<td>
					<input name='support_email_from_address' size="40" value='<?php echo $this->settings['support_email_from_address']; ?>' >
					</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_from_address'); ?></div>
			</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("EMAIL_FROM_NAME_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
				</td>
				<td>
					<input name='support_email_from_name' size="40" value='<?php echo $this->settings['support_email_from_name']; ?>' >
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_from_name'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("OVERRIDE_SITE_NAME_IN_EMAIL_LEAVE_BLANK_TO_USE_DEFAULT_JOOMLA_ONE"); ?>:
					
				</td>
				<td>
					<input name='support_email_site_name' size="40" value='<?php echo $this->settings['support_email_site_name']; ?>' >
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_site_name'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("EMAIL_USER_ON_TICKET_CREATE"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_email_on_create' value='1' <?php if ($this->settings['support_email_on_create'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_create'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("EMAIL_HANDLER_ON_CREATE_IF_ONE_IS_ASSIGNED"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_email_handler_on_create' value='1' <?php if ($this->settings['support_email_handler_on_create'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_create'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("EMAIL_USER_ON_HANDLER_REPLY"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_email_on_reply' value='1' <?php if ($this->settings['support_email_on_reply'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_reply'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("EMAIL_HANDER_ON_USER_REPLY"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_email_handler_on_reply' value='1' <?php if ($this->settings['support_email_handler_on_reply'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_reply'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("EMAIL_NEW_HANDLER_ON_FORWARD"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_email_handler_on_forward' value='1' <?php if ($this->settings['support_email_handler_on_forward'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_handler_on_forward'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("EMAIL_USER_ON_CLOSE"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_email_on_close' value='1' <?php if ($this->settings['support_email_on_close'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_email_on_close'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_GENERAL_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("HIDE_PRIORITY"); ?>:
					
				</td>
				<td style="width:250px;">
					<select name="support_hide_priority">
						<option value="0" <?php if ($this->settings['support_hide_priority'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('PRI_SHOWN'); ?></option>
						<option value="1" <?php if ($this->settings['support_hide_priority'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('PRI_HIDE'); ?></option>
						<option value="2" <?php if ($this->settings['support_hide_priority'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('PRI_ONLY_FOR_ADMINS'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_priority'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("HIDE_HANDLER"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_hide_handler' value='1' <?php if ($this->settings['support_hide_handler'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_handler'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("HIDE_USERS_OTHER_TICKET_SECTION"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_hide_users_tickets' value='1' <?php if ($this->settings['support_hide_users_tickets'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_users_tickets'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("HIDE_TICKET_TAGS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_hide_tags' value='1' <?php if ($this->settings['support_hide_tags'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_hide_tags'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("SHOW_MESSGAE_COUNTS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='support_show_msg_counts' value='1' <?php if ($this->settings['support_show_msg_counts'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_show_msg_counts'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("NO_COLS_IN_TICKET_INFO"); ?>:
					
				</td>
				<td>
					<input name='support_info_cols' value='<?php echo $this->settings['support_info_cols']; ?>' />
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_info_cols'); ?></div>
				</td>
			</tr>
			
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("TICKET_LOCK_TIMEOUT"); ?>:
					
				</td>
				<td>
					<input name='support_lock_time' value='<?php echo $this->settings['support_lock_time']; ?>' />
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_lock_time'); ?></div>
				</td>
			</tr>
			<tr>
				<td valign="top" align="right" class="key">
					
						<?php echo JText::_("TICKET_REFERENCE"); ?>:
					
				</td>
				<td valign="top" width="200">
					<input name='support_reference' id='support_reference' value='<?php echo $this->settings['support_reference']; ?>' /><br>
					<button onclick="testreference();return false;"><?php echo JText::_("TEST_REFERENCE_NO"); ?></button><br>
					<br><br>
					<div id="testref"></div>
				</td>
				<td>
				<div class='fss_help'><?php echo JText::_('SETHELP_support_reference'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">				
						<?php echo JText::_("ATTACHMENT_FILENAME"); ?>:
				</td>
				<td>
					<select name="support_filename">
						<option value="0" <?php if ((int)$this->settings['support_filename'] == 0) echo " SELECTED"; ?> ><?php echo JText::_('AF_FILENAME'); ?></option>
						<option value="1" <?php if ($this->settings['support_filename'] == 1) echo " SELECTED"; ?> ><?php echo JText::_('AF_USER_FILENAME'); ?></option>
						<option value="2" <?php if ($this->settings['support_filename'] == 2) echo " SELECTED"; ?> ><?php echo JText::_('AF_USER_DATE_FILENAME'); ?></option>
						<option value="3" <?php if ($this->settings['support_filename'] == 3) echo " SELECTED"; ?> ><?php echo JText::_('AF_DATE_USER_FILENAME'); ?></option>
						<option value="4" <?php if ($this->settings['support_filename'] == 4) echo " SELECTED"; ?> ><?php echo JText::_('AF_DATE_FILENAME'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_ATTACHMENT_FILENAME'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">				
						<?php echo JText::_("HIDE_MESSAGE_SUBJECT"); ?>:
				</td>
				<td>
					<select name="support_subject_message_hide">
						<option value="none" <?php if ($this->settings['support_subject_message_hide'] != "subject" && $this->settings['support_subject_message_hide'] != "message") echo " SELECTED"; ?> ><?php echo JText::_('SHOW_BOTH'); ?></option>
						<option value="subject" <?php if ($this->settings['support_subject_message_hide'] == "subject") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_SUBJECT'); ?></option>
						<option value="message" <?php if ($this->settings['support_subject_message_hide'] == "message") echo " SELECTED"; ?> ><?php echo JText::_('HIDE_MESSAGE'); ?></option>
					</select>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_SUPPORT_SUBJECT_MESSAGE_HIDE'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("ticket_per_page"); ?>:
				</td>
				<td>
					<?php $this->PerPage('ticket_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_ticket_per_page'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_SEARCH_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SS_BASIC_NAME"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='support_basic_name' value='1' <?php if ($this->settings['support_basic_name'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_name'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SS_BASIC_USERNAME"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_basic_username' value='1' <?php if ($this->settings['support_basic_username'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_username'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SS_BASIC_EMAIL"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_basic_email' value='1' <?php if ($this->settings['support_basic_email'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_email'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SS_BASIC_MESSAGES"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_basic_messages' value='1' <?php if ($this->settings['support_basic_messages'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_basic_messages'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_ADMIN_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_ALL_CLOSED_TAB"); ?>:
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='support_tabs_allclosed' value='1' <?php if ($this->settings['support_tabs_allclosed'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_CLOSED_TAB'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_ALL_OPEN_TAB"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_tabs_allopen' value='1' <?php if ($this->settings['support_tabs_allopen'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_OPEN_TAB'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("SHOW_ALL_TICKETS_TAB"); ?>:
				</td>
				<td>
					<input type='checkbox' name='support_tabs_all' value='1' <?php if ($this->settings['support_tabs_all'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_SHOW_ALL_TICKETS_TAB'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>

</div>
<?php //##NOT_FAQS_END## ?>
<?php //##NOT_TEST_END## ?>

<div id="tab_visual" style="display:none;">

	<fieldset class="adminform">
		<legend><?php echo JText::_("VISUAL_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("USE_SKIN_STYLING_FOR_PAGEINATION_CONTROLS"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='skin_style' value='1' <?php if ($this->settings['skin_style'] == 1) { echo " checked='yes' "; } ?>>
					</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_skin_style'); ?></div>
			</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					
					<?php echo JText::_("USE_JOOMLA_SETTING_FOR_PAGE_TITLE_VISIBILITY"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='use_joomla_page_title_setting' value='1' <?php if ($this->settings['use_joomla_page_title_setting'] == 1) { echo " checked='yes' "; } ?>>
					</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_use_joomla_page_title_setting'); ?></div>
			</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_("CSS_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
					
						<?php echo JText::_("HIGHLIGHT_COLOUR"); ?>:
					
				</td>
				<td style="width:250px;">
					<input name='css_hl' value='<?php echo $this->settings['css_hl'] ?>'>
					&nbsp;
					<input type="button" value="Color picker" onclick="showColorPicker(this,document.forms[0].css_hl)">
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_css_hl'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("BORDER_COLOUR"); ?>:
					
				</td>
				<td>
					<input name='css_bo' value='<?php echo $this->settings['css_bo'] ?>'>
					&nbsp;
					<input type="button" value="Color picker" onclick="showColorPicker(this,document.forms[0].css_bo)">
					</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_css_bo'); ?></div>
			</td>
			</tr>
			<tr>
				<td align="right" class="key">
					
						<?php echo JText::_("TAB_BACKGROUND_COLOUR"); ?>:
					
				</td>
				<td>
					<input name='css_tb' value='<?php echo $this->settings['css_tb'] ?>'>
					&nbsp;
					<input type="button" value="Color picker" onclick="showColorPicker(this,document.forms[0].css_tb)">
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_css_tb'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
<?php //##NOT_FAQS_START## ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_("SUPPORT_COLOR_SETTINGS"); ?></legend>
		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("USER_MESSAGE_COLOR"); ?>:
				</td>
				<td style="width:250px;">
					<input name='support_user_message' value='<?php echo $this->settings['support_user_message'] ?>'>
					&nbsp;
					<input type="button" value="Color picker" onclick="showColorPicker(this,document.forms[0].support_user_message)">
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_user_message'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("HANDLER_MESSAGE_COLOR"); ?>:
				</td>
				<td>
					<input name='support_admin_message' value='<?php echo $this->settings['support_admin_message'] ?>'>
					&nbsp;
					<input type="button" value="Color picker" onclick="showColorPicker(this,document.forms[0].support_admin_message)">
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_admin_message'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("PRIVATE_MESSAGE_COLOR"); ?>:
				</td>
				<td>
					<input name='support_private_message' value='<?php echo $this->settings['support_private_message'] ?>'>
					&nbsp;
					<input type="button" value="Color picker" onclick="showColorPicker(this,document.forms[0].support_private_message)">
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_support_private_message'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
<?php //##NOT_FAQS_END## ?>	
</div>
<?php //##NOT_TEST_START## ?>

<div id="tab_glossary">

	<fieldset class="adminform">
		<legend><?php echo JText::_("GLOSSARY_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("USE_GLOSSARY_ON_FAQS"); ?>:
					
				</td>
				<td style="width:250px;">
					<input type='checkbox' name='glossary_faqs' value='1' <?php if ($this->settings['glossary_faqs'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_glossary_faqs'); ?></div>
				</td>
			</tr>
<?php //##NOT_FAQS_START## ?>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("USE_GLOSSARY_ON_KNOWELEDGE_BASE"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='glossary_kb' value='1' <?php if ($this->settings['glossary_kb'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_glossary_kb'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("USE_GLOSSARY_ON_ANNOUNCEMENTS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='glossary_announce' value='1' <?php if ($this->settings['glossary_announce'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_glossary_announce'); ?></div>
				</td>
			</tr>
<?php //##NOT_FAQS_END## ?>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("LINK_ITEMS_TO_GLOSSARY_PAGE"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='glossary_link' value='1' <?php if ($this->settings['glossary_link'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_glossary_link'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
						<?php echo JText::_("SHOW_GLOSSARY_WORD_IN_TOOLTIP"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='glossary_title' value='1' <?php if ($this->settings['glossary_title'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_glossary_title'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("GLOSSARY_USE_CONTENT_PLUGINS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='glossary_use_content_plugins' value='1' <?php if ($this->settings['glossary_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_glossary_use_content_plugins'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("GLOSSARY_IGNORE"); ?>:
					
				</td>
				<td>
					<textarea name='glossary_ignore' id="glossary_ignore" rows="12" cols="40" style="float:none;"><?php echo $this->settings['glossary_ignore']; ?></textarea><br>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_glossary_ignore'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div id="tab_faq">

	<fieldset class="adminform">
		<legend><?php echo JText::_("FAQ_SETTINGS"); ?></legend>

		<table class="admintable">
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("FAQS_POPUP_WIDTH"); ?>:
					
				</td>
				<td style="width:250px;">
					<input name='faq_popup_width' value='<?php echo $this->settings['faq_popup_width'] ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_faq_popup_width'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("FAQS_POPUP_HEIGHT"); ?>:
					
				</td>
				<td>
					<input name='faq_popup_height' value='<?php echo $this->settings['faq_popup_height'] ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_faq_popup_height'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
						<?php echo JText::_("FAQS_POPUP_INNER_WIDTH"); ?>:
					
				</td>
				<td>
					<input name='faq_popup_inner_width' value='<?php echo $this->settings['faq_popup_inner_width'] ?>'>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_faq_popup_inner_width'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQ_USE_CONTENT_PLUGINS"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='faq_use_content_plugins' value='1' <?php if ($this->settings['faq_use_content_plugins'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_faq_use_content_plugins'); ?></div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key">
					<?php echo JText::_("FAQ_USE_CONTENT_PLUGINS_LIST"); ?>:
					
				</td>
				<td>
					<input type='checkbox' name='faq_use_content_plugins_list' value='1' <?php if ($this->settings['faq_use_content_plugins_list'] == 1) { echo " checked='yes' "; } ?>>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_faq_use_content_plugins_list'); ?><div>
				</td>
			</tr>
			<tr>
				<td align="right" class="key" style="width:250px;">
					<?php echo JText::_("FAQS_PER_PAGE"); ?>:
				</td>
				<td>
					<?php $this->PerPage('faq_per_page'); ?>
				</td>
				<td>
					<div class='fss_help'><?php echo JText::_('SETHELP_faq_per_page'); ?></div>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<?php //##NOT_TEST_END## ?>

</form>

<script>

function testreference()
{
	$('testref').innerHTML = "<?php echo JText::_('PLEASE_WAIT'); ?>";
	var format = $('support_reference').value
	var url = '<?php echo FSSRoute::x("index.php?option=com_fss&view=settings&what=testref",false); ?>&ref=' + format;
	
<?php if (FSS_Helper::Is16()): ?>
	$('testref').load(url);
<?php else: ?>
	new Ajax(url, {
	method: 'get',
	update: $('testref')
	}).request();
<?php endif; ?>
}

window.addEvent('domready', function(){

	if (location.hash)
	{
		ShowTab(location.hash.replace('#',''));
	}
	else
	{
		ShowTab('general');
	}
	
<?php if (FSS_Helper::Is16()): ?>
	jQuery('#test_date_formats').click(function (ev) {
		ev.preventDefault();
			
		var url = '<?php echo FSSRoute::x("index.php?option=com_fss&view=settings&what=testdates",false); ?>';

		url += '&date_dt_short=' + encodeURIComponent(jQuery('#date_dt_short').val());
		url += '&date_dt_long=' + encodeURIComponent(jQuery('#date_dt_long').val());
		url += '&date_d_short=' + encodeURIComponent(jQuery('#date_d_short').val());
		url += '&date_d_long=' + encodeURIComponent(jQuery('#date_d_long').val());

		jQuery.get(url, function (data) {
			var result = jQuery.parseJSON(data);
			jQuery('#test_date_dt_short').html("<?php echo JText::_('DATE_TEST_RESULT'); ?>" + ": " + result.date_dt_short);
			jQuery('#test_date_dt_long').html("<?php echo JText::_('DATE_TEST_RESULT'); ?>" + ": " + result.date_dt_long);
			jQuery('#test_date_d_short').html("<?php echo JText::_('DATE_TEST_RESULT'); ?>" + ": " + result.date_d_short);
			jQuery('#test_date_d_long').html("<?php echo JText::_('DATE_TEST_RESULT'); ?>" + ": " + result.date_d_long);
			jQuery('#test_timezone_offset').html("<?php echo JText::_('DATE_TEST_RESULT'); ?>" + ": " + result.timezone_offset);
		});
	});
<?php endif; ?>
});
 
</script>
