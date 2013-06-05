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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);
if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

	$css = FSSRoute::x( "index.php?option=com_fss&view=css&layout=default" );
	$document =& JFactory::getDocument();
	$document->addStyleSheet($css); 

	$fss_current_page_show = 0;

	$view = JRequest::getVar('view','', '', 'string');
	$customid = $params->get('customid');

	if ($view == "kb")
	{ 
		$kbartid = JRequest::getVar('kbartid',0, '', 'int');
		$catid = JRequest::getVar('catid',0, '', 'int');
		$prodid = JRequest::getVar('prodid',0, '', 'int');

		if ($kbartid > 0)
		{
			if ($params->get('show_kb_art'))
				$fss_current_page_show = 1;
		} else if ($catid > 0)
		{
			if ($params->get('show_kb_cat'))
				$fss_current_page_show = 1;
		} else if ($prodid > 0)
		{
			if ($params->get('show_kb_prod'))
				$fss_current_page_show = 1;
		} else {
			if ($params->get('show_kb_main'))
				$fss_current_page_show = 1;
		}

	} else if ($view == "faq")
	{
		$faqid = JRequest::getVar('faqid',0, '', 'int');
		$catid = JRequest::getVar('catid',0, '', 'int');
		
		if ($faqid > 0)
		{
			if ($params->get('show_faq_faq'))
				$fss_current_page_show = 1;
		} else if ($catid > 0)
		{
			if ($params->get('show_faq_cat'))
				$fss_current_page_show = 1;
		} else {
			if ($params->get('show_faq_main'))
				$fss_current_page_show = 1;
		}
	}
	$customid = $params->get('customid');

	if ($fss_current_page_show == 1 && $customid > 0)
	{
		
		$db =& JFactory::getDBO();
		$query = "SELECT body FROM #__fss_custom_text WHERE id = " . $customid;

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		
		require( JModuleHelper::getLayoutPath( 'mod_fss_custom' ) );
	} else {
		$module->showtitle = 0;
		$attribs['style'] = "hide_me";
	}
}