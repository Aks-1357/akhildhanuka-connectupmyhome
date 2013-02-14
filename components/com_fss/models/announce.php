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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'paginationex.php');

class FssModelAnnounce extends JModelLegacy
{
	function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication(); global $option;

		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', FSS_Settings::Get('announce_per_page'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	
	}
	
 	function &getAnnounces( )
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit') );
			$this->_data = $this->_db->loadAssocList();
        }
        return $this->_data;
    }
    
    function &getAnnounce()
    {
        $db =& JFactory::getDBO();
        $announceid = JRequest::getVar('announceid', 0, '', 'int');
        $query = "SELECT * FROM #__fss_announce";
		
		$where = array();
		$where[] = "id = '".FSSJ3Helper::getEscaped($db, $announceid)."'";
				
		if ($this->content->permission['artperm'] > 1) // we have editor so can see all unpublished arts
		{
			
		} else if ($this->content->permission['artperm'] == 1){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}
		
		if (FSS_Helper::Is16())
		{
			$db =& JFactory::getDBO();
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

        $db->setQuery( $query  );
        $rows = $db->loadAssoc();
        return $rows;      
    }
 
   	function _buildQuery()
	{
		$query = "SELECT * FROM #__fss_announce ";
		
		$where = array();
		
		if ($this->content->permission['artperm'] > 1) // we have editor so can see all unpublished arts
		{
			
		} else if ($this->content->permission['artperm'] == 1){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}
			
		// add language and access to query where
		if (FSS_Helper::Is16())
		{
			$db =& JFactory::getDBO();
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
	
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY added DESC";
		return $query;
	}

	function getTotal()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
	}

	function &getPagination()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPaginationEx($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
	}
	
}

