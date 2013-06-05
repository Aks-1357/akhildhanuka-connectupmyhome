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

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);
if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'models'.DS.'admin.php' );

	$db =& JFactory::getDBO();

	$ph = new FSS_Helper();
	$permissions = $ph->getPermissions();

	if ($permissions['mod_kb'] || $permissions['support'])
	{
		$model = new FssModelAdmin();

		$comments = new FSS_Comments(null,null);
		$moderatecount = $comments->GetModerateTotal();

		$css = FSSRoute::x( "index.php?option=com_fss&view=css&layout=default" );
		$document =& JFactory::getDocument();
		$document->addStyleSheet($css); 
		
		require( JModuleHelper::getLayoutPath( 'mod_fss_support' ) );
	} else {
		$module->showtitle = 0;
		$attribs['style'] = "hide_me";	
	}

}