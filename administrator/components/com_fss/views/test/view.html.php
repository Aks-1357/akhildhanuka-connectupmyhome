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
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php' );



class FsssViewTest extends JViewLegacy
{

	function display($tpl = null)
	{
		$test		=& $this->get('Data');
		$isNew		= ($test->id < 1);
		
		$this->ident_to_name = array();
		
//##NOT_TEST_START##
		$this->ident_to_name[1] = "kb";
		$this->ident_to_name[4] = "announce";
//##NOT_TEST_END##
		$this->ident_to_name[5] = "test";
		
		$task = JRequest::getVar('task');
		if ($task == "ident")
			return $this->ShowItemList();

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("MODERATION").': <small><small>[ ' . $text.' ]</small></small>', 'fss_moderate' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		JToolBarHelper::divider();
		JToolBarHelper::help("",false,"http://www.freestyle-joomla.com/comhelp/fss/" . JRequest::getVar('view'));

		$this->assignRef('test',		$test);

		$this->comment_objs = array();
		$comment_itemids = array();
	
		$sections = array();
//##NOT_TEST_START##
		$sections[] = JHTML::_('select.option', '0', "-- ".JText::_("CHOOSE_SECTION") . " --", 'id', 'title');
//##NOT_TEST_END##
		foreach($this->ident_to_name as $ident => $name)
		{
			$this->comment_objs[$ident] = new FSS_Comments($name);		
			$sections[] = JHTML::_('select.option', $ident, $this->comment_objs[$ident]->handler->descriptions, 'id', 'title');
			
			if ($ident == $test->ident)
				$this->lists['comments'] = $this->comment_objs[$ident];
		}
		$this->lists['sections'] = JHTML::_('select.genericlist',  $sections, 'ident', 'class="inputbox" size="1" onchange="change_section();"', 'id', 'title', $test->ident);

		if ($test->ident)
		{
			$this->lists['itemid'] = $this->GetSelect($this->lists['comments']->handler, $test->ident, $test->itemid);
		} else {
			$this->lists['itemid'] = JText::_("PLEASE_SELECT_A_SECTION_FIRST");	
		}
		
		$pulished = array();
		$pulished[] = JHTML::_('select.option', '-1', "-- ".JText::_("MOD_STATUS") . " --", 'id', 'title');
		$pulished[] = JHTML::_('select.option', '0', JText::_("AWAITING_MODERATION"), 'id', 'title');
		$pulished[] = JHTML::_('select.option', '1', JText::_("ACCEPTED"), 'id', 'title');
		$pulished[] = JHTML::_('select.option', '2', JText::_("DECLINED"), 'id', 'title');
		$this->lists['published'] = JHTML::_('select.genericlist',  $pulished, 'published', 'class="inputbox" size="1"', 'id', 'title', $test->published);

		
		parent::display($tpl);
	}
	
	function ShowItemList()
	{
		$ident = JRequest::getVar('ident','');
		$name = $this->ident_to_name[$ident];
		$comments = new FSS_Comments($name);
		
		$handler =& $comments->handler;
		
		$output['select'] = $this->GetSelect($handler, $ident, 0);
		$output['title'] = $handler->email_article_type;
		ob_clean();
		
		echo json_encode($output);
		
		exit;
	}
	
	function GetSelect(&$handler, $ident, $itemid)
	{
				
		$db = & JFactory::getDBO();
		$qry = "SELECT ".FSSJ3Helper::getEscaped($db, $handler->field_title).", ".FSSJ3Helper::getEscaped($db, $handler->field_id)." FROM ".FSSJ3Helper::getEscaped($db, $handler->table)." ORDER BY ".FSSJ3Helper::getEscaped($db, $handler->field_title);
		$db->setQuery($qry);
		$items = $db->loadObjectList();
		if ($ident == 5)
		{
			$newitems[] = 	JHTML::_('select.option', '0', JText::_("GENERAL_TESTIMONIALS"), $handler->field_id, $handler->field_title);
			$items = array_merge($newitems, $items);
		}		
		return JHTML::_('select.genericlist',  $items, 'itemid', 'class="inputbox" size="1"', $handler->field_id, $handler->field_title, $itemid);
	}
}


