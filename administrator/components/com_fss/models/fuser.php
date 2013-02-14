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

jimport('joomla.application.component.model');



class FsssModelFuser extends JModelLegacy
{

	var $_users = null;
	
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			if (FSS_Helper::Is16())
			{
				$query = ' SELECT u.*, ' .
					'CONCAT(m.username," (",m.name,")") as name ' .
					' FROM #__fss_user as u ' .
					' LEFT JOIN #__users as m ON u.user_id = m.id '.
					'  WHERE u.id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			} else {
				$query = ' SELECT u.*, ' .
					'CONCAT(m.username," (",m.name,")") as name, ' .
					'g.name as groupname ' .
					' FROM #__fss_user as u ' .
					' LEFT JOIN #__users as m ON u.user_id = m.id '.
					' LEFT JOIN #__core_acl_aro_groups as g ON u.group_id = g.id ' .
					'  WHERE u.id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			}
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->mod_kb = 0;
			$this->_data->mod_test = 0;
			$this->_data->support = 0;
			$this->_data->user_id = 0;
			$this->_data->group_id = 0;
			$this->_data->seeownonly = 0;
			$this->_data->autoassignexc = 0;
			$this->_data->allprods = 1;
			$this->_data->alldepts = 1;
			$this->_data->allcats = 1;
			$this->_data->artperm = 0;
			$this->_data->groups = 0;
			$this->name = "";
		}
		return $this->_data;
	}

	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) {
			print $this->_db->getErrorMsg();
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
//##NOT_TEST_START##
		// sort code for all products
		$db	= & JFactory::getDBO();
		$query = "DELETE FROM #__fss_user_prod WHERE user_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new product ids
		if (!$row->allprods)
		{
			$query = "SELECT * FROM #__fss_prod ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "prod_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__fss_user_prod (user_id, prod_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
		
		
		// sort code for all departments
		$query = "DELETE FROM #__fss_user_dept WHERE user_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new department ids
		if (!$row->alldepts)
		{
			$query = "SELECT * FROM #__fss_ticket_dept ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "dept_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__fss_user_dept (user_id, ticket_dept_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
		
		
		// sort code for all categories
		$query = "DELETE FROM #__fss_user_cat WHERE user_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new category ids
		if (!$row->allcats)
		{
			$query = "SELECT * FROM #__fss_ticket_cat ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "cat_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__fss_user_cat (user_id, ticket_cat_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
//##NOT_TEST_END##

		$this->_id = $row->id;

		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();
		$db =& JFactory::getDBO();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				
//##NOT_TEST_START##
				$qry = "DELETE FROM #__fss_user_prod WHERE user_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
				$qry = "DELETE FROM #__fss_user_dept WHERE user_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
				$qry = "DELETE FROM #__fss_user_cat WHERE user_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
//##NOT_TEST_END##
			}
		}
		
		return true;
	}
	
	function getUsers()
	{
		if (empty( $this->_users )) 
		{
			$query = "SELECT m.id, CONCAT(m.username,' (',m.name,')') as name, m.email FROM #__users as m LEFT JOIN #__fss_user as u ON m.id = u.user_id WHERE u.id IS NULL ORDER BY m.username";
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$this->_users = $db->loadAssocList();
		}
		return $this->_users;
	}
	
	function getGroups()
	{
		// DISABLE GROUPS
		return array();

		if (empty( $this->_groups )) 
		{
			$query = "SELECT m.id, m.name FROM #__core_acl_aro_groups as m LEFT JOIN #__fss_user as u ON m.id = u.group_id WHERE u.id IS NULL ORDER BY m.name";
			$db =& JFactory::getDBO();
			$db->setQuery($query);
			$this->_groups = $db->loadAssocList();
		}
		return $this->_groups;
		
	}
}


