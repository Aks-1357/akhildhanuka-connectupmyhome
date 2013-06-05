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

	$prodid = JRequest::getVar('prodid', 0, '', 'int');
	if ($prodid > 0)
	{
		$show_prod = $params->get('show_prod');
		$show_cat = $params->get('show_cat');
		$show_art = $params->get('show_art');
		
		$show = true;
		
		if (JRequest::getVar('kbartid',0, '', 'int'))
		{
			if (!$show_art)
				$show = false;
		} else if (JRequest::getVar('catid',0, '', 'int'))
		{
			if (!$show_cat)
				$show = false;
		} else {
			if (!$show_prod)
				$show = false;
		}
		
		if ($show)
		{
			$db =& JFactory::getDBO();
			$query = "SELECT extratext FROM #__fss_prod WHERE id = " . $prodid;

			$db->setQuery($query);
			$rows = $db->loadAssoc();
			
			if ($rows['extratext'])
				require( JModuleHelper::getLayoutPath( 'mod_fss_kbprodinfo' ) );
		}
	} else {
		$module->showtitle = 0;
		$attribs['style'] = "hide_me";
	}
}