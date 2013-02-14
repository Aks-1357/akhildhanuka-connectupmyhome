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

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');

class FsssModelField extends JModelLegacy
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
			$query = ' SELECT * FROM #__fss_field WHERE id = '.FSSJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->description = '';
			$this->_data->type = 'text';
			$this->_data->default = '';
			$this->_data->grouping = '';
			$this->_data->allprods = 1;
			$this->_data->alldepts = 1;
			$this->_data->required = 0;
			$this->_data->permissions = 0;
			$this->_data->published = 1;
			$this->_data->advancedsearch  = 0;
			$this->_data->inlist  = 0;
			$this->_data->basicsearch   = 0;
			$this->_data->ordering = 0;
			$this->_data->ident = 0;
			$this->_data->peruser = 0;
		}
		return $this->_data;
	}

	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) {
			print $this->_db->getErrorMsg();
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (!$row->store()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
		
		// sort code for all products
		$db	= & JFactory::getDBO();
		$query = "DELETE FROM #__fss_field_prod WHERE field_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new product ids
		if (!$row->allprods)
		{
			$query = "SELECT * FROM #__fss_prod ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "prod_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__fss_field_prod (field_id, prod_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();
				}
			}
		}
		
		
		// sort code for all departments
		$query = "DELETE FROM #__fss_field_dept WHERE field_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		
		$db->setQuery($query);$db->Query();

		// store new department ids
		if (!$row->alldepts)
		{
			$query = "SELECT * FROM #__fss_ticket_dept ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "dept_" . $product->id );
				if ($value != "")
				{
					$query = "INSERT INTO #__fss_field_dept (field_id, ticket_dept_id) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . "," . FSSJ3Helper::getEscaped($db, $id) . ")";
					$db->setQuery($query);$db->Query();					
				}
			}
		}
		
		// sort code for all categories
		$query = "DELETE FROM #__fss_field_values WHERE field_id = " . FSSJ3Helper::getEscaped($db, $row->id);
		$db->setQuery($query);$db->Query();					
		
		if ($row->type == "text")
		{
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'text_min=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('text_min',0)) . "')";
			$db->setQuery($query);$db->Query();					
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'text_max=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('text_max',60)) . "')";
			$db->setQuery($query);$db->Query();					
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'text_size=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('text_size',40)) . "')";
			$db->setQuery($query);$db->Query();					
		} else if ($row->type == "area")
		{
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'area_width=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('area_width',60)) . "')";
			$db->setQuery($query);$db->Query();					
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'area_height=" . FSSJ3Helper::getEscaped($db, JRequest::getInt('area_height',4)) . "')";
			$db->setQuery($query);$db->Query();					
		} else if ($row->type == "combo")
		{
			$values = explode("\n",JRequest::getVar('combo_values',''));
			$offset = 0;
			foreach ($values as $value)
			{
				$value = trim($value);
				if ($value == '')
					continue;
				
				$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'" . sprintf("%02d",$offset) . "|" . FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($query);$db->Query();					
				$offset++;
			}	
		} else if ($row->type == "radio")
		{
			$values = explode("\n",JRequest::getVar('radio_values',''));
			$offset = 0;
			foreach ($values as $value)
			{
				$value = trim($value);
				if ($value == '')
					continue;
				
				$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'" . sprintf("%02d",$offset) . "|" . FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($query);$db->Query();					
				$offset++;
			}	
		} else if ($row->type == "plugin")
		{
			$plugin = JRequest::getVar( "plugin" , "");
			
			$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'plugin=" . FSSJ3Helper::getEscaped($db, $plugin) . "')";
			$db->setQuery($query);$db->Query();	
			
			if ($plugin)
			{
				$plo = FSSCF::get_plugin($plugin);
				$data = $plo->SaveSettings();
				
				$query = "INSERT INTO #__fss_field_values (field_id, value) VALUES (" . FSSJ3Helper::getEscaped($db, $row->id) . ",'plugindata=" . FSSJ3Helper::getEscaped($db, $data) . "')";
				$db->setQuery($query);$db->Query();	
			}
		}
		
		$this->_id = $row->id;
		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();
		$db =& JFactory::getDBO();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
				
				$qry = "DELETE FROM #__fss_field_prod WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
				$qry = "DELETE FROM #__fss_field_dept WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
				$qry = "DELETE FROM #__fss_field_values WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();				
				$qry = "DELETE FROM #__fss_ticket_field WHERE field_id = '".FSSJ3Helper::getEscaped($db, $cid)."'";
				$db->setQuery($qry);$db->Query();
			}
		}
		
		return true;
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
}


