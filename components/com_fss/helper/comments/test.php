<?php

class FSS_Comments_Handler_Test extends FSS_Comments_Handler
{
	var $ident = 5;	
	
	function __construct($parent) 
	{
		$this->comments = $parent;
		$this->comments->use_comments = 1;
		$this->comments->showheader = 0;
		$this->comments->show_item_select = 1; 
		
		$this->comments->use_email = FSS_Settings::get('test_use_email');//FSJ_Settings::GetComponentSetting( fsj_get_com(), 'comments_email', 1 );
		$this->comments->use_website = FSS_Settings::get('test_use_website'); //FSJ_Settings::GetComponentSetting( fsj_get_com(), 'comments_website', 1 );

		//$this->comments->opt_display = 0;
		
		$this->comments->add_a_comment = JText::_('ADD_A_TESTIMONIAL');
		$this->comments->post_comment = JText::_('POST_TESTIMONIAL');

		$this->email_title = "A Testimonial";
		$this->email_article_type = JText::_('PRODUCT');
		$this->description = JText::_('TESTIMONIALS');	
		$this->descriptions = JText::_('TESTIMONIALS');
		$this->long_desc = JText::_('TESTIMONIALS');
		
		$this->article_link = "index.php?option=com_fss&view=test&prodid={id}";
		
		if (FSS_Settings::get('test_allow_no_product'))
		{
			$this->item_select_default = JText::_('GENERAL_TESTIMONIAL');
		} else {
			$this->item_select_default = JText::_('SELECT_PRODUCT');
			$this->item_select_must_have = 1;
		}
		
		if (FSS_Settings::get('test_who_can_add') == "anyone")
		{
			$this->comments->can_add = 1;
		} else if (FSS_Settings::get('test_who_can_add') == "moderators")
		{
			if ($this->comments->_permissions['mod_kb'] == 0)
			{
				$this->comments->can_add = 0;	
			}
		} else if (FSS_Settings::get('test_who_can_add') == "registered")
		{
			if ($this->comments->_permissions['userid'] == 0)
			{
				$this->comments->can_add = 0;	
			}
		}
		
		// set up moderation
		$commod = FSS_Settings::get('test_moderate');
		$this->comments->moderate = 0;
		
		//print_p($this->comments->_permissions);
		if ($commod == "all")
		{
			$this->comments->moderate = 1;
		} elseif ($commod == "guests")
		{
			if ($this->comments->_permissions['userid'] == 0)
				$this->comments->moderate = 1;
		} elseif ($commod == "registered")
		{
			if ($this->comments->_permissions['mod_kb'] == 0)
				$this->comments->moderate = 1;
		}
		
		$this->comments->dest_email = FSS_Settings::get( 'test_email_on_submit' );
		$this->table = "#__fss_prod";
		$this->has_published = 1;
		$this->field_title = "title";
		$this->field_id = "id";
	}

	
	function GetItemTitle($itemid)
	{
		if ($itemid == 0)
			return JText::_("GENERAL_TESTIMONIALS");

		return parent::GetItemTitle($itemid);
	}
	
	function GetItemData($itemids = null)
	{
		$db =& JFactory::getDBO();
		$qry = "SELECT {$this->field_id}, {$this->field_title}, translation FROM {$this->table} WHERE intest = 1 AND published = 1";
		if ($itemids)
		{
			$ids = array();
			foreach ($itemids as $id)
				$ids[] = FSSJ3Helper::getEscaped($db, $id);
			$qry .= " AND {$this->field_id} IN (" . implode(", ",$ids) . ")";
		}
		$db->setQuery($qry);
		$this->itemdata = $db->loadAssocList($this->field_id);		
		
		FSS_Helper::Tr($this->itemdata);
	}
}