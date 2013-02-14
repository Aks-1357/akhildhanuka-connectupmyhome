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

class FsssControllerlistuser extends JControllerLegacy
{

	function __construct()
	{
		parent::__construct();
	}

	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function adduser()
	{
		$cid = JRequest::getVar('cid',  0, '', 'array');
		$groupid = JRequest::getVar('groupid');

		$this->AddMembership($cid,$groupid);

		$link = "index.php?option=com_fss&view=members&groupid=$groupid";
		echo "<script>\n";
		echo "parent.location.href='$link';\n";
		echo "</script>";
		//$this->setRedirect($link, $msg);
	}

	function AddMembership($userids, $groupid)
	{
		$db	= & JFactory::getDBO();
		foreach ($userids as $userid)
		{
			$qry = "REPLACE INTO #__fss_ticket_group_members (group_id, user_id) VALUES ('" . FSSJ3Helper::getEscaped($db, $groupid) . "', '" . FSSJ3Helper::getEscaped($db, $userid)."')";
			$db->setQuery($qry);
			$db->query($qry);
		}
	}
}


