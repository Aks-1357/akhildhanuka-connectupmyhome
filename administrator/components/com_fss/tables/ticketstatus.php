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


class TableTicketstatus extends JTable
{

	var $id = null;

	var $title = null;

	var $color = '#ffffff';
	/*var $def_open = 0;
	var $def_user = 0;
	var $def_admin = 0;
	var $is_closed = 0;*/
  	var $ordering = 0;
	var $userdisp = '';
	var $translation = '';


	function TableTicketstatus(& $db) {
		parent::__construct('#__fss_ticket_status', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{		
			$this->set('ordering', $this->getNextOrder());
			$this->set('published', 1);
		}

		return true;
	}
	
	function set_closed($value)
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = & JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
			$ids[] = (int)FSSJ3Helper::getEscaped($db, $id);

		$value = (int)FSSJ3Helper::getEscaped($db, $value);
		$qry = "UPDATE #__fss_ticket_status SET is_closed = $value WHERE id IN (" . implode(", ", $ids) . ")";
		if ($value == 0) // dont allow def closed and archived to not be is_closed
			$qry .= " AND def_closed = 0 AND def_archive = 0";
		
		$db->setQuery($qry);
		
		return $db->query();			
	}
	
	function set_tab($value)
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = & JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
			$ids[] = (int)FSSJ3Helper::getEscaped($db, $id);

		$value = (int)FSSJ3Helper::getEscaped($db, $value);
		$qry = "UPDATE #__fss_ticket_status SET own_tab = $value WHERE id IN (" . implode(", ", $ids) . ")";
		
		$db->setQuery($qry);
		
		return $db->query();			
	}

	function set_autoclose($value)
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = & JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
			$ids[] = (int)FSSJ3Helper::getEscaped($db, $id);

		$value = (int)FSSJ3Helper::getEscaped($db, $value);
		$qry = "UPDATE #__fss_ticket_status SET can_autoclose = $value WHERE id IN (" . implode(", ", $ids) . ")";
		if ($value == 1) // dont allow closed status to autoclose
			$qry .= " AND is_closed = 0";
		
		$db->setQuery($qry);
		
		return $db->query();			
	}
		
	function set_one_field($field)
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = & JFactory::getDBO();		
		$id = 0;
		foreach ($cids as $id)
		{
			$id = (int)FSSJ3Helper::getEscaped($db, $id);
			break;
		}

		$field = FSSJ3Helper::getEscaped($db, $field);
		$qry = "UPDATE #__fss_ticket_status SET $field = 0 WHERE 1";
		$db->setQuery($qry);
		$db->query();	
		
		$extraset = "";
		if ($field == "def_closed" || $field == "def_archive")
			$extraset .= ", is_closed = 1 ";
		if ($field == "def_user" || $field == "def_open" || $field == "def_admin")
			$extraset .= ", is_closed = 0 ";
				
		$qry = "UPDATE #__fss_ticket_status SET $field = 1 $extraset WHERE id = $id";
 
		
		$db->setQuery($qry);
		return $db->query();			
	}
	
	function publish($cids, $value)
	{
		$db = & JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
		{
			$ids[] = (int)FSSJ3Helper::getEscaped($db, $id);
		}

		$query = "SELECT * FROM #__fss_ticket_status WHERE id IN ( " . implode(", ", $ids) . ")";
		
		$db->setQuery($query);
        $rows = $db->loadObjectList();
		
		foreach ($rows as $row)
		{
			if ($row->def_user || $row->def_open || $row->def_admin)
			{
				return "You cannot unpublish your default status";		
			}
		}
		
		return parent::publish($cids, $value);
	}
	
	function delete($id)
	{
		$db = & JFactory::getDBO();		
		$id = (int)$id;
		$query = "SELECT * FROM #__fss_ticket_status WHERE id = $id";
		$db->setQuery($query);
        $rows = $db->loadObjectList();
		
		foreach ($rows as $row)
		{
			if ($row->def_user || $row->def_open || $row->def_admin)
			{
				return "You cannot delete your default status";		
			}
		}
	
		return parent::delete($id);
	}
}


