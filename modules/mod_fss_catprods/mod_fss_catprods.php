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

	$listtype = $params->get('listtype');

	$db =& JFactory::getDBO();

	if ($listtype == 'faqcat')
	{
		$query = "SELECT * FROM #__fss_faq_cat";
		
		$where = array();
		$where[] = " published = 1 ";
		if (FSS_Helper::Is16())
		{
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= "ORDER BY ordering";

		$db->setQuery($query);
		$rows = $db->loadAssocList();
		
		require( JModuleHelper::getLayoutPath( 'mod_fss_catprods', 'faqcat' ) );
	} else if ($listtype == 'kbprod')
	{
		$query = "SELECT * FROM #__fss_prod";
		
		$where = array();
		$where[] = "published = 1";
		$where[] = "inkb = 1";
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
		
		$query .= " ORDER BY ordering";

		$db->setQuery($query);
		$rows = $db->loadAssocList();
		FSS_Helper::Tr($rows);
		
		require( JModuleHelper::getLayoutPath( 'mod_fss_catprods', 'kbprod' ) );	
	} else if ($listtype == 'kbcats')
	{
		
		$prodid = $params->get('prodid');

		if ($prodid == -1)
			$prodid = JRequest::getVar('prodid');


		if ($prodid > 0)
		{
			$qry1 = "SELECT a.kb_cat_id FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "' AND published = 1 GROUP BY a.kb_cat_id";
			$qry2 = "SELECT a.kb_cat_id FROM #__fss_kb_art as a WHERE a.allprods = '1' AND published = 1 GROUP BY a.kb_cat_id";
			
			$query = "($qry1) UNION ($qry2)";
			$db->setQuery($query);
			
			$rows = $db->loadAssocList('kb_cat_id');
			$catids = array();
			foreach($rows as &$rows)
			{
				$catids[$rows['kb_cat_id']] = $rows['kb_cat_id'];
			}

			$query = "SELECT parcatid FROM #__fss_kb_cat WHERE id IN (".implode(", ",$catids).") AND parcatid > 0";
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			foreach($rows as &$rows)
			{
				$catids[$rows['parcatid']] = $rows['parcatid'];
			}

			$query = "SELECT * FROM #__fss_kb_cat";
			$where = array();
			
			$where[] = "published = 1";
			$where[] = "id IN (".implode(", ",$catids) . ")";
			$where[] = "parcatid = 0";
			
			if (FSS_Helper::Is16())
			{
				$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
			
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);
			
			$query .= " ORDER BY ordering";
		} else {
			
			$query = "SELECT * FROM #__fss_kb_cat";
			
			$where = array();
			$where[] = "published = 1";
			$where[] = "parcatid = 0";
			
			if (FSS_Helper::Is16())
			{
				$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
			
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);

			$query .= "ORDER BY ordering";
		}

		$db->setQuery($query);
		$rows = $db->loadAssocList('id');

		require( JModuleHelper::getLayoutPath( 'mod_fss_catprods', 'kbcats' ) );	
	} else {
		$module->showtitle = 0;
		$attribs['style'] = "hide_me";
		
	}

}