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


class TableTest extends JTable
{

	var $id = null;

	var $ident = null;
	var $itemid = null;
	var $body = null;
	var $name = null;
	var $email = null;
	var $website = null;
   	var $created = null;
   	var $published = null;
	var $userid = null;

	function TableTest(& $db) {
		parent::__construct('#__fss_comments', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			if ($this->created == "")
			{
				$current_date = new JDate();
 				if (FSSJ3Helper::IsJ3())
				{
					$mySQL_conform_date = $current_date->toSql();
				} else { 
   					$mySQL_conform_date = $current_date->toMySQL();
				}
   				$this->set('created', $mySQL_conform_date);
			}
		}

		return true;
	}
}


