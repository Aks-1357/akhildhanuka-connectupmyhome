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

class FssModelTicket extends JModelLegacy
{
	var $multiuser = 0;

	function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication(); global $option;

		if (FSS_Settings::get('support_advanced') == 1)
		{
			$limit = $mainframe->getUserStateFromRequest('global.list.limit_prod', 'limit', FSS_Settings::Get('ticket_prod_per_page'), 'int');

			$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

			// In case limit has been changed, adjust it
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limit_prod', $limit);
			$this->setState('limitstart', $limitstart);
		}
	}
	
	function &getProducts()
	{
		// if data hasn't already been obtained, load it
		if (empty($this->_data)) {
			$query = $this->_buildProdQuery();
			if (FSS_Settings::get('support_advanced') == 1)
			{
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit_prod') );
			} else {
				$this->_db->setQuery( $query );
			}
			$this->_data = $this->_db->loadAssocList();
			FSS_Helper::Tr($this->_data);
		}
		return $this->_data;
	}
	
	function getProdLimit()
	{
		return $this->getState('limit_prod');
	}
	
	function _buildProdQuery()
	{
        $db =& JFactory::getDBO();
		$search = JRequest::getVar('prodsearch', '', '', 'string');  
		
		// products general query
		
		$query = "SELECT *, 0 as type FROM #__fss_prod";
		$where = array();
		$where[] = "insupport = 1";
		$order = array();
		$order[] = "ordering";
		
		if ($search != "__all__" && $search != '')
		{
			$where[] = "title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%'";
		}
		
		if (FSS_Settings::get('support_restrict_prod'))
			$where[] = "id = 0";
				
		$where[] = "insupport = 1";
		$where[] = "published = 1";

		
		// add language and access to query where
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	


		$query .= " WHERE " . implode(" AND ", $where);


		$prodids = $this->GetUserProdIDs();
		
			
		$qry = "SELECT *, 1 as type FROM #__fss_prod";
		$where = array();
		$where[] = "insupport = 1";
		$where[] = "published = 1";
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (is_array($prodids) && count($prodids) > 0)
			$where[] = "id IN (" . implode(", ", $prodids) . ")";
			
		$order = array();
		$order[] = "ordering";

		if ($search != "__all__" && $search != '')
		{
			$where[] = "title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%'";
		}

		$qry .= " WHERE " . implode(" AND ", $where);

		$fq = "SELECT *, MAX(type) as maxtype FROM (($query) UNION ($qry)) as a GROUP BY id";
		$fq .= " ORDER BY maxtype DESC, " . implode(", ", $order);

		return $fq;        
	}
	
	function GetUserProdIDs()
	{
		// get list of users groups
        $db =& JFactory::getDBO();
		
		$user =& JFactory::getUser();
		$userid = $user->get('id');

 		$qry = "SELECT * FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($qry);
		$user_groups = $db->loadObjectList('group_id');
		
		$gids = array();
		foreach ($user_groups as $group_id => $group)
		{
			$gids[$group_id] = $group_id;
		}

		if (count($gids) == 0)
			return -1;

		$qry = "SELECT * FROM #__fss_ticket_group WHERE id IN (" . implode(", ", $gids) . ")";
		$db->setQuery($qry);	
		$groups = $db->loadObjectList('id');
		
		// check for all prods
		foreach($groups as $group)
		{
			if ($group->allprods)
			{
				return -1;	
			}		
		}
		
		$qry = "SELECT prod_id FROM #__fss_ticket_group_prod WHERE group_id IN (" . implode(", ", $gids) . ")";
		$db->setQuery($qry);	
		$prods = $db->loadObjectList('prod_id');
		
		$pids = array();
		foreach($prods as $id => &$prod)
		{
			$pids[$id] = $id;	
		}
		
		return $pids;
	}
	
	function getTotalProducts()
	{
		if (empty($this->_prodtotal)) {
			$query = $this->_buildProdQuery();
			$this->_prodtotal = $this->_getListCount($query);
		}
		return $this->_prodtotal;		
	}

	function &getProdPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationAjax($this->getTotalProducts(), $this->getState('limitstart'), $this->getState('limit_prod') );
		}
		return $this->_pagination;
	}	

    function &getProduct()
    {
        $db =& JFactory::getDBO();
        $prodid = JRequest::getVar('prodid', 0, '', 'int');
        $query = "SELECT * FROM #__fss_prod WHERE id = '".FSSJ3Helper::getEscaped($db, $prodid)."'";

        $db->setQuery($query);
        $rows = $db->loadAssoc();
		FSS_Helper::TrSingle($rows);
        return $rows;        
	} 
	
	function compartTitle($a, $b) 
	{ 
		return strnatcmp($a['title'], $b['title']); 
	}
	
	function &getDepartments()
    {
		$prodid = JRequest::getInt('prodid',0);
        $db =& JFactory::getDBO();
		
        $query1 = "SELECT * FROM #__fss_ticket_dept";
		$where = array();
		$where[] = "allprods = 1";

		// add language and access to query where
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (count($where) > 0)
			$query1 .= " WHERE " . implode(" AND ",$where);

		$query2 = "SELECT * FROM #__fss_ticket_dept";
			
		$where = array();
		// add language and access to query where
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	

		$where[] = "id IN ( SELECT ticket_dept_id FROM #__fss_ticket_dept_prod WHERE prod_id = '".FSSJ3Helper::getEscaped($db, $prodid)."' )";

		if (count($where) > 0)
			$query2 .= " WHERE " . implode(" AND ",$where);

		$db->setQuery($query1);
		$rows1 = $db->loadAssocList();

		$db->setQuery($query2);
		$rows2 = $db->loadAssocList();
		
		$rows = array_merge($rows1, $rows2);
		
		usort($rows, array('FssModelTicket','compartTitle'));

        return $rows;        
	}  
	
	function &getCats()
    {
        $db =& JFactory::getDBO();
        $query = "SELECT * FROM #__fss_ticket_cat";
		
		$where = array();
		// add language and access to query where
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
		
		$query .= "ORDER BY section, title";
		
        $db->setQuery($query);
        $rows = $db->loadAssocList();
		
		$prodid = JRequest::getInt('prodid',0);
		$deptid = JRequest::getInt('deptid',0);
		
		$query = "SELECT * FROM #__fss_ticket_cat_prod WHERE prod_id = '".FSSJ3Helper::getEscaped($db, $prodid)."'";
		$db->setQuery($query);
		$products = $db->loadAssocList('ticket_cat_id');
		
		$query = "SELECT * FROM #__fss_ticket_cat_dept WHERE ticket_dept_id = '".FSSJ3Helper::getEscaped($db, $deptid)."'";
		$db->setQuery($query);
		$departments = $db->loadAssocList('ticket_cat_id');
		
		$output = array();
		
		if (is_array($rows))
		{
			foreach ($rows as $row)
			{
				if ($row['allprods'] == 0)
					if (!array_key_exists($row['id'],$products))
					{
						continue;
					}
					
				if ($row['alldepts'] == 0)
					if (!array_key_exists($row['id'],$departments))
					{
						continue;
					}
				
				$output[] = $row;
			}
		}
		
		return $output;        
	}  
	
    function &getDepartment()
    {
        $db =& JFactory::getDBO();
        $deptid = JRequest::getVar('deptid', 0, '', 'int');
        $query = "SELECT * FROM #__fss_ticket_dept WHERE id = '".FSSJ3Helper::getEscaped($db, $deptid)."'";

        $db->setQuery($query);
        $rows = $db->loadAssoc();
        return $rows;        
	} 
	
	function &getTicket($ticketid)
	{
        $db =& JFactory::getDBO();

		$query = "SELECT t.*, u.name, u.username, p.title as product, d.title as dept, d.translation as dtr, c.title as cat, c.translation as ctr, s.title as status, s.translation as str, s.userdisp, ";
		$query .= "s.color as scolor, s.id as sid, pr.title as pri, pr.color as pcolor, pr.translation as ptr, pr.id as pid, au.name as assigned, p.translation as prtr ";
		$query .= " FROM #__fss_ticket_ticket as t ";
		$query .= " LEFT JOIN #__users as u ON t.user_id = u.id ";
		$query .= " LEFT JOIN #__fss_prod as p ON t.prod_id = p.id ";
		$query .= " LEFT JOIN #__fss_ticket_dept as d ON t.ticket_dept_id = d.id ";
		$query .= " LEFT JOIN #__fss_ticket_cat as c ON t.ticket_cat_id = c.id ";
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id ";
		$query .= " LEFT JOIN #__fss_ticket_pri as pr ON t.ticket_pri_id = pr.id ";
		$query .= " LEFT JOIN #__fss_user as a ON t.admin_id = a.id ";
		$query .= " LEFT JOIN #__users as au ON a.user_id = au.id ";
		$query .= " WHERE t.id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' ";

		$db->setQuery($query);
        $rows = $db->loadAssoc();
        return $rows;   		
	}
	
	function &getMessages($ticketid)
	{
        $db =& JFactory::getDBO();
        
 		$query = "SELECT m.*, u.name FROM #__fss_ticket_messages as m LEFT JOIN #__users as u ON m.user_id = u.id WHERE ticket_ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' ORDER BY posted DESC";

        $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;   		
	}
	
	function &getAttach($ticketid)
	{
        $db =& JFactory::getDBO();
 
 		$query = "SELECT a.*, u.name FROM #__fss_ticket_attach as a LEFT JOIN #__users as u ON a.user_id = u.id WHERE ticket_ticket_id = '".FSSJ3Helper::getEscaped($db, $ticketid)."' AND hidefromuser = 0 ORDER BY added DESC";

        $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;   		
	}
	
	function &getTickets()
	{		
        $db =& JFactory::getDBO();
        
		$user =& JFactory::getUser();
		$userid = $user->get('id');
		
		$uidlist = $this->getUIDS($userid);
		$tidlist = $this->getTIDS($userid);
			
		//$query = "SELECT t.*, u.name, u.username, p.title as product, d.title as dept, d.translation as dtr, c.title as cat, c.translation as ctr, s.title as status, s.translation as str, s.userdisp, ";
		//$query .= "s.color as scolor, s.id as sid, pr.title as pri, pr.color as pcolor, pr.translation as ptr, pr.id as pid, au.name as assigned ";

		$query = "SELECT t.*, s.title as status, s.translation as str, s.userdisp, s.color, au.name as assigned, tu.name as user FROM #__fss_ticket_ticket as t ";
		
		$query .= " LEFT JOIN #__fss_ticket_status as s ON t.ticket_status_id = s.id ";
		$query .= " LEFT JOIN #__fss_user as a ON t.admin_id = a.id ";
		$query .= " LEFT JOIN #__users as au ON a.user_id = au.id ";
		$query .= " LEFT JOIN #__users as tu ON t.user_id = tu.id ";
		
				
		$query .= " WHERE ( t.user_id IN (" . implode(", ",$uidlist) . ") OR t.id IN (" . implode(", ", $tidlist) . ") ) ";

        $tickets = JRequest::getVar('tickets','open');
        if ($tickets == 'open')
        {
 				$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
				// tickets that arent closed
				$query .= " AND ticket_status_id IN ( " . implode(", ", $allopen) . ") ";
		}
        if ($tickets == 'closed')
        {
				$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed");
				// remove the archived tickets from the list to deal with
				
				$def_archive = FSS_Ticket_Helper::GetStatusID('def_archive');
				foreach ($allopen as $offset => $value)
					if ($value == $def_archive)
						unset($allopen[$offset]);

				// tickets that are closed
				$query .= " AND ticket_status_id IN ( " . implode(", ", $allopen) . ") ";
		}
        
        $query .= " ORDER BY lastupdate DESC ";
		
		$mainframe = JFactory::getApplication();

		$limit = $mainframe->getUserStateFromRequest('global.list.limit_ticket', 'limit', FSS_Settings::Get('ticket_per_page'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		
		//echo $query."<br>";
		$db->setQuery($query);
		$db->query();
		$count = $db->getNumRows();
		$result['pagination'] = new JPaginationEx($count, $limitstart, $limit );
		
		$db->setQuery($query, $limitstart, $limit);
		$result['tickets'] = $db->loadAssocList();
		
		return $result;   		
	}
	
	function &getPriority($priid)
	{
        $db =& JFactory::getDBO();
        
  		$query = "SELECT * FROM #__fss_ticket_pri WHERE id = '".FSSJ3Helper::getEscaped($db, $priid)."'";

        $db->setQuery($query);
        $rows = $db->loadAssoc();
        return $rows;   		
		
	}
	
	function &getCategory($catid)
	{
        $db =& JFactory::getDBO();
        
  		$query = "SELECT * FROM #__fss_ticket_cat WHERE id = '".FSSJ3Helper::getEscaped($db, $catid)."'";

        $db->setQuery($query);
        $rows = $db->loadAssoc();
        return $rows;   		
		
	}
	
	function &getPriorities()
	{
        $db =& JFactory::getDBO();
        
        $query = "SELECT * FROM #__fss_ticket_pri";
		
		$where = array();
		// add language and access to query where
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY ordering ASC";

        $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;   		
		
	}
	
	function &getStatuss()
	{
		if (empty($this->_statuss))
		{
			$db =& JFactory::getDBO();
		
			$query = "SELECT * FROM #__fss_ticket_status ORDER BY id ASC";

			$db->setQuery($query);
			$this->_statuss = $db->loadAssocList('id');
		}
		return $this->_statuss;   		
	}	
	
	function &getStatus($statusid)
	{
		if (empty($this->_statuss))
		{
			$this->getStatuss();
		}

		return $this->_statuss[$statusid];
	}
	
	function &getTicketCount()
	{
		$user =& JFactory::getUser();
		$userid = $user->get('id');

		$uidlist = $this->getUIDS($userid);
		$tidlist = $this->getTIDS($userid);

        $db =& JFactory::getDBO();
		$query = "SELECT count( * ) AS count, ticket_status_id FROM #__fss_ticket_ticket WHERE (user_id IN (".implode(", ",$uidlist) . ") OR id IN ( " . implode(", ",$tidlist) . ")) GROUP BY ticket_status_id";
	
				
		$db->setQuery($query);
		$rows = $db->loadAssocList();
				
		$out = array();
		FSS_Ticket_Helper::GetStatusList();
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			$out[$status->id] = 0;
		}
			
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$out[$row['ticket_status_id']] = $row['count'];
			}
		}
			
		// work out counts for allopen, closed, all, archived
			
		$archived = FSS_Ticket_Helper::GetStatusID("def_archive");
		$out['archived'] = $out[$archived];


		$allopen = FSS_Ticket_Helper::GetStatusIDs("is_closed", true);
		$out['open'] = 0;
		foreach ($allopen as $id)
			$out['open'] += $out[$id];
		
			
		$allclosed = FSS_Ticket_Helper::GetClosedStatus();
		$out['closed'] = 0;
		foreach ($allclosed as $id)
			$out['closed'] += $out[$id];

			
		$all = FSS_Ticket_Helper::GetStatusIDs("def_archive");
		$out['all'] = 0;
		foreach ($rows as $row)
		{
			if ($row['ticket_status_id'] != $all)
				$out['all'] += $row['count'];
		}
			
			
		$this->_counts = $out;
		
		return $this->_counts;	
        
		/*
		$db->setQuery($query);
        $rows = $db->loadAssocList();
    
    	$out["open"] = 0;
		$out["reply"] = 0;
		$out["follow"] = 0;
    	$out["closed"] = 0;
    	
		if (count($rows) > 0)
		{
    		foreach ($rows as $row)
    		{
    			if ($row['ticket_status_id'] == 1)
    			{
    				$out["open"] = $row['count'];
				} else if ($row['ticket_status_id'] == 2)
    			{
    				$out["follow"] = $row['count'];
				} else if ($row['ticket_status_id'] == 3)
				{
					$out["closed"] = $row['count'];
				} else if ($row['ticket_status_id'] == 4)
				{
					$out["reply"] = $row['count'];
				}	
			}
		}
		
		$out['all'] = $out["open"] + $out["follow"] + $out["closed"] + $out["reply"];
		
		return $out;*/
    }
    
    	
	function getUser($user_id)
	{
		$db =& JFactory::getDBO();
		$query = " SELECT * FROM #__users ";
		$query .= " WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		
		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;   		
	}
	
	function getTIDS($user_id)
	{
		if (!empty($this->tidlist))
			return $this->tidlist;

		$db =& JFactory::getDBO();
		
		$qry = "SELECT ticket_id FROM #__fss_ticket_cc WHERE user_id = '".FSSJ3Helper::getEscaped($db,  $user_id)."' AND isadmin = 0";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();
		$this->tidlist = array();
		
		foreach ($rows as $row)
		{
			$this->tidlist[$row['ticket_id']] = $row['ticket_id'];	
			
			$this->multiuser = 1;
		}		
		
		if (count($this->tidlist) == 0)
			$this->tidlist[] = 0;
			
		return $this->tidlist;
	}

	function getUIDS($user_id)
	{
		if (!empty($this->uidlist))
			return $this->uidlist;

		$db =& JFactory::getDBO();
		
		// get groups
		$query = " SELECT * FROM #__fss_ticket_group_members ";
		$query .= " WHERE user_id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		
		$db->setQuery($query);
		$usergrouplist = $db->loadAssocList('group_id');
		//print_p($usergrouplist);
		
		if (count($usergrouplist) == 0)
		{
			$this->uidlist = array($user_id => $user_id);
			return $this->uidlist;
		}

		$gids = array();
		foreach ($usergrouplist as $group)
		{
			$gids[] = FSSJ3Helper::getEscaped($db, $group['group_id']);
		}
		
		$query = "SELECT * FROM #__fss_ticket_group WHERE id IN (" . implode(", ",$gids) .")";
		$db->setQuery($query);
		$grouplist = $db->loadAssocList();
		//print_p($grouplist);
		
		$gids = array();
		foreach($grouplist as $group)
		{
			// find if the user has permissions to view this groups tickets
			$perm = $usergrouplist[$group['id']]['allsee'];
			$groupperm = $group['allsee'];
			if ($perm == 0)
				$perm = $groupperm;
	
			if ($perm > 0) // view allowed
			{
				$gids[] = FSSJ3Helper::getEscaped($db, $group['id']);
			}
		}

		if (count($gids) == 0)
		{
			$this->uidlist = array($user_id => $user_id);
			return $this->uidlist;
		}
		
		$query = "SELECT user_id FROM #__fss_ticket_group_members WHERE group_id IN (" . implode(", ",$gids) .")";
		$db->setQuery($query);
		$groupmemberlist = $db->loadAssocList();

		if (count($groupmemberlist) == 0)
			return array($user_id => $user_id);

		$uids = array();
		
		foreach ($groupmemberlist as $row)
		{
			$uids[$row['user_id']] = $row['user_id'];
		}

		$this->uidlist = $uids;
		$this->multiuser = 1;	        
        
		return $uids;   		
	}
}

