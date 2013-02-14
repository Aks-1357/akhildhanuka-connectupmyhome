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

class FsssViewKbcat extends JViewLegacy
{

	function display($tpl = null)
	{
		$kbcat		=& $this->get('Data');
		$isNew		= ($kbcat->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("KNOWLEDGE_BASE_CATEGORY").': <small><small>[ ' . $text.' ]</small></small>', 'fss_categories' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		$db	= & JFactory::getDBO();

		$this->assignRef('kbcat',		$kbcat);
		
		$path = JPATH_SITE.DS.'images'.DS.'fss'.DS.'kbcats';

		if (!file_exists($path))
			mkdir($path,0777,true);
		
		$files = JFolder::files($path,'.png$');
		
		$sections = array();
		$sections[] = JHTML::_('select.option', '', JText::_("NO_IMAGE"), 'id', 'title');
		foreach ($files as $file)
		{
			$sections[] = JHTML::_('select.option', $file, $file, 'id', 'title');
		}
		
		$lists['images'] = JHTML::_('select.genericlist',  $sections, 'image', 'class="inputbox" size="1" ', 'id', 'title', $kbcat->image);

		$query = 'SELECT id, title' .
				' FROM #__fss_kb_cat';
				
		if ($kbcat->id)
			$query .= " WHERE id != '".FSSJ3Helper::getEscaped($db, $kbcat->id)."' ";
			
		$query .= ' ORDER BY ordering';
		
		$db->setQuery($query);
		$sections = array();
		$sections[] = JHTML::_('select.option', '0', JText::_("NO_PARENT_CATEGORY"), 'id', 'title');
		$sections = array_merge($sections, $db->loadObjectList());

		$lists['parcatid'] = JHTML::_('select.genericlist',  $sections, 'parcatid', 'class="inputbox" size="1" ', 'id', 'title', intval($kbcat->parcatid));

		$this->assignRef('lists', $lists);

		parent::display($tpl);
	}
}


