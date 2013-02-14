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
jimport('joomla.filesystem.folder');
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'settings.php');



class FsssViewMainmenu extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainmenu	=& $this->get('Data');
		$isNew		= ($mainmenu->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("MENU_ITEM").': <small><small>[ ' . $text.' ]</small></small>', 'fss_menu' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		$this->assignRef('mainmenu',		$mainmenu);
		
		$path = JPATH_SITE.DS.'images'.DS.'fss'.DS.'menu';

		if (!file_exists($path))
			mkdir($path,0777,true);
		
		$files = JFolder::files($path,'.png$');
		
		$sections[] = JHTML::_('select.option', '', JText::_("NO_IMAGE"), 'id', 'title');
		foreach ($files as $file)
		{
			$sections[] = JHTML::_('select.option', $file, $file, 'id', 'title');
		}
				
		$lists['images'] = JHTML::_('select.genericlist',  $sections, 'icon', 'class="inputbox" size="1" ', 'id', 'title', $mainmenu->icon);

		$this->assignRef('lists', $lists);

		if ($mainmenu->itemtype != 7)
		{
			$menus = FSS_GetMenus($mainmenu->itemtype);
			
			$menuitems = array();
			foreach ($menus as $menu)
			{
				$menuitems[] = JHTML::_('select.option', $menu->id . "|" . $menu->link, $menu->title . " (Itemid = ".$menu->id.")", 'id', 'title');
			}
			if (count($menuitems) > 1)
			{
				$lists['menuitems'] = JHTML::_('select.genericlist',  $menuitems, 'menuitem', 'class="inputbox" size="1" onchange="changeMenuItem();"', 'id', 'title', $mainmenu->itemid . "|" . $mainmenu->link);
			} else if (count($menuitems) == 1)
			{
				$lists['menuitems'] = JHTML::_('select.genericlist',  $menuitems, 'menuitem', 'class="inputbox" size="1" onchange="changeMenuItem();" style="display:none;"', 'id', 'title', $mainmenu->itemid . "|" . $mainmenu->link);
				$lists['menuitems'] = "<div><b>".JText::_('SINGLE_MENU_ITEM'). "</b> - " . $menuitems[0]->title . "</div>";	
			} else {
				$lists['menuitems'] = "<div><b>".JText::_('NO_MENU_ITEMS_FOUND')."</b></div>";	
			}
		}

		$types = array();
		$types[] = JHTML::_('select.option', '7', JText::_('IT_LINK'), 'id', 'title');
		for ($i = 1 ; $i < 11 ; $i++)
		{
			if ($i == 7) continue;
			$types[] = JHTML::_('select.option', $i, $this->ItemType($i), 'id', 'title');
		}
				
		$lists['types'] = JHTML::_('select.genericlist',  $types, 'itemtype', 'class="inputbox" size="1" ', 'id', 'title', $mainmenu->itemtype);
		

		parent::display($tpl);
	}
	
	function ItemType($id)
	{
		switch($id)
		{
		case 1:
			return JText::_("IT_KB");
		case 2:
			return JText::_("IT_FAQS");
		case 3:
			return JText::_("IT_TEST");
		case 4: 
			return JText::_("IT_NEW_TICKET");
		case 5:
			return JText::_("IT_VIEW_TICKET");
		case 6:
			return JText::_("IT_ANNOUNCE");
		case 7: 
			return JText::_("IT_LINK");
		case 8:
			return JText::_("IT_GLOSSARY");
		case 9:
			return JText::_("IT_ADMIN");
		case 10:
			return JText::_("IT_GROUPS");		
		}		
		return "Unknown";
	}
}


