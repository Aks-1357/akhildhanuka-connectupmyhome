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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'paginationajax.php');

class FssModelGroups extends JModelLegacy
{
	function getGroups()
	{
		if (!empty($this->groups))
			return $this->groups;
			
		$db =& JFactory::getDBO();

		
		$qry = "SELECT g.id, g.groupname, g.description, cnt, allsee, allemail, allprods, ccexclude FROM #__fss_ticket_group as g LEFT JOIN (SELECT group_id, count(*) as cnt FROM #__fss_ticket_group_members GROUP BY group_id) as c ON g.id = c.group_id ";
		
		if (count($this->group_id_access) > 0 && $this->permission['groups'] != 1)
			$qry .= " WHERE g.id IN (" . implode(", ", $this->group_id_access) . ") ";
		
		$db->setQuery($qry);
			
		$this->groups = $db->loadObjectList();
		
		return $this->groups;
	}
	
	function getGroupProds()
	{
		if (!empty($this->group_prods))
			return $this->group_prods;
			
		$db =& JFactory::getDBO();

		$groupid = JRequest::getVar('groupid');
		
		$db	= & JFactory::getDBO();

		$query = "SELECT * FROM #__fss_ticket_group_prod as a LEFT JOIN #__fss_prod as p ON a.prod_id = p.id WHERE a.group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."' ORDER BY p.title ";
		$db->setQuery($query);
			
		$this->group_prods = $db->loadObjectList();
		
		return $this->group_prods;
	}
	
	function getGroup()
	{
		if (!empty($this->group))
			return $this->group;
			
		$db =& JFactory::getDBO();

		$groupid = JRequest::getVar('groupid');
		
		$db	= & JFactory::getDBO();

		$query = "SELECT * FROM #__fss_ticket_group WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($query);
			
		$this->group = $db->loadObject();
		
		return $this->group;
		
	}
	
	function getGroupMembers()
	{
		if (!empty($this->groupmembers))
			return $this->groupmembers;
			
		$mainframe = JFactory::getApplication();
		$db =& JFactory::getDBO();

		$groupid = JRequest::getInt('groupid');
		
		$db	= & JFactory::getDBO();

	    $query = ' SELECT g.group_id, g.user_id, u.name, u.username, u.email, g.allsee, g.allemail, g.isadmin FROM #__fss_ticket_group_members as g LEFT JOIN ';
		$query .= "#__users as u ON g.user_id = u.id";
		$where = array();
		$where[] = 'g.group_id = "' . $groupid . '"';
 		$query .= (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

		$this->order = JRequest::getVar('filter_order');
		$this->order_Dir = JRequest::getVar('filter_order_Dir');
		if ($this->order) {
			$query .= ' ORDER BY '. $this->order .' '. $this->order_Dir .'';
		}

		$db->setQuery($query);
		$db->query();

		$row_count = $db->getNumRows();
		
		$this->filter_values['limitstart'] = JRequest::getVar("limit_start",0);
		$this->filter_values['limit'] = $mainframe->getUserStateFromRequest("gmemberslimit_base","limit_base","20");
		
		$this->groupmembers_pagination = new JPaginationAjax($row_count, $this->filter_values['limitstart'], $this->filter_values['limit'] );

		$db->setQuery($query, $this->filter_values['limitstart'], $this->filter_values['limit']);	
		$this->groupmembers = $db->loadObjectList();
		
		return $this->groupmembers;	
	}
	
	function getGroupMembersPagination()
	{
		return $this->groupmembers_pagination;
	}
	
	function getUsers()
	{
		if (!empty($this->users))
			return $this->users;
			
		$mainframe = JFactory::getApplication();

		$db =& JFactory::getDBO();

		$db	= & JFactory::getDBO();

		if (FSS_Helper::Is16())
		{
			$query = 'SELECT a.id, a.username, a.name, a.email, g.title as lf1, gm.group_id as gid FROM #__users as a 
				LEFT JOIN #__user_usergroup_map as gm ON a.id = gm.user_id
				LEFT JOIN #__usergroups as g ON gm.group_id = g.id';
		} else {
			$query = 'SELECT a.id as id, a.username as username, a.name as name, a.email as email, a.gid as gid, l1.name as lf1
					FROM #__users AS a';
			$query .= ' LEFT JOIN #__core_acl_aro_groups AS l1 ON a.gid = l1.id ';
		}

		$where = array();

		$this->search = strtolower(JRequest::getVar('search'));

 		if ($this->permission['groups'] == 2)
 		{
			$this->username = strtolower(JRequest::getVar('username'));
			$this->email = strtolower(JRequest::getVar('email'));
			if ($this->username && $this->email)
			{
				$where[] = "LOWER( a.username ) = '".FSSJ3Helper::getEscaped($db, $this->username)."'";
				$where[] = "LOWER( email ) = '".FSSJ3Helper::getEscaped($db, $this->email)."'";
			} else {
 				$where[] = "a.id = 0";	
			}
		} elseif ($this->search) {
			$search = array();
			$search[] = '(LOWER( a.username ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->search, true ).'%', false ) . ')';
			$search[] = '(LOWER( a.name ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->search, true ).'%', false ) . ')';
			$search[] = '(LOWER( a.email ) LIKE '.$db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $this->search, true ).'%', false ) . ')';

			$where[] = " ( " . implode(" OR ",$search) . " ) ";
		}

		$order = "";
		
		$this->order = JRequest::getVar('filter_order');
		$this->order_Dir = JRequest::getVar('filter_order_Dir');
		if ($this->order) {
			$order = ' ORDER BY '. FSSJ3Helper::getEscaped($db,  $this->order) .' '. FSSJ3Helper::getEscaped($db,  $this->order_Dir) .'';
		}

		$this->gid = JRequest::getInt('gid');
		if ($this->gid != '')
		{
			if (FSS_Helper::Is16())
			{
				$where[] = 'gm.group_id = "' . FSSJ3Helper::getEscaped($db,  $this->gid) . '"';
			} else {
				$where[] = 'gid = "' . FSSJ3Helper::getEscaped($db,  $this->gid) . '"';
			}
		}

  		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');

  		$query .= $where . $order;

		//echo $query;

		$db->setQuery($query);
		$db->query();

		$row_count = $db->getNumRows();
		
		$this->filter_values['limitstart'] = JRequest::getVar("limit_start",0);
		$this->filter_values['limit'] = $mainframe->getUserStateFromRequest("pickuserlimit_base","limit_base","20");
		
		$this->users_pagination = new JPaginationAjax($row_count, $this->filter_values['limitstart'], $this->filter_values['limit'] );

		$db->setQuery($query, $this->filter_values['limitstart'], $this->filter_values['limit']);
		
		$this->users = $db->loadObjectList();
		
		return $this->users;		
	}
	
	function getUsersPagination()
	{
		return $this->users_pagination;
	}
	
	
	function GetProducts()
	{
		if (empty($this->products))
		{
			$db =& JFactory::getDBO();
			$query = "SELECT * FROM #__fss_prod WHERE insupport = 1 ORDER BY title";
			$db->setQuery($query);
			$this->products = $db->loadObjectList();
		}
		
		return $this->products;
	}
}

