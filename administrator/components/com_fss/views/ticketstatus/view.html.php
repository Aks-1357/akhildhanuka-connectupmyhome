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

class FsssViewTicketstatus extends JViewLegacy
{

	function display($tpl = null)
	{
		$ticketstatus		=& $this->get('Data');
		$isNew		= ($ticketstatus->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("TICKET_STATUS").': <small><small>[ ' . $text.' ]</small></small>', 'fss_ticketstatuss' );
		if (FSS_Helper::Is16())
		{
			JToolBarHelper::custom('translate','translate', 'translate', 'Translate', false);
			JToolBarHelper::spacer();
		}
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();
		$db	= & JFactory::getDBO();

		$this->assignRef('lists', $lists);

		$this->assignRef('ticketstatus', $ticketstatus);

		parent::display($tpl);
	}
}


