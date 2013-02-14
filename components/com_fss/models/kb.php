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

jimport( 'joomla.application.component.model' );
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'paginationajax.php');

class FssModelKb extends JModelLegacy
{
 
    var $_total = null;

    var $_pagination = null;

    var $_curcatid = 0;

    var $_curcattitle = "";

    var $_search = "";

    var $_catlist = "";
	
	var $_art = null;



    function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication(); global $option;

		$aparams = FSS_Settings::GetViewSettingsObj('kb');
		$this->_enable_prod_pages = $aparams->get('main_prod_pages',0);
		
		if ($this->_enable_prod_pages == 1)
		{
			$limit = $mainframe->getUserStateFromRequest('global.list.limit_prod', 'limit', FSS_Settings::Get('kb_prod_per_page'), 'int');

			$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

			// In case limit has been changed, adjust it
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limit_prod', $limit);
			$this->setState('limitstart', $limitstart);
		}

		$this->_enable_art_pages = $aparams->get('cat_art_pages',0);
		
		if ($this->_enable_art_pages == 1)
		{
			$limit = $mainframe->getUserStateFromRequest('global.list.limit_art', 'limit', FSS_Settings::Get('kb_art_per_page'), 'int');

			$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

			// In case limit has been changed, adjust it
			$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

			$this->setState('limit_art', $limit);
			$this->setState('limitstart', $limitstart);
		}
		//$aparams = new stdClass();
	}
    
    function &getProducts()
    {
		// if data hasn't already been obtained, load it
		if (empty($this->_data)) {
			$query = $this->_buildProdQuery();
			if ($this->_enable_prod_pages)
			{
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit_prod') );
			} else {
				$this->_db->setQuery( $query );
			}
			$this->_data = $this->_db->loadAssocList();
		}
		return $this->_data;
	}
	
	function getProdLimit()
	{
		return $this->getState('limit_prod');
	}
	
	function _buildProdQuery()
	{
		$db =& JFactory::getDBO();
		$search = JRequest::getVar('prodsearch', '', '', 'string');  
		if ($search == "__all__" || $search == '')
		{
			$query = "SELECT * FROM #__fss_prod";
			
			$where = array();
			$where[] = "published = 1";
			$where[] = "inkb = 1";
			
			if (FSS_Helper::Is16())
			{
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
		
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);

			$query .= " ORDER BY ordering";
		} else {
			$query = "SELECT * FROM #__fss_prod";
			
			$where = array();
			$where[] ="published = 1";
			$where[] = "inkb = 1";
			$where[] = "title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%'";
			
			if (FSS_Helper::Is16())
			{
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
		
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);

			$query .= " ORDER BY ordering";
		}

		return $query;        
	}
	
	function getTotalProducts()
	{
		if (empty($this->_prodtotal)) {
			$query = $this->_buildProdQuery();
			$this->_prodtotal = $this->_getListCount($query);
		}
		return $this->_prodtotal;		
	}

	function &getProdPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationAjax($this->getTotalProducts(), $this->getState('limitstart'), $this->getState('limit_prod') );
		}
		return $this->_pagination;
	}	
	 
    function &getCats()
    {
    	$prodid = JRequest::getVar('prodid', 0, '', 'int');
        $db =& JFactory::getDBO();
        
        if ($prodid > 0)
        {
        	$query1 = "SELECT * FROM #__fss_kb_cat WHERE published = 1 AND id IN (";
        	$query1 .= "SELECT a.kb_cat_id FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "'";
			if (FSS_Helper::Is16()) // filter sub query so only correct cats are shown
			{
				$query1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query1 .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
				
			$query1 .= " GROUP BY a.kb_cat_id";
        	$query1 .= ")";
			
			if (FSS_Helper::Is16())
			{
				$query1 .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query1 .= ' AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
			
			$query2 = "SELECT * FROM #__fss_kb_cat WHERE published = 1 AND id IN (";
			$query2 .= "SELECT a.kb_cat_id FROM #__fss_kb_art as a WHERE a.allprods = '1'";
			if (FSS_Helper::Is16()) // filter sub query so only correct cats are shown
			{
				$query2 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query2 .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
			$query2 .= " GROUP BY a.kb_cat_id";
			$query2 .= ")";
			
				
			if (FSS_Helper::Is16())
			{
				$query2 .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query2 .= ' AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	

	 		//echo $query1."<br>";
			//echo $query2."<br>";
			
			$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
			//$query = $query2;	
		} else {
            $query = "SELECT * FROM #__fss_kb_cat WHERE published = 1";
			if (FSS_Helper::Is16())
			{
				$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query .= ' AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
			$query .= " ORDER BY ordering";
		}
		//echo $query."<br>";
		
        $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;        
	}    
	
	function &getCatsForProd()
	{
    	$prodid = JRequest::getVar('prodid', 0, '', 'int');
    	$db =& JFactory::getDBO();
		if ($prodid > 0)
		{
			$qry1 = "SELECT a.kb_cat_id FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "' AND published = 1";
			if (FSS_Helper::Is16())
			{
				$qry1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$qry1 .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
			$qry1 .= " GROUP BY a.kb_cat_id";
			
			$qry2 = "SELECT a.kb_cat_id FROM #__fss_kb_art as a WHERE a.allprods = '1' AND published = 1 ";
			if (FSS_Helper::Is16())
			{
				$qry2 .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$qry2 .= ' AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
			$qry2 .= " GROUP BY a.kb_cat_id";
			
			$query = "($qry1) UNION ($qry2)";
			$db->setQuery($query);
			
			$rows = $db->loadAssocList('kb_cat_id');
			$catids = array();
			foreach($rows as &$rows)
			{
				$catids[$rows['kb_cat_id']] = $rows['kb_cat_id'];
			}

			if (count($catids) > 0)
			{
				$query = "SELECT parcatid FROM #__fss_kb_cat WHERE id IN (".implode(", ",$catids).") AND parcatid > 0";
				$db->setQuery($query);
				$rows = $db->loadAssocList();
				foreach($rows as &$rows)
				{
					$catids[$rows['parcatid']] = $rows['parcatid'];
				}
			}

			$query = "SELECT * FROM #__fss_kb_cat WHERE published = 1";
			if (count($catids) > 0)
				$query .= " AND id IN (".implode(", ",$catids) . ")";
			if (FSS_Helper::Is16())
			{
				$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query .= ' AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	

			$query .= " ORDER BY ordering";
		} else {
			$query = "SELECT * FROM #__fss_kb_cat WHERE published = 1";
			if (FSS_Helper::Is16())
			{
				$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query .= ' AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
			$query .= " ORDER BY ordering";
		}
		//echo $query."<br>";
		
	    $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;        
	}
    
    function &getCatsArts()
    {
		//echo "CAT ARTS<br>";
    	//$cats =& $this->getCats();
    	
		// get all categories that are relevant to the product
		$cats = $this->getCatsForProd();
				
    	$prodid = JRequest::getVar('prodid', 0, '', 'int');
    	$db =& JFactory::getDBO();

		$where = array();
		if ($this->content->permission['artperm'] > 1) // we have editor so can see all unpublished arts
		{
			
		} else if ($this->content->permission['artperm'] == 1){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}


        if ($prodid > 0)
		{
			$query1 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "'";
			if (FSS_Helper::Is16())
			{
				$query1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query1 .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	
			
			$query2 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a WHERE a.allprods = '1'";
			if (FSS_Helper::Is16())
			{
				$query2 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query2 .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	

			if (count($where) > 0)
				$query1 .= " AND " . implode(" AND ",$where);
			if (count($where) > 0)
				$query2 .= " AND " . implode(" AND ",$where);
			$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
		} else {
			$query = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a";
			if (FSS_Helper::Is16())
			{
				$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
		
			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);
		
			$query .= " ORDER BY ordering";	
		}
		
		//echo $query."<br>";
		
        $db->setQuery($query);
        $rows = $db->loadAssocList();
        
        
        foreach ($rows as &$row)
        {
        	$catid = $row['kb_cat_id'];	
        	
        	foreach ($cats as &$cat)
        	{
        		if ($cat['id'] == $catid)
        		{
        			$cat['arts'][] = $row;
        			break;
				}
			}
		}
        
		$notinbase = array();
		foreach($cats as $key => &$cat)
		{
			$pid = $cat['parcatid'];
			if ($pid != 0)
			{
				$foundid = 0;
				foreach($cats as $fid => &$pcat)
				{
					if ($pcat['id'] == $pid)
					{
						$foundid = $fid;
						break;		
					}
				}
				//echo "Putting $pid ({$cat['title']}) into $foundid ({$cats[$foundid]['title']})<br>";
				
				if (!array_key_exists($foundid, $cats))
					continue;
					
				if (!array_key_exists("subcats",$cats[$foundid]))
					$cats[$foundid]["subcats"] = array();
					
				$cats[$foundid]["subcats"][] = &$cat;
				$notinbase[$key] = $key;
			}
		}
		
	
		// if we have a cat id set, then we need to return a list of cats that live inside the current cat id
		$curcatid = JRequest::getVar('catid',0);
		if ($curcatid > 0)
		{
			foreach($cats as &$cat)
			{
				if ($cat['id'] == $curcatid)
					return $cat['subcats'];		
			}
		}
		
		foreach($notinbase as $id)
		{
			unset($cats[$id]);	
		}
		
		/*print "<pre>";
        print_r($cats);
        print "</pre>";*/
        return $cats;
	}
	
	function &getUncatArts()
	{
        $db =& JFactory::getDBO();
 		$prodid = JRequest::getVar('prodid', 0, '', 'int');
		
		$where = array();
		if ($this->content->permission['artperm'] > 1) // we have editor so can see all unpublished arts
		{
			
		} else if ($this->content->permission['artperm'] == 1){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}

		if ($prodid > 0)
		{
			$query1 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a LEFT JOIN #__fss_kb_art_prod as p ON a.id = p.kb_art_id WHERE p.prod_id = '" . FSSJ3Helper::getEscaped($db, $prodid) . "' AND kb_cat_id = 0";
			if (FSS_Helper::Is16())
			{
				$query1 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query1 .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	

			$query2 = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a WHERE a.allprods = '1' AND kb_cat_id = 0";
			if (FSS_Helper::Is16())
			{
				$query2 .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
				$user = JFactory::getUser();
				$query2 .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
			}	

			if (count($where) > 0)
				$query1 .= " AND " . implode(" AND ",$where);
			if (count($where) > 0)
				$query2 .= " AND " . implode(" AND ",$where);
			$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
		} else {
			$query = "SELECT a.id, a.title, a.kb_cat_id, a.ordering, a.published, a.author FROM #__fss_kb_art as a WHERE kb_cat_id = 0";
			if (FSS_Helper::Is16())
			{
				$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
				$user = JFactory::getUser();
				$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
			}	
			if (count($where) > 0)
				$query .= " AND " . implode(" AND ",$where);
			$query .= " ORDER BY ordering";
		}
        $db->setQuery($query);

        $rows = $db->loadAssocList();
		
		return $rows;        
	}	
    
    function &getArticle()
    {
        $db =& JFactory::getDBO();
        $kbartid = JRequest::getVar('kbartid', 0, '', 'int');
        $query = "SELECT f.id, f.title, f.body, f.kb_cat_id, c.title as cattile, f.allprods, f.created, f.modified, f.published, f.author FROM #__fss_kb_art as f LEFT JOIN #__fss_kb_cat as c ON f.kb_cat_id = c.id";
		
		$where = array();
		$where[] = "f.id = " . FSSJ3Helper::getEscaped($db, $kbartid);
		
		if ($this->content->permission['artperm'] > 1) // we have editor so can see all unpublished arts
		{
			
		} else if ($this->content->permission['artperm'] == 1){
			$where[] = " ( f.published = 1 OR f.author = {$this->content->userid} ) ";	
		} else {
			$where[] = "f.published = 1";	
		}

		$query .= " WHERE " . implode(" AND ", $where);
		

        $db->setQuery($query);
        $rows = $db->loadAssoc();
		$this->_art = $rows;
        return $rows;        
    }
    
    function &getProduct()
    {
        $db =& JFactory::getDBO();
    	$prodid = JRequest::getVar('prodid', 0, '', 'int');
        $query = "SELECT * FROM #__fss_prod WHERE id = " . FSSJ3Helper::getEscaped($db, $prodid);

        $db->setQuery($query);
        $rows = $db->loadAssoc();
        return $rows;        
    }
    
    function &getCat()
    {
        $db =& JFactory::getDBO();
    	$catid = JRequest::getVar('catid', 0, '', 'int');
        $query = "SELECT * FROM #__fss_kb_cat WHERE id = " . FSSJ3Helper::getEscaped($db, $catid);

        $db->setQuery($query);
        $rows = $db->loadAssoc();
		return $rows;        
    }
   
    function &getArts()
    {
		// if data hasn't already been obtained, load it
		if (empty($this->_arts)) {
			$query = $this->_buildArtQuery();
			if ($this->_enable_art_pages)
			{
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit_art') );
			} else {
				$this->_db->setQuery( $query );
			}
			$this->_arts = $this->_db->loadAssocList();
		}
		return $this->_arts;
	}
	
    function &getArtsWhat()
    {
		$db =& JFactory::getDBO();
		// if data hasn't already been obtained, load it
		if (empty($this->_arts)) {
			
    		$catid = JRequest::getVar('catid', 0, '', 'int');
    		$prodid = JRequest::getVar('prodid', 0, '', 'int');
			$search = JRequest::getVar('kbsearch', '', '', 'string');  
			$what = JRequest::getVar('what', '', '', 'string');  

			$query1 = "SELECT a.* FROM #__fss_kb_art as a WHERE 1 ";
		
			if ($catid > 0)
			{
				// need to get a list of cats that are under the current one
				$catlist = array();
				$cats = $this->getCatsForProd();
				$catlist[$catid] = $catid;
				
				$count = 0;
				$listcount = count($catlist);
				$runs = 0;
				//echo "Searching for subcats in " . implode(" ",$catlist) . "<br>";
				while ($count != $listcount && $runs < 10)
				{
					$runs++;
					$count = $listcount;
					foreach ($cats as &$cat)
					{
						$pid = $cat['parcatid'];
						//echo "Cat {$cat['title']} ({$cat['id']}) - Parent : $pid<br>";
						if ($pid == 0)
							continue;
							
						// is parent id in the current cat list
						if (array_key_exists($pid, $catlist))
						{
							//echo "Cat {$cat['id']} has parid $pid<br>";
							$catlist[$cat['id']] = $cat['id'];
						}
					}
					$listcount = count($catlist);
					//echo "Ending list : " .implode(" ",$catlist)."<br>";
				}
				
				//print_p($catlist);
				
				$query1 .= " AND kb_cat_id IN (" . implode(", ",$catlist) . ")";
			}
        
			if ($prodid > 0)
        		$query1 .= " AND a.id IN (SELECT kb_art_id FROM #__fss_kb_art_prod WHERE prod_id = " . FSSJ3Helper::getEscaped($db, $prodid) . ") ";
        	
			if ($search != '')
			{
				$words = explode(" ",$search);
			
				$query1 .= " AND (";
			
				$searches = array();
				foreach($words as $word)
				{
					$searches[] = "(title LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%' OR body LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%')";
				}
			
				$query1 .= implode(" AND ",$searches);
				$query1 .= ") ";

				//$query1 .= " AND (title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR body LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
			}
			
			$query1 .= " AND published = 1 ";
		
			$query2 = "SELECT a.* FROM #__fss_kb_art as a WHERE 1 ";
			
			if ($catid > 0)
			{
				$query2 .= " AND kb_cat_id IN (" . implode(", ",$catlist) . ")";
			}
			
			if ($search != '')
			{
				$words = explode(" ",$search);
			
				$query2 .= " AND (";
			
				$searches = array();
				foreach($words as $word)
				{
					$searches[] = "(title LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%' OR body LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%')";
				}
			
				$query2 .= implode(" AND ",$searches);
				$query2 .= ") ";

				//$query2 .= " AND (title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR body LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
			}
		
			$query2 .= " AND a.allprods = 1 AND published = 1 ";
		
			$query = "(" . $query1 . ") UNION (" . $query2 . ") ";
			
			if ($what == "recent")
				$query .= " ORDER BY modified DESC ";
	
			if ($what == "viewed")
				$query .= " ORDER BY views DESC ";
	
			if ($what == "rated")
				$query .= " ORDER BY rating DESC ";
	
			$this->_db->setQuery( $query, 0, 20 );
						
			$this->_arts = $this->_db->loadAssocList();
		}
		return $this->_arts;
	}
	
	function getArtLimit()
	{
		return $this->getState('limit_art');
	}
	
	function _buildArtQuery()
	{
 		$db =& JFactory::getDBO();
     	$catid = JRequest::getVar('catid', 0, '', 'int');
    	$prodid = JRequest::getVar('prodid', 0, '', 'int');
		$search = JRequest::getVar('kbsearch', '', '', 'string');  

		$query1 = "SELECT a.* FROM #__fss_kb_art as a WHERE 1 ";
		
		if ($catid > 0)
		 $query1 .= " AND kb_cat_id = " . FSSJ3Helper::getEscaped($db, $catid);
        
        if ($prodid > 0)
        	$query1 .= " AND a.id IN (SELECT kb_art_id FROM #__fss_kb_art_prod WHERE prod_id = " . FSSJ3Helper::getEscaped($db, $prodid) . ") ";
        	
			
		// stuff to show extra arts when have edit permission
		$where = array();
		if ($this->content->permission['artperm'] > 1) // we have editor so can see all unpublished arts
		{
			
		} else if ($this->content->permission['artperm'] == 1){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}
		
		if (FSS_Helper::Is16())
		{
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		

		// search
		if ($search != '')
		{
			$words = explode(" ",$search);
			
			$query1 .= " AND (";
			
			$searches = array();
			foreach($words as $word)
			{
				$searches[] = "(title LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%' OR body LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%')";
			}
			
			$query1 .= implode(" AND ",$searches);
			$query1 .= ") ";

			//$query1 .= " AND (title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR body LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
		}
			
		//$query1 .= " AND published = 1 ";
				
		if (count($where) > 0)
			$query1 .= " AND " . implode(" AND ",$where);

		
		$query2 = "SELECT a.* FROM #__fss_kb_art as a WHERE 1 ";
		if ($catid > 0)
			$query2 .= " AND kb_cat_id = " . FSSJ3Helper::getEscaped($db, $catid);
			
		if ($search != '')
		{
			$words = explode(" ",$search);
			
			$query2 .= " AND (";
			
			$searches = array();
			foreach($words as $word)
			{
				$searches[] = "(title LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%' OR body LIKE '%" . FSSJ3Helper::getEscaped($db, $word) . "%')";
			}
			
			$query2 .= implode(" AND ",$searches);
			$query2 .= ") ";

			//$query2 .= " AND (title LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%' OR body LIKE '%".FSSJ3Helper::getEscaped($db, $search)."%')";
		}
		
		$query2 .= " AND a.allprods = 1";// AND published = 1 ";
		if (count($where) > 0)
			$query2 .= " AND " . implode(" AND ",$where);

		$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY ordering";
		
		//echo $query."<br>";
		return $query;        
	}
	
	function getTotalArts()
	{
		if (empty($this->_arttotal)) {
			$query = $this->_buildArtQuery();
			$this->_arttotal = $this->_getListCount($query);
		}
		return $this->_arttotal;		
	}

	function &getArtPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationEx($this->getTotalArts(), $this->getState('limitstart'), $this->getState('limit_art') );
		}
		return $this->_pagination;
	}	 

	function &getArtPaginationSearch()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = new JPaginationAjax($this->getTotalArts(), $this->getState('limitstart'), $this->getState('limit_art') );
		}
		return $this->_pagination;
	}	 


    function &getArticleAttach()
    {
        $db =& JFactory::getDBO();
        $kbartid = JRequest::getVar('kbartid', 0, '', 'int');
        $query = "SELECT * FROM #__fss_kb_attach WHERE kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbartid);
        
        $db->setQuery($query);
        $rows = $db->loadAssocList();
        return $rows;        
    }
    
    function &getAppliesTo()
    {
        $db =& JFactory::getDBO();
        $kbartid = JRequest::getVar('kbartid', 0, '', 'int');
        $query = "SELECT p.* FROM #__fss_kb_art_prod as ap LEFT JOIN #__fss_prod as p ON ap.prod_id = p.id WHERE p.published = 1 AND p.inkb = 1 AND ap.kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbartid);
		if (FSS_Helper::Is16())
		{
			$user = JFactory::getUser();
			$query .= ' AND p.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		}	
		        
        $db->setQuery($query);
        $rows = $db->loadAssocList();
		FSS_Helper::Tr($rows);
		
		if ($this->_art['allprods'] > 0)
		{
			$allprod = array();
			$allprod['title'] = JText::_("ALL_PRODUCTS");
			$rows[] = $allprod;
		}
				
		return $rows;        
	}
	
    function &getRelated()
    {
        $db =& JFactory::getDBO();
        $kbartid = JRequest::getVar('kbartid', 0, '', 'int');
        $query = "SELECT a.id, a.title FROM #__fss_kb_art_related as r LEFT JOIN #__fss_kb_art as a ON r.related_id = a.id WHERE a.published = 1 AND r.kb_art_id = " . FSSJ3Helper::getEscaped($db, $kbartid) . " ORDER BY a.title";
 		if (FSS_Helper::Is16())
		{
			$query .= ' AND a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$user = JFactory::getUser();
			$query .= ' AND a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
		}	
       
        $db->setQuery($query);
        $rows = $db->loadAssocList();
		return $rows;        
	}
	
	function &getSubCats()
	{
		$catid = JRequest::getVar('catid', 0, '', 'int');
		if ($catid == 0)
			return array();
			
        $db =& JFactory::getDBO();
        $query = "SELECT * FROM #__fss_kb_cat WHERE parcatid = ".FSSJ3Helper::getEscaped($db, $catid);
		if (FSS_Helper::Is16())
		{
			$query .= ' AND language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$user = JFactory::getUser();
			$query .= ' AND access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ') ';				
		}	

		$query .= " ORDER BY ordering";
        
	    $db->setQuery($query);
        $rows = $db->loadAssocList();		
		return $rows;   		
	}
}

