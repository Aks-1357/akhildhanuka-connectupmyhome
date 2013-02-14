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

require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');

class FssViewFaq extends JViewLegacy
{
    function display($tpl = null)
    {
		$document =& JFactory::getDocument();
		if (FSS_Helper::Is16())
			JHtml::_('behavior.framework');
	
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.'faqs.php');
		$this->content = new FSS_ContentEdit_FAQs();
		$this->content->Init();
		
		$model =& $this->getModel();
		$model->content = $this->content;
		
        $mainframe = JFactory::getApplication();
        
        $faqid = JRequest::getVar('faqid', 0, '', 'int'); 
       
        $aparams = FSS_Settings::GetViewSettingsObj('faqs');
        
		JHTML::_('behavior.tooltip');
        JHTML::_('behavior.modal', 'a.modal');

        if ($faqid > 0)
        {
            $tmpl = JRequest::getVar('tmpl'); 
            $this->assignRef( 'tmpl', $tmpl );
            $this->setLayout("faq");
            $faq =& $this->get("Faq");
            $this->assignRef( 'faq', $faq );
            
			if (!$this->faq)
			{
				return JError::raiseWarning(404, JText::_('FAQ NOT FOUND'));	
			}
			
            $pathway =& $mainframe->getPathway();
            if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'faq' )))	
				$pathway->addItem(JText::_('FREQUENTLY_ASKED_QUESTIONS'), FSSRoute::x( 'index.php?option=com_fss&view=faq' ) );
			$pathway->addItem($faq['title'], FSSRoute::x( '&limitstart=&layout=&faqid=&catid=' . $faq['faq_cat_id'] ) );
            $pathway->addItem($faq['question']);
            
			if (FSS_Settings::get('faq_use_content_plugins'))
			{
				// apply plugins to article body
				$dispatcher	=& JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				$art->text = $faq['answer'];
				$art->noglossary = 1;
				if (FSS_Helper::Is16())
				{
					$results = $dispatcher->trigger('onContentPrepare', array('com_fss.faq', &$art, &$this->params, 0));
				} else {
					$aparams = new stdClass();
					$results = $dispatcher->trigger('onPrepareContent', array (& $art, & $aparams, 0));
				} 
				$faq['answer'] = $art->text;
			}

			// load tags
			$db	= & JFactory::getDBO();
			
			$qry = "SELECT * FROM #__fss_faq_tags WHERE faq_id IN (" . FSSJ3Helper::getEscaped($db, $faqid) .") GROUP BY tag ORDER BY tag";
			$db->setQuery($qry);
			$rows = $db->loadObjectList();
			
			$this->tags = array();
			foreach ($rows as &$row)
			{
				$id = $row->faq_id;
			
				$this->tags[] = "<a href='" . FSSRoute::_('index.php?option=com_fss&view=faq&tag=' . urlencode($row->tag) . '&Itemid=' . JRequest::getVar('Itemid')) . "'>{$row->tag}</a>";
			}		
		    //$document =& JFactory::getDocument();
		    //$document->setTitle(JText::_("FAQS") . ' - ' . $faq['title']);
            
            parent::display();    
            return;
        }  
 		
        $pathway =& $mainframe->getPathway();
        if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'faq' )))	
			$pathway->addItem(JText::_('FREQUENTLY_ASKED_QUESTIONS'), FSSRoute::x( 'index.php?option=com_fss&view=faq' ) );
        	
		$always_show_cats = $aparams->get('always_show_cats',0);
        $always_show_faqs = $aparams->get('always_show_faqs',0);
        $hide_allfaqs = $aparams->get('hide_allfaqs',0);
        $show_featured = $aparams->get('show_featured',0);
        $hide_tags = $aparams->get('hide_tags',0);
        $hide_search = $aparams->get('hide_search',0);
        $view_mode = $aparams->get('view_mode','questionwithpopup');
        $view_mode_cat = $aparams->get('view_mode_cat','list');
        $view_mode_incat = $aparams->get('view_mode_incat','list');
        $enable_pages = $aparams->get('enable_pages',1);
        $num_cat_colums = $aparams->get('num_cat_colums',1);
        if ($num_cat_colums < 1 && !$num_cat_colums) $num_cat_colums = 1;
        if ($view_mode_cat != "list") $num_cat_colums = 1;
    
    	$catlist =& $this->get("CatList");
        $this->assignRef( 'catlist', $catlist );

        $search =& $this->get("Search");
        $this->assignRef( 'search', $search );
        
        $curcattitle =& $this->get("CurCatTitle");
        $this->assignRef( 'curcattitle', $curcattitle );

        $curcatimage =& $this->get("CurCatImage");
        $this->assignRef( 'curcatimage', $curcatimage );

        $curcatdesc =& $this->get("CurCatDesc");
        $this->assignRef( 'curcatdesc', $curcatdesc );

        $curcatid = $this->get("CurCatID");

        // Get data from the model
        
		if ($curcatid == -4)
			return $this->listTags();

		if ($curcatid == -5)
		{
			$curcattitle = "Featured FAQs";
			$curcatimage = "/components/com_fss/assets/images/featured.png";
		}
		
		$pagination =& $this->get('Pagination');
		$model = $this->getModel();
		
		$search = $model->_search;
		if ($search || $curcatid > 0 || JRequest::getVar('catid') != "" || JRequest::getVar('tag') != "")
			$view_mode_cat = "";
				
		if ($view_mode_cat == "inline" || $view_mode_cat == "accordian")
        {
        	$alldata =& $this->get("AllData");
			
			if (FSS_Settings::get('faq_use_content_plugins_list'))
			{
				// apply plugins to article body
				$dispatcher	=& JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				foreach ($alldata as &$item)
				{
					$art->text = $item['answer'];
					$art->noglossary = 1;
					if (FSS_Helper::Is16())
					{
						//$aparams = JParameter(null);
						$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.faq', & $art, &$this->params, 0));
					} else {
						$aparams = new stdClass();
						$results = $dispatcher->trigger('onPrepareContent', array (& $art, & $aparams, 0));
					} 
					$item['answer'] = $art->text;
				}
			}

			foreach ($catlist as &$cat)
        	{
        		$catid = $cat['id'];
        		foreach ($alldata as &$faq)
        		{
        			if ($faq['faq_cat_id'] == $catid)
        			{
        				$cat['faqs'][] = &$faq;
					}	
				}
			}  	
			
			// inline mode, so if we have featured faqs listed, load the data for those
			$this->featured_faqs = $this->get('FeaturedFaqs');
					
		} else {
			
			$items =& $this->get('Data');

			if (FSS_Settings::get('faq_use_content_plugins_list'))
			{
				// apply plugins to article body
				$dispatcher	=& JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				foreach ($items as &$item)
				{
					$art->text = $item['answer'];
					$art->noglossary = 1;
					if (FSS_Helper::Is16())
					{
						//$aparams = JParameter(null);
						$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.faq', & $art, &$this->params, 0));
					} else {
						$aparams = new stdClass();
						$results = $dispatcher->trigger('onPrepareContent', array (& $art, & $aparams, 0));
					} 
					$item['answer'] = $art->text;
				}
			}
		}

        // push data into the template
        $this->assignRef('items', $items);
        $this->assignRef('pagination', $pagination);

        $showfaqs = true;
        $showcats = true;
        
       
        if (JRequest::getVar('tag') != "") {
			
			// got tag selected
			$showfaqs = true;
			$showcats = false;
			$curcatid = -2;
			$pathway =& $mainframe->getPathway();
            $pathway->addItem(JText::_("TAGS"), FSSRoute::_('index.php?option=com_fss&view=faq&catid=-4&Itemid=' . JRequest::getVar('Itemid')));
            $pathway->addItem(JRequest::getVar('tag'));
			$curcattitle = JRequest::getVar('tag');
		// do we have a category specified???
		} else if (JRequest::getVar('catid', '') == '' && JRequest::getVar('search', '') == '')
        {
            // no cat specified
            if (!$always_show_faqs)
            {
                $showfaqs = false;
                $curcatid = -2;
            } else {
                $pathway =& $mainframe->getPathway();
                $pathway->addItem($curcattitle);
          }
        } else {
            // got a cat specced
            $pathway =& $mainframe->getPathway();
            $pathway->addItem($curcattitle);

            if (!$always_show_cats)
            {
                $showcats = false;   
            }
        }

		// load tags
		$faqids = array();
		if ($this->items && is_array($this->items))
			foreach ($this->items as &$item) 
				$faqids[] = FSSJ3Helper::getEscaped($db,  $item['id']);
		$db	= & JFactory::getDBO();
		
		$this->tags = array();
		if (count($faqids) > 0)
		{
			$qry = "SELECT * FROM #__fss_faq_tags WHERE faq_id IN (" . implode(", ",$faqids) .") GROUP BY tag ORDER BY tag";
			$db->setQuery($qry);
			$rows = $db->loadObjectList();
			
			foreach ($rows as &$row)
			{
				$id = $row->faq_id;
			
				if (!array_key_exists($id, $this->tags))
					$this->tags[$id] = array();
			
				$this->tags[$id][] = "<a href='" . FSSRoute::_('index.php?option=com_fss&view=faq&tag=' . urlencode($row->tag) . '&Itemid=' . JRequest::getVar('Itemid')) . "'>{$row->tag}</a>";
			}
		}
		
		// hide tags if none have been set
		$qry = "SELECT count(*) as cnt FROM #__fss_faq_tags";
		$db->setQuery($qry);
		$row = $db->loadObject();
		if ($row->cnt == 0)
			$hide_tags = true;
		
		$this->assign( 'curcatid', $curcatid );
        
		$this->assign('showcats', $showcats);
        $this->assign('showfaqs', $showfaqs);
        $this->assign('hide_allfaqs', $hide_allfaqs);
        $this->assign('show_featured', $show_featured);
        $this->assign('hide_tags', $hide_tags);
        $this->assign('hide_search', $hide_search);
        $this->assign('view_mode', $view_mode);
        $this->assign('num_cat_colums', $num_cat_colums);
		$this->assign('view_mode_cat', $view_mode_cat);
		$this->assign('view_mode_incat', $view_mode_incat);
		$this->assign('enable_pages', $enable_pages);
		
        parent::display($tpl);
    }
	
	function listTags()
	{
	    $mainframe = JFactory::getApplication();
        $aparams = FSS_Settings::GetViewSettingsObj('faqs');
  
		$pathway =& $mainframe->getPathway();
        $pathway->addItem(JText::_("TAGS"));
		
		$qry = "SELECT tag FROM #__fss_faq_tags GROUP BY tag ORDER BY tag";
		$db	= & JFactory::getDBO();
		$db->setQuery($qry);
		
		$this->tags = $db->loadObjectList();
  
        parent::display("tags");		
	}	
}

