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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class FsssViewMembers extends JViewLegacy
{
  
    function display($tpl = null)
    {
 		$groupid = JRequest::getVar('groupid');
		$db	= & JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_ticket_group WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$group = $db->loadObject();
		$this->group = &$group;
		
		$task = JRequest::getVar('task');
		
		if ($task == "setperm")
			return $this->SetPerm();	
		
		if ($task == "toggleallemail")
			return $this->ToggleAllEMail();
		
		if ($task == "toggleadmin")
			return $this->ToggleIsAdmin();
		
        JToolBarHelper::title( JText::_("TICKET_GROUP_MEMBERS") . " - " . $group->groupname, 'fss_groups' );
		//$bar=& JToolBar::getInstance( 'toolbar' );
		//$bar->appendButton( 'Popup', 'new', "OLD", 'index.php?option=com_fss&view=listusers&tmpl=component&groupid='. $groupid, 630, 440 );
        JToolBarHelper::custom('popup','new', 'new', 'ADD_USERS', false);
		JToolBarHelper::deleteList();
        JToolBarHelper::cancel('cancellist','Close');
		FSSAdminHelper::DoSubToolbar();

		$this->assignRef( 'lists', $this->get('Lists') );
		
 		$query = 'SELECT * FROM #__fss_ticket_group ORDER BY groupname';
		$db->setQuery($query);
		$filter = $db->loadObjectList();
		$this->lists['groupid'] = JHTML::_('select.genericlist',  $filter, 'groupid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'groupname', $this->lists['groupid']);
		$this->groupid = $groupid;

        $this->assignRef( 'data', $this->get('Data') );
        $this->assignRef( 'pagination', $this->get('Pagination'));

 		$document =& JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'administrator/components/com_fss/assets/css/groupperm.css'); 
		$document->addStyleSheet(JURI::root().'components/com_fss/assets/css/popup.css'); 
		$document->addScript(JURI::root().'components/com_fss/assets/js/popup.js'); 

       parent::display($tpl);
    }
	
	function SetPerm()
	{
		$db	= & JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		$perm = JRequest::getVar('perm');
		
		$qry = "UPDATE #__fss_ticket_group_members SET allsee = '".FSSJ3Helper::getEscaped($db, $perm)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo "1";
		
		exit;		
	}
	
	function ToggleIsAdmin()
	{
		$db	= & JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		
		$qry = "SELECT isadmin FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$isadmin = $current->isadmin;
		$isadmin = 1 - $isadmin;
		
		$qry = "UPDATE #__fss_ticket_group_members SET isadmin = '".FSSJ3Helper::getEscaped($db, $isadmin)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_GetYesNoText($isadmin);
		
		exit;		
		
	}
	
	function ToggleAllEMail()
	{
		$db	= & JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		
		$qry = "SELECT allemail FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$allemail = $current->allemail;
		$allemail = 1 - $allemail;
		
		$qry = "UPDATE #__fss_ticket_group_members SET allemail = '".FSSJ3Helper::getEscaped($db, $allemail)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_GetYesNoText($allemail);
		
		exit;		
		
	}
}



