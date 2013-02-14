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



class FsssViewTicketgroup extends JViewLegacy
{

	function display($tpl = null)
	{
		$ticketgroup		=& $this->get('Data');
		$isNew		= ($ticketgroup->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("TICKET_GROUP").': <small><small>[ ' . $text.' ]</small></small>', 'fss_groups' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();
		$db	= & JFactory::getDBO();
		
		
		$idents = array();
		$idents[] = JHTML::_('select.option', '0', JText::_("VIEW_NONE"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '1', JText::_("VIEW"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '2', JText::_("VIEW_REPLY"), 'id', 'title');			
		$idents[] = JHTML::_('select.option', '3', JText::_("VIEW_REPLY_CLOSE"), 'id', 'title');			
		$this->allsee = JHTML::_('select.genericlist',  $idents, 'allsee', ' class="inputbox" size="1"', 'id', 'title', $ticketgroup->allsee);

		$this->assignRef('ticketgroup',		$ticketgroup);


		$this->allprod = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
				'size' => "1", 
				'onclick' => "DoAllProdChange();"),
			 intval($ticketgroup->allprods));

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_ticket_group_prod WHERE group_id = " . FSSJ3Helper::getEscaped($db, $ticketgroup->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$ticketgroup->allprods);
		
		$prodcheck = "";
		foreach($products as $product)
		{
			$checked = false;
			if (array_key_exists($product->id,$selprod))
			{
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' checked />" . $product->title . "<br>";
			} else {
				$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' />" . $product->title . "<br>";
			}
		}
		$this->products = $prodcheck;
	
		parent::display($tpl);
	}
}


