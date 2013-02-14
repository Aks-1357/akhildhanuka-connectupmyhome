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
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_fss'.DS.'settings.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'parser.php');
//##NOT_EXT_START##	
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'tickethelper.php');
//##NOT_EXT_END##	
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');


class FsssViewSettings extends JViewLegacy
{
	
	function display($tpl = null)
	{
		JHTML::_('behavior.modal');

		$what = JRequest::getString('what','');
		$this->tab = JRequest::getVar('tab');
		
		if (JRequest::getVar('task') == "cancellist")
		{
			$mainframe = JFactory::getApplication();
			$link = FSSRoute::x('index.php?option=com_fss&view=fsss',false);
			$mainframe->redirect($link);
			return;			
		}
		
		$settings = FSS_Settings::GetAllSettings();
		$db	= & JFactory::getDBO();
		
		if ($what == "testref")
		{
			return $this->TestRef();
		} else if ($what == "testdates")
		{
			return $this->testdates();
		} else if ($what == "save")
		{
//##NOT_EXT_START##	
			// auto close settings
			{
				$support_autoclose = JRequest::getInt('support_autoclose');	
				$support_autoclose_duration = JRequest::getInt('support_autoclose_duration');	
				$support_autoclose_audit = JRequest::getInt('support_autoclose_audit');	
				$support_autoclose_email = JRequest::getInt('support_autoclose_email');	

				$aparams = "addaudit:$support_autoclose_audit;emailuser:$support_autoclose_email;closeinterval:$support_autoclose_duration;";
				$qry = "UPDATE #__fss_cron SET params = '" . FSSJ3Helper::getEscaped($db, $aparams) . "', published = $support_autoclose, `interval` = 5 WHERE class = 'AutoClose'";
				$db->setQuery($qry);
				//echo $qry."<br>";
				$db->Query();

				unset($_POST['support_autoclose']);
				unset($_POST['support_autoclose_duration']);
				unset($_POST['support_autoclose_audit']);
				unset($_POST['support_autoclose_email']);
			}
//##NOT_EXT_END##
			
			$large = FSS_Settings::GetLargeList();
			$templates = FSS_Settings::GetTemplateList();
			
			// save any large settings that arent in the templates list				
			foreach($large as $setting)
			{
				// skip any setting that is in the templates list
				if (array_key_exists($setting,$templates))
					continue;
	
				// 
				$value = JRequest::getVar($setting, '', 'post', 'string', JREQUEST_ALLOWRAW);
				$qry = "REPLACE INTO #__fss_settings_big (setting, value) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();

				$qry = "DELETE FROM #__fss_settings WHERE setting = '".FSSJ3Helper::getEscaped($db, $setting)."'";
				$db->setQuery($qry);$db->Query();

				unset($_POST[$setting]);
			}		
			
			$data = JRequest::get('POST',JREQUEST_ALLOWRAW);

			foreach ($data as $setting => $value)
				if (array_key_exists($setting,$settings))
					$settings[$setting] = $value;
			
			foreach ($settings as $setting => $value)
			{
				if (!array_key_exists($setting,$data))
				{
					$settings[$setting] = 0;
					$value = 0;	
				}
				
				// skip any setting that is in the templates list
				if (array_key_exists($setting,$templates))
					continue;

				if (array_key_exists($setting,$large))
					continue;

				$qry = "REPLACE INTO #__fss_settings (setting, value) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();
				//echo $qry."<br>";
			}

			$link = 'index.php?option=com_fss&view=settings#' . $this->tab;
			
			if (JRequest::getVar('task') == "save")
				$link = 'index.php?option=com_fss';

			//exit;
			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link, JText::_("Settings_Saved"));		
			exit;
		} else if ($what == "customtemplate") {
			$this->CustomTemplate();
			exit;	
		} else {
		
			$qry = "SELECT * FROM #__fss_templates WHERE template = 'custom'";
			$db->setQuery($qry);
			$rows = $db->loadAssocList();
			if (count($rows) > 0)
			{	
				foreach ($rows as $row)
				{
					if ($row['tpltype'])
					{
						$settings['support_list_head'] = $row['value'];
					} else {
						$settings['support_list_row'] = $row['value'];
					}
				}
			} else {
				$settings['support_list_head'] = '';
				$settings['support_list_row'] = '';
			}

//##NOT_EXT_START##
			$qry = "SELECT * FROM #__fss_cron WHERE class = 'AutoClose' LIMIT 1";
			$db->setQuery($qry);
			$row = $db->loadAssoc();
			if ($row)
			{
				$settings['support_autoclose'] = $row['published'];
				$aparams = $this->ParseParams($row['params']);

				$settings['support_autoclose_duration'] = $aparams['closeinterval'];
				$settings['support_autoclose_audit'] = $aparams['addaudit'];
				$settings['support_autoclose_email'] = $aparams['emailuser'];
			}
//##NOT_EXT_END##

			$document =& JFactory::getDocument();
			$document->addStyleSheet(JURI::root().'administrator/components/com_fss/assets/css/js_color_picker_v2.css'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/color_functions.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/js_color_picker_v2.js'); 

			$this->assignRef('settings',$settings);

			JToolBarHelper::title( JText::_("FREESTYLE_SUPPORT_PORTAL") .' - '. JText::_("SETTINGS") , 'fss_settings' );
			JToolBarHelper::apply();
			JToolBarHelper::save();
			JToolBarHelper::cancel('cancellist');
			FSSAdminHelper::DoSubToolbar();
			parent::display($tpl);
		}
	}

	function ParseParams(&$aparams)
	{
		$out = array();
		$bits = explode(";",$aparams);
		foreach ($bits as $bit)
		{
			if (trim($bit) == "") continue;
			$res = explode(":",$bit,2);
			if (count($res) == 2)
			{
				$out[$res[0]] = $res[1];	
			}
		}
		return $out;	
	}

	function CustomTemplate()
	{
		$template = JRequest::getVar('name');
		$db	= & JFactory::getDBO();
		$qry = "SELECT * FROM #__fss_templates WHERE template = '" . FSSJ3Helper::getEscaped($db, $template) . "'";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();
		$output = array();
		foreach ($rows as $row)
		{
			if ($row['tpltype'])
			{
				$output['head'] = $row['value'];
			} else {
				$output['row'] = $row['value'];
			}
		}
		echo json_encode($output);
		exit;	
	}

	function TestRef()
	{
		$format = JRequest::getVar('ref');
		
		$ref = FSS_Ticket_Helper::createRef(1234,$format);
		echo $ref;
		exit;	
	}
	
	function testdates()
	{
		// test the 4 date formats
		
		$date = time();
		$result = array();
		$result['date_dt_short'] = $this->testdate($date, JRequest::GetVar('date_dt_short'));
		$result['date_dt_long'] = $this->testdate($date, JRequest::GetVar('date_dt_long'));
		$result['date_d_short'] = $this->testdate($date, JRequest::GetVar('date_d_short'));
		$result['date_d_long'] = $this->testdate($date, JRequest::GetVar('date_d_long'));
		$result['timezone_offset'] = $this->testdate($date, 'Y-m-d H:i:s');
		echo json_encode($result);
		exit;
	}
	
	function testdate($date, $format)
	{
		$date = new JDate($date, new DateTimeZone("UTC"));
		$date->setTimezone(FSS_Helper::getTimezone());
		return $date->format($format, true);	
	}

	function PerPage($var)
	{
		echo "<select name='$var'>";
		
		$values = array(0 => JText::_('All'), 5 => '5', 10 => '10', 15 => '15', 20 => '20', 25 => '25', 30 => '30', 50 => '50', 100 => '100');
		
		foreach ($values as $val => $text)
		{
			echo "<option value='$val' ";
			if ($this->settings[$var] == $val) echo " SELECTED";
			echo ">" . $text . "</option>";
		}
		
		echo "</select>";
	}
}


