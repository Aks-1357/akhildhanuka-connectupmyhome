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



class FsssViewKbarts extends JViewLegacy
{
  
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("KNOWLEDGE_BASE_ARTICLES"), 'fss_kb' );
		JToolBarHelper::custom('resetviews','resetviews', 'resetviews', 'KB_RESET_VIEWS', false);
		JToolBarHelper::custom('resetrating','resetrating', 'resetrating', 'KB_RESET_RATING', false);
		JToolBarHelper::spacer();
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

		$lists = $this->get('Lists');
        $this->assignRef( 'data', $this->get('Data') );
        $this->assignRef( 'pagination', $this->get('Pagination'));

		$query = 'SELECT id, title FROM #__fss_kb_cat ORDER BY ordering';

		$db	= & JFactory::getDBO();
		$categories[] = JHTML::_('select.option', '0', JText::_("SELECT_CATEGORY"), 'id', 'title');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$lists['cats'] = JHTML::_('select.genericlist',  $categories, 'kb_cat_id', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['kb_cat_id']);

		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', JText::_("IS_PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("UNPUBLISHED"), 'id', 'title');
		$lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['ispublished']);

		$query = 'SELECT id, title FROM #__fss_prod ORDER BY ordering';

		$db	= & JFactory::getDBO();
		$categories = array();
		$categories[] = JHTML::_('select.option', '0', JText::_("SELECT_PRODUCT"), 'id', 'title');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$lists['prods'] = JHTML::_('select.genericlist',  $categories, 'prod_id', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['prod_id']);

		$this->assignRef( 'lists', $lists );

		parent::display($tpl);
    }
}



