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

// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class FssModelTest extends JModelLegacy
{
	function &getProduct()
	{
		$db =& JFactory::getDBO();
		$prodid = JRequest::getVar('prodid', 0, '', 'int');
		$query = "SELECT * FROM #__fss_prod WHERE id = '".FSSJ3Helper::getEscaped($db, $prodid)."'";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;        
	} 
	
	function &getProducts()
	{
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__fss_prod";
		
		$where = array();
		$where[] = "published = 1";
		$where[] = "intest = 1";
		
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$db->setQuery($query);
		$rows = $db->loadAssocList('id');
		if (!is_array($rows))
			return array();
		return $rows;        
	} 
}

