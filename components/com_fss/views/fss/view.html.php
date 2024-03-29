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
jimport('joomla.utilities.date');

class FssViewFss extends JViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
		$option = JRequest::getVar('option');
		if ($option == "com_fst")
		{
			$link = FSSRoute::_('index.php?option=com_fss&view=test',false);
		} else if ($option == "com_fsf")
		{
			$link = FSSRoute::_('index.php?option=com_fss&view=faq',false);
		} else {
			$link = FSSRoute::_('index.php?option=com_fss&view=main',false);
		}
		$mainframe->redirect($link);
    }
}

