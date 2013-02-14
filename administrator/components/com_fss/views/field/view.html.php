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

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');

class FsssViewField extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->comments = new FSS_Comments(null,null);

		if (JRequest::getString('task') == "prods")
			return $this->displayProds();
		
		if (JRequest::getString('task') == "depts")
			return $this->displayDepts();
		
		if (JRequest::getString('task') == "plugin_form")
			return $this->pluginForm();
		
		$field 	=& $this->get('Data');
		$isNew		= ($field->id < 1);

		$db	= & JFactory::getDBO();

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("FIELD").': <small><small>[ ' . $text.' ]</small></small>', 'fss_customfields' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		FSSAdminHelper::DoSubToolbar();

		//  User product selection
		$this->assign('allprod', JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox",
						'size' => "1", 
						'onclick' => "DoAllProdChange();"),
					intval($field->allprods)) );

		$query = "SELECT * FROM #__fss_prod ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_field_prod WHERE field_id = " . FSSJ3Helper::getEscaped($db, $field->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$field->allprods);
		
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
		$this->assignRef('products', $prodcheck);

		// field permissions
        $fieldperms[] = JHTML::_('select.option', '0', JText::_("USER_CAN_SEE_AND_EDIT"), 'id', 'title');
        $fieldperms[] = JHTML::_('select.option', '1', JText::_("USER_CAN_SEE_ONLY_ADMIN_CAN_EDIT"), 'id', 'title');
        $fieldperms[] = JHTML::_('select.option', '2', JText::_("ONLY_ADMIN_CAN_SEE_AND_EDIT"), 'id', 'title');
        $fieldperms[] = JHTML::_('select.option', '4', JText::_("Only show on ticket create"), 'id', 'title');
        $this->assign('fieldperm',JHTML::_('select.genericlist',  $fieldperms, 'permissions', 'class="inputbox" size="1"', 'id', 'title', $field->permissions));

		//  User department selection
		$this->assign('alldept', JHTML::_('select.booleanlist', 'alldepts', 
			array('class' => "inputbox",
						'size' => "1", 
						'onclick' => "DoAllDeptChange();"),
					intval($field->alldepts)) );

		$query = "SELECT * FROM #__fss_ticket_dept ORDER BY title";
		$db->setQuery($query);
		$departments = $db->loadObjectList();

		$query = "SELECT * FROM #__fss_field_dept WHERE field_id = " . FSSJ3Helper::getEscaped($db, $field->id);
		$db->setQuery($query);
		$seldept = $db->loadAssocList('ticket_dept_id');
		
		$this->assign('alldepts',$field->alldepts);
		
		$deptcheck = "";
		foreach($departments as $department)
		{
			$checked = false;
			if (array_key_exists($department->id,$seldept))
			{
				$deptcheck .= "<input type='checkbox' name='dept_" . $department->id . "' checked />" . $department->title . "<br>";
			} else {
				$deptcheck .= "<input type='checkbox' name='dept_" . $department->id . "' />" . $department->title . "<br>";
			}
		}
		$this->assignRef('departments', $deptcheck);

		// get field values	

		$query = "SELECT value FROM #__fss_field_values WHERE field_id = " . FSSJ3Helper::getEscaped($db, $field->id);
		$db->setQuery($query);
		$values = FSSJ3Helper::loadResultArray($db);

		if ($field->type == "radio" || $field->type == "combo")
		{
			$res = "";
			foreach ($values as $value)
			{
				if (strpos($value,"|") == 2)
				{
					$value = substr($value,3);	
				}
				$res .= $value."\n";
			}
			$this->values = $res;
		}
		else
			$this->assign('values','');
		
		$area_width = 60;
		$area_height = 4;
		$text_min = 0;
		$text_max = 60;
		$text_size = 40;
		$plugin_name = '';
		$plugin_data = '';
		
		foreach ($values as $value)
		{
			$bits = explode("=",$value);
			if (count($bits == 2))
			{
				if ($bits[0] == "area_width")
					$area_width = $bits[1];
				if ($bits[0] == "area_height")
					$area_height = $bits[1];
				if ($bits[0] == "text_min")
					$text_min = $bits[1];
				if ($bits[0] == "text_max")
					$text_max = $bits[1];
				if ($bits[0] == "text_size")
					$text_size = $bits[1];
				if ($bits[0] == "plugin")
					$plugin_name = $bits[1];
				if ($bits[0] == "plugindata")
				{
					$plugin_data = $bits[1];
				}
			}
		}

		$this->assignRef('field', $field);
		$this->assignRef('area_width', $area_width);
		$this->assignRef('area_height', $area_height);
		$this->assignRef('text_min', $text_min);
		$this->assignRef('text_max', $text_max);
		$this->assignRef('text_size', $text_size);

		// load plugin list
		$plugins = array();
		$this->plugins = FSSCF::get_plugins();
		$pllist = array();
		$pllist[] = JHTML::_('select.option', '', JText::_("Select a plugin"), 'id', 'title');
		foreach ($this->plugins as $id => &$plugins)
		{
			$pllist[] = JHTML::_('select.option', $id, $plugins->name, 'id', 'title');
		}
		$this->pllist = JHTML::_('select.genericlist',  $pllist, 'plugin', 'id="plugin" class="inputbox" size="1" onchange="plugin_changed()"', 'id', 'title', $plugin_name);
	
		$this->plugin_form = "";
		if ($plugin_name != '') // editing an existing plugin?
		{
			if (array_key_exists($plugin_name,$this->plugins))
			{
				$plugin = $this->plugins[$plugin_name];
				$this->plugin_form = $plugin->DisplaySettings($plugin_data);
			}
		}
	
		$idents = array();
		$idents[] = JHTML::_('select.option', '0', JText::_("TICKETS"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '999', JText::_("ALL_COMMENTS"), 'id', 'title');
		$db	= & JFactory::getDBO();
		foreach($this->comments->handlers as $handler)
			$idents[] = JHTML::_('select.option', $handler->ident, $handler->GetLongDesc(), 'id', 'title');
				
		$this->ident = JHTML::_('select.genericlist',  $idents, 'ident', ' class="inputbox" size="1" onchange="ident_changed()"', 'id', 'title', $field->ident);

		
		parent::display($tpl);
	}
	
	function pluginForm()
	{
		$plugin = JRequest::GetVar('plugin','');	
		if ($plugin == "")
			exit;
		
		$plugin = FSSCF::get_plugin($plugin);
		
		echo $plugin->DisplaySettings(null);
		exit;
	}

	function displayProds()
	{
		$field_id = JRequest::getInt('field_id',0);
		$db	= & JFactory::getDBO();

		$query = "SELECT * FROM #__fss_field_prod as u LEFT JOIN #__fss_prod as p ON u.prod_id = p.id WHERE u.field_id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$products = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_field WHERE id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$field = $db->loadObject();
	
		$this->assignRef('field',$field);
		$this->assignRef('products',$products);
		parent::display();
	}
	
	function displayDepts()
	{
		$field_id = JRequest::getInt('field_id',0);
		$db	= & JFactory::getDBO();

		$query = "SELECT * FROM #__fss_field_dept as u LEFT JOIN #__fss_ticket_dept as p ON u.ticket_dept_id = p.id WHERE u.field_id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$departments = $db->loadObjectList();
		
		$query = "SELECT * FROM #__fss_field WHERE id = '".FSSJ3Helper::getEscaped($db, $field_id)."'";
		$db->setQuery($query);
		$field = $db->loadObject();
		
		$this->assignRef('field',$field);
		$this->assignRef('departments',$departments);
		parent::display();
	}
	
}


