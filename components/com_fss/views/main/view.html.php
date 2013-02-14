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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'models'.DS.'admin.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');


class FssViewMain extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
		$aparams = $mainframe->getPageParameters('com_fss');

		$this->assign('template', $aparams->get('template'));
		if ($this->template == "") $this->template = "grid";
		$prefix = $this->template . "_";
		
		$this->assign('show_desc', $aparams->get('show_desc'));
		$this->assign('mainwidth', $aparams->get($prefix.'mainwidth'));
		$this->assign('maincolums', $aparams->get($prefix.'maincolums'));
		if ($this->maincolums == 0 || $this->maincolums == "")
			$this->maincolums = 3;
		$this->assign('hideicons', $aparams->get($prefix.'hideicons'));
		
		$this->assign('imagewidth', $aparams->get($prefix.'imagewidth'));
		if ($this->imagewidth == 0 || $this->imagewidth == "")
			$this->imagewidth = 128;
			
		$this->assign('imageheight', $aparams->get($prefix.'imageheight'));
		if ($this->imageheight == 0 || $this->imageheight == "")
			$this->imageheight = 128;
			
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__menu';
		$db->setQuery($query);
		$this->assignRef('joomlamenus',$db->loadAssocList('id'));

		// work out permissions, and if to show admin or not
		$this->permission = FSS_Helper::getPermissions(false);
		$showadmin = false;
		$showgroups = false;
		if ($this->permission['mod_kb'])
			$showadmin = true;
		if ($this->permission['support'])
			$showadmin = true;
		if ($this->permission['groups'] > 0)
			$showgroups = true;
		
		$this->showadmin = $showadmin;
		
		if ($showadmin)
			$this->getSupportOverView();
		
		$query = 'SELECT * FROM #__fss_main_menu ';
		$where = array();
		
		if (!$showadmin)
			$where[] = 'itemtype != 9';
		if (!$showgroups)
			$where[] = 'itemtype != 10';
		
		// add language and access to query where
		if (FSS_Helper::Is16())
		{
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
			
		$query .= " ORDER BY ordering";
			
		$db->setQuery($query);
		$this->assignRef('menus',$db->loadAssocList('id'));
			
		$this->ValidateMenuLinks();
		
        parent::display($this->template);
    }
	
	function ValidateMenuLinks()
	{
		$allmenus = FSS_GetAllMenus();
		$basemenus = array();
	
		if (count($allmenus) > 0)
		{
			foreach($allmenus as $allmenu)
			{
				$basemenus[$allmenu->id] = $allmenu;	
			}
		}
		
		//print_r($basemenus);
		//print_r($this->menus);
		
		if (count($this->menus) > 0)
		{
			foreach($this->menus as &$menu)
			{
				if ($menu['itemtype'] == FSS_IT_LINK)
					continue;
				
				$itemid = $menu['itemid'];

				if ($menu['link'] != "")
				{
					if (array_key_exists($itemid,$basemenus))
					{
						if ($basemenus[$itemid]->link != $menu['link'])
						{
							//echo "Not using $itemid, link different<br>";	
							$menu['link'] = "";	
							$menu['itemid'] = 0;	
						}
					} else {
						//echo "Not using $itemid, link not found<br>";	
						$menu['link'] = "";	
						$menu['itemid'] = 0;	
					}
				}
			
				// fix 1.5.2 cockup with these 2 back to front
				if ($menu['itemtype'] == FSS_IT_NEWTICKET && strpos($menu['link'],"layout=open") < 1)
				{
					$menu['link'] = "";	
					$menu['itemid'] = 0;	
				}
			
				// fix 1.5.2 cockup with these 2 back to front
				if ($menu['itemtype'] == FSS_IT_VIEWTICKETS && strpos($menu['link'],"layout=open") > 0)
				{
					$menu['link'] = "";	
					$menu['itemid'] = 0;	
				}
			
				// menu link as null, find out itemid and link from database and store it
				$menus = FSS_GetMenus($menu['itemtype']);
				
				
				if ($menu['link'] == "")
				{
				
					if (count($menus) > 0)
					{
						/*print_r($menus[0]);*/
						$menu['link'] = $menus[0]->link;	
						$menu['itemid'] = $menus[0]->id;
					
						$db =& JFactory::getDBO();
						$id = $menu['id'];

						$qry = "UPDATE #__fss_main_menu SET link = '".FSSJ3Helper::getEscaped($db, $menu['link'])."', itemid = '".FSSJ3Helper::getEscaped($db, $menu['itemid'])."' WHERE id = '".FSSJ3Helper::getEscaped($db, $id)."'";	
						$db->setQuery($qry);$db->Query();
						//echo $qry."<br>";
					} else {
						$id = $menu['id'];
						$qry = "UPDATE #__fss_main_menu SET link = '', itemid = 0 WHERE id = '$id'";	
						$db =& JFactory::getDBO();
						$db->setQuery($qry);$db->Query();
					
						switch($menu['itemtype'])
						{
						case FSS_IT_KB:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=kb' );
							break;		
						case FSS_IT_FAQ:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=faq' );
							break;		
						case FSS_IT_TEST:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=test' );
							break;		
						case FSS_IT_NEWTICKET:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=ticket&layout=open' );
							break;		
						case FSS_IT_VIEWTICKETS:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=ticket' );
							break;		
						case FSS_IT_ANNOUNCE:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=announce' );
							break;		
						case FSS_IT_GLOSSARY:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=glossary' );
							break;		
						case FSS_IT_ADMIN:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=admin' );
							break;		
						case FSS_IT_GROUPS:
							$menu['link'] = JRoute::_( 'index.php?option=com_fss&view=groups' );
							break;		
						}
					}
				}
			}
		}
	}

	function getSupportOverView()
	{
		$model = new FssModelAdmin();
		
		$this->comments = new FSS_Comments(null,null);
		$this->moderatecount = $this->comments->GetModerateTotal();
	}
}

