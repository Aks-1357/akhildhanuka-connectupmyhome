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
<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle("SUPPORT","VIEW_SUPPORT_TICKET"); ?>
<div class="fss_spacer"></div>
<?php if ($this->type == 0) : ?>
	<div class="fss_ticket_login_error"><?php echo JText::_("YOU_MUST_BE_LOGGED_IN_TO_VIEW_A_SUPPORT_TICKET"); ?></div>
<?php elseif ($this->type == 2) : ?>
<div class="fss_ticket_login_error"><?php echo JText::_("UNABLE_TO_FIND_A_SUPPORT_TICKET_WITH_THE_PROVIDED_EMAIL_AND_PASSWORD"); ?></div>
<?php endif; ?>

<?php if (FSS_Settings::get('support_no_logon') == 0): ?>
<?php echo FSS_Helper::PageSubTitle("LOGIN"); ?>
<div class="fss_ticket_login_subtext"><?php echo JText::_("LOG_IN_TO_AN_EXISTING_ACCOUNT"); ?></div>
<form action="<?php echo FSSRoute::x("index.php?option=com_user"); ?>"  method="post" name="com-login" id="com-form-login">
<table class="fss_table" cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo JText::_("USERNAME"); ?></th>
		<td><input name="username" id="username" class="inputbox" alt="username" size="18" type="text" /></td>
	</tr>
	<tr>
		<th><?php echo JText::_("PASSWORD"); ?></th>
<?php if (FSS_Helper::Is16()): ?>
		<td><input id="password" name="password" class="inputbox" size="18" alt="password" type="password" /></td>
<?php else: ?>
		<td><input id="passwd" name="passwd" class="inputbox" size="18" alt="password" type="password" /></td>
<?php endif; ?>
	</tr>
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
	<tr>
	<td colspan="2" align="center"><label for="remember"><?php echo JText::_("REMEMBER_ME"); ?> </label><input id="remember" name="remember" class="inputbox" value="yes" alt="Remember Me" type="checkbox" /></td>	
	</tr>
	<?php endif; ?>
	<tr>
		<td colspan="2" align="center"><input class='button' type="submit" value="<?php echo JText::_("LOGIN"); ?>" /></td>	
	</tr>
</table>

<?php if (FSS_Helper::Is16()): ?>
	<input name="option" value="com_users" type="hidden" />
	<input name="task" value="user.login" type="hidden" />
<?php else: ?>
	<input name="option" value="com_user" type="hidden" />
	<input name="task" value="login" type="hidden" />
<?php endif; ?>
	<input name="return" value="<?php echo $this->return; ?>" type="hidden" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<ul>
	<li>
<?php if (FSS_Helper::Is16()): ?>
		<a href="<?php echo FSSRoute::x('index.php?option=com_users&view=reset'); ?>">
<?php else: ?>
		<a href="<?php echo FSSRoute::x( 'index.php?option=com_user&view=reset' ); ?>">
<?php endif; ?>
		<?php echo JText::_("FORGOT_YOUR_PASSWORD"); ?></a>
	</li>
	<li>
<?php if (FSS_Helper::Is16()): ?>
		<a href="<?php echo FSSRoute::x('index.php?option=com_users&view=reset'); ?>">
<?php else: ?>
		<a href="<?php echo FSSRoute::x( 'index.php?option=com_user&view=remind' ); ?>">
<?php endif; ?>
		<?php echo JText::_("FORGOT_YOUR_USERNAME"); ?></a>
	</li>
</ul>

<?php endif; ?>

<?php if (FSS_Settings::get('support_allow_unreg') == 1): ?>

<?php echo FSS_Helper::PageSubTitle("VIEW_TICKET_CREATED_WITHOUT_ACCOUNT"); ?>

<div class="fss_ticket_login_subtext"><?php echo JText::_("PLEASE_ENTER_YOUR_EMAIL_ADDRESS_AND_PASSWORD_PROVIDED_WITH_YOUR_TICKET"); ?></div>
<form action="<?php echo FSSRoute::x("&what="); ?>"  method="post" name="uregform" id="uregform">
<table class="fss_table" cellpadding="0" cellspacing="0">
<tr>
<th><?php echo JText::_("EMAIL_ADDRESS"); ?></th>
<td><input name="email" class="inputbox" alt="email" size="18" type="text" /></td>
</tr>
<tr>
<th><?php echo JText::_("TICKET_PASSWORD"); ?></th>
<td><input name="password" type="password" class="inputbox" alt="password" size="18" /></td>
</tr>
<tr>
<td colspan="2" align="center"><input class='button' type="submit" value="<?php echo JText::_("FIND_TICKET"); ?>" /></td>	
</tr>
</table>
</form>
<?php endif; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>