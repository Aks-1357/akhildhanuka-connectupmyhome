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

jimport( 'joomla.application.component.view' );



class FsssViewFuser extends JViewLegacy
{

	function display($tpl = null)
	{
		if (JRequest::getString('task') == "prods")
			return $this->displayProds();
			
//##NOT_TEST_START##
		if (JRequest::getString('task') == "depts")
			return $this->displayDepts();
			
		if (JRequest::getString('task') == "cats")
			return $this->displayCats();
//##NOT_TEST_END##
		
		$user 	=& $this->get('Data');
		$isNew		= ($user->id < 1);

		$db	= & JFactory::getDBO();

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("USER").': <small><small>[ ' . $text.' ]</small></small>' , 'fss_users');
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();
		
		if ($isNew)
		{
			$users =& $this->get("Users");
			$this->assignRef('users',JHTML::_('select.genericlist',  $users, 'user_id', 'class="inputbox" size="1" ', 'id', 'name'));
			
			$groups =& $this->get("Groups");
			$this->assignRef('groups',JHTML::_('select.genericlist',  $groups, 'group_id', 'class="inputbox" size="1" ', 'id', 'name'));

			$this->assignRef('type',  JHTML::_('select.booleanlist', 'type', 
				array('class' => "inputbox",
							'size' => "1", 
							'onclick' => "DoAllTypeChange();"),0, 'Group', 'User' ) );
				
			if (count($users) == 0)
			{
				$this->assign('showtypes',0);
				$this->assign('showusers',0);
				$this->assign('showgroups',1);	
			} else if (count($groups) == 0) {
				$this->assign('showtypes',0);
				$this->assign('showusers',1);
				$this->assign('showgroups',0);				
			} else {
				$this->assign('showtypes',1);
				$this->assign('showusers',1);
				$this->assign('showgroups',0);				
			}
				
		} else {
			$input = "<input type='hidden' name='user_id' id='user_id' value='" . $user->user_id . "' />";
			$this->assign('users',$input.$user->name);	
			
			$this->assign('showtypes',0);
			
			if ($user->user_id > 0)
			{
				$this->assign('showusers',1);
				$this->assign('showgroups',0);
			} else {
				$this->assign('showusers',0);
				$this->assign('showgroups',1);
			}
			
			//$input = "<input type='hidden' name='group_id' id='group_id' value='" . $user->group_id . "' />";
			//$this->assign('groups',$input.$user->groupname);
			$this->groups = "";	
		}


		$artperms = array();
        $artperms[] = JHTML::_('select.option', '0', JText::_("ART_NONE"), 'id', 'title');
        $artperms[] = JHTML::_('select.option', '1', JText::_("AUTHOR"), 'id', 'title');
        $artperms[] = JHTML::_('select.option', '2', JText::_("EDITOR"), 'id', 'title');
        $artperms[] = JHTML::_('select.option', '3', JText::_("PUBLISHER"), 'id', 'title');
        $this->assign('artperms',JHTML::_('select.genericlist',  $artperms, 'artperm', 'class="inputbox" size="1"', 'id', 'title', $user->artperm));

//##NOT_TEST_START##
		//  User product selection
		$this->assign('allprod', JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
						'size' => "1", 
						'onclick' => "DoAllProdChange();"),
					intval($user->allprods)) );

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_user_prod WHERE user_id = " . FSSJ3Helper::getEscaped($db, $user->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$user->allprods);
		
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
		$this->assignRef('products', $prodcheck);



		//  User department selection
		$this->assign('alldept', JHTML::_('select.booleanlist', 'alldepts', 
			array('class' => "inputbox",
						'size' => "1", 
						'onclick' => "DoAllDeptChange();"),
					intval($user->alldepts)) );

		$query = "SELECT * FROM #__fss_ticket_dept ORDER BY title";
		$db->setQuery($query);
		$departments = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_user_dept WHERE user_id = " . FSSJ3Helper::getEscaped($db, $user->id);
		$db->setQuery($query);
		$seldept = $db->loadAssocList('ticket_dept_id');
		
		$this->assign('alldepts',$user->alldepts);
		
		$deptcheck = "";
		foreach($departments as $department)
		{
			$checked = false;
			if (array_key_exists($department->id,$seldept))
			{
				$deptcheck .= "<input type='checkbox' name='dept_" . $department->id . "' checked />" . $department->title . "<br>";
			} else {
				$deptcheck .= "<input type='checkbox' name='dept_" . $department->id . "' />" . $department->title . "<br>";
			}
		}
		$this->assignRef('departments', $deptcheck);



		//  User category selection
		$this->assign('allcat', JHTML::_('select.booleanlist', 'allcats', 
			array('class' => "inputbox",
						'size' => "1", 
						'onclick' => "DoAllCatChange();"),
					intval($user->allcats)) );

		$query = "SELECT * FROM #__fss_ticket_cat ORDER BY title";
		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_user_cat WHERE user_id = " . FSSJ3Helper::getEscaped($db, $user->id);
		$db->setQuery($query);
		$selcat = $db->loadAssocList('ticket_cat_id');
		
		$this->assign('allcats',$user->allcats);
		
		$catcheck = "";
		foreach($categories as $category)
		{
			$checked = false;
			if (array_key_exists($category->id,$selcat))
			{
				$catcheck .= "<input type='checkbox' name='cat_" . $category->id . "' checked />" . $category->title . "<br>";
			} else {
				$catcheck .= "<input type='checkbox' name='cat_" . $category->id . "' />" . $category->title . "<br>";
			}
		}
		$this->assignRef('categories', $catcheck);
//##NOT_TEST_END##




		$this->assignRef('user', $user);

		parent::display($tpl);
	}
	
	function displayProds()
	{
		$user_id = JRequest::getInt('user_id',0);
		$db	= & JFactory::getDBO();

		$query = "SELECT * FROM #__fss_user_prod as u LEFT JOIN #__fss_prod as p ON u.prod_id = p.id WHERE u.user_id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($query);
		$products = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_user WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($query);
		$userpermissions = $db->loadObject();
		
		$jid = $userpermissions->user_id;
		
		$query = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $jid)."'";
		$db->setQuery($query);
		$joomlauser = $db->loadObject();
		
		$this->assignRef('userpermissions',$userpermissions);
		$this->assignRef('joomlauser',$joomlauser);
		$this->assignRef('products',$products);
		parent::display();
	}
	
	function displayDepts()
	{
		$user_id = JRequest::getInt('user_id',0);
		$db	= & JFactory::getDBO();

		$query = "SELECT * FROM #__fss_user_dept as u LEFT JOIN #__fss_ticket_dept as p ON u.ticket_dept_id = p.id WHERE u.user_id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($query);
		$departments = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_user WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($query);
		$userpermissions = $db->loadObject();
		
		$jid = $userpermissions->user_id;
		
		$query = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $jid)."'";
		$db->setQuery($query);
		$joomlauser = $db->loadObject();
		
		$this->assignRef('userpermissions',$userpermissions);
		$this->assignRef('joomlauser',$joomlauser);
		$this->assignRef('departments',$departments);
		parent::display();
	}
	
	function displayCats()
	{
		$user_id = JRequest::getInt('user_id',0);
		$db	= & JFactory::getDBO();

		$query = "SELECT * FROM #__fss_user_cat as u LEFT JOIN #__fss_ticket_cat as p ON u.ticket_cat_id = p.id WHERE u.user_id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($query);
		$catogries = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_user WHERE id = '".FSSJ3Helper::getEscaped($db, $user_id)."'";
		$db->setQuery($query);
		$userpermissions = $db->loadObject();
		
		$jid = $userpermissions->user_id;
		
		$query = "SELECT * FROM #__users WHERE id = '".FSSJ3Helper::getEscaped($db, $jid)."'";
		$db->setQuery($query);
		$joomlauser = $db->loadObject();
		
		$this->assignRef('userpermissions',$userpermissions);
		$this->assignRef('joomlauser',$joomlauser);
		$this->assignRef('catogries',$catogries);
		parent::display();
	}
}


