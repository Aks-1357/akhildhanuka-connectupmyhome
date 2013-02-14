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



class FsssModelTicketgroup extends JModelLegacy
{

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
			$query = ' SELECT * FROM #__fss_ticket_group '.
					'  WHERE id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->groupname = null;
			$this->_data->description = null;
			$this->_data->allsee = 0;
			$this->_data->allemail = 0;
			$this->_data->allprods = 1;
			$this->_data->ccexclude = 0;
		}
		return $this->_data;
	}

	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// sort code for all products
		$db	= & JFactory::getDBO();
		
		$query = "DELETE FROM #__fss_ticket_group_prod WHERE group_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
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
					$query = "INSERT INTO #__fss_ticket_group_prod (group_id, prod_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				
				$qry = "DELETE FROM #__fss_ticket_group_members WHERE group_id = '".FSSJ3Helper::getEscaped($this->_db,$cid)."'";
				$this->_db->setQuery($qry);
				$this->_db->query();

				$qry = "DELETE FROM #__fss_ticket_group_prod WHERE group_id = '".FSSJ3Helper::getEscaped($this->_db,$cid)."'";
				$this->_db->setQuery($qry);
				$this->_db->query();

				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}

			}
		}
		return true;
	}
}


