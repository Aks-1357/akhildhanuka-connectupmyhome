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
jimport('joomla.utilities.date');


class FsssViewlistusers extends JViewLegacy
{
  
    function display($tpl = null)
    {
		JToolBarHelper::title( JText::_( 'List Users' ), 'fss_groups' );
        JToolBarHelper::editList();
		JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

        $lists = $this->get('Lists');

		$document =& JFactory::getDocument();
		//JHTML::_( 'behavior.mootools' );
 		$document->addStyleSheet( JURI::base() . 'components/com_fsj_main/assets/slimbox/slimbox.css' );
		$document->addScript( JURI::base() .'components/com_fsj_main/assets/slimbox/slimbox.js');

	
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
		$lists['gid'] = JHTML::_('select.genericlist',  $filter, 'gid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'name', $lists['gid']);


		$this->assignRef( 'lists', $lists );

        $this->assignRef( 'data', $this->get('Data') );
        $this->assignRef( 'pagination', $this->get('Pagination'));

        parent::display($tpl);
    }
}


