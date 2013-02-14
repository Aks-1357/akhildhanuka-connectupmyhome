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

require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');


class FssViewKb extends JViewLegacy
{
    function display($tpl = null)
    {
		$mainframe = JFactory::getApplication();

		$document =& JFactory::getDocument();
		if (FSS_Helper::Is16())
			JHtml::_('behavior.framework');
	
  		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.'kb.php');
		$this->content = new FSS_ContentEdit_KB();
		$this->content->Init();
		
		$this->pagetitle = "";
		
		$model =& $this->getModel();
		$model->content = $this->content;
             
		JHTML::_('behavior.modal', 'a.modal');
		//JHTML::_('behavior.mootools');
		
		$this->assign('search','');
		
		$what = JRequest::getVar('what', '', '', 'string');
		
		// basic permissions on article stats stop people seeing stats when disabled
		if (!FSS_Settings::get( 'kb_show_views' ))
			$what = "";
		if ($what == "recent" && !FSS_Settings::get( 'kb_show_recent' ))
			$what = "";
		if ($what == "viewed" && !FSS_Settings::get( 'kb_show_viewed' ))
			$what = "";
		if ($what == "rated" && !FSS_Settings::get( 'kb_show_rated' ))
			$what = "";


		$user = JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = "SELECT * FROM #__fss_user WHERE user_id = '".FSSJ3Helper::getEscaped($db, $userid)."'";
		$db->setQuery($query);
		$this->_permissions = $db->loadAssoc();

		if ($what != "")
			return $this->showWhat();
		
		$search = JRequest::getVar('kbsearch', '', '', 'string');     
		if ($search != "")
			return $this->searchArticles();
		
		$search = JRequest::getVar('prodsearch', '', '', 'string');     
		if ($search != "")
			return $this->searchProducts();
		
        $fileid = JRequest::getVar('fileid', 0, '', 'int');            
        if ($fileid > 0)
        	return $this->downloadFile();       
		
        $kbartid = JRequest::getVar('kbartid', 0, '', 'int');            
        if ($kbartid > 0)
        	return $this->showArt();       
        
        $catid = JRequest::getVar('catid', 0, '', 'int');  
        if ($catid > 0)
       		return $this->showCat();       
        
        $prodid = JRequest::getVar('prodid', 0, '', 'int');            
        if ($prodid > 0)
       		return $this->showProd();       
		
		
		$this->showMain();
    	
    }
    
    function showCat()
    {
        $mainframe = JFactory::getApplication();

    	$aparams = FSS_Settings::GetViewSettingsObj('kb');
		
		$this->assign('view_mode_cat', $aparams->get('cat_cat_mode','normal'));
	    $this->assign('view_mode', $aparams->get('cat_cat_arts','normal'));
	    $this->assign('cat_art_pages', $aparams->get('cat_art_pages',0));
	    $this->assign('cat_search', $aparams->get('cat_search',0));
		
		$this->assignRef( 'arts', $this->get("Arts") );
	    $this->assignRef( 'cat', $this->get("Cat") );
		$this->assignRef( 'product', $this->get("Product") );
	    
		$this->assign('curcatid',0);
		
        $pathway =& $mainframe->getPathway();
        
		if ($this->product)
			$this->pagetitle = $this->product['title'] . " - ";
		$this->pagetitle .= $this->cat['title']; 
	    //$document =& JFactory::getDocument();
	    //$document->setTitle(JText::_("KNOWLEDGE_BASE"). ' - ' . $this->product['title'] . " - " . $this->cat['title']);
        
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::x( 'index.php?option=com_fss&view=kb' ) );

		$prodid = "";
        if ($this->product['title'])
        {
			$prodid = $this->product['id'];
            $pathway->addItem($this->product['title'], FSSRoute::x( '&catid=&prodid=' . $this->product['id'] ) );
		}
    
	    $cat = $this->cat;
		$max = 3;
		
		$selectedcatid = JRequest::getVar('catid');
		
		while ($cat['parcatid'] > 0 && $max-- > 0)
		{
			JRequest::setVar('catid', $cat['parcatid']);
			$cat = $this->get("Cat");
			$pathway->addItem($cat['title'], FSSRoute::x( "&kbartid=&prodid={$prodid}&catid={$cat['id']}" ) );
		}
		
		JRequest::setVar('catid', $selectedcatid);
		
        $pathway->addItem($this->cat['title']);

		$pagination =& $this->get('ArtPagination');
		$this->assignRef('pagination', $pagination);
		$this->assign('limit',$this->get("ArtLimit"));

		$this->assignRef('subcats',$this->get('SubCats'));
			
		$this->assignRef('cats',$this->get('CatsArts'));
		$this->main_cat_colums = 1;
		$this->hide_choose = 1;
		$this->hide_no_arts = 1;
	
		//print_p($this->subcats);
		//print_p($this->cats);
		parent::display("cat");
	}
	
    function showWhat()
    {
        $mainframe = JFactory::getApplication();

    	$aparams = FSS_Settings::GetViewSettingsObj('kb');
	    $this->assign('view_mode', $aparams->get('cat_cat_arts','normal'));
	    $this->assign('cat_art_pages', $aparams->get('cat_art_pages',0));
	    $this->assign('cat_search', $aparams->get('cat_search',0));
		$this->assign('view_mode_cat', 'normal');
		$what = JRequest::getVar('what','','','string'); 
		
	    $this->assignRef( 'arts', $this->get("ArtsWhat") );
	    $this->assignRef( 'cat', $this->get("Cat") );
		$this->assignRef( 'product', $this->get("Product") );
	    
		$this->assign('curcatid',0);
		
        $pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::x( 'index.php?option=com_fss&view=kb' ) );
        
		if ($what == "recent")
		{
			$this->title = JText::_("MOST_RECENT");
			$this->image = "mostrecent.png";
		}
		if ($what == "viewed")
		{
			$this->title = JText::_("MOST_VIEWED");
			$this->image = "mostviewed.png";
		}
		if ($what == "rated")
		{
			$this->title = JText::_("HIGHEST_RATED");
			$this->image = "highestrated.png";
		}
			
	    $document =& JFactory::getDocument();
		
		$pagetitle = '';
		if ($this->product['title'])
			$pagetitle .= $this->product['title'] . " - ";
		if ($this->cat['title'])
			$pagetitle .= $this->cat['title'] . " - ";
		$pagetitle .= $this->title;
		
		$this->pagetitle = $pagetitle;
		
	    //$document->setTitle( $pagetitle );
        
        if ($this->product['title'])
        {
            $pathway->addItem($this->product['title'], FSSRoute::x( '&catid=&what=&prodid=' . $this->product['id'] ) );
		}
        
        if ($this->cat['title'])
        {
            $pathway->addItem($this->cat['title'], FSSRoute::x( '&catid=' . $this->cat['id'] . '&what=&prodid=' . $this->product['id'] ) );
		}
        
        $pathway->addItem($this->title);

		$pagination =& $this->get('ArtPagination');
		$this->assignRef('pagination', $pagination);
		$this->assign('limit',$this->get("ArtLimit"));

		parent::display("what");
	}
	
	function showProd()
	{
        $mainframe = JFactory::getApplication();
        
	   	$aparams = FSS_Settings::GetViewSettingsObj('kb');
	    $this->assign('view_mode_cat', $aparams->get('prod_cat_mode','normal'));
	    $this->assign('view_mode', $aparams->get('prod_cat_arts','list'));
	    $this->assign('main_cat_colums', $aparams->get('prod_cat_colums',1));
	    $this->assign('prod_search', $aparams->get('prod_search',1));
		
		//if ($this->view_mode_cat != 'normal')
		//{
			$this->assignRef( 'cats', $this->get("CatsArts") );
		//} else {
		//	$this->assignRef( 'cats', $this->get("Cats") );
		//}
		$this->assign('curcatid',0);

		$this->assignRef( 'product', $this->get("Product") );
	    
	    $document =& JFactory::getDocument();
	    $document->setTitle(JText::_("KNOWLEDGE_BASE") .' - ' . $this->product['title']);

        $pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::x( 'index.php?option=com_fss&view=kb' ) );
        $pathway->addItem($this->product['title']);
		
		$this->assignRef('arts',$this->get('UncatArts'));
		
        parent::display("prod");
	}
	
	function showArt()
	{
        $mainframe = JFactory::getApplication();
			
        $kbartid = JRequest::getVar('kbartid', 0, '', 'int');
		
		$this->comments = new FSS_Comments('kb',$kbartid);
		$this->comments->PerPage(FSS_Settings::Get('kb_comments_per_page'));

		if ($this->comments->Process())
			return;			

		$this->assign( 'kb_rate', FSS_Settings::get( 'kb_rate' ));
		$this->assign( 'kb_comments', FSS_Settings::get( 'kb_comments' ));
		//$this->assign( 'kb_comments_captcha', FSS_Settings::get( 'kb_comments_captcha' ));
		//$this->assign( 'kb_comments_moderate', FSS_Settings::get( 'kb_comments_moderate' ));
		
		$error = "";
		
        $rate = JRequest::getVar('rate');   
        
        if ($rate != "")
        {	
            $this->RateArticle($kbartid, $rate);
            return;
		}   
		
        $this->setLayout("article");
        $this->assignRef( 'art', $this->get("Article") );
		if (FSS_Settings::get('kb_use_content_plugins'))
		{
			// apply plugins to article body
			$dispatcher	=& JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;
			$art->text = $this->art['body'];
			$art->noglossary = 1;
			if (FSS_Helper::Is16())
			{
				//$aparams = new JParameter(null);
				$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.kb', & $art, &$this->params, 0));
			} else {
				$aparams = new stdClass();
				$results = $dispatcher->trigger('onPrepareContent', array (& $art, & $aparams, 0));
			} 
			$this->art['body'] = $art->text;
		}
		
        $this->assignRef( 'artattach', $this->get("ArticleAttach") );         
		$this->assignRef( 'products', $this->get("Products") );         
		$this->assignRef( 'applies', $this->get("AppliesTo") );
 		$this->assignRef( 'related', $this->get("Related") );
        
	    $document =& JFactory::getDocument();
	    $document->setTitle(JText::_("KNOWLEDGE_BASE").' - ' . $this->art['title']);
	    
        $pathway =& $mainframe->getPathway();
 		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::x( 'index.php?option=com_fss&view=kb' ) );
       
		$this->assignRef( 'product', $this->get("Product") );
		$prodid = "";
        if ($this->product['title'])
        {
			$prodid = $this->product['id'];
            $pathway->addItem($this->product['title'], FSSRoute::x( 'index.php?option=com_fss&view=kb&prodid=' . $this->product['id'] ) );
		}

		JRequest::setVar('catid', $this->art['kb_cat_id']);
	    $this->assignRef( 'cat', $this->get("Cat") );
        if ($this->cat['title'])
        {
			$cat = $this->cat;
			$max = 3;
			while ($cat['parcatid'] > 0 && $max-- > 0)
			{
				JRequest::setVar('catid', $cat['parcatid']);
				$cat = $this->get("Cat");
				$pathway->addItem($cat['title'], FSSRoute::x( "index.php?option=com_fss&view=kb&prodid={$prodid}&catid={$cat['id']}" ) );
			}
			//print_p($this->cat);
            $pathway->addItem($this->cat['title'], FSSRoute::x( "index.php?option=com_fss&view=kb&prodid={$prodid}&catid={$this->cat['id']}" ) );
		}
        $pathway->addItem($this->art['title']);

		// update views
		if ($this->art['id'])
		{
			$db =& JFactory::getDBO();
			$query = "UPDATE #__fss_kb_art SET views = views + 1 WHERE id = " . FSSJ3Helper::getEscaped($db, $this->art['id']);
			$db->setQuery($query);$db->Query();	
		}
		
		if (JRequest::getVar('only') == "article")
		{
			parent::display('only');
			exit;
		} else {
			parent::display();    
		}
	}
	
	function showMain()
	{
        $mainframe = JFactory::getApplication();
        
		$this->assign('curcatid',0);
		$this->assignRef( 'products', $this->get("Products") );
		
		if (count($this->products) == 0)
		{
			return $this->showProd();
		} else if (count($this->products) == 1)
		{
			JRequest::setVar('prodid',$this->products[0]['id']);
			return $this->showProd();
		}
		
        $pathway =& $mainframe->getPathway();
        if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::x( 'index.php?option=com_fss&view=kb' ) );
        

		// default view, show prods and/or cats
		$aparams = FSS_Settings::GetViewSettingsObj('kb');
        $this->assign('main_show_prod', $aparams->get('main_show_prod',1));
        $this->assign('main_show_cat', $aparams->get('main_show_cat',0));
        $this->assign('main_show_sidebyside', $aparams->get('main_show_sidebyside',0));
        $this->assign('main_show_search', $aparams->get('main_show_search',0));
        
        $this->assign('main_prod_colums', $aparams->get('main_prod_colums',1));
        $this->assign('main_prod_search', $aparams->get('main_prod_search',1));
        $this->assign('main_prod_pages', $aparams->get('main_prod_pages',0));
        
        $this->assign('view_mode_cat', $aparams->get('main_cat_mode','normal'));
        $this->assign('view_mode', $aparams->get('main_cat_arts','normal'));
        $this->assign('main_cat_colums', $aparams->get('main_cat_colums',1));
			
		$this->arts = array();
					
		if ($this->main_show_cat)
		{
			if ($this->view_mode_cat != 'normal')
			{
				$this->assignRef( 'cats', $this->get("CatsArts") );
			} else {
				$this->assignRef( 'cats', $this->get("Cats") );
			}
			
			$this->assignRef('arts',$this->get('UncatArts'));
		}
		
		$pagination =& $this->get('ProdPagination');
		$this->assignRef('pagination', $pagination);
		$this->assign('limit',$this->get("ProdLimit"));
        parent::display();		
	}
    
    function RateArticle($kbartid, $rate)
    {
    	if ($kbartid < 1)
    		return;
    	
        $db =& JFactory::getDBO();
		
    	$query = 'SELECT id, rating, ratingdetail FROM #__fss_kb_art WHERE id = "' . FSSJ3Helper::getEscaped($db, $kbartid) . '"';
        $db->setQuery($query);
        $row = $db->loadAssoc();
        
        $rating = $row['rating'];
        $ratingdetail = $row['ratingdetail'];
        list($rating_up,$rating_same,$rating_down) = explode("|",$ratingdetail);
        
        if ($rating == "") $rating = 0;
        if ($rating_up == "") $rating_up = 0;
        if ($rating_same == "") $rating_same = 0;
        if ($rating_down == "") $rating_down = 0;
        
        if ($rate == "up")
        {
        	$rating++;
        	$rating_up++;	
		} else if ($rate == "same")
		{
			$rating_same++;
		} else if ($rate == "down")
		{
			$rating--;
			$rating_down++;
		}
		
		$ratingdetail = implode("|",array($rating_up,$rating_same,$rating_down));
		
		$query = 'UPDATE #__fss_kb_art SET rating = "' . FSSJ3Helper::getEscaped($db, $rating) . '", ratingdetail = "' . FSSJ3Helper::getEscaped($db, $ratingdetail) . '" WHERE id = "' . FSSJ3Helper::getEscaped($db, $kbartid) . '"';
		$db->setQuery($query);$db->Query();
	}
	
	function downloadFile()
	{
        $fileid = JRequest::getVar('fileid', 0, '', 'int');            
		
        $db =& JFactory::getDBO();
    	$query = 'SELECT * FROM #__fss_kb_attach WHERE id = "' . FSSJ3Helper::getEscaped($db, $fileid) . '"';
        $db->setQuery($query);
        $row = $db->loadAssoc();
        
        
        $filename = basename($row['filename']);
	    $file_extension = strtolower(substr(strrchr($filename,"."),1));
	    $ctype = FSS_Helper::datei_mime($file_extension);
	    ob_end_clean();
	    header("Cache-Control: public, must-revalidate");
	    header('Cache-Control: pre-check=0, post-check=0, max-age=0');
	    header("Pragma: no-cache");
	    header("Expires: 0"); 
	    header("Content-Description: File Transfer");
	    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	    header("Content-Type: " . $ctype);
	    header("Content-Length: ".(string)$row['size']);
	    header('Content-Disposition: attachment; filename="'.$filename.'"');
	    header("Content-Transfer-Encoding: binary\n");
	    
	    //echo getcwd(). "<br>";
	    $file = "components/com_fss/files/" . $row['diskfile'];
	    //echo $file;
	    @readfile($file);
	    exit;
  	}
  	
  	function searchProducts()
  	{
        $mainframe = JFactory::getApplication();
        $aparams = FSS_Settings::GetViewSettingsObj('kb');
		$this->assign('main_prod_pages', $aparams->get('main_prod_pages',0));
		
		$pagination =& $this->get('ProdPagination');
		$this->assignRef('pagination', $pagination);
		
  		$search = JRequest::getVar('prodsearch', '', '', 'string');  
  		
		$this->assignRef('prodsearch',$search);
		$this->assignRef( 'results', $this->get("Products") );
		
		parent::display("search"); 
		exit;
	} 	
	
  	function searchArticles()
  	{
        $mainframe = JFactory::getApplication();
        $aparams = FSS_Settings::GetViewSettingsObj('kb');
		
        $search = JRequest::getVar('kbsearch', '');  
    	$prodid = JRequest::getVar('prodid', 0, '', 'int');  
    	$catid = JRequest::getVar('catid', 0, '', 'int');  
		$this->assign('cat_art_pages', $aparams->get('cat_art_pages',0));
		
    	$search = JRequest::getVar('kbsearch', '', '', 'string');  
	    $this->assign('view_mode', $aparams->get('cat_cat_arts'));
	    
	    $document =& JFactory::getDocument();
	    $document->setTitle(JText::_("KNOWLEDGE_BASE") .' - ' . JText::_("SEARCH_RESULTS"));
		
		$pagination =& $this->get('ArtPaginationSearch');
		$this->assignRef('pagination', $pagination);
		$this->assign('limit',$this->get("ArtLimit"));
		
	    /*$db =& JFactory::getDBO();
        
        if ($prodid > 0)
        {
        	$query = "SELECT * FROM #__fss_kb_art_prod as ap LEFT JOIN #__fss_kb_art as a ON a.id = ap.kb_art_id WHERE published = 1 AND (title LIKE '%".$search."%' OR body LIKE '%".$search."%')";
        	$query .= " AND ap.prod_id = " . $prodid;
		} else {
			$query = "SELECT * FROM #__fss_kb_art WHERE published = 1 AND (title LIKE '%".$search."%' OR body LIKE '%".$search."%')";
		}
        	
        if ($catid > 0)
        	$query .= " AND kb_cat_id = " . $catid;

        $db->setQuery($query);
        $row = $db->loadAssocList();*/
        
        
		$this->assignRef( 'product', $this->get("Product") );
        $this->assignRef( 'cat', $this->get("Cat") );
        
		$this->assignRef( 'results', $this->get("ArtsWhat") );
		//$this->assignRef('results', $row);
        $this->assignRef('search', $search);
 		 		
        $pathway =& $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'kb' )))	
			$pathway->addItem(JText::_('KNOWLEDGE_BASE'), FSSRoute::x( 'index.php?option=com_fss&view=kb' ) );
        $pathway->addItem(JText::_("SEARCH_RESULTS"));
               
		parent::display("kbsearch");  		
	}

}
