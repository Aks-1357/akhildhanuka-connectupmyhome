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

<table width="100%">
	<tr>
		<td width="55%" valign="top">
		
			<fieldset class="adminform">
				<legend><?php echo JText::_("GENERAL"); ?></legend>
		
				<?php $this->Item("SETTINGS","index.php?option=com_fss&view=settings","settings","FSS_HELP_SETTINGS"); ?>
				<?php $this->Item("TEMPLATES","index.php?option=com_fss&view=templates","templates","FSS_HELP_TEMPLATES"); ?>
				<?php $this->Item("VIEW_SETTINGS","index.php?option=com_fss&view=settingsview","viewsettings","FSS_HELP_VIEWSETTINGS"); ?>
<!--##NOT_FAQS_START##-->

<!--##NOT_TEST_START##-->
				<?php $this->Item("USER_PERMISSIONS","index.php?option=com_fss&view=fusers","users","FSS_HELP_USER_PERMISSIONS"); ?>
				<?php $this->Item("EMAIL_TEMPLATES","index.php?option=com_fss&view=emails","emails","FSS_HELP_EMAIL_TEMPLATES"); ?>
				<?php $this->Item("CUSTOM_FIELDS","index.php?option=com_fss&view=fields","customfields","FSS_HELP_CUSTOM_FIELDS"); ?>
				<?php $this->Item("PRODUCTS","index.php?option=com_fss&view=prods","prods","FSS_HELP_PRODUCTS"); ?>
				<?php $this->Item("MAIN_MENU_ITEMS","index.php?option=com_fss&view=mainmenus","menu","FSS_HELP_MAIN_MENU_ITEMS"); ?>
<!--##NOT_TEST_END##-->
				<?php $this->Item("MODERATION","index.php?option=com_fss&view=tests","moderate","MODERATION"); ?>

<!--##NOT_FAQS_END##-->
			</fieldset>
		
<!--##NOT_TEST_START##-->
<!--##NOT_FAQS_START##-->
			<fieldset class="adminform">
				<legend><?php echo JText::_("SUPPORT"); ?></legend>
				<?php $this->Item("PRODUCTS","index.php?option=com_fss&view=prods","prods","FSS_HELP_SUPPORT_PRODUCTS"); ?>
				<?php $this->Item("TICKET_EMAIL_ACCOUNTS","index.php?option=com_fss&view=ticketemails","emailaccounts","FSS_HELP_TICKET_EMAIL_ACCOUNTS"); ?>
				<?php $this->Item("TICKET_CATEGORIES","index.php?option=com_fss&view=ticketcats","categories","FSS_HELP_TICKET_CATEGORIES"); ?>
				<?php $this->Item("TICKET_DEPARTMENTS","index.php?option=com_fss&view=ticketdepts","ticketdepts","FSS_HELP_TICKET_DEPARTMENTS"); ?>
				<?php $this->Item("TICKET_PRIORITIES","index.php?option=com_fss&view=ticketpris","ticketpris","FSS_HELP_TICKET_PRIORITIES"); ?>
				<?php $this->Item("TICKET_GROUPS","index.php?option=com_fss&view=ticketgroups","groups","FSS_HELP_TICKET_GROUPS"); ?>
				<?php $this->Item("TICKET_STATUS","index.php?option=com_fss&view=ticketstatuss","ticketstatus","FSS_HELP_TICKET_STATUS"); ?>
				<?php $this->Item("CRON_LOG","index.php?option=com_fss&view=cronlog","cronlog","FSS_HELP_CRON_LOG"); ?>
			</fieldset>
		
			<fieldset class="adminform">
				<legend><?php echo JText::_("KNOWELEDGE_BASE"); ?></legend>
				<?php $this->Item("PRODUCTS","index.php?option=com_fss&view=prods","prods","FSS_HELP_KB_PRODUCTS"); ?>
				<?php $this->Item("KB_CATS","index.php?option=com_fss&view=kbcats","categories","FSS_HELP_KB_CATS"); ?>
				<?php $this->Item("KB_ARTS","index.php?option=com_fss&view=kbarts","kb","FSS_HELP_KB_ARTS"); ?>
	
			</fieldset>
<!--##NOT_FAQS_END##-->		

			<fieldset class="adminform">
				<legend><?php echo JText::_("FAQS"); ?></legend>
				<?php $this->Item("FAQ_CATS","index.php?option=com_fss&view=faqcats","categories","FSS_HELP_FAQ_CATS"); ?>
				<?php $this->Item("FAQS","index.php?option=com_fss&view=faqs","faqs","FSS_HELP_FAQS"); ?>
	
			</fieldset>
		
<!--##NOT_FAQS_START##-->
			<fieldset class="adminform">
				<legend><?php echo JText::_("ANNOUNCEMENTS"); ?></legend>
				<?php $this->Item("ANNOUNCEMENTS","index.php?option=com_fss&view=announces","announce","FSS_HELP_ANNOUNCEMENTS"); ?>
	
			</fieldset>

			<fieldset class="adminform">
				<legend><?php echo JText::_("GLOSSARY"); ?></legend>
				<?php $this->Item("GLOSSARY_ITEMS","index.php?option=com_fss&view=glossarys","glossary","FSS_HELP_GLOSSARY_ITEMS"); ?>
	
			</fieldset>
<!--##NOT_TEST_END##-->
		
			<fieldset class="adminform">
				<legend><?php echo JText::_("TESTIMONIALS"); ?></legend>
				<?php $this->Item("PRODUCTS","index.php?option=com_fss&view=prods","prods","FSS_HELP_TEST_PRODUCTS"); ?>
				<?php $this->Item("MODERATION","index.php?option=com_fss&view=tests","moderate","MODERATION"); ?>
			</fieldset>
<!--##NOT_FAQS_END##-->	


<!--##NOT_FAQS_START##-->
<!--##NOT_TEST_START##-->
		
			<fieldset class="adminform">
				<legend><?php echo JText::_("COM_FSS_ADMIN"); ?></legend>
				<?php $this->Item("COM_FSS_ADMIN","index.php?option=com_fss&view=backup","settings","COM_FSS_ADMIN"); ?>
				<?php //$this->Item("Timezone","index.php?option=com_fss&view=timezone","settings","Timezone"); ?>
	
			</fieldset>
			
<!--##NOT_TEST_END##-->
<!--##NOT_FAQS_END##-->	
	
		</td>
		<td width="45%" valign="top">


<?php
if (FSSAdminHelper::Is16())
{

JHTML::addIncludePath(array(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_fss'.DS.'html'));	 ?>	

<?php

echo JHTML::_( 'fsjtabs.start' );

$title = "Version";
echo JHTML::_( 'fsjtabs.panel', $title, 'cpanel-panel-'.$title, true );

$ver_inst = FSSAdminHelper::GetInstalledVersion();
$ver_files = FSSAdminHelper::GetVersion();

?>
<?php if (FSSAdminHelper::IsFAQs()) :?>
	<h3>If you like Freestyle FAQs please vote or review us at the <a href='http://extensions.joomla.org/extensions/directory-a-documentation/faq/11910' target="_blank">Joomla extensions directory</a></h3>
<?php elseif (FSSAdminHelper::IsTests()) :?>
<h3>If you like Freestyle Testimonials please vote or review us at the <a href='http://extensions.joomla.org/extensions/contacts-and-feedback/testimonials-a-suggestions/11911' target="_blank">Joomla extensions directory</a></h3>
<?php else: ?>
<h3>If you like Freestyle Support, please vote or review us at the <a href='http://extensions.joomla.org/extensions/clients-a-communities/help-desk/11912' target="_blank">Joomla extensions directory</a></h3>
<?php endif; ?>
<?php
echo "<h4>Currently Installed Verison : <b>$ver_files</b></h4>";
if ($ver_files != $ver_inst)
	echo "<h4>".JText::sprintf('INCORRECT_VERSION',FSSRoute::x('index.php?option=com_fss&view=backup&task=update'))."</h4>";

?>
<div id="please_wait">Please wait while fetching latest version information...</div>

<iframe id="frame_version" height="300" width="100%" frameborder="0" border="0"></iframe>	
<?php

$title = "Announcements";
echo JHTML::_( 'fsjtabs.panel', $title, 'cpanel-panel-'.$title );
?>
<iframe id="frame_announce" height="600" width="100%" frameborder="0" border="0"></iframe>
<?php

$title = "Help";
echo JHTML::_( 'fsjtabs.panel', $title, 'cpanel-panel-'.$title );
?>
<iframe id="frame_help" height="600" width="100%" frameborder="0" border="0"></iframe>
<?php
echo JHTML::_( 'fsjtabs.end' );

} else {
?>
	
<?php

$pane = &JPane::getInstance('tabs', array('allowAllClose' => true));
echo $pane->startPane("content-pane");


$title = "Version";
echo $pane->startPanel( $title, 'cpanel-panel-'.$title );

$ver_inst = FSSAdminHelper::GetInstalledVersion();
$ver_files = FSSAdminHelper::GetVersion();

?>
<?php if (FSSAdminHelper::IsFAQs()) :?>
	<h3>If you like Freestyle FAQs please vote or review us at the <a href='http://extensions.joomla.org/extensions/directory-a-documentation/faq/11910' target="_blank">Joomla extensions directory</a></h3>
<?php elseif (FSSAdminHelper::IsTests()) :?>
<h3>If you like Freestyle Testimonials please vote or review us at the <a href='http://extensions.joomla.org/extensions/contacts-and-feedback/testimonials-a-suggestions/11911' target="_blank">Joomla extensions directory</a></h3>
<?php else: ?>
<h3>If you like Freestyle Support, please vote or review us at the <a href='http://extensions.joomla.org/extensions/clients-a-communities/help-desk/11912' target="_blank">Joomla extensions directory</a></h3>
<?php endif; ?>
<?php
echo "<h4>Currently Installed Verison : <b>$ver_files</b></h4>";
if ($ver_files != $ver_inst)
	echo "<h4>".JText::sprintf('INCORRECT_VERSION',FSSRoute::x('index.php?option=com_fss&view=backup&task=update'))."</h4>";

?>
<div id="please_wait">Please wait while fetching latest version information...</div>

<iframe id="frame_version" height="300" width="100%" frameborder="0" border="0"></iframe>	
<?php
echo $pane->endPanel();

$title = "Announcements";
echo $pane->startPanel( $title, 'cpanel-panel-'.$title );
?>
<iframe id="frame_announce" height="600" width="100%" frameborder="0" border="0"></iframe>
<?php
echo $pane->endPanel();

$title = "Help";
echo $pane->startPanel( $title, 'cpanel-panel-'.$title );
?>
<iframe id="frame_help" height="600" width="100%" frameborder="0" border="0"></iframe>
<?php
echo $pane->endPanel();

echo $pane->endPane();

}
?>

		</td>	
	</tr>
</table>

<script>
jQuery(document).ready(function () {
	jQuery('#frame_version').attr('src',"http://freestyle-joomla.com/latestversion-fss?ver=<?php echo FSSAdminHelper::GetVersion();?>");
	jQuery('#frame_version').load(function() 
    {
        jQuery('#please_wait').remove();
    });

	jQuery('.fss_main_item').mouseenter(function () {
		jQuery(this).css('background-color', '<?php echo FSS_Settings::get('css_hl'); ?>');
	});
	jQuery('.fss_main_item').mouseleave(function () {
		jQuery(this).css('background-color' ,'transparent');
	});

	jQuery('#frame_announce').attr('src',"http://freestyle-joomla.com/support/announcements?tmpl=component");
	jQuery('#frame_help').attr('src',"http://freestyle-joomla.com/comhelp/fss-main-help");
});
</script>