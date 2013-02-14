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


class FsssModelTests extends JModelLegacy
{
    
    var $_data;

	
	var $_total = null;

	var $lists = array(0);

	
	var $_pagination = null;

    function __construct()
	{
        parent::__construct();

        $mainframe = JFactory::getApplication(); global $option;
		$context = "test_";

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

 		$search	= $mainframe->getUserStateFromRequest( $context.'search', 'search',	'',	'string' );
		$search	= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		$filter_prod_id	= $mainframe->getUserStateFromRequest( $context.'filter_testprodid',	'prod_id',	0,	'int' );

		$ispublished	= $mainframe->getUserStateFromRequest( $context.'filter_ispublished',	'ispublished',	-1,	'int' );

		$this->lists['order_Dir']	= $filter_order_Dir;
		$this->lists['order']		= $filter_order;
		$this->lists['search'] = $search;
		$this->lists['prod_id'] = $filter_prod_id;
		$this->lists['ispublished'] = $ispublished;

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
   }

    function _buildQuery()
    {
 		$db	=& JFactory::getDBO();

        $query = ' SELECT t.id as id,t.body as body, t.email as email, t.name as name, t.website as website, t.published as published, ';
        $query .= ' t.created as added, ident, itemid FROM #__fss_comments as t';

		$where = array();

        if ($this->lists['search']) {
			$where[] = '(LOWER( t.title ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->lists['search'], true ).'%', false ) . ')';
		}

		$order = "";
		if ($this->lists['order'] == 'added') {
			$order = ' ORDER BY added '. FSSJ3Helper::getEscaped($db, $this->lists['order_Dir']);
		} else if ($this->lists['order']) {
			$order = ' ORDER BY '. FSSJ3Helper::getEscaped($db, $this->lists['order']) .' '. FSSJ3Helper::getEscaped($db, $this->lists['order_Dir']) .'';
		}

		if ($this->lists['prod_id'] > 0)
		{
			$where[] = 'p.id = ' . FSSJ3Helper::getEscaped($db, $this->lists['prod_id']);
		}

		if ($this->lists['ispublished'] > -1)
		{
			$where[] = 't.published = ' . FSSJ3Helper::getEscaped($db, $this->lists['ispublished']);
		}
		
		$ident = JRequest::getVar('ident','');
		if ($ident > 0)
			$where[] = 'ident = ' . FSSJ3Helper::getEscaped($db, $ident);
			
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


