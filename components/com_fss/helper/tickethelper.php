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

class FSS_Ticket_Helper
{

	
	function ListHandlers($prodid, $deptid, $catid, $allownoauto = false)
	{
		$db =& JFactory::getDBO();

		// assign to any available user
		$qry = "SELECT user_id FROM #__fss_user_prod WHERE prod_id = '".FSSJ3Helper::getEscaped($db, $prodid)."'";
		$db->setQuery($qry);
		$produsers = $db->loadAssocList('user_id');

		$qry = "SELECT user_id FROM #__fss_user_dept WHERE ticket_dept_id = '".FSSJ3Helper::getEscaped($db, $deptid)."'";
		$db->setQuery($qry);
		$deptusers = $db->loadAssocList('user_id');

		$qry = "SELECT user_id FROM #__fss_user_cat WHERE ticket_cat_id = '".FSSJ3Helper::getEscaped($db, $catid)."'";
		$db->setQuery($qry);
		$catusers = $db->loadAssocList('user_id');
		
		$qry = "SELECT * FROM #__fss_user";
		$db->setQuery($qry);
		$users = $db->loadAssocList();
		
		$okusers = array();
		
		$count = 0;
		
		foreach ($users as $admin)
		{
			if ($admin['allprods'] == 0)
				if (empty($produsers[$admin['id']]))
					continue;	

			if ($admin['alldepts'] == 0)
				if (empty($deptusers[$admin['id']]))
					continue;	

			if ($admin['allcats'] == 0)
				if (empty($catusers[$admin['id']]))
					continue;	

			if ($admin['autoassignexc'] > 0 && !$allownoauto)
			{
				continue;	
			}

			$okusers[] = $admin['id'];
		}
		
		return $okusers;
	}
	
	function AssignHandler($prodid, $deptid, $catid)
	{
		//echo "Assigning hander for $prodid, $deptid, $catid<br>";
		$admin_id = 0;
		
		$assignuser = FSS_Settings::get('support_autoassign');
		if ($assignuser == 1)
		{
			$okusers = FSS_Ticket_Helper::ListHandlers($prodid, $deptid, $catid);

			if (count($okusers) > 0)
			{
				$count = count($okusers);
				$picked = mt_rand(0,$count-1);
				$admin_id = $okusers[$picked];
			}
		}

		return $admin_id;
	}

	function createRef($ticketid,$format = "",$depth = 0)
	{
		if ($format == "")
			$format = FSS_Settings::get('support_reference');

		if ($depth > 4)
			$format = "4L-4L-4L";

		preg_match_all("/(\d[LNX])/i",$format,$out);
		if (strpos($format, "{") !== false)
		{
			preg_match_all("/{([A-Za-z0-9]+)}/i",$format,$out);
			
			$key = $format;
			foreach($out[1] as $match)
			{
				$count = substr($match,0,1);
				$type = strtoupper(substr($match,1,1));
				if ($type == "" && (int)$count < 1)
				{
					$type = $count;
					$count = 1;
				}
				$replace = "";

				if ($type == "X")
				{
					$replace = sprintf("%0".$count."d",$ticketid);		
				} else if ($type == "N")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= rand(0,9);	
					}		
				} else if ($type == "L")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= chr(rand(0,25)+ord('A'));	
					}								
				} else if ($type == "D")
				{
					$replace = date("Y-m-d");	
				}
				
				$pos = strpos($key,"{".$match."}");
				if ($pos !== false)
				{
					$key = substr($key,0,$pos) . $replace . substr($key,$pos+strlen($match)+2);	
				}

			}
		} elseif (count($out) > 0)
		{
			$key = $format;
			foreach($out[0] as $match)
			{
				$count = substr($match,0,1);
				$type = strtoupper(substr($match,1,1));
				$replace = "";

				if ($type == "X")
				{
					$replace = sprintf("%0".$count."d",$ticketid);
						
				} else if ($type == "N")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= rand(0,9);	
					}		
				} else if ($type == "L")
				{
					for ($i = 0; $i < $count; $i++)
					{
						$replace .= chr(rand(0,25)+ord('A'));	
					}								
				}
				
				$pos = strpos($key,$match);
				if ($pos !== false)
				{
					$key = substr($key,0,$pos) . $replace . substr($key,$pos+strlen($match));	
				}

			}
		} else {
			$key = FSS_Ticket_Helper::createRef($ticketid,"4L-4L-4L",$depth + 1);	
		}	
				
 		$db =& JFactory::getDBO();
		
		$query = "SELECT id FROM #__fss_ticket_ticket WHERE reference = '".FSSJ3Helper::getEscaped($db, $key)."'";
		$db->setQuery($query);
        $rows = $db->loadAssoc();
        
        if ($rows)
        {
        	$key = FSS_Ticket_Helper::createRef($ticketid,$format,$depth + 1);
		}		
		
		return $key;
	}

	static $status_list;
	function GetStatusList()
	{
		// get a list of all status
		if (empty(FSS_Ticket_Helper::$status_list))
		{
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT * FROM #__fss_ticket_status ORDER BY ordering");
			FSS_Ticket_Helper::$status_list = $db->loadObjectList();
		}
	}
	
	function GetStatusByID($id)
	{
		FSS_Ticket_Helper::GetStatusList();

		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($status->id == $id)
				return $status;
		}		
		
		return null;
	}
	
	function GetStatus($type)
	{
		FSS_Ticket_Helper::GetStatusList();
		// returns the object of the status row with field $type set as 1	
	}
		
	function GetStatuss($type, $not = false)
	{
		// returns the object of the status row with field $type set as 1	
		FSS_Ticket_Helper::GetStatusList();
		
		$rows = array();
		
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($not)
			{
				if ($status->$type == 0)
					$rows[] = $status;
			} else {
				if ($status->$type > 0)
					$rows[] = $status;
			}
		}

		return $rows;
	}
	
	function GetStatusID($type)
	{
		FSS_Ticket_Helper::GetStatusList();
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($status->$type > 0)
				return (int)$status->id;
		}
		
		return 0;	
	}
	
	function GetStatusIDs($type, $not = false)
	{
		FSS_Ticket_Helper::GetStatusList();
		
		$ids = array();
		
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($not)
			{
				if ($status->$type == 0)
					$ids[] = (int)$status->id;
			} else {
				if ($status->$type > 0)
					$ids[] = (int)$status->id;
			}
		}
		
		if (count($ids) == 0)
			$ids[] = 0;
		
		return $ids;
	}	
	
	function GetClosedStatus()
	{
		FSS_Ticket_Helper::GetStatusList();
		
		$ids = array();
		
		foreach (FSS_Ticket_Helper::$status_list as $status)
		{
			if ($status->is_closed && ! $status->def_archive)
					$ids[(int)$status->id] = (int)$status->id;
		}
		
		if (count($ids) == 0)
			$ids[] = 0;
		
		return $ids;
	}
	
	static $_permissions;
	static $_perm_prods;
	static $_perm_depts;
	static $_perm_cats;
	static $_perm_where;
	static $_perm_only;
	
	function getAdminPermissions()
	{
		if (empty(FSS_Ticket_Helper::$_permissions)) {
			$mainframe = JFactory::getApplication(); global $option;
			$user = JFactory::getUser();
			
			$userid = $user->id;
			
			$db =& JFactory::getDBO();
			$query = "SELECT * FROM #__fss_user WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
			$db->setQuery($query);
			FSS_Ticket_Helper::$_permissions = $db->loadAssoc();
			
			if (FSS_Ticket_Helper::$_permissions['allprods'] == 0)
			{
				$query = "SELECT prod_id FROM #__fss_user_prod WHERE user_id = '" . FSSJ3Helper::getEscaped($db, FSS_Ticket_Helper::$_permissions['id']) . "'";
				$db->setQuery($query);
				FSS_Ticket_Helper::$_perm_prods = $db->loadResultArray();
				if (count(FSS_Ticket_Helper::$_perm_prods) == 0)
					FSS_Ticket_Helper::$_perm_prods[] = 0;
				FSS_Ticket_Helper::$_perm_prods = " AND prod_id IN (" . implode(",",FSS_Ticket_Helper::$_perm_prods) . ") ";
			} else {
				FSS_Ticket_Helper::$_perm_prods = '';	
			}
			
			if (FSS_Ticket_Helper::$_permissions['alldepts'] == 0)
			{
				$query = "SELECT ticket_dept_id FROM #__fss_user_dept WHERE user_id = '" . FSSJ3Helper::getEscaped($db, FSS_Ticket_Helper::$_permissions['id']) . "'";
				$db->setQuery($query);
				FSS_Ticket_Helper::$_perm_depts = $db->loadResultArray();
				if (count(FSS_Ticket_Helper::$_perm_depts) == 0)
					FSS_Ticket_Helper::$_perm_depts[] = 0;
				FSS_Ticket_Helper::$_perm_depts = " AND ticket_dept_id IN (" . implode(",",FSS_Ticket_Helper::$_perm_depts) . ") ";
			} else {
				FSS_Ticket_Helper::$_perm_depts = '';	
			}
			
			if (FSS_Ticket_Helper::$_permissions['allcats'] == 0)
			{
				$query = "SELECT ticket_cat_id FROM #__fss_user_cat WHERE user_id = '" . FSSJ3Helper::getEscaped($db, FSS_Ticket_Helper::$_permissions['id']) . "'";
				$db->setQuery($query);
				FSS_Ticket_Helper::$_perm_cats = $db->loadResultArray();
				if (count(FSS_Ticket_Helper::$_perm_cats) == 0)
					$this->_perm_cats[] = 0;
				FSS_Ticket_Helper::$_perm_cats = " AND ticket_cat_id IN (" . implode(",",FSS_Ticket_Helper::$_perm_cats) . ") ";
			} else {
				FSS_Ticket_Helper::$_perm_cats = '';	
			}
			
			if (FSS_Ticket_Helper::$_permissions['seeownonly'])
			{
				FSS_Ticket_Helper::$_perm_only = ' AND (admin_id = 0 OR admin_id = ' . FSS_Ticket_Helper::$_permissions['id'] .') ';	
			} else {
				FSS_Ticket_Helper::$_perm_only = '';	
			}
			
			FSS_Ticket_Helper::$_perm_where = FSS_Ticket_Helper::$_perm_prods . FSS_Ticket_Helper::$_perm_depts . FSS_Ticket_Helper::$_perm_cats . FSS_Ticket_Helper::$_perm_only;
		}
		return FSS_Ticket_Helper::$_permissions;			
	}
	
}