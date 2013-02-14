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

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');
//JHTML::_('behavior.mootools');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'paginationex.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php');
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'models'.DS.'admin.php' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');

class FssViewGroups extends JViewLegacy
{
    function display($tpl = null)
    {
		//print_p($_POST);
		
		$this->assignRef('permission',FSS_Helper::getPermissions());
		$this->view = JRequest::getVar('view');
		
		$this->getGroupPerms();
		
		if (!$this->permission['groups'] > 0)
			return $this->noPerm();
		
        $layout = JRequest::getVar('layout',  JRequest::getVar('_layout', ''));
    	$this->assignRef('layout',$layout);
		
		$document =& JFactory::getDocument();
		$document->addStyleSheet( JURI::base().'components/com_fss/assets/css/popup.css' ); 
		$document->addStyleSheet( JURI::base().'components/com_fss/assets/css/groupperm.css' ); 
		$document->addScript( JURI::base().'components/com_fss/assets/js/popup.js' );
		$this->groupid = JRequest::getVar('groupid', 0);
		
		$this->comments = new FSS_Comments(null,null);
		$this->moderatecount = $this->comments->GetModerateTotal();

		$what = JRequest::getVar('what','');
		
		if ($what == "productlist")
			return $this->DisplayProducts();
			
		if ($what == "setperm")
			return $this->SetPerm();	
		
		if ($what == "toggleallemail")
			return $this->ToggleAllEMail();
		
		if ($what == "toggleadmin")
			return $this->ToggleIsAdmin();
			
		if ($what == "pickuser")
			return $this->PickUser();
	
		if ($what == "adduser")
			return $this->AddUser();
		
		if ($what == "removemembers")
			return $this->RemoveUsers();
			
		if ($what == "savegroup" || $what == "saveclose")
			return $this->SaveGroup($what);
			
		if ($what == "create")
			return $this->CreateGroup();
		
		if ($what == "deletegroup")
			return $this->DeleteGroup();
		
		if ($this->groupid > 0)
			return $this->DisplayGroup();
		
		return $this->DisplayGroupList();
		
		parent::display();
    }
	
	function getGroupPerms()
	{
		$this->group_id_access = array();
		
		// if we are not a global admin, but just in a couple of groups, get a groupid list
		if ($this->permission['groups'] == 2)
		{
			$user = JFactory::getUser();
			$userid = $user->id;
		
			$qry = "SELECT group_id FROM #__fss_ticket_group_members WHERE user_id = '$userid' AND isadmin = 1";
			$db =& JFactory::getDBO();
		
			$db->setQuery($qry);
			$group_id_access = $db->loadObjectList('group_id');
			
			foreach ($group_id_access as $id => &$temp)
				$this->group_id_access[$id] = $id;
		}
		$model = $this->getModel();
		$model->group_id_access =& $this->group_id_access;
		$model->permission = $this->permission;
		
	}
	
	function noPerm()
	{
		parent::display("noperm");
		
	}
	
	function DisplayGroup()
	{
		$this->creating = false;
		
		$groupid = JRequest::getVar('groupid');
		if (!$this->canAdminGroup($groupid))
			return $this->noPerm();
		
		$this->group =& $this->get('Group');
		$this->groupmembers =& $this->get('GroupMembers');
		//print_p($this->groupmembers);
		
		$this->pagination = $this->get('GroupMembersPagination');
	
		$mainframe = JFactory::getApplication();
		$pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'groups' )))	
			$pathway->addItem(JText::_('TICKET_GROUPS'), FSSRoute::x( 'index.php?option=com_fss&view=groups' ) );
		$pathway->addItem($this->group->groupname );

		$this->buildGroupEditForm();

		$this->order = JRequest::getVar('filter_order');
		$this->order_Dir = JRequest::getVar('filter_order_Dir');
		$this->limit_start = JRequest::getVar("limit_start",0);

		parent::display('group');
	}
	
	function buildGroupEditForm()
	{
		$db =& JFactory::getDBO();
		
		$idents = array();
		$idents[] = JHTML::_('select.option', '0', JText::_("VIEW_NONE"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '1', JText::_("VIEW"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '2', JText::_("VIEW_REPLY"), 'id', 'title');			
		$idents[] = JHTML::_('select.option', '3', JText::_("VIEW_REPLY_CLOSE"), 'id', 'title');			
		$this->allsee = JHTML::_('select.genericlist',  $idents, 'allsee', ' class="inputbox" size="1"', 'id', 'title', $this->group->allsee);

		$this->allprod = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
				'size' => "1", 
				'onclick' => "DoAllProdChange();"),
			 intval($this->group->allprods));

		$query = "SELECT * FROM #__fss_prod WHERE insupport = 1 AND published = 1 ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_ticket_group_prod WHERE group_id = " . FSSJ3Helper::getEscaped($db, $this->group->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$this->group->allprods);
		
		$prodcheck = "";
		foreach($products as $product)
		{
			$checked = false;
			if (array_key_exists($product->id,$selprod))
			{
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' checked />" . $product->title . "<br>";
			} else {
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' />" . $product->title . "<br>";
			}
		}
		$this->products = $prodcheck;	
		
		$this->order = "";
		$this->order_Dir = "";
	}
		
	function DisplayGroupList()
	{
		$this->groups =& $this->get('Groups');
		
		$mainframe = JFactory::getApplication();
		$pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'groups' )))	
			$pathway->addItem(JText::_('TICKET_GROUPS'), FSSRoute::x( 'index.php?option=com_fss&view=groups' ) );
			
		parent::display();
	}
	
	function DisplayProducts()
	{
		$this->products =& $this->get('GroupProds');
		$this->group =& $this->get('Group');
		
		parent::display('prods');	
	}
	
	function SetPerm()
	{
		$db	= & JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		$perm = JRequest::getVar('perm');
		
		if (!$this->canAdminGroup($groupid))
			return;
		
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
		
		if (!$this->canAdminGroup($groupid))
			return;
			
		$qry = "SELECT isadmin FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$isadmin = $current->isadmin;
		$isadmin = 1 - $isadmin;
		
		$qry = "UPDATE #__fss_ticket_group_members SET isadmin = '".FSSJ3Helper::getEscaped($db, $isadmin)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_Helper::GetYesNoText($isadmin);
		
		exit;		
		
	}
	
	function ToggleAllEMail()
	{
		$db	= & JFactory::getDBO();
		
		$userid = JRequest::getVar('userid');
		$groupid = JRequest::getVar('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "SELECT allemail FROM #__fss_ticket_group_members WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$allemail = $current->allemail;
		$allemail = 1 - $allemail;
		
		$qry = "UPDATE #__fss_ticket_group_members SET allemail = '".FSSJ3Helper::getEscaped($db, $allemail)."' WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."' AND group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo FSS_Helper::GetYesNoText($allemail);
		
		exit;		
		
	}
	
	function canAdminGroup($groupid)
	{
		if ($this->permission['groups'] == 1)
			return true;
		if (!array_key_exists($groupid, $this->group_id_access))
			return false;
		return true;	
	}
	
	function PickUser()
	{
		$filter = array();
		$filter[] = JHTML::_('select.option', '', JText::_('JOOMLA_GROUP'), 'id', 'name');
		if (FSS_Helper::Is16())
		{
			$query = 'SELECT id, title as name FROM #__usergroups ORDER BY title';
		} else {
			$query = 'SELECT id, name FROM #__core_acl_aro_groups ORDER BY name';
		}
		$db	= & JFactory::getDBO();
		$db->setQuery($query);
		$filter = array_merge($filter, $db->loadObjectList());
		$this->gid = JHTML::_('select.genericlist',  $filter, 'gid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'name', JRequest::getVar('gid'));

        $this->assignRef( 'users', $this->get('Users') );

		$this->search = JRequest::getVar('search');
		$this->username = JRequest::getVar('username');
		$this->email = JRequest::getVar('email');
		$this->order = JRequest::getVar('filter_order');
		$this->order_Dir = JRequest::getVar('filter_order_Dir');

		$this->pagination = $this->get('UsersPagination');
		$this->limit_start = JRequest::getVar("limit_start",0);
		parent::display("users");
	}
	
	function AddUser()
	{
		$userids = JRequest::getVar('cid',  0, '', 'array');
		$groupid = JRequest::getVar('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;

		$db	= & JFactory::getDBO();
		if (count($userids) > 0)
		{
			foreach ($userids as $userid)
			{
				$qry = "REPLACE INTO #__fss_ticket_group_members (group_id, user_id) VALUES ('".FSSJ3Helper::getEscaped($db, $groupid)."', '".FSSJ3Helper::getEscaped($db, $userid)."')";
				//echo $qry . "<br>";
				$db->setQuery($qry);
				$db->query($qry);
			}
		}
	
		$link = FSSRoute::x('index.php?option=com_fss&view=groups&groupid=' . $groupid);
		echo "<script>\n";
		echo "parent.location.href='$link';\n";
		echo "</script>";
	
		exit;	
	}
	
	function RemoveUsers()
	{
		$userids = JRequest::getVar('cid',  0, '', 'array');
		$groupid = JRequest::getVar('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;

		$db	= & JFactory::getDBO();
		if (count($userids) > 0)
		{
			foreach ($userids as $userid)
			{
				$qry = "DELETE FROM #__fss_ticket_group_members WHERE group_id ='".FSSJ3Helper::getEscaped($db, $groupid)."' AND user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
				$db->setQuery($qry);
				$db->query($qry);
			}
		}
		
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::x('index.php?option=com_fss&view=groups&groupid=' . $groupid,false);
		$mainframe->redirect($link,JText::_('SEL_REMOVED'));
	}
	
	function SaveGroup($what)
	{
		$db	= & JFactory::getDBO();

		//echo "Saving Group<br>";
		//print_p($_POST);
		//exit;
		
		$groupid = JRequest::getVar('groupid',0);
		$groupname = JRequest::getVar('groupname','');
		$description = JRequest::getVar('description','');
		$allemail = JRequest::getVar('allemail',0);
		$allsee = JRequest::getVar('allsee',0);
		$allprods = JRequest::getVar('allprods',0);
		$ccexclude = JRequest::getVar('ccexclude',0);
		
		if (!$this->canAdminGroup($groupid))
			return;
		$msg = "";		
		if ($groupid > 0)
		{	
			$msg = JText::_("GROUP_SAVED");			
			
			// saving existing group	
			$qry = "UPDATE #__fss_ticket_group SET ";
			$qry .= " groupname = '".FSSJ3Helper::getEscaped($db, $groupname)."', ";
			$qry .= " description = '".FSSJ3Helper::getEscaped($db, $description)."', ";
			$qry .= " allsee = '".FSSJ3Helper::getEscaped($db, $allsee)."', ";
			$qry .= " allprods = '".FSSJ3Helper::getEscaped($db, $allprods)."', ";
			$qry .= " allemail = '".FSSJ3Helper::getEscaped($db, $allemail)."', ";
			$qry .= " ccexclude = '".FSSJ3Helper::getEscaped($db, $ccexclude)."' ";
			$qry .= " WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
			$db->setQuery($qry);
			//echo $qry."<br>";
			$db->Query();
			
			// save products
		} else {
			$msg = JText::_("GROUP_CREATED");			
			// creating new group	
			$qry = "INSERT INTO #__fss_ticket_group (groupname, description, allsee, allprods, allemail, ccexclude) VALUES (";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $groupname)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $description)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $allsee)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $allprods)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $allemail)."', ";
			$qry .= " '".FSSJ3Helper::getEscaped($db, $ccexclude)."') ";
			
			$db->setQuery($qry);
			$db->Query();
			//echo $qry."<br>";
			$groupid = $db->insertid();
			//echo "New ID : $groupid<br>";
		}
		
		// save products
		if ($groupid > 0)
		{
			$qry = "DELETE FROM #__fss_ticket_group_prod WHERE group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'"; 
			//echo $qry."<br>";
			$db->setQuery($qry);
			$db->Query();
					
			if (!$allprods)
			{
				// get a product list
				$products = $this->get('Products');	
				foreach($products as &$product)
				{
					$id = $product->id;
					$field = "prod_" . $id;
					$value = JRequest::getVar($field,'');
					if ($value == "on")
					{
						$qry = "REPLACE INTO #__fss_ticket_group_prod (group_id, prod_id) VALUES ('".FSSJ3Helper::getEscaped($db, $groupid)."', '".FSSJ3Helper::getEscaped($db, $id)."')";
						//echo $qry."<br>";
						$db->setQuery($qry);
						$db->Query();
				}	
				}
			}
		}
		//exit;
		
		$mainframe = JFactory::getApplication();
		
		if ($what == "saveclose")
		{
			$link = FSSRoute::x('index.php?option=com_fss&view=groups',false);
			
		} else {
			$link = FSSRoute::x('index.php?option=com_fss&view=groups&groupid=' . $groupid,false);
		}
		$mainframe->redirect($link,$msg);
	}
	
	function CreateGroup()
	{
		if ($this->permission['groups'] != 1)
			return $this->noPerm();
			
		//print_p($this->permission);
		$this->creating = true;
		$this->group = new stdclass();
		$this->group->id = 0;
		$this->group->groupname = null;
		$this->group->description = null;
		$this->group->allsee = 0;
		$this->group->allemail = 0;
		$this->group->allprods = 1;
		$this->group->ccexclude = 0;
		
		$this->buildGroupEditForm();
		
		parent::display('group');	
	}
	
	function DeleteGroup()
	{
		$db	= & JFactory::getDBO();

		//echo "Deleting Group";
		$groupid = JRequest::getVar('groupid',0);
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "DELETE FROM #__fss_ticket_group WHERE id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__fss_ticket_group_members WHERE group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__fss_ticket_group_prod WHERE group_id = '".FSSJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		//exit;
		
		$mainframe = JFactory::getApplication();
		$link = FSSRoute::x('index.php?option=com_fss&view=groups',false);
		$mainframe->redirect($link,JText::_('GROUP_DELETED'));
	}
}

