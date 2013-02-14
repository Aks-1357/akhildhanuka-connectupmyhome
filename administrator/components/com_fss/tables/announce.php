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


class TableAnnounce extends JTable
{
	
	var $id = null;

	var $title = null;
	
	var $subtitle = null;

	var $body = null;
	
	var $added = null;

	var $fulltext = null;
	
	var $language = "*";
	
	var $access = 1;
	
	var $author;
	
	function TableAnnounce(& $db) {
		parent::__construct('#__fss_announce', 'id', $db);
	}

	function check()
	{
		// make published by default and get a new order no
		if (!$this->id)
		{
			$this->set('added', date("Y-m-d H:i:s"));
			$this->set('published', 1);
			
			$user = JFactory::GetUser();
			$this->set('author', $user->id);
		}

		return true;
	}
}


