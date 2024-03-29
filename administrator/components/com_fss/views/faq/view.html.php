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



class FsssViewFaq extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$faq		=& $this->get('Data');
		$isNew		= ($faq->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("FAQ").': <small><small>[ ' . $text.' ]</small></small>', 'fss_faqs' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		$this->assignRef('faq',		$faq);

		$query = 'SELECT id, title' .
				' FROM #__fss_faq_cat' .
				' ORDER BY ordering';
		$db	= & JFactory::getDBO();
		$db->setQuery($query);

		$sections = $db->loadObjectList();

		if (count($sections) < 1)
		{
			$link = FSSRoute::x('index.php?option=com_fss&view=faqs',false);
			$mainframe->redirect($link,"You must create a FAQ category first");
			return;
					
		}
		
		if ($faq->id > 0)
		{
			$qry = "SELECT * FROM #__fss_faq_tags WHERE faq_id = ".FSSJ3Helper::getEscaped($db, $faq->id)." ORDER BY tag";
			$db->setQuery($qry);
			$this->tags = $db->loadObjectList();
		} else {
			$this->tags = array();	
		}
		
		$lists['catid'] = JHTML::_('select.genericlist',  $sections, 'faq_cat_id', 'class="inputbox" size="1" ', 'id', 'title', intval($faq->faq_cat_id));

		$this->assignRef('lists', $lists);

		parent::display($tpl);
	}
}


