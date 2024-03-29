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


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );



class FsssModelKbcats extends JModelLegacy
{
    
    var $_data;

	
	var $_total = null;

	var $lists = array(0);

	
	var $_pagination = null;

    function __construct()
	{
        parent::__construct();

        $mainframe = JFactory::getApplication(); global $option;

  		$context = "kb_cats_";

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

 		$search	= $mainframe->getUserStateFromRequest( $context.'search', 'search',	'',	'string' );
		$search	= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$ispublished	= $mainframe->getUserStateFromRequest( $context.'filter_ispublished',	'ispublished',	-1,	'int' );
		if (!$filter_order)
			$filter_order = "c.ordering";

		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		$this->lists['ispublished'] = $ispublished;

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
   }

   
    function _buildQuery()
    {
 		$db	=& JFactory::getDBO();

        $query = ' SELECT c.id, c.title, c.ordering, c.published, c.description, c.image, c.parcatid, pc.title as parcattitle, c.access, c.language FROM #__fss_kb_cat as c ';
		$query .= " LEFT JOIN #__fss_kb_cat as pc on c.parcatid = pc.id ";
		$where = array();

		if ($this->lists['search']) {
			$where[] = '(LOWER( title ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
		}

		if ($this->lists['order'] == 'c.ordering') {
			$order = ' ORDER BY c.ordering '. $this->lists['order_Dir'];
		} else {
			$order = ' ORDER BY '. $this->lists['order'] .' '. $this->lists['order_Dir'] .', c.ordering';
		}

		if ($this->lists['ispublished'] > -1)
		{
			$where[] = 'c.published = ' . $this->lists['ispublished'];
		}
		
		if (FSSAdminHelper::Is16())
		{
			FSSAdminHelper::LA_GetFilterState();
			if (FSSAdminHelper::$filter_lang)	
				$where[] = "c.language = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_lang) . "'";
			if (FSSAdminHelper::$filter_access)	
				$where[] = "c.access = '" . FSSJ3Helper::getEscaped($db, FSSAdminHelper::$filter_access) . "'";
		}

  		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

  		$query .= $where . $order;

        return $query;
    }

   
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_data;
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

    function getPagination()
    {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }

    function getLists()
    {
		return $this->lists;
	}

}


