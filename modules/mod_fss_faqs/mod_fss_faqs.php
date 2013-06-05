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

	global $posdata, $date_format;

	$css = FSSRoute::x( "index.php?option=com_fss&view=css&layout=default" );
	$document =& JFactory::getDocument();
	$document->addStyleSheet($css); 
	$css = "modules/mod_fss_faqs/tmpl/mod_fss_faqs.css";
	$document->addStyleSheet($css); 
	if (!FSSJ3Helper::IsJ3())
	{
		if (!JFactory::getApplication()->get('jquery')) {
			JFactory::getApplication()->set('jquery', true);
			$document->addScript( JURI::root().'components/com_fss/assets/js/jquery.1.8.3.min.js' );
		}
	}
	$document->addScript( JURI::base().'components/com_fss/assets/js/jquery.autoscroll.js' );

	$catid = $params->get('catid');
	$dispcount = $params->get('dispcount');
	$maxheight = $params->get('maxheight');

	$db =& JFactory::getDBO();

	$qry = "SELECT * FROM #__fss_faq_faq";

	$where = array();
	$where[] = "published = 1";

	// for cats
	if ($catid > 0)
	{
		$where[] = "faq_cat_id = " .  FSSJ3Helper::getEscaped($db, $catid);
	} else if ($catid == -5)
	{
		$where[] = "featured = 1";
	}

	if (count($where) > 0)
	{
		$qry .= " WHERE " . implode(" AND ",$where);	
	}

	$order = "ordering DESC";
	$qry .= " ORDER BY $order ";


	if ($dispcount > 0)
		$qry .= " LIMIT $dispcount";

	$db->setQuery($qry);
	$data = $db->loadObjectList();

	$posdata = array();

	require( JModuleHelper::getLayoutPath( 'mod_fss_faqs' ) );
}