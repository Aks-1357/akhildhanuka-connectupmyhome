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
jimport( 'joomla.mail.helper' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'paginationex.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');

//JHTML::_('behavior.mootools');
	
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');

class FssViewTest extends JViewLegacy
{
	var $product = null;

    function display($tpl = null)
    {
		$document =& JFactory::getDocument();
		if (FSS_Helper::Is16())
			JHtml::_('behavior.framework');

		$mainframe = JFactory::getApplication();
		JHTML::_('behavior.modal', 'a.modal');
		//JHTML::_('behavior.mootools');

		$user = JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__fss_user WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($query);
		$this->_permissions = $db->loadAssoc();
		$this->params =& FSS_Settings::GetViewSettingsObj('test');
		$this->test_show_prod_mode = $this->params->get('test_show_prod_mode','accordian');
		$this->test_always_prod_select = $this->params->get('test_always_prod_select','0');
		$layout = JRequest::getVar('layout','');
			
		$this->prodid = JRequest::getVar('prodid');
		if ($this->prodid == "")
			$this->prodid = -1;
		
		$this->products = $this->get('Products');
		//print_p($this->products);
		if (count($this->products) == 0)
			$this->prodid = 0;
		
		$this->comments = new FSS_Comments("test",$this->prodid);
		if ($this->prodid == -1)
			$this->comments->opt_show_posted_message_only = 1;
			
		if ($layout == "create")
		{
			$this->setupCommentsCreate();	
		}
			
		if ($this->comments->Process())
			return;
			
		if ($layout == "create")
			return $this->displayCreate();
			
		if ($this->prodid != -1)
		{
			return $this->displaySingleProduct();	
		}

		return $this->displayAllProducts();
		
 	}
	
	function setupCommentsCreate()
	{
		$this->comments->opt_display = 0;
		$this->comments->comments_hide_add = 0;
		$this->comments->opt_show_form_after_post = 1;
		$this->comments->opt_show_posted_message_only = 1;
	}
	
	function displayCreate()
	{
		$this->tmpl = JRequest::getVar('tmpl','');
		parent::display();	
	}
	
	function displaySingleProduct()
	{
		$this->product = $this->get('Product');
		$this->products = $this->get('Products');	
		
		FSS_Helper::TrSingle($this->product);
 		FSS_Helper::Tr($this->products);
		
        $mainframe = JFactory::getApplication();
		$pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'test' )))	
			$pathway->addItem(JText::_('TESTIMONIALS'), FSSRoute::x( 'index.php?option=com_fss&view=test' ) );
        $pathway->addItem($this->product['title']);
		
		// no product then general testimonials
		if (!$this->product && count($this->products) > 0)
		{
			$this->product = array();
			$this->product['title'] = JText::_('GENERAL_TESTIMONIALS');	
			$this->product['id'] = 0;
			$this->product['description'] = '';
			$this->product['image'] = '/components/com_fss/assets/images/generaltests.png';
		}
		
		if ($this->test_always_prod_select)
		{
			$this->comments->show_item_select = 1;
		} else {
			$this->comments->show_item_select = 0;
		}
		
		$this->comments->PerPage(FSS_Settings::Get('test_comments_per_page'));
		
		parent::display("single");
	}
	
	function displayAllProducts()
	{
		$this->products = $this->get('Products');
		if (!is_array($this->products))
			$this->products = array();
 		FSS_Helper::Tr($this->products);
		
		$this->showresult = 1;
		
        $mainframe = JFactory::getApplication();
        $pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'test' )))	
			$pathway->addItem(JText::_('TESTIMONIALS'), FSSRoute::x( 'index.php?option=com_fss&view=test' ) );
 
		if (FSS_Settings::get('test_allow_no_product'))
		{
			$noproduct = array();
			$noproduct['id'] = 0;
			$noproduct['title'] = JText::_('GENERAL_TESTIMONIALS');
			$noproduct['description'] = '';
			$noproduct['image'] = '/components/com_fss/assets/images/generaltests.png';
			$this->products = array_merge(array($noproduct), $this->products);
		}
		
		if ($this->test_show_prod_mode != "list")
		{
			$idlist = array();
			if (count($this->products) > 0)
			{
				foreach($this->products as &$prod) 
				{
					$prod['comments'] = array();
					$idlist[] = $prod['id'];	
				}
			}
			
			// not in normal list mode, get comments for each product
			
			$this->comments->itemid = $idlist;
			
			$this->comments->GetComments();
						
			foreach($this->comments->_data as &$data)
			{
				if ($data['itemid'] > 0)
					$this->products[$data['itemid']]['comments'][] =& $data;
			}
		}
		
		parent::display();
	}
}

