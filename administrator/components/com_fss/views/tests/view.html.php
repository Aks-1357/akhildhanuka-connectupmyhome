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
require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php' );

class FsssViewTests extends JViewLegacy
{
 	function DoPublishComment($published)
	{
		$commentid = JRequest::getVar('commentid',0,'','int');   
		
		if (!$commentid)
			return;
			
		$db =& JFactory::getDBO();
		$qry = "UPDATE #__fss_comments SET published = $published WHERE id = '".FSSJ3Helper::getEscaped($db, $commentid)."'";
		$db->SetQuery($qry);
		$db->Query();
		
		echo $qry;	
		exit;
		return true;	
	}
 
    function display($tpl = null)
    {
		$task = JRequest::getVar('task');
		if ($task == "removecomment")
			return $this->DoPublishComment(2);

		if ($task == "approvecomment")
			return $this->DoPublishComment(1);
				
        JToolBarHelper::title( JText::_("MODERATION"), 'fss_moderate' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
		FSSAdminHelper::DoSubToolbar();

        $this->assignRef( 'lists', $this->get('Lists') );
        $this->assignRef( 'data', $this->get('Data') );
        $this->assignRef( 'pagination', $this->get('Pagination'));

		/*$query = 'SELECT id, title FROM #__fss_prod ORDER BY title';

		$db	= & JFactory::getDBO();
		$categories[] = JHTML::_('select.option', '0', JText::_("SELECT_PRODUCT"), 'id', 'title');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$this->lists['prods'] = JHTML::_('select.genericlist',  $categories, 'prod_id', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->lists['prod_id']);*/
	
		$this->ident_to_name = array();
//##NOT_TEST_START##
		$this->ident_to_name[1] = "kb";
		$this->ident_to_name[4] = "announce";
//##NOT_TEST_END##
		$this->ident_to_name[5] = "test";
		$this->comment_objs = array();
		$comment_itemids = array();
	
		$this->ident = JRequest::getVar('ident','');		

		$sections = array();
		$sections[] = JHTML::_('select.option', '0', "-- ".JText::_("ALL_SECTIONS") . " --", 'id', 'title');
		foreach($this->ident_to_name as $ident => $name)
		{
			$this->comment_objs[$ident] = new FSS_Comments($name);		
			$sections[] = JHTML::_('select.option', $ident, $this->comment_objs[$ident]->handler->descriptions, 'id', 'title');
		}
		$this->lists['sections'] = JHTML::_('select.genericlist',  $sections, 'ident', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->ident);


		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', "-- ".JText::_("MOD_STATUS") . " --", 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("AWAITING_MODERATION"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("ACCEPTED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '2', JText::_("DECLINED"), 'id', 'title');
		$this->lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->lists['ispublished']);


		foreach ($this->data as &$data)
		{
			$ident = $data->ident;
			$comment_itemids[$ident][$data->itemid] = $data->itemid;
		}
	
		$db	=& JFactory::getDBO();
		
		foreach ($this->comment_objs as $ident => &$obj)
		{
			if (array_key_exists($ident, $comment_itemids))
			{
				$idlist = $comment_itemids[$ident];
				$obj->handler->GetItemData($idlist);
			}
		}
		
        parent::display($tpl);
    }
}



