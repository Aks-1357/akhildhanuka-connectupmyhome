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
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'fields.php');


class FsssViewSettingsView extends JViewLegacy
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
		
		$settings = FSS_Settings::GetAllViewSettings(); // CHANGE
		$db	= & JFactory::getDBO();
		
		if ($what == "save")
		{
			$data = JRequest::get('POST',JREQUEST_ALLOWRAW);

			foreach ($data as $setting => $value)
				if (array_key_exists($setting,$settings))
				{
					$settings[$setting] = $value;
				}
			
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

				$qry = "REPLACE INTO #__fss_settings_view (setting, value) VALUES ('";
				$qry .= FSSJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= FSSJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();
				//echo $qry."<br>";
			}

			$link = 'index.php?option=com_fss&view=settingsview#' . $this->tab;
			
			if (JRequest::getVar('task') == "save")
				$link = 'index.php?option=com_fss';

			//exit;
			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link, JText::_("View_Settings_Saved"));		
			exit;
		} else {
		
			$document =& JFactory::getDocument();
			$document->addStyleSheet(JURI::root().'administrator/components/com_fss/assets/css/js_color_picker_v2.css'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/color_functions.js'); 
			$document->addScript(JURI::root().'administrator/components/com_fss/assets/js/js_color_picker_v2.js'); 

			$this->assignRef('settings',$settings);

			JToolBarHelper::title( JText::_("FREESTYLE_SUPPORT_PORTAL") .' - '. JText::_("VIEW_SETTINGS") , 'fss_viewsettings' );
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

}


