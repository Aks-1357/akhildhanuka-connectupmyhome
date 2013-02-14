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

class fsssViewticketemail extends JViewLegacy
{

	function display($tpl = null)
	{
		global $mainframe;

		$document =& JFactory::getDocument();
		//JHTML::_( 'behavior.mootools' );
 		$document->addStyleSheet( JURI::base() . 'components/com_fss/assets/slimbox/slimbox.css' );
		$document->addScript( JURI::base() .'components/com_fss/assets/slimbox/slimbox.js');

		$item		=& $this->get('Data');
		$isNew		= ($item->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'TICKET_EMAIL_ACCOUNT' ).': <small><small>[ ' . $text.' ]</small></small>', 'fss_emailaccounts' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		$db	= & JFactory::getDBO();

		$combo = array();
		$combo[] = JHTML::_('select.option', 'pop3', JText::_('POP3'), 'id', 'value');
		$combo[] = JHTML::_('select.option', 'imap', JText::_('IMAP'), 'id', 'value');
		$lists['type'] = JHTML::_('select.genericlist',  $combo, 'type', 'class="inputbox" size="1" ', 'id', 'value', $item->type);
			
		$combo = array();
		$combo[] = JHTML::_('select.option', 'markread', JText::_('Mark EMail as read'), 'id', 'value');
		$combo[] = JHTML::_('select.option', 'delete', JText::_('Delete EMail'), 'id', 'value');
		$lists['onimport'] = JHTML::_('select.genericlist',  $combo, 'onimport', 'class="inputbox" size="1" ', 'id', 'value', $item->onimport);
			
		$combo = array();
		$combo[] = JHTML::_('select.option', 'registered', JText::_('Registered Users Only'), 'id', 'value');
		$combo[] = JHTML::_('select.option', 'everyone', JText::_('Everyone'), 'id', 'value');
		$lists['newticketsfrom'] = JHTML::_('select.genericlist',  $combo, 'newticketsfrom', 'class="inputbox" size="1" ', 'id', 'value', $item->newticketsfrom);
			
		$query = 'SELECT id, title' .
			' FROM #__fss_prod' .
			' ORDER BY title';
		$db->setQuery($query);

		$sections_prod_id = $db->loadObjectList();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('No Product'), 'id', 'title');
		$sections_prod_id = array_merge($prods,$sections_prod_id);
		$lists['prod_id'] = JHTML::_('select.genericlist',  $sections_prod_id, 'prod_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->prod_id));
	
		$query = 'SELECT id, title' .
			' FROM #__fss_ticket_dept' .
			' ORDER BY title';
		$db->setQuery($query);

		$sections_dept_id = $db->loadObjectList();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('No Department'), 'id', 'title');
		$sections_dept_id = array_merge($prods,$sections_dept_id);
		$lists['dept_id'] = JHTML::_('select.genericlist',  $sections_dept_id, 'dept_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->dept_id));

		$query = 'SELECT id, title' .
			' FROM #__fss_ticket_cat' .
			' ORDER BY title';
		$db->setQuery($query);

		$sections_cat_id = $db->loadObjectList();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('No Category'), 'id', 'title');
		$sections_cat_id = array_merge($prods,$sections_cat_id);
		$lists['cat_id'] = JHTML::_('select.genericlist',  $sections_cat_id, 'cat_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->cat_id));
		$query = 'SELECT id, title' .
			' FROM #__fss_ticket_pri' .
			' ORDER BY id';
		$db->setQuery($query);

		$sections_pri_id = $db->loadObjectList();
		
		$lists['pri_id'] = JHTML::_('select.genericlist',  $sections_pri_id, 'pri_id', 'class="inputbox" size="1" ', 'id', 'title', intval($item->pri_id));
	
		$query = 'SELECT u.id, u.name' .
			' FROM #__fss_user as s LEFT JOIN #__users as u ON s.user_id = u.id WHERE support = 1 ORDER BY name';
	
		$db->setQuery($query);
		$sections_handler = $db->loadObjectList();
		$prods = array();
		$prods[] = JHTML::_('select.option', '', JText::_('Leave Unassigned'), 'id', 'name');
		$prods[] = JHTML::_('select.option', '-1', JText::_('Auto Assign'), 'id', 'name');
		$sections_handler = array_merge($prods,$sections_handler);
		$lists['handler'] = JHTML::_('select.genericlist',  $sections_handler, 'handler', 'class="inputbox" size="1" ', 'id', 'name', intval($item->handler));
	

		$this->assignRef('item',$item);
		$this->assignRef('lists', $lists);

		parent::display($tpl);
	}
}

