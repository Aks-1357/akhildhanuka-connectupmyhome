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

jimport('joomla.utilities.date');


class TableMember extends JTable
{

	var $id = null;

	var $groupid;

	function TableMember(& $db) {
		parent::__construct('#__fss_ticket_group_members', 'user_id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
		}

		return true;
	}

	function delete($userid, $groupid)
	{
		if (!$userid)
		{
			$this->setError("No userid specified");
			return false;	
		}

		if (!$groupid)
		{
			$this->setError("No groupid specified");
			return false;	
		}
		// inherited from JTable

		$query = 'DELETE FROM '.$this->_tbl.
				" WHERE user_id = '".FSSJ3Helper::getEscaped($this->_db,$userid)."' AND group_id = '".FSSJ3Helper::getEscaped($this->_db,$groupid)."' ";
		$this->_db->setQuery( $query );
		
		if ($this->_db->query())
		{
			return true;
		}
		else
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}	
		
		// End inherited	
	}
}

