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
<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'j3helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php' );

	$css = FSSRoute::x( "index.php?option=com_fss&view=css&layout=default" );
	$document =& JFactory::getDocument();
	$document->addStyleSheet($css); 
	if (!FSSJ3Helper::IsJ3())
	{
		if (!JFactory::getApplication()->get('jquery')) {
			JFactory::getApplication()->set('jquery', true);
			$document->addScript( JURI::root().'components/com_fss/assets/js/jquery.1.8.3.min.js' );
		}
	}
	$document->addScript( JURI::base().'components/com_fss/assets/js/jquery.autoscroll.js' );

	$db =& JFactory::getDBO();

	jimport('joomla.utilities.date');


	$query = "SELECT * FROM #__fss_announce";

	$where = array();
	$where[] = "published = 1";
	if (FSS_Helper::Is16())
	{
		$db =& JFactory::getDBO();
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
	}	

	if (count($where) > 0)
		$query .= " WHERE " . implode(" AND ",$where);

	$query .= " ORDER BY added DESC ";

	if ($params->get('listall') == 0)
	{
		$query .= " LIMIT " . $params->get('dispcount');
	}

	$maxheight = $params->get('maxheight');

	$db->setQuery($query);
	$rows = $db->loadAssocList();

	$parser = new FSSParser();
	$type = FSS_Settings::Get('announcemod_use_custom') ? 2 : 3;
	$parser->Load("announcemod", $type);
	
	$parser->SetVar('showdate', $params->get('show_date'));
	if ($params->get('viewannounce'))
		$parser->SetVar('readmore', JText::_("READ_MORE"));
	
	require( JModuleHelper::getLayoutPath( 'mod_fss_announce' ) );
}