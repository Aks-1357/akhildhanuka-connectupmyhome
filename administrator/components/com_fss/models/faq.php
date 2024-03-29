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

jimport('joomla.application.component.model');



class FsssModelFaq extends JModelLegacy
{

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__fss_faq_faq '.
					'  WHERE id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$user =& JFactory::getUser();
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->question = null;
			$this->_data->answer = null;
			$this->_data->longanswer = null;
			$this->_data->ordering = 0;
			$this->_data->published = 1;
			$this->_data->featured = 0;
			$this->_data->faq_cat_id = 0;
			$this->_data->fullanswer = null;
			$this->_data->author = $user->id;
			$this->_data->access = 1;
			$this->_data->language = "*";
		}
		return $this->_data;
	}

	function store($data)
	{
		$row =& $this->getTable();
		
		$user =& JFactory::getUser();
		if (!array_key_exists('author', $data) || $data['author'] == 0)
		{
			$data['author'] = $user->id;
		}

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// sort code for all products
		$db	= & JFactory::getDBO();
		
		$query = "DELETE FROM #__fss_faq_tags WHERE faq_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new product ids
		$tags = JRequest::getVar('tags');
		$lines = explode("\n",$tags);
			
		foreach ($lines as $tag)
		{
			$tag = trim($tag);
			if ($tag == "") continue;
			
			$query = "REPLACE INTO #__fss_faq_tags (faq_id, tag) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'" . FSSJ3Helper::getEscaped($db, $tag) . "')";
			$db->setQuery($query);$db->Query();					
		}
		    
		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		return true;
	}

	function unpublish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table =& $this->getTable();
		
		return $table->publish($cids, 0);
	}

	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table =& $this->getTable();

		return $table->publish($cids, 1);
	}

	function unfeature()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = & JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
			$ids[] = (int)FSSJ3Helper::getEscaped($db, $id);

		$qry = "UPDATE #__fss_faq_faq SET featured = 0 WHERE id IN (" . implode(", ", $ids) . ")";
		
		$db->setQuery($qry);
		
		return $db->query();
	}

	function feature()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = & JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
			$ids[] = (int)FSSJ3Helper::getEscaped($db, $id);

		$qry = "UPDATE #__fss_faq_faq SET featured = 1 WHERE id IN (" . implode(", ", $ids) . ")";
		
		$db->setQuery($qry);
		
		return $db->query();
	}

	function changeorder($direction)
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		if (isset( $cid[0] ))
		{
			$row =& $this->getTable();
			$row->load( (int) $cid[0] );
			$row->move($direction);

			return true;
		}
		return false;
	}

	function saveorder()
	{
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// Instantiate an article table object
		$row = & $this->getTable();

		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];

				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}

		$row->reorder();
		$row->reset();

		return true;
	}
}


