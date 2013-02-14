<?php echo FSS_Helper::PageStyle(); ?>
<?php echo FSS_Helper::PageTitle('SUPPORT_ADMIN',"NO_PERM"); ?>

<?php echo JText::_("YOU_DO_NOT_HAVE_PERMISSION_TO_PERFORM_AND_SUPPORT_ADMINISTRATION_ACTIVITIES"); ?>

<?php $user =& JFactory::getUser(); if ($user->id == 0): ?>
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
	<input name="option" value="com_users" type="hidden">
	<input name="task" value="user.login" type="hidden">
<?php else: ?>
<input name="option" value="com_user" type="hidden">
<input name="task" value="login" type="hidden">
<?php endif; ?>
	<input name="return" value="<?php echo $this->return; ?>" type="hidden">
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'_powered.php'; ?>
<?php echo FSS_Helper::PageStyleEnd(); ?>
