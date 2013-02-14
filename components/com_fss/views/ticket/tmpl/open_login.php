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
<?php echo FSS_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>
<div class="fss_spacer"></div>
<?php if ($this->type == 0) : ?>
	<?php if (!FSS_Settings::get('support_allow_unreg')): ?>
		<div class="fss_ticket_login_error"><?php echo JText::_("YOU_MUST_BE_LOGGED_IN_TO_CREATE_A_SUPPORT_TICKET"); ?></div>
	<?php endif; ?>
<?php elseif ($this->type == 1) : ?>
	<div class="fss_ticket_login_error"><?php echo JText::_("THIS_EMAIL_ADDRESS_IS_ALREADY_IN_USE_PLEASE_LOG_INTO_YOUR_ACCOUNT_BELOW"); ?></div>
<?php elseif ($this->type == 3) : ?>
	<div class="fss_ticket_login_error"><?php echo JText::_("YOU_HAVE_ENTERED_AN_INVALID_EMAIL_ADDRESS_PLEASE_ENTER_A_VAILD_ONE"); ?></div>
<?php endif; ?>

<?php if (FSS_Settings::get('support_no_logon') == 0): ?>
<div class="fss_ticket_login_head"><?php echo JText::_("LOGIN"); ?></div>
<div class="fss_ticket_login_subtext"><?php echo JText::_("LOG_IN_TO_AN_EXISTING_ACCOUNT"); ?></div>
<form action="<?php echo FSSRoute::x("index.php?option=com_user"); ?>"  method="post" name="fss_login" id="fss_login">
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
<?php if (FSS_Settings::get('support_no_register') == 0): ?>
<?php
$usersConfig = &JComponentHelper::getParams( 'com_users' );
if ($usersConfig->get('allowUserRegistration')) : ?>
	<div class="fss_ticket_login_head"><?php echo JText::_("REGISTER"); ?></div>
	<?php if (FSS_Settings::get('support_custom_register')) : ?>
		<div class="fss_ticket_login_subtext"><?php echo JText::sprintf('IF_YOU_WOULD_LIKE_TO_CREATE_A_USER_ACCOUNT_PLEASE_REGISTER_HERE',FSSRoute::x(FSS_Settings::get('support_custom_register'))); ?></div>
	<?php elseif (FSS_Helper::Is16()): ?>
		<div class="fss_ticket_login_subtext"><?php echo JText::sprintf('IF_YOU_WOULD_LIKE_TO_CREATE_A_USER_ACCOUNT_PLEASE_REGISTER_HERE',FSSRoute::x("index.php?option=com_users&view=registration")); ?></div>
	<?php else: ?>
		<div class="fss_ticket_login_subtext"><?php echo JText::sprintf('IF_YOU_WOULD_LIKE_TO_CREATE_A_USER_ACCOUNT_PLEASE_REGISTER_HERE',FSSRoute::x("index.php?option=com_user&view=register")); ?></div>
	<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php if (FSS_Settings::get('support_allow_unreg') == 1): ?>
<div class="fss_ticket_login_head"><?php echo JText::_("CREATE_WITHOUT_ACCOUNT"); ?></div>
<div class="fss_ticket_login_subtext"><?php echo JText::_("YOU_WILL_BE_ABLE_TO_ACCESS_YOUR_SUPPORT_TICKET_USING_THE_TICKET_REFERENCE_EMAIL_ADDRESS_AND_PASSWORD_PROVIDED"); ?></div>
<form action="<?php echo FSSRoute::x("&what=&type=without"); ?>"  method="post" name="uregform" id="uregform">
<table class="fss_table" cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo JText::_("EMAIL_ADDRESS"); ?></th>
		<td><input name="email" class="inputbox" /></td>
	</tr>
	<tr>
		<th><?php echo JText::_("NAME"); ?></th>
<td><input name="name" class="inputbox" /></td>
</tr>
<tr>
		<td colspan="2" align="center"><input class='button' type="submit" value="<?php echo JText::_("CREATE_TICKET"); ?>" /></td>	
	</tr>
</table>
</form>
<?php endif; ?>

<?php echo FSS_Helper::PageStyleEnd(); ?>