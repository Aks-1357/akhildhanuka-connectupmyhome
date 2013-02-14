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

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');

class FsssViewFields extends JViewLegacy
{
 
    function display($tpl = null)
    {
		$this->comments = new FSS_Comments(null,null);
		
        JToolBarHelper::title( JText::_("FIELDS"), 'fss_customfields' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		FSSAdminHelper::DoSubToolbar();

		$lists = $this->get('Lists');

        $this->assignRef( 'data', $this->get('Data') );
        $this->assignRef( 'pagination', $this->get('Pagination'));
		
		$idents = array();
		$idents[] = JHTML::_('select.option', '-1', JText::_("ALL"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '0', JText::_("TICKETS"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '999', JText::_("ALL_COMMENTS"), 'id', 'title');
		$db	= & JFactory::getDBO();
		foreach($this->comments->handlers as $handler)
			$idents[] = JHTML::_('select.option', $handler->ident, $handler->GetLongDesc(), 'id', 'title');
				
		$lists['ident'] = JHTML::_('select.genericlist',  $idents, 'ident', ' class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $lists['ident']);
	
        $this->assignRef( 'lists', $lists);
        
		parent::display($tpl);
    }
	
	function GetIdentLabel($sectionid)
	{
		if ($sectionid < 1)
			return JText::_("TICKETS");		
			
		if ($sectionid == 999)
			return JText::_("ALL_COMMENTS");
			
		if (array_key_exists($sectionid,$this->comments->handlers))
			return $this->comments->handlers[$sectionid]->GetLongDesc();
			
		return "Unknown";
	}
	
	function GetTypeLabel($type, &$row)
	{
		if ($type == "checkbox")	
			return JText::_("CHECKBOX");
		if ($type == "text")	
			return JText::_("TEXT_ENTRY");
		if ($type == "radio")	
			return JText::_("RADIO_GROUP");
		if ($type == "combo")	
			return JText::_("COMBO_BOX");
		if ($type == "area")	
			return JText::_("TEXT_AREA");
		if ($type == "plugin")
		{
			$plugin = FSSCF::get_plugin_from_row($row);
			return JText::_("PLUGIN") . " - " . $plugin->name;
		}
		return $type;		
	}
}